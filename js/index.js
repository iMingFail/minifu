var open_order_interval;
var load_lottery_id_interval;
var load_data_interval;
var open_order_second = 59;
var flow = 1;
var r_o_id = 0;
var load_temp_data_flag = false;
var option;
var myChart;
var socket;
var vouchers_list = [];
var use_vouchers_money = 0;

$(function(){
    /*if (isLogin() == false) {
        window.location.href = "login.html";
    }*/

    // var u = $.cookie("u");
    var u = getUserSession();
    if (u.split(",")[7] == "0") {
        $("#mycoupon").addClass("shipan");
        $(".panmian_").attr("index", 2).html("模拟盘");
        $(".a-u").html("模拟资金");
        $("#minijin").html(parseFloat(u.split(",")[8]).toFixed(2));
    } else {
        $("#mycoupon").addClass("right");
        $(".panmian_").attr("index", 1).html("实盘");
        $(".a-u").html("实际余额(元)");
        $("#minijin").html(parseFloat(u.split(",")[7]).toFixed(2));
    }

    $("#mycoupon").addClass("right");
    $(".panmian_").attr("index", 1).html("实盘");
    $(".a-u").html("实际余额(元)");
    $("#minijin").html(parseFloat(u.split(",")[7]).toFixed(2));

    if (null != u.split(",")[11] && u.split(",")[11] != "") {
        $("#balance_").css({"background":"url("+u.split(",")[11]+") left no-repeat", "backgroundSize":"4rem 4rem"});
    }


    /* 用途: 接收地直栏参数 取id=1 根据ID的值 */
    var urlinfo=window.location.href; //获取当前页面的url
    var len=urlinfo.length;//获取url的长度
    var offset=urlinfo.indexOf("?");//设置参数字符串开始的位置
    var newsidinfo=urlinfo.substr(offset,len)//取出参数字符串 这里会获得类似“id=1”这样的字符串
    var newsids=newsidinfo.split("=");//对获得的参数字符串按照“=”进行分割
    // var newsname=newsids[0];//得到参数名字
    var newsid="";
    if (newsids.length > 1) {//得到参数值
        newsid = newsids[1];
    }

    $.ajax({
        type:"POST",
        url:"action/toolAction.php?action=get_types&typeId="+newsid,
        dataType:"json",
        success:function(json){
            if (json.success == 1) {
                var html = "";
                var active_html = "";
                var d=new Date().getDay();
                for (var i=0; i<json.data.length; i++) {
                    // var css = "";
                    if (null != newsid && newsid != "" && isNaN(newsid) == false) {
                        if (newsid == json.data[i].id) {
                            // css = "sw_active";
                            active_html = "<li class='sw_active' index='"+json.data[i].id+"'><a href='index.php?t="+json.data[i].id+"'  type='"+json.data[i].id+"'>"+json.data[i].name+"</a></li>";
                        } else {
                            html  += "<li index='"+json.data[i].id+"'><a href='index.php?t="+json.data[i].id+"'  type='"+json.data[i].id+"'>"+json.data[i].name+"</a></li>";
                        }
                    } else {
                        if (d == 6 || d == 0) {
                            if (json.data[i].id == 3) {
                                active_html = "<li class='sw_active' index='"+json.data[i].id+"'><a href='index.php?t="+json.data[i].id+"'  type='"+json.data[i].id+"'>"+json.data[i].name+"</a></li>";
                            } else {
                                html  += "<li index='"+json.data[i].id+"'><a href='index.php?t="+json.data[i].id+"'  type='"+json.data[i].id+"'>"+json.data[i].name+"</a></li>";
                            }
                        } else {
                            if (i == 0) {
                                // css = "sw_active";
                                active_html = "<li class='sw_active' index='"+json.data[i].id+"'><a href='index.php?t="+json.data[i].id+"'  type='"+json.data[i].id+"'>"+json.data[i].name+"</a></li>";
                            } else {
                                html  += "<li index='"+json.data[i].id+"'><a href='index.php?t="+json.data[i].id+"'  type='"+json.data[i].id+"'>"+json.data[i].name+"</a></li>";
                            }
                        }
                    }
                }
                $(".product_switch").html(active_html+html);
                init();
                /*if (isClosed($(".sw_active").attr("index"))) {
                    init();
                } else {
                    open_info_msg("该资产已休市");
                    setTimeout(tt, 1000);
                }*/
            } else {
                open_info_msg("该资产已休市");
                setTimeout(tt, 1000);
                // alert("该资产已休市");
                // open_info_msg(json.msg);
            }
        }
    });
});
function isClosed(play_type){
    var d=new Date().getDay();
    if (d == 6 || d == 0) {
        if (play_type != 3) {
            return false;
        }
    }
    return true;
}
function tt() {
    window.location.href = "index.php?t=3";
}
function init() {
    socket = io.connect('http://211.149.232.173:3000/');
    loadOrders();
    getDate();
    loadVouchers();
    loadUseVouchersMoney();
    // loadNewOrder();
    // 创建websocket连接
    socket.on('type_max_min_value', function (data) {
        var type= $(".product_switch li.sw_active").attr("index");
        if(data.list[0].type == type) {
            $(".height_").html(parseFloat(data.list[0].max_v).toFixed(2));
            $(".low_").html(parseFloat(data.list[0].min_v).toFixed(2));
    }
    // var type= $(".product_switch li.sw_active").attr("index");
    // for (var i = 0; i<data.list.length; i++) {history
    //     if (data.list[i].id == type) {
    //         $(".height_").html(parseFloat(data.list[i].max_value).toFixed(2));
    //         $(".low_").html(parseFloat(data.list[i].min_value).toFixed(2));
    //     }
        // }
    });

    var u = getUserSession();
    var user_id = u.split(",")[0];
    socket.on('user_money_'+user_id, function (data) {
        // data.list[0].money
        var money = 0;
        if (data.list.length > 0) {
            // var u = $.cookie("u");
            var u = getUserSession();
            var user_id = u.split(",")[0];
            if (data.list[0].id == user_id) {
                if (parseFloat(data.list[0].money) > 0) {
                    var isModel = get_panmian_key();
                    if (isModel == 1) {
                        money = data.list[0].money;
                        $("#minijin").html(parseFloat(money).toFixed(2));
                    }
                }
            }
        }
    });

    socket.on('new_order_user_num', function (data) {
        if (data.list.length > 0) {
            $("#renshu").html(parseInt(data.list[0].userNum)+10000);
            $("#trade_count").html(parseInt(data.list[0].orderNum)+100000);
        }
    });
    socket.on('new_order', function (data) {
        var ul = $(".box-ct-transcation ul");
        if (ul.length == 0) {
            $(".box-ct-transcation").html("<ul class='l-ct-transcation' id='depth_buy_context'></ul>");
        }
        var css1 = "sell";
        var css2 = "#f5ad5f";
        var css3 = "sellspan";
        var type = "买涨";
        if (data.list[0].type == "2") {
            type = "买跌";
            css1 = "buy";
            css2 = "green";
            css3 = "buyspan";
        }
        var width = parseInt(data.list[0].order_price)*0.3 + "px";

        var html  = "<li class='li-ct-transcation' style='margin-top: 0px;'>";
        html  += "<div class='part-ct-transcation' style='width:20%;'>"+data.list[0].begin_time.split(" ")[1]+"</div>";
        html  += "<div class='part-ct-transcation "+css1+"' style='width:20%;'>"+data.list[0].name+"</div>";
        html  += "<div class='part-ct-transcation_right' style='width:20%;'><span><font color='"+css2+"'>"+type+"</font></span></div>";
        html  += "<div class='part-ct-transcation_right' style='padding-left: 0px'><span >"+data.list[0].order_price+"</span></div>";
        html  += "<div class='part-ct-transcation_right' style='padding-left:0.8em'><span style='width:"+width+";' class='"+css3+"'></span></div>";
        html  += "</li>";
        $(".box-ct-transcation ul").prepend(html);
    });

    new Swiper('#options', {
        paginationClickable: true,
        centeredSlides: true,
        slidesPerView:2,
        watchActiveIndex: true,
    });

    $('#doc-modal-1').on('open.modal.amui', function(){
        $("#dangqian").removeClass("none");
        $("#daoqi").addClass("none");

        $("#flow_span_time").html($(".swiper-slide-active").attr("index")+"秒");
        var type = $(".sw_active").attr("index");
        var type_txt = $(".sw_active a").html();
        $("#flow_span").html(type_txt.replace(/\s+/g,""));
        if (get_panmian_key() == 1) {

            // var u = $.cookie("u");
            var u = getUserSession();

            $("#money_list").find(".use_vouchers").remove();
            if (u.split(",")[14] == 0) {
                $("#money_list").append('<li class="use_vouchers"><p><i>使用代金劵</i></p></li>');
            }
            //use_vouchers_money
            if (null != u.split(",")[13] && u.split(",")[13] != "" && parseInt(u.split(",")[13]) > 0) {
                var html = "<select id='vouchers_txt' style='float:left;width:100%;height:1.9rem;background:#2a2a2a;color:#d9d9d9;'>";
                html += "<option value='0'>使用代金劵</option>";
                for (var i=0; i<vouchers_list.length; i++) {
                    var selected = "";
                    if (u.split(",")[14] == 0 && i == 0) {
                        selected = "selected"
                    }
                    html += "<option "+selected+" value='"+vouchers_list[i].money+","+vouchers_list[i].id+"'>代金卷面值为:"+parseFloat(vouchers_list[i].money).toFixed(2)+"元</option>";
                }
                html += "</select>";
                $(".home-quan-input").html(html);
            } else {
                $(".home-quan-input").html("<p>您还没有可使用的代金劵</p>");
            }

            $(".active ul").find('li').siblings().removeClass('slct');
            if (u.split(",")[14] == 0) {
                $(".active ul").find('li').eq(8).addClass('slct');

                $("#vouchers_txt").on('change', function(){
                    compute_money();
                });
            } else {
                $(".active ul").find('li').eq(4).addClass('slct');
            }
        } else {
            $(".home-quan-input").html("");
        }

        compute_money();

        $('#input_money').on('input propertychange', function() {
            compute_money();
        });
        $('.active ul li:not(.not)').click(function() {
            $(this).addClass('slct').siblings().removeClass('slct');
            $('.active h2').show();
            $('.active ul li.not input').fadeOut();
        });
        $('.active ul li.not').click(function() {
            $(".active ul").find('li').siblings().removeClass('slct');
            $(this).find('input').show().focus();
        });
        $('.active ul li:not(.other)').click(function() {
            compute_money();
        });

        // if (type == "1") {
        //     $("#flow_span").html("黄金");
        // } else if (type == "2") {
        //     $("#flow_span").html("白银");
        // }
        if (flow == 1) {
            $("#flow_span_dir").html("<font color='red'>买涨</font>");
        } else {
            $("#flow_span_dir").html("<font color='#29a9df'>买跌</font>");
        }
    });
    $('#doc-modal-1').on('close.modal.amui', function(){
        $("#buybtn").removeAttr("disabled");
        $('.active ul li.not input').val(200).fadeOut();
    });
    $("#doc-modal-2").on('close.modal.amui', function(){
        clearInterval(open_order_interval);
    });
    $(".up").on("click",function(){
        flow = 1;
    });
    $(".down").on("click",function(){
        flow = 2;
    });
    $("#buybtn").on("click",function(){
        $(this).attr("disabled", true);
        if (isLogin() == false) {
            // window.location.href = "login.html";
        } else {
			
			//open_info_msg("账户余额不足,请充值！");
            var min_order_price = 20; //最小下单默认金额
            var max_order_price = 5000; //最大下单默认金额
            var play_type; //玩法
            var trade_time; //交易周期
            var order_price; //下单金额
            var open_price; //下单点位
            var isModel; //是否为模拟订单
            var begin_time; //下单时间

            isModel = get_panmian_key();
            order_price = $(".slct p i").text();
            if(order_price=='') {
                order_price = $("#input_money").val();
            }
            if (!order_price) {
                order_price = 200;
            }

            // var u = $.cookie("u");
            // user_id = u.split(",")[0];
            var u = getUserSession();
            if (isModel == "1") {
                if (parseFloat(u.split(",")[7]) < parseFloat(order_price)) {
                    var isUse = true;
					
                    //var vouchers_txt = $("#vouchers_txt").val().split(",")[0];
					var vouchers_txt = get_vouchers();
					
                    if ((parseFloat(vouchers_txt) + parseFloat(u.split(",")[7])) < parseFloat(order_price)) {
                        isUse = false;
                    }
                    if (parseInt(u.split(",")[14]) > 0) {
                        if (parseFloat(order_price) < use_vouchers_money) {
                            isUse = false;
                        }
                    }
                    if (isUse == false) {
                        $("#doc-modal-1").modal("close");
                        open_info_msg("账户余额不足,请充值！");
                        setTimeout(close_info_msg, 2000);
                        return;
                    }
                }
            } else {
                if (parseFloat(u.split(",")[8]) < parseFloat(order_price)) {
                    $("#doc-modal-1").modal("close");
                    open_info_msg("虚拟余额不足！");
                    setTimeout(close_info_msg, 2000);
                    return;
                }
            }

            if (parseInt(u.split(",")[14]) > 0) {
                if (order_price < min_order_price) { //最低下单金额限制
                    $("#doc-modal-1").modal("close");
                    open_info_msg("下单金额不能低于"+min_order_price+"元，无法交易！");
                    setTimeout(close_info_msg, 2000);
                    return;
                } else if (order_price > max_order_price) { //最高下单金额限制
                    $("#doc-modal-1").modal("close");
                    open_info_msg("下单金额不能高于"+max_order_price+"元，无法交易！");
                    setTimeout(close_info_msg, 2000);
                    return;
                }
            }

            $.ajax({
                type:"POST",
                url:"action/userAction.php?action=user_balance",
                dataType:"json",
                data:data,
                success:function(json){
                    if (isModel == "1") {
                        if (parseFloat(json.balance) < parseFloat(order_price)) {
                            // var u = $.cookie("u");
                            var u = getUserSession();
                            var isUse = true;
                            //var vouchers_txt = $("#vouchers_txt").val().split(",")[0];
							var vouchers_txt = $("#vouchers_txt").val();
							if(vouchers_txt){
								vouchers_txt = vouchers_txt.split(",")[0];
							}
					
                            if ((parseInt(vouchers_txt) + parseFloat(json.balance)) < parseFloat(order_price)) {
                                isUse = false;
                            }
                            if (parseInt(u.split(",")[14]) > 0) {
                                if (parseFloat(order_price) < use_vouchers_money) {
                                    isUse = false;
                                }
                            }
                            if (isUse == false) {
                                $("#doc-modal-1").modal("close");
                                open_info_msg("账户余额不足,请充值！");
                                setTimeout(close_info_msg, 2000);
                                return;
                            }
                        }
                    } else {
                        if (parseFloat(json.tempBalance) < parseFloat(order_price)) {
                            $("#doc-modal-1").modal("close");
                            open_info_msg("虚拟余额不足！");
                            setTimeout(close_info_msg, 2000);
                            return;
                        }
                    }
                    play_type = get_cap_key();
                    open_price = $("#nowpotis").text();
                    trade_time = $(".swiper-slide-active").attr("index");
                    begin_time = getc_Datetiome();
                    // var u = $.cookie("u");
                    var u = getUserSession();
                    var deal_order = "deal_order";
                    if (isModel == "1") {
                        var v_id = 0;
                        if ($("#vouchers_txt").val() !== undefined) {
                            v_id = $("#vouchers_txt").val().split(",")[1];
                        }
                        var money = $(".slct p i").text();
                        if (money == "使用代金劵") {
                            deal_order = "deal_order_vouchers";
                            order_price = $("#vouchers_txt").val().split(",")[0];
                            data = {"play_type":play_type,"type":flow,"trade_time":trade_time, "open_price":open_price,"isModel":isModel, "begin_time":begin_time, "vouchers_id":v_id};
                        } else {
                            deal_order = "deal_order";
                            data = {"play_type":play_type,"type":flow,"trade_time":trade_time,"order_price":order_price,"open_price":open_price,"isModel":isModel, "begin_time":begin_time, "vouchers_id":v_id};
                        }
                    } else {
                        deal_order = "deal_order";
                        data = {"play_type":play_type,"type":flow,"trade_time":trade_time,"order_price":order_price,"open_price":open_price,"isModel":isModel, "begin_time":begin_time, "vouchers_id":0};
                    }

                    $.ajax({
                        type:"POST",
                        url:"action/orderAction.php?action="+deal_order,
                        dataType:"json",
                        data:data,
                        beforeSend:function(){
                            open_info_msg("正在下单...");
                        },
                        success:function(json){
                            loadVouchers();
                            $("#doc-modal-1").modal("close");
                            if (json.success == 1) {
                                if (flow == 1) {
                                    $("#flow_span_dir1").html("<font color='red'>买涨</font>");
                                } else {
                                    $("#flow_span_dir1").html("<font color='#29a9df'>买跌</font>");
                                }
                                socket.emit('new_order', {"begin_time":begin_time, "play_type":play_type,"type":flow,"order_price":order_price});
                                r_o_id = json.r_o_id;
                                $("#buy_price").text(open_price);

                                var times = $(".swiper-slide-active h4 span").html();
                                times=times.replace(" ","");
                                times = parseInt(times);
                                open_order_second  = times;
                                // var money = parseFloat($("#minijin").html());
                                // $("#minijin").html(parseFloat(money - parseFloat(order_price)).toFixed(2));
                                if(isModel == 1) {
                                    // $("#minijin").html(parseFloat(json.balance).toFixed(2));
                                } else {
                                    // $("#minijin").html(parseFloat(json.tempBalance).toFixed(2));
                                }
                                open_order_interval = setInterval(await_lottery, 1000);
                            } else {
                                open_info_msg(json.msg);
                                setTimeout(close_info_msg, 2000);
                            }
                        }
                    });
                }
            });
        }
    });
    $('#time_diff li a').on("click", function(){
        $('#time_diff li a.changed').removeClass("changed");
        $(this).addClass("changed");
        load_temp_data_flag = false;
        loadData();
    });
    $('.product_switch li').on("click", function(){
        // $('.product_switch li.sw_active').removeClass("sw_active");
        // $(this).addClass("sw_active");
        // optionname
        // load_temp_data_flag = false;
        // loadData();
    });
    $(".info-nav li a").on("click",function(){
        $(".info-nav li a.selected").removeClass("selected");
        $(this).addClass("selected");
        loadOrders();
    });
    $("#mycoupon").on("click", function () {
        // var u = $.cookie("u");
        var u = getUserSession();
        if ($(".panmian_").attr("index") == "1") {
            $(this).removeClass("right").addClass("shipan");
            $(".panmian_").attr("index", 2).html("模拟盘");
            $(".a-u").html("模拟资金");
            $("#minijin").html(parseFloat(u.split(",")[8]).toFixed(2));
        } else {
            $(this).removeClass("shipan").addClass("right");
            $(".panmian_").attr("index", 1).html("实盘");
            $(".a-u").html("实际余额(元)");
            $("#minijin").html(parseFloat(u.split(",")[7]).toFixed(2));
        }
    });
}

function compute_money() {
    // var u = $.cookie("u");
    var u = getUserSession();
    var money = $(".slct p i").text();
    if(money==''){
        money = $("#input_money").val();
    } else if (money=='使用代金劵'){
        money = $("#vouchers_txt").val().split(",")[0];
    }

   if (u.split(",")[14] > 0) {
        if (parseFloat(money) < parseFloat(use_vouchers_money)) {
            $("#vouchers_txt").attr("disabled", "disabled");
            $("#vouchers_txt option:selected").removeAttr("selected");
            $("#vouchers_txt option").eq(0).html("订单金额未到达"+use_vouchers_money+"元，无法使用代金劵").selected();
        } else {
            $("#vouchers_txt").removeAttr("disabled");
            $("#vouchers_txt option").eq(0).html("使用代金劵").css("display", "block");
        }
    }
    var times = $(".swiper-slide-active h4 span").html();
    times=times.replace(" ","");
    times = parseInt(times);
    var bili = set_profit(times);
    $("#input_money").val(money);
    $('.active h1 b').text(money);
    var yuqi = money*bili;
    var vouchers_money =0;
    if (null != $("#vouchers_txt").val() && $("#vouchers_txt").val() != "" && $("#vouchers_txt").val() != undefined) {
        vouchers_money = $("#vouchers_txt").val();
    }
    yuqi = parseFloat(yuqi)+parseFloat(money)-parseFloat(vouchers_money);

    $('.active h1 i').html('预期收益：<span>' + yuqi.toFixed(2) + '</span>元');
}

function loadVouchers() {
    // var u = $.cookie("u");
    var u = getUserSession();
    if (null != u.split(",")[13] && u.split(",")[13] != "" && parseInt(u.split(",")[13]) > 0) {
        $.ajax({
            type: "POST",
            url: "action/vouchersAction.php?action=mylist",
            dataType: "json",
            success: function (json) {
                vouchers_list = json.data;
            }
        });
    } else {
        $(".home-quan-input").html("<p>您还没有可使用的代金劵</p>");
    }
}
function loadUseVouchersMoney() {
    $.ajax({
        type: "POST",
        url: "action/toolAction.php?action=use_vouchers_money",
        dataType: "json",
        success: function (json) {
            use_vouchers_money = json.use_vouchers_money;
        }
    });
}


function loadOrders(){
    var info_nav = $(".info-nav li a.selected").attr("id");
    if (info_nav == "newodrers") {
        $("#historybox").addClass("none");
        $("#positionbox").addClass("none");
        $("#realtimebox").removeClass("none");
        loadNewOrder();
    } else if (info_nav == "history") {
        $("#realtimebox").addClass("none");
        $("#positionbox").addClass("none");
        $("#historybox").removeClass("none");
        loadHistory();
    } else if (info_nav == "position") {
        $("#realtimebox").addClass("none");
        $("#historybox").addClass("none");
        $("#positionbox").removeClass("none");
        // loadPosition();
    }
}
function loadPosition() {
    var isModel = get_panmian_key();
    $.ajax({
        type:"POST",
        url:"action/orderAction.php?action=get_position_order",
        dataType:"json",
        data:{"isModel":isModel},
        success:function(json){
            var html = "";
            html = "<tr class='rec-tr'>";
            html += "<th class='rec-th'>下单时间</th><th class='rec-th'>类型</th><th class='rec-th'>涨/跌</th>";
            html += "<th class='rec-th'>买入价</th><th class='rec-th'>当前价</th><th class='rec-th'>状态</th>";
			html += "<th class='rec-th'>倒计时</th>";
            html += "</tr>";
            if (json.success == 1) {
                for (var i=0; i<json.data.length; i++) {
                    var type = "买涨";
                    if (json.data[i].type == "2") {
                        type = "买跌";
                    }
                    html += "<tr class='rec-tr'>";
                    html += "<td class='rec-td'>"+json.data[i].begin_time.substring(5,19)+"</td>";
                    html += "<td class='rec-td'>"+json.data[i].name+"</td>";
                    html += "<td class='rec-td fcgray3'>"+type+"</td>";
                    html += "<td class='rec-td fcgray3'>"+json.data[i].open_price+"</td>";
                    html += "<td class='rec-td fcgray3 class_"+json.data[i].play_type+" position_tab_price'>"+json.data[i].open_price+"</td>";
                    //html += "<td class='rec-td fcgray3 position_tab_price class_"+json.data[i].play_type+"'>"+"</td>";
					html += "<td class='rec-td fcgray3'>未结算</td>";
					html += "<td class='rec-td fcgray3'>"+json.data[i].count_down+"秒</td>";
                    html += "</tr>";
                }
            }
            $("#now_list").html(html);
        }
    });
}
function loadHistory() {
    var isModel = get_panmian_key();
    $.ajax({
        type:"POST",
        url:"action/orderAction.php?action=get_history_order",
        dataType:"json",
        data:{"isModel":isModel},
        success:function(json){
            var html = "";
            html = "<tr class='rec-tr'>";
            html += "<th class='rec-th'>资产类型</th><th class='rec-th'>涨/跌</th><th class='rec-th'>到期时间</th>";
            html += "<th class='rec-th'>买入金额</th><th class='rec-th'>盈利情况</th><th class='rec-th'>订单状态</th>";
            html += "</tr>";
            if (json.success == 1) {
                for (var i=0; i<json.data.length; i++) {
                    var type = "买涨";
                    if (json.data[i].type == "2") {
                        type = "买跌";
                    }
                    // var end_time = new Date(json.data[i].end_time);
                    var gain_price = json.data[i].gain_price;
                    var state = json.data[i].state;
                    var state_str = "平局";
                    if (state == "1") {
                        state_str = "<font color='#f5ad5f'>盈利</font>";
                        gain_price = "<font color='#f5ad5f'>+"+gain_price+"</font>";
                    } else if(state == "2") {
                        state_str = "<font color='green'>亏损</font>";
                        gain_price = "<font color='green'>"+gain_price+"</font>";
                    }

                    html += "<tr class='rec-tr'>";
                    html += "<td class='rec-td'>"+json.data[i].name+"</td>";
                    html += "<td class='rec-td'>"+type+"</td>";
                    html += "<td class='rec-td fcgray3'>"+json.data[i].end_time.substring(5,19)+"</td>";
                    html += "<td class='rec-td fcgray3'>"+json.data[i].price+"+"+json.data[i].vouchers_price+"</td>";
                    html += "<td class='rec-td fcgray3'>"+gain_price+"</td>";
                    html += "<td class='rec-td fcgray3'>"+state_str+"</td>";
                    html += "</tr>";
                }
                $("#all_list").html(html);
            }
        }
    });
}
function loadNewOrder() {
    var isModel = get_panmian_key();
    $.ajax({
        type:"POST",
        url:"action/orderAction.php?action=get_new_order",
        data:{"isModel":isModel},
        dataType:"json",
        success:function(json){
            if (json.success == 1) {
               var html = "<ul class='l-ct-transcation' id='depth_buy_context'>";
                for (var i=0; i<json.data.length; i++) {
                    var css1 = "sell";
                    var css2 = "#f5ad5f";
                    var css3 = "sellspan";
                    var type = "买涨";
                    if (json.data[i].type == "2") {
                        type = "买跌";
                        css1 = "buy";
                        css2 = "green";
                        css3 = "buyspan";
                    }
                    var width = parseInt(json.data[i].order_price)*0.3 + "px";
                    html  += "<li class='li-ct-transcation' style='margin-top: 0px;'>";
                    html  += "<div class='part-ct-transcation' style='width:20%;'>"+json.data[i].begin_time.split(" ")[1]+"</div>";
                    html  += "<div class='part-ct-transcation "+css1+"' style='width:20%;'>"+json.data[i].name+"</div>";
                    html  += "<div class='part-ct-transcation_right' style='width:20%;'><span><font color='"+css2+"'>"+type+"</font></span></div>";
                    html  += "<div class='part-ct-transcation_right' style='padding-left: 0px'><span >"+json.data[i].order_price+"</span></div>";
                    html  += "<div class='part-ct-transcation_right' style='padding-left:0.8em'><span style='width:"+width+";' class='"+css3+"'></span></div>";
                    html  += "</li>";
                }
                html += "</ul>";
                $(".box-ct-transcation").html(html);
            } else {
                $(".box-ct-transcation").html(json.msg);
            }
        }
    });
}
function await_lottery() {
    // alert(open_order_second);
    if (open_order_second >= 0) {
        var buy_price = parseFloat($("#buy_price").text());
        var nowpotis = parseFloat($("#nowpotis").text());
        $("#flow_span_value1").text($("#nowpotis").text());
        if (buy_price > nowpotis) {
            // 跌
            if (flow == 1) {
                $("#flow_span_value2").html("<font color='#29a9df'>亏</font>");
            } else {
                $("#flow_span_value2").html("<font color='red'>盈</font>");
            }
        } else if (buy_price < nowpotis) {
            // 涨
            if (flow == 2) {
                $("#flow_span_value2").html("<font color='#29a9df'>亏</font>");
            } else {
                $("#flow_span_value2").html("<font color='red'>盈</font>");
            }
        } else {
            $("#flow_span_value2").html("<font color='gray'>平局</font>");
        }
        var times = $(".swiper-slide-active h4 span").html();
        times=times.replace(" ","");
        times = parseInt(times);
        if (open_order_second  == times) {
            $("#doc-modal-2").modal("open");
            close_info_msg();
        }
        if (open_order_second<10) {
            $("#fnTimeCountDown").html("<span class='sec'>"+open_order_second+"</span>");
        } else {
            $("#fnTimeCountDown").html("<span class='sec'>"+open_order_second+"</span>");
        }

        open_order_second--;
    } else {
        clearInterval(open_order_interval);
        load_lottery_id();
    }
}
function load_lottery_id(){
    $("#flow_span_value1").text($("#nowpotis").text());
    $("#fnTimeCountDown").html("<font color='red'>正在计算结果...</font>");
    $.ajax({
        type:"POST",
        url:"action/orderAction.php?action=get_order_by_id",
        dataType:"json",
        data:{"r_o_id":r_o_id},
        success:function(json){
            if (json.order[0].state != 0) {
                // clearInterval(load_lottery_id_interval);
                $("#dangqian").addClass("none");
                $("#flow_span_daoqi").text(json.order[0].close_price);
                $("#daoqi").removeClass("none");
                if (json.order[0].state == 1) {
                    $("#fnTimeCountDown").html("<font color='red'>盈+"+parseFloat(json.order[0].gain_price).toFixed(2)+"</font>");
                    $("#flow_span_value2").html("<font color='red'>盈</font>");
                    // var m = parseFloat($("#minijin").html())+parseFloat(json.order[0].gain_price);
                    // $("#minijin").html(parseFloat(m).toFixed(2));
                } else if (json.order[0].state == 2) {
                    $("#fnTimeCountDown").html("<font color='green'>亏"+parseFloat(json.order[0].gain_price).toFixed(2)+"</font>");
                    $("#flow_span_value2").html("<font color='green'>亏</font>");
                } else if (json.order[0].state == 3) {
                    $("#fnTimeCountDown").html("<font color='gray'>平+0</font>");
                    $("#flow_span_value2").html("<font color='gray'>平</font>");
                    // var m = parseFloat($("#minijin").html())+parseFloat(json.order[0].order_price);
                    // $("#minijin").html(parseFloat(m).toFixed(2));
                }
            } else {
                load_lottery_id();
                // setTimeout(load_lottery_id, 1000);
            }
        }
    });
}
function loadIndexTime() {
    var type= $(".product_switch li.sw_active").attr("index");
    socket.emit('type_max_min_value', {"type":type});

    // var u = $.cookie("u");
    var u = getUserSession();
    var user_id = u.split(",")[0];
    socket.emit('user_money', {"id":user_id});

    var now = new Date();
    var hh = now.getHours();            //时
    var mm = now.getMinutes();          //分
    var ss = now.getSeconds()           //秒
    var clock = "";
    if (hh < 10) {
        clock += "0";
    }
    clock += hh + ":";
    if (mm < 10) {
        clock += '0';
    }
    clock += mm + ":"
    if (ss < 10) {
        clock += '0';
    }
    clock += ss;
    $(".zuoshou_").html(clock);
}
function set_profit(valu_t) {
    if(valu_t==60){
        return 0.80;
    }else if(valu_t==180){
        return 0.82;
    }else if(valu_t==300){
        return 0.85;
    }
}
function get_cap_key(){
    var key = $(".sw_active a").attr("type");
    return key;
}
function get_panmian_key() {
    return $(".panmian_").attr("index");
}
function isLogin() {
    /*var u = $.cookie("u");
    if (u === undefined || u == "null") {
        return false;
    } else {
        return true;
    }*/
}
function open_info_msg(msg) {
    $("#info-msg").html(msg);
    $("#msg-dialog").modal();
}
function close_info_msg(){
    $("#info-msg").html("");
    $("#msg-dialog").modal("close");
}
function getc_Datetiome() {
    var now = new Date();
    var year = now.getFullYear();
    var month =(now.getMonth() + 1).toString();
    var day = (now.getDate()).toString();
    var hh = (now.getHours()).toString();;           //时
    var mm = (now.getMinutes()).toString();          //分
    var ss = (now.getSeconds()).toString();           //秒

    if (month.length == 1) {
        month = "0" + month;
    }
    if (day.length == 1) {
        day = "0" + day;
    }
    if (hh.length == 1) {
        hh = "0" + hh;
    }
    if (mm.length == 1) {
        mm = "0" + mm;
    }
    if (ss.length == 1) {
        ss = "0" + ss;
    }

    var dateTime = year + "-" + month + "-" +  day + " "+ hh + ":" + mm + ":" +  ss;
    return dateTime;
}
function getDate() {
    load_data_interval = setInterval(function (){
        var myDate = new Date();
        if (myDate.getSeconds() == "59") {
            load_temp_data_flag = false;
            // clearInterval(load_data_interval);
            // setTimeout(getDate, 1000);
        }
        loadPosition();
        loadIndexTime();
        loadData();
    }, 1000);
}
function get_vouchers(){
	var result = 0;
	var vouchers_txt = $("#vouchers_txt").val();
	if(vouchers_txt != null){
		vouchers_txt = vouchers_txt.split(",")[0];
	}
	var vouchers_val = parseInt(vouchers_txt);
	if(vouchers_val>0){
		result = vouchers_val;
	}
	return result;
}
Date.prototype.format = function(format) {
    var o = {
        "M+": this.getMonth() + 1, //month
        "d+": this.getDate(), //day
        "h+": this.getHours(), //hour
        "m+": this.getMinutes(), //minute
        "s+": this.getSeconds(), //second
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter
        "S": this.getMilliseconds() //millisecond
    }
    if(/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
    for(var k in o) {
        if(new RegExp("("+ k +")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
        }
    }
    return format;
}
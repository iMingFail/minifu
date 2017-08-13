function showLoading(msg,time){
    $("#msg").text(msg);
    $(".loading-wrapper").show();
    if(time>0){
        setTimeout("hideLoading()",time);
    }
}

function hideLoading(){
    $(".loading-wrapper").hide();
}
function jump(url){
    window.location.href =url;
}
function loadUserInfo(isModel) {
    $.ajax({
        type:"POST",
        url:"action/userAction.php?action=user_info",
        dataType:"json",
        data : "&isModel="+isModel,
        success:function(json){
            var u = getUserSession();
            var us = u.split(",");
            if ($("#screen").attr("index") == 1) {
                $("#balance_tip").html(parseFloat(us[7]).toFixed(2));
            } else {
                $("#balance_tip").html(parseFloat(us[8]).toFixed(2));
            }

            if (null != us[11] && us[11] != "") {
                $("#balance_").attr("src", us[11]);
            }

            var user_level = "普通会员";
            if (json.user_level != "" && json.user_level != null) {
                user_level = json.user_level;
            }
            $("#user_level").html(user_level);

            var priceNum = "0.00";
            if (json.order_state[0].priceNum != null) {
                priceNum = parseFloat(json.order_state[0].priceNum).toFixed(2)
            }
            $(".span-right").html("累计交易："+json.order_state[0].orderNum+"笔<p>累计流水："+priceNum+"元</p>");

            var priceNum_today = "0.00";
            if (json.order_today_gain_today[0].today_gain != null) {
                priceNum_today = parseFloat(json.order_today_gain_today[0].today_gain).toFixed(2)
            }
            $(".span-left").html("今日交易："+json.order_orderNum_today[0].orderNum+"笔<p>今日盈利："+priceNum_today+"元</p>");
            load_water(1, 15,$("#screen").attr("index"),1);
        }
    });
}

var _hmt = _hmt || [];
(function() {
    loadUserInfo(1);
    var hm = document.createElement("script");
    hm.src = "//hm.baidu.com/hm.js?175ececd09f00f4cec1ccf6e498c02ef";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();
var range = 600;             //距下边界长度/单位px
var elemt = 300;           //插入元素高度/单位px
var maxnum = 'no';            //设置加载最多次数
var num = 2;
var totalheight = 0;
var PAGESIZE= 15;
var type= 0;
 $(document).ready(function(){  
		// load_water(1,15,$("#screen").attr("index"),1);
        var all_list = $("#all_list");                     //主体元素  
        $(window).scroll(function(){  
            var srollPos = $(window).scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)  
              
            //console.log("滚动条到顶部的垂直高度: "+$(document).scrollTop());  
            //console.log("页面的文档高度 ："+$(document).height());  
            //console.log('浏览器的高度：'+$(window).height());  
              
            totalheight = parseFloat($(window).height()) + parseFloat(srollPos);  
            if(($(document).height()-range) <= totalheight  && num != maxnum) {  
				$(".more-btn").removeClass("hide");
				load_water(num,PAGESIZE,$("#screen").attr("index"),0);
                num++;  
            }  
        }); 
    $("#pay").click(function() {
		$("#all_list").removeClass("hide");
        $('#pay').attr('class', 'button margin-bottom12 jz-flex-col');
		$('#cash').attr('class', 'button button-gray button-f7 jz-flex-col');
		$("#cash_tips").addClass("hide");
		load_water(1,15,$("#screen").attr("index"),1);
    });	
    $("#cash").click(function() {
		$("#all_list").removeClass("hide");
        $('#cash').attr('class', 'button margin-bottom12 jz-flex-col');
		$('#pay').attr('class', 'button button-gray button-f7 jz-flex-col');
		$("#cash_tips").removeClass("hide");
		load_water(1,15,$("#screen").attr("index"),1);
    });	
	$("#screen").click(function() {
        showLoading('切换中...');
	    if ($(this).attr("index") == "2") {
	        $(".font-sm").html("余额(元)");
            $(this).attr("index", 1).html("切换模拟盘");
        } else {
            $(".font-sm").html("模拟盘余额");
            $(this).attr("index", 2).html("切换实盘赚翻天");
        }
        loadUserInfo($("#screen").attr("index"));
	});
 });  
function load_water(num,PAGESIZE,listtype,isnull) {
    $.ajax({
        type:"POST",
        url:"action/orderAction.php?action=get_history_order_list",
        data : "page=" + num + "&pagesize=" + PAGESIZE + "&isModel="+listtype,
        dataType:"json",
        success:function(json){
            $(".more-btn").addClass("hide");
            hideLoading();
            if (json.success == 1) {
                $("#all_list").removeClass("hide");
                if(isnull==1){
                    $("#all_list").html('<tr class="rec-tr"><th class="rec-th">到期时间</th><th class="rec-th">资产类型</th><th class="rec-th">周期</th><th class="rec-th">涨/跌</th><th class="rec-th">买入金额</th><th class="rec-th">盈利情况</th><th class="rec-th">订单状态</th> </tr>');
                    $(".com-empty").addClass("hide");
                }
                $.each(json.data, function(key, value) {
                    var option_key = "买涨";
                    if (value.type == "2") {
                        option_key = "买跌";
                    }
                    var state = "平局";
                    var gain_price = value.gain_price;
                    if (value.state == 1) {
                        state = "<font color='red'>盈利</font>";
                        gain_price = "<font color='red'>"+gain_price+"</font>";
                    } else if (value.state == 2) {
                        state = "<font color='green'>亏损</font>";
                        gain_price = "<font color='green'>"+gain_price+"</font>";
                    }
                    //trade_time
                    //  alert(value.cash_type==1);
                    //$("#all_page").append('<div class="J_zclx"> <h4 class="L_J_zclx">' + value.cash_type + ' ￥' + value.amount + '<span class="J_Tmoney">' + value.auth_state + '</span></h4><div class="ui-block-a J_block01 J_border_B L_J_zclx01"><h5>' + value.create_time + '&nbsp&nbsp&nbsp' + value.cash + '&nbsp&nbsp&nbsp流水号：' + value.id + '</h5></div></div>');
                    $("#all_list").append('<tr class="rec-tr"><td class="rec-td fcgray3">'+value.end_time+'</td><td class="rec-td">'+value.name+'</td><td class="rec-td">'+value.trade_time+'秒</td><td class="rec-td">'+option_key+'</td><td class="rec-td fcgray3">'+value.price+'+'+value.vouchers_price+'</td><td class="rec-td fcgray3">'+gain_price+'</td><td class="rec-td fcgray3">'+state+'</td></tr>');
                });
            } else {
                num==maxnum;
                if(num==1){
                    $("#all_list").html("");
                    $("#all_list").addClass("hide");
                    $(".come-txt").text("您还没有任何记录");
                    $(".com-empty").removeClass("hide");
                }else{
                    $(".come-txt").text("没有更多记录");
                    $(".com-empty").removeClass("hide");
                }
                return false;
            }
        }
    });
}
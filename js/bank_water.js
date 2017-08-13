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
var _hmt = _hmt || [];
(function() {
    var hm = document.createElement("script");
    hm.src = "//hm.baidu.com/hm.js?175ececd09f00f4cec1ccf6e498c02ef";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();

function loadUserInfo() {
    $.ajax({
        type:"POST",
        url:"action/userAction.php?action=user_info",
        dataType:"json",
        success:function(json){
            var u = getUserSession();
            var us = u.split(",");
            $("#balance_tip").html(parseFloat(us[7]).toFixed(2));

            $("#filling_money_txt").html("累计充值："+parseFloat(json.filling_money).toFixed(2));
            $("#out_money_txt").html("累计提现："+parseFloat(json.user_out_money).toFixed(2));

            if (null != us[11] && us[11] != "") {
                $("#balance_").attr("src", us[11]);
            }

            var user_level = "普通会员";
            if (json.user_level != "" && json.user_level != null) {
                user_level = json.user_level;
            }
            $("#user_level").html(user_level);
            load_water(1,15,1,1);
        }
    });
}

var range = 200;             //距下边界长度/单位px
var elemt = 500;           //插入元素高度/单位px
var maxnum = 'no';            //设置加载最多次数
var num = 2;
var totalheight = 0;
var PAGESIZE= 15;
var type= 0;
$(document).ready(function(){
    loadUserInfo();
    var all_list = $("#all_list");                     //主体元素
    $(window).scroll(function(){
        var srollPos = $(window).scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)
        //console.log("滚动条到顶部的垂直高度: "+$(document).scrollTop());
        //console.log("页面的文档高度 ："+$(document).height());
        //console.log('浏览器的高度：'+$(window).height());
        totalheight = parseFloat($(window).height()) + parseFloat(srollPos);
        if(($(document).height()-range) <= totalheight  && num != maxnum) {
            $(".more-btn").removeClass("hide");
            load_water(num,PAGESIZE,1,0);
            num++;
        }
    });
    $("#pay").click(function() {
        $("#all_list").removeClass("hide");
        $('#pay').attr('class', 'button margin-bottom12 jz-flex-col');
        $('#cash').attr('class', 'button button-gray button-f7 jz-flex-col');
        $("#cash_tips").addClass("hide");
        load_water(1,15,1,1);
    });
    $("#cash").click(function() {
        $("#all_list").removeClass("hide");
        $('#cash').attr('class', 'button margin-bottom12 jz-flex-col');
        $('#pay').attr('class', 'button button-gray button-f7 jz-flex-col');
        $("#cash_tips").removeClass("hide");
        load_water(1,15,2,1);
    });
});

function load_water(num,PAGESIZE,listtype,isnull){
    $.ajax({
        type:"POST",
        url:"../action/userAction.php?action=bank_water_show",
        data : "page=" + num + "&pagesize=" + PAGESIZE + "&type="+listtype,
        dataType:"json",
        success : function(json) {
            $(".more-btn").addClass("hide");
            hideLoading();
            if (json.success == 1) {
                $("#all_list").removeClass("hide");
                if(isnull==1){
                    $("#all_list").html('<tr class="rec-tr"><th class="rec-th">类型</th><th class="rec-th">金额</th><th class="rec-th">时间</th><th class="rec-th">状态</th></tr>');
                    $(".com-empty").addClass("hide");
                }
                $.each(json.data, function(key, value) {
                    var type_str = "充值";
                    if (value.type == 2) {
                        type_str = "提现";
                    }
                    var state_str = "未到账";
                    if (listtype == 2) {
                        state_str = "已申请";
                    }
                    if (value.state == 1) {
                        state_str = "充值成功";
                        if (listtype == 2) {
                            state_str = "提现成功";
                        }
                    } else if (value.state == 2) {
                        state_str = "资金冻结";
                        if (listtype == 2) {
                            state_str = "提现未通过";
                        }
                    } else if (value.state == 3) {
                        state_str = "充值失败";
                        if (listtype == 2) {
                            state_str = "提现失败";
                        }
                    }
                    $("#all_list").append('<tr class="rec-tr"><td class="rec-td">'+type_str+'</td><td class="rec-td">'+parseFloat(value.money).toFixed(2)+'元</td><td class="rec-td fcgray3">'+value.datetime+'</td><td class="rec-td fcgray3">'+state_str+'</td></tr>');
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
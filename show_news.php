<?php
include 'isLogin.php';
?>
<!DOCTYPE html>
<html><head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport">
	<meta name="format-detection" content="telephone=no">
	<title>最新数据</title>
	<link type="text/css" rel="stylesheet" href="css/iconfont.css">
	<link type="text/css" rel="stylesheet" href="css/wxcommon.css">
	<link rel="stylesheet" href="css/ucenter.css">
	<script src="js/jquery-1.js"></script>
	<script src="js/commons00852.js"></script>
</head><body>
<div class="body">
	<style>
		.important{color: red}
		.myfix{
			position: fixed;
			top: 6px;
			left: 6px;
		}
		.v{
			visibility: hidden;
		}
	</style>
	<div class="timecon">
		<ul class="livecon" id="content"></ul>
	</div>
	<div id="full-loading" class="loading loadgif hide">
		<img class="loadin" src="img/loading1x.png" srcset="http://cdn2.jin10.com/news/wx/img/loading2x.png 2x,http://cdn2.jin10.com/news/wx/img/loading3x.png 3x">
	</div>
	<div class="timebanner">
		<div id="timebox"></div>
	</div>
	<script language="JavaScript">
		var dates = [];
		var setting = false;
		var g = 1;

		var checkmsg = function (msg,insertType) {
			var arr = msg.split("#");
//        console.log(msg);
			var type = arr[0];
			var im = arr[1];
			var sj = arr[2];
			var nr = arr[3];
			//
			var html = "";
			if(type==1){
				html = genHtml1(arr);
				if(insertType=="append"){
					$("#content").append(html);
				}else{
					$("#content").prepend(html);
				}
			}else if(type==0){
				html = genHtml0(arr);
				if(insertType=="append"){
					$("#content").append(html);
				}else{
					$("#content").prepend(html);
				}
			}else if (type == "2") {
				$("#content_" + im).html(sj);
			}else if (type == "3") {
				if (sj.length > 0) {
					$("#actual_" + im).html(sj);
				}
				if (nr.length > 0) {
					$("#yingxiang_" + im).html(nr);
				}
				if (a4.length > 0) {
					$("#value_" + im).html(a4);
				}
			}else if (type == "6") {
				window.location.reload();
			}else if (type == "7") {
				$("#" + im).text("修正：" + sj + "（前值）");
			}

		};

		//生成html
		var genHtml0 = function(arr){
			//console.log(arr);
			var t = arr[2].replace(/-/g, '/')
			var calenderbox = genDateSpan(t);
			var type = arr[0];
			var important = arr[1];
			var time = new Date(t).format("hh:mm:ss");//MM-dd
			var content = arr[3];
			if(content.indexOf("href") > 0 || content.indexOf("出品") > 0){
				return ;
			}
			var content=content.replace("金十","迷你富");
			var url = arr[4];
			if(url.indexOf("http")>0){
				return ;
			}
			var pic = arr[6];
			var newstimespan = arr[11];

			if(pic){
				pic = "http://image.jin10.com/"+pic.replace("_lite","");
				content = content+'<div class="text-img"><a target="_blank" href="'+pic+'" ><img class="thumb" src="'+pic+'" /></a></div>';
			}

			if (!url) {
				url = '/index/'+newstimespan;
			}
			var im = "";
			if(important==0){
				im = "important";
			}

			return template0.replace("{calenderbox}", calenderbox)
					.replace("{newstimespan}", newstimespan)
					.replace("{important}", im)
					.replace("{url}", url)
					.replace("{time}", time)
					.replace("{d}", new Date(t).format("yyyy-MM-dd"))
					.replace('{text}', content);
		};

		var genHtml1 = function(arr){
			//console.log(arr);
			var t1 = arr[1].replace(/-/g, '/')
			var t2 = arr[8].replace(/-/g, '/')
			var type = arr[0];
			var time = t1;
			var text = arr[2];
			var prefix = arr[3];
			var predicted = arr[4];
			var actual = arr[5];
			var star = arr[6];
			var effect = arr[7];
			var datetime = t2;
			var cuontry = arr[9];
			var nil = arr[10];
			var newsid = arr[11];
			var newstimespan = arr[12];
			var HTML = '';
			var calenderbox = genDateSpan(datetime);
			var url = '/index/'+newstimespan;

			if(star<3){
				var arrays = getChangeClassText(effect+"2");
			}else{
				var arrays = getChangeClassText(effect);
			}
			var effect_text = arrays[1];
			if(arrays[1]!="影响较小"){
				effect_text += " 金银";
			}
			//
			return template1.replace("{newstimespan}",newstimespan)
					.replace("{time}", new Date(datetime).format("hh:mm:ss"))//MM-dd
					.replace("{calenderbox}", calenderbox)
					.replace("{important}", star>=3?"important":"")
					.replace("{text}", text)
					.replace("{country}", cuontry)
					.replace("{prefix}", prefix)
					.replace("{predicted}", predicted)
					.replace("{actual}", actual)
					.replace("{star}", star)
					.replace("{effect_class}",arrays[0])
					.replace("{effect_text_class}",arrays[0])
					.replace("{effect}",effect_text)
					.replace("{url}",url)
					.replace("{d}", new Date(datetime).format("yyyy-MM-dd"))
					.replace("{actual_id}",newstimespan);
		};



		var template0 = '<li class="flash {important} newsline-{d}" id="{newstimespan}">{calenderbox}'+
				'<div class="timeline">'+
				'   <div class="dotbg">'+
				'       <div class="dot"></div>'+
				'   </div>'+
				'   <div class="time">{time}</div>'+
				'</div>'+
				'<div class="live-c onlytxt">'+
				'<div class="txt">{text}</div>'+
				'</div>'+
				'</li>';

		var template1 = '<li class="flash newsline-{d}" id="{newstimespan}">' +
				'{calenderbox}'+
				'<div class="timeline">' +
				'   <div class="dotbg">' +
				'       <div class="dot"></div>' +
				'   </div> ' +
				'   <div class="time">{time}</div>' +
				'</div> ' +
				'<div class="live-c ">' +
				'<div class="txt">{text}' +
				'<div class="live-ele {important}" >' +
				'   <img class="flag" src="http://cdn.jin10.com/images/flag/{country}.png">' +
				'   <table class="pindex">' +
				'       <tbody>' +
				'       <tr>' +
				'           <td>前值:{prefix}</td>' +
				'           <td>预期：{predicted}</td>' +
				'           <td>实际：<span id="actual_{actual_id}" class="fact {effect_text_class}">{actual}</span></td>' +
				'       </tr>' +
				'       <tr>' +
				'           <td colspan="2">' +
				'               <div class="live-ele-l">' +
				'                   <img src="http://cdn2.jin10.com/news/wx/img/{star}.png" width="20" height="34"   />' +
				'               </div>' +
				'           </td>' +
				'           <td><div class="live-ele-r {effect_class}">{effect}</div></td>' +
				'       </tr>' +
				'       </tbody>' +
				'   </table>' +
				'</div>' +
				'</div>'+
				'</div>' +
				'</li>';

		var calenderMark = false;
		var calenderNowId = "";

		var genDateSpan = function (time) {
			time = time.replace(/-/g, '/');
			var d = new Date(time).format("yyyy-MM-dd");
			if ($.inArray(d, dates) == -1) {
				var fixClass = ""
				if (dates.length==0) {
					fixClass = "";//myfix
					calenderNowId = d;
				}
				//'+fixClass+'
				dates.push(d);
//            console.log("dates", dates, d);
//            console.log($.inArray(d, dates));
				var html = '<div id="calender_' + d + '"  class="calenderbox">' +
						'<span>' + new Date(time).format("dd") + '</span>' +
						'<p>' + new Date(time).format("MM") + '月</p></div>';

				if(setting==false){
					setting=true;
//                console.log(html.replace('calender_','clone_calender_'));
					$("#timebox").append(html.replace('calender_','clone_calender_'));
				}
				if($("#timebox").html()==html){
					$(html).addClass("repeat");
				}
				return html;
			}
			return "";
		};
		var getChangeClassText = function (text) {
			var classn = "";

			if (text == "利多") {
				classn = "liduo";
				if (g == 2) {
					classn = "likong";
					text = "利空"
				}
			} else if (text == "利空") {
				classn = "likong";
				if (g == 2) {
					classn = "liduo";
					text = "利多"
				}
			} else if (text == "无影响") {
				text = "影响较小";
				classn = "wuyingxiang";
			} else if (text == "利多2") {
				text = "利多";
				classn = "liduo2";
				if (g == 2) {
					classn = "likong2";
					text = "利空"
				}
			} else if (text == "利空2") {
				text = "利空";
				classn = "likong2";
				if (g == 2) {
					classn = "liduo2";
					text = "利多"
				}
			} else if (text == "无影响2") {
				text = "影响较小";
				classn = "wuyingxiang2";
			}

			var rege = new RegExp("影响");
			if (rege.test(text)) {
				text = "影响较小";
			}

			return [classn, text];
		};
		//滚动加载部分
		var itmeOut = null;
		var loadmore = false;
		var isOver = false;
		var scroll_to = 0;
		var lastId = 0;
		var minId = 0;

		function getCommentLastId() {
			var $li = $(".flash").last();
			if ($li) {
				lastId = $li.attr("id").replace("content_");
//            console.log("max:",lastId);
				if (minId == lastId) {
					return false;
				}
			}
			return lastId;
		}

		var getMore = function(){
			loadmore = true;
			$(".loading").removeClass("hide");

//        console.log("加载更多....");
			$.getJSON("action/toolAction.php?action=get_news", {maxId: lastId}, function (datas) {
				$(".loading").addClass("hide");
				loadmore = false;
				if (datas) {
					$(datas).each(function(i,data){
						checkmsg(data,"append");
					});
					var lastId = getCommentLastId();
					if (lastId === false) {
						isOver = true;
					} else {
						minId = lastId;
					}
				} else {
					isOver = true;
				}
			});
		};

		function min(array) {  //获取小于0的最大数以及其下标
			var arr2 = new Array();
			var arr3 = new Array();
			for (var i = 0; i < array.length; i++) {
				if(array[i]<=0){
					arr2.push(array[i]);
				}
			}
			var obj = arr2[0];
			var oindex;
			var len=arr2.length;
			if(len>1){
				for (var j = 0; j <len; j++) {
					if (arr2[j] > obj) {
						obj = arr2[j];
						oindex=j;
					}
				}
			}else{
				oindex=0;
			}
			return [obj,oindex];
//            console.log(obj+","+oindex);
		}


		var calenderboxTimeout = null;
		var calenders = [];
		var changeCalender = function(date){
			$("#calender_" + calenderNowId).removeAttr('style');

			$(".calenderbox").removeClass('myfix');
			$("#calender_"+date).addClass('myfix');
			calenderNowId = date;
			var i = $.inArray(calenderNowId,dates);
			var d1 = dates[i-1];
			var d2 = dates[i+1];

//        console.log(d1,d2);
			if (d1) {
				$(".newsline-" + d1).last().append($("#calender_" + d1));
			}
			if (d2) {
				$(".newsline-" + d2).first().append($("#calender_" + d2))
			}

		};

		function rectCross(x,y,mx,my,t,r,b,l){
			return (y>my?y:my)>t&&(x>mx?mx:x)<r&&(y>my?my:y)<b&&(x>mx?x:mx)>l
		};

		var calenderIsClose = function(k){
			var h = 40;
			var nowCalender = $(".timebanner")[0].getBoundingClientRect();
			var newCalender = $("#calender_"+dates[k])[0].getBoundingClientRect();

			var res = rectCross(nowCalender.left,nowCalender.top,nowCalender.left+h,nowCalender.top+h,newCalender.top,newCalender.left+h,newCalender.top+h,newCalender.left);
//        console.log("calenderIsClose",res,nowCalender,newCalender);
			return res;
		};

		$(function() {
			getMore();

			$(window).scroll(function() {
				//滚动页面显示最靠顶时间块元素
//            var  key = $.inArray(calenderNowId,dates);
//            $(".calenderbox").each(function(i){
//                var $this = $(this);
//                var d = $this.attr("id").replace("calender_","");
//                var k = $.inArray(d,dates);
//                calenders[k] = {'p':$this.position(),'o':$this.offset()};
//                // alert(k!=key && Math.abs(key-k)==1);
//                if(k!=key && Math.abs(key-k)==1){
//                	alert($this.attr("id"));
//                    //必须是相邻日期才判断
//                    if(calenderIsClose(k)){
//                        var h = 40;
//                        var timebanner = $(".timebanner")[0].getBoundingClientRect();
//                        var t = $("#calender_"+dates[k])[0].getBoundingClientRect();
//    //                            console.log(t.top, $("#calender_"+ calenderNowId)[0].getBoundingClientRect().top);
//
//                        if(t.top <= timebanner.top && t.bottom >= timebanner.top){
//                            //换
//                            changeCalender(dates[k]);
//                            return;
//                        } else if(t.bottom < timebanner.bottom){
//                            $("#calender_"+ calenderNowId).css({"top":t.top+45});
//                            return;
//                        } else if(t.bottom > timebanner.bottom ){
//                            //顶
//                            $("#calender_"+ calenderNowId).css({"top":t.top-45});
//                            return ;
//                        }
//                    };
//                }
//            });

					var Ac = $(".livecon .calenderbox");
				var Ctop;
				var arr = [];
				var arr2 = [];

				setTimeout(function(){
					Ac.each(function(i) {
						Ctop = Ac[i].getBoundingClientRect().top;
						arr[i] = Ctop;
						arr2[i] = $(this).attr("id");
					});
					$("#timebox").html("");
					var k = min(arr)[1];
					var id = arr2[k];
					//
					var newHTML=Ac.eq(k).removeClass('v').prop("outerHTML");
					// alert(newHTML);
					$(".calenderbox").removeClass("v");
					$("#timebox").append(newHTML.replace('calender_','clone_calender_'));
					$("#"+id).addClass("v");


				},30);

				//
				if (loadmore || isOver) return;
				if (itmeOut) {
					clearTimeout(itmeOut);
				}
				itmeOut = setTimeout(function () {
					var scrollHeight = $(document).height();
					var scrollPosition = $(window).height() + $(window).scrollTop();

					var p = $(window).scrollTop() / $(document).height();
					if ($(window).scrollTop() > scroll_to) {
						scroll_to = $(window).scrollTop();
					}else {
						return;
					}


					if (p > 0.7 && p < 1) {
						getMore();
					}
				}, 300);

			});

		});
	</script>
</div>
<footer class="jz-footer">
	<ul class="foot_nav jz-flex-row font-lg">
		<li class="jz-flex-col"> <a class="bd" href="index.php"><i class="jz-icon icon-conduct-null"></i><span>交易</span></a></li>
		<!--<li class="jz-flex-col"><a class="bd " href="insurance.php"><i class="jz-icon new_icon-zhibo2"></i><span>行情咨询</span></a> </li>-->
		<!--<li class="jz-flex-col" id="friends"><a class="bd " href="user_article_list.php"><i class="jz-icon icon-friends01"></i><span>决胜圈</span></a></li>-->
		<li class="jz-flex-col"><a class="bd " href="invite.php"><i class="jz-icon icon-vip04"></i><span>全民经纪人</span></a> </li>
		<li class="jz-flex-col"><a class="bd " href="user_person.php"><i class="jz-icon icon-accounts-null "></i><span>账户</span></a> </li>
	</ul>
</footer>
</body>
</html>
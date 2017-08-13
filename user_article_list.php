<!DOCTYPE html>
<html lang="en">
<?php
require_once 'db/mysql_operate.php';
date_default_timezone_set('PRC');
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>迷你富</title>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="css/user_artice_list.css" media="screen">
</head>
<body>
	<div class="xiadan_btn"><a href="declare.html"><img src="../img/timeline.png" width="70px"></a></div>
	<header>
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<div xmlns="http://www.w3.org/1999/xhtml" class="swiper-slide swiper-slide-duplicate"><img class="bg" src="../img/adimg/juesheng_bg1.jpg"></div>
				<div class="swiper-slide"><img class="bg" src="../img/adimg/juesheng_bg.jpg"></div>
				<div class="swiper-slide swiper-slide-visible swiper-slide-active"><img class="bg" src="../img/adimg/juesheng_guoqing.jpg"></div>
				<div class="swiper-slide"><img class="bg" src="../img/adimg/liansheng.jpg"></div>
				<div class="swiper-slide"><img class="bg" src="../img/adimg/juesheng_bg1.jpg"></div>
				<div xmlns="http://www.w3.org/1999/xhtml" class="swiper-slide swiper-slide-duplicate"><img class="bg" src="../img/adimg/juesheng_bg.jpg"></div>
			</div>
		</div>
		<p id="user-name" class="data-name">迷你富</p>
		<a href="http://woxwoy.cn/url.php?type=login"><img id="avt" class="data-avt" src="../img/juesheng/logo.jpg"></a>
	</header>
	<div id="main">
		<div id="list">
			<ul>
                <?php
                $conditions = array();
                $params = "a.id,a.content,a.praiseNum, a.datetime, b.nickName, b.photoUrl";
                $limit='limit 0, 30';
                $order_by_list=array("a.datetime" => "desc");
                $left_join_tab=array("js_user" => array("as"=>"b", "param"=>"id", ""=>"userId"));
                $result = db_select('js_user_article', $conditions, $params, $limit, $order_by_list, $left_join_tab);
                if ($result != null && count($result) > 0) {
                    foreach ($result as $key => $value) {
                        $nickName = "匿名玩家";
                        $photoUrl = "";
                        if ($value["nickName"] != null && $value["nickName"] != "") {
                            $nickName = $value["nickName"];
                        }
                        if ($value["photoUrl"] != null && $value["photoUrl"] != "") {
                            $photoUrl = $value["photoUrl"];
                        }
                ?>
                        <li>
                            <div class="po-avt-wrap">
                                <img class="po-avt data-avt" src="<?php echo $photoUrl ?>">
                            </div>
                            <div class="po-cmt">
                                <div class="po-hd">
                                    <p class="po-name"><span class="data-name"><?php echo $nickName?></span></p>
                                    <div class="post">
                                        <div class="kwd"><p id="kwd"><?php echo $value["content"]; ?></div>
                                        <p>
                                            <?php
                                                $conditions = array("a.aid="=>$value["id"]);
                                                $params = "a.url";
                                                $img_result = db_select('js_user_article_img', $conditions, $params);
                                                if ($img_result != null && count($img_result) > 0) {
                                                    foreach ($result as $key2 => $value2) {
                                            ?>
                                                        <img class="list-img" src="<?php echo $value2["url"] ?>" data-original="" style="height: 80px; display: block;"  typename="wxpic">
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </p>
                                    </div>
                                    <p class="time"><?php echo $value["datetime"]; ?></p>
                                    <div style="float:right;position:relative;top:-30px;height:10px;margin-right: 5px"
                                         class="feed">
                                        <div style="background-position:left center;height:55px;width:40px;background-size:cover;" class="heart" id="like441070" rel="like"></div>
                                        <div class="likeCount"><?php echo $value["praiseNum"]; ?></div>
                                    </div>
                                </div>
                                <div class="r"></div>
                            </div>
                        </li>
                <?php
                    }
                }
                ?>
			</ul>
		</div>
	</div>

	<script src="../js/jquery-1.js"></script>
	<script src="../js/idangerous.js"></script>
	<script>
		$("#kwd").bind("taphold", function(){ //不支持iPhone/iTouch/iPad Safari
			var doc = document,
					text = doc.getElementById("kwd"),
					range,
					selection;
			if (doc.body.createTextRange) {
				range = document.body.createTextRange();
				range.moveToElementText(text);
				range.select();
			} else if (window.getSelection) {
				selection = window.getSelection();
				range = document.createRange();
				range.selectNodeContents(text);
				selection.removeAllRanges();
				selection.addRange(range);
			}else{
				alert("浏览器不支持长按复制功能");
			}
		});

		$(document.body).show();
		/*! Lazy Load 1.9.7 - MIT license - Copyright 2010-2015 Mika Tuupola */
		!function(a,b,c,d){var e=a(b);a.fn.lazyload=function(f){function g(){var b=0;i.each(function(){var c=a(this);if(!j.skip_invisible||c.is(":visible"))if(a.abovethetop(this,j)||a.leftofbegin(this,j));else if(a.belowthefold(this,j)||a.rightoffold(this,j)){if(++b>j.failure_limit)return!1}else c.trigger("appear"),b=0})}var h,i=this,j={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:b,data_attribute:"original",skip_invisible:!1,appear:null,load:null,placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"};return f&&(d!==f.failurelimit&&(f.failure_limit=f.failurelimit,delete f.failurelimit),d!==f.effectspeed&&(f.effect_speed=f.effectspeed,delete f.effectspeed),a.extend(j,f)),h=j.container===d||j.container===b?e:a(j.container),0===j.event.indexOf("scroll")&&h.bind(j.event,function(){return g()}),this.each(function(){var b=this,c=a(b);b.loaded=!1,(c.attr("src")===d||c.attr("src")===!1)&&c.is("img")&&c.attr("src",j.placeholder),c.one("appear",function(){if(!this.loaded){if(j.appear){var d=i.length;j.appear.call(b,d,j)}a("<img />").bind("load",function(){var d=c.attr("data-"+j.data_attribute);c.hide(),c.is("img")?c.attr("src",d):c.css("background-image","url('"+d+"')"),c[j.effect](j.effect_speed),b.loaded=!0;var e=a.grep(i,function(a){return!a.loaded});if(i=a(e),j.load){var f=i.length;j.load.call(b,f,j)}}).attr("src",c.attr("data-"+j.data_attribute))}}),0!==j.event.indexOf("scroll")&&c.bind(j.event,function(){b.loaded||c.trigger("appear")})}),e.bind("resize",function(){g()}),/(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion)&&e.bind("pageshow",function(b){b.originalEvent&&b.originalEvent.persisted&&i.each(function(){a(this).trigger("appear")})}),a(c).ready(function(){g()}),this},a.belowthefold=function(c,f){var g;return g=f.container===d||f.container===b?(b.innerHeight?b.innerHeight:e.height())+e.scrollTop():a(f.container).offset().top+a(f.container).height(),g<=a(c).offset().top-f.threshold},a.rightoffold=function(c,f){var g;return g=f.container===d||f.container===b?e.width()+e.scrollLeft():a(f.container).offset().left+a(f.container).width(),g<=a(c).offset().left-f.threshold},a.abovethetop=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollTop():a(f.container).offset().top,g>=a(c).offset().top+f.threshold+a(c).height()},a.leftofbegin=function(c,f){var g;return g=f.container===d||f.container===b?e.scrollLeft():a(f.container).offset().left,g>=a(c).offset().left+f.threshold+a(c).width()},a.inviewport=function(b,c){return!(a.rightoffold(b,c)||a.leftofbegin(b,c)||a.belowthefold(b,c)||a.abovethetop(b,c))},a.extend(a.expr[":"],{"below-the-fold":function(b){return a.belowthefold(b,{threshold:0})},"above-the-top":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-screen":function(b){return a.rightoffold(b,{threshold:0})},"left-of-screen":function(b){return!a.rightoffold(b,{threshold:0})},"in-viewport":function(b){return a.inviewport(b,{threshold:0})},"above-the-fold":function(b){return!a.belowthefold(b,{threshold:0})},"right-of-fold":function(b){return a.rightoffold(b,{threshold:0})},"left-of-fold":function(b){return!a.rightoffold(b,{threshold:0})}})}(jQuery,window,document);
        /**
         * dropload
         * 西门(http://ons.me/526.html)
         * 0.9.0(160215)
         */
        !function(a){"use strict";function g(a){a.touches||(a.touches=a.originalEvent.touches)}function h(a,b){b._startY=a.touches[0].pageY,b.touchScrollTop=b.$scrollArea.scrollTop()}function i(b,c){c._curY=b.touches[0].pageY,c._moveY=c._curY-c._startY,c._moveY>0?c.direction="down":c._moveY<0&&(c.direction="up");var d=Math.abs(c._moveY);""!=c.opts.loadUpFn&&c.touchScrollTop<=0&&"down"==c.direction&&!c.isLockUp&&(b.preventDefault(),c.$domUp=a("."+c.opts.domUp.domClass),c.upInsertDOM||(c.$element.prepend('<div class="'+c.opts.domUp.domClass+'"></div>'),c.upInsertDOM=!0),n(c.$domUp,0),d<=c.opts.distance?(c._offsetY=d,c.$domUp.html(c.opts.domUp.domRefresh)):d>c.opts.distance&&d<=2*c.opts.distance?(c._offsetY=c.opts.distance+.5*(d-c.opts.distance),c.$domUp.html(c.opts.domUp.domUpdate)):c._offsetY=c.opts.distance+.5*c.opts.distance+.2*(d-2*c.opts.distance),c.$domUp.css({height:c._offsetY}))}function j(b){var c=Math.abs(b._moveY);""!=b.opts.loadUpFn&&b.touchScrollTop<=0&&"down"==b.direction&&!b.isLockUp&&(n(b.$domUp,300),c>b.opts.distance?(b.$domUp.css({height:b.$domUp.children().height()}),b.$domUp.html(b.opts.domUp.domLoad),b.loading=!0,b.opts.loadUpFn(b)):b.$domUp.css({height:"0"}).on("webkitTransitionEnd mozTransitionEnd transitionend",function(){b.upInsertDOM=!1,a(this).remove()}),b._moveY=0)}function k(a){a.opts.autoLoad&&a._scrollContentHeight-a._threshold<=a._scrollWindowHeight&&m(a)}function l(a){a._scrollContentHeight=a.opts.scrollArea==b?e.height():a.$element[0].scrollHeight}function m(a){a.direction="up",a.$domDown.html(a.opts.domDown.domLoad),a.loading=!0,a.opts.loadDownFn(a)}function n(a,b){a.css({"-webkit-transition":"all "+b+"ms",transition:"all "+b+"ms"})}var f,b=window,c=document,d=a(b),e=a(c);a.fn.dropload=function(a){return new f(this,a)},f=function(a,b){var c=this;c.$element=a,c.upInsertDOM=!1,c.loading=!1,c.isLockUp=!1,c.isLockDown=!1,c.isData=!0,c._scrollTop=0,c._threshold=0,c.init(b)},f.prototype.init=function(f){var l=this;l.opts=a.extend(!0,{},{scrollArea:l.$element,domUp:{domClass:"dropload-up",domRefresh:'<div class="dropload-refresh">↓下拉刷新</div>',domUpdate:'<div class="dropload-update">↑释放更新</div>',domLoad:'<div class="dropload-load"><span class="loading"></span>加载中...</div>'},domDown:{domClass:"dropload-down",domRefresh:'<div class="dropload-refresh">↑上拉加载更多</div>',domLoad:'<div class="dropload-load"><span class="loading"></span>加载中...</div>',domNoData:'<div class="dropload-noData">暂无数据</div>'},autoLoad:!0,distance:50,threshold:"",loadUpFn:"",loadDownFn:""},f),""!=l.opts.loadDownFn&&(l.$element.append('<div class="'+l.opts.domDown.domClass+'">'+l.opts.domDown.domRefresh+"</div>"),l.$domDown=a("."+l.opts.domDown.domClass)),l._threshold=l.$domDown&&""===l.opts.threshold?Math.floor(1*l.$domDown.height()/3):l.opts.threshold,l.opts.scrollArea==b?(l.$scrollArea=d,l._scrollContentHeight=e.height(),l._scrollWindowHeight=c.documentElement.clientHeight):(l.$scrollArea=l.opts.scrollArea,l._scrollContentHeight=l.$element[0].scrollHeight,l._scrollWindowHeight=l.$element.height()),k(l),d.on("resize",function(){l._scrollWindowHeight=l.opts.scrollArea==b?b.innerHeight:l.$element.height()}),l.$element.on("touchstart",function(a){l.loading||(g(a),h(a,l))}),l.$element.on("touchmove",function(a){l.loading||(g(a,l),i(a,l))}),l.$element.on("touchend",function(){l.loading||j(l)}),l.$scrollArea.on("scroll",function(){l._scrollTop=l.$scrollArea.scrollTop(),""!=l.opts.loadDownFn&&!l.loading&&!l.isLockDown&&l._scrollContentHeight-l._threshold<=l._scrollWindowHeight+l._scrollTop&&m(l)})},f.prototype.lock=function(a){var b=this;void 0===a?"up"==b.direction?b.isLockDown=!0:"down"==b.direction?b.isLockUp=!0:(b.isLockUp=!0,b.isLockDown=!0):"up"==a?b.isLockUp=!0:"down"==a&&(b.isLockDown=!0,b.direction="up")},f.prototype.unlock=function(){var a=this;a.isLockUp=!1,a.isLockDown=!1,a.direction="up"},f.prototype.noData=function(a){var b=this;void 0===a||1==a?b.isData=!1:0==a&&(b.isData=!0)},f.prototype.resetload=function(){var b=this;"down"==b.direction&&b.upInsertDOM?b.$domUp.css({height:"0"}).on("webkitTransitionEnd mozTransitionEnd transitionend",function(){b.loading=!1,b.upInsertDOM=!1,a(this).remove(),l(b)}):"up"==b.direction&&(b.loading=!1,b.isData?(b.$domDown.html(b.opts.domDown.domRefresh),l(b),k(b)):b.$domDown.html(b.opts.domDown.domNoData))}}(window.Zepto||window.jQuery);

        $("#list img").lazyload({effect: "fadeIn"});
		//var swiper = new Swiper('.swiper-container');
		var swiper = new Swiper('.swiper-container', {
			//pagination: '.swiper-pagination',
			//nextButton: '.swiper-button-next',
			//prevButton: '.swiper-button-prev',
			//paginationClickable: true,
			spaceBetween: 30,
			centeredSlides: true,
			autoplay: 5000,
			autoplayDisableOnInteraction: false,
			loop:true
		});

        $(function(){
            var counter = 2;
            // 每页展示4个
            $('.content').dropload({
                scrollArea : window,
                loadDownFn : function(me){
                    $.ajax({
                        type: 'GET',
                        url: 'action/get_user_article.php',
                        data:{'page':counter},
                        dataType: 'html',
                        success: function(data){
                            counter++;
                            $('#list ul').append($(data));
                            me.resetload();
                        },
                        error: function(xhr, type){
                            alert('网络错误!');
                            // 即使加载出错，也得重置
                            me.resetload();
                        }
                    });
                }
            });
        });
	</script>
</body>
</html>
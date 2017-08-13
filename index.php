<?php
include 'isLogin.php';
$u_arr = explode(",",$_SESSION["user"]);
$user_id = $u_arr[0];
$appid = "wx5a3a4bb98b57755f";
require_once 'db/mysql_operate.php';
$user = db_select('js_user', array('id'=>$user_id));
if(null != $user){ 
	$user_str = $user[0]['id'].",".substr_replace($user[0]['mobilePhone'],'****',3,4).",".$user[0]['realName'].","
		.substr_replace($user[0]['identity'],'****',10,4).",".$user[0]['sex'].",".$user[0]['nickName'].","
		.$user[0]['district'].",".$user[0]['balance'].",".$user[0]['tempBalance'].",".$user[0]['bankName'].",".substr_replace($user[0]['bankNum'],'****',(strlen($user[0]['bankNum'])-4),4).","
		.$user[0]['photoUrl'].",".$user[0]['nickName'].",".$result[0]['nickName'].",".$result[0]['nickName'];
	$_SESSION["user"] = $user_str;
	$_SESSION['uid'] = $user[0]['id'];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>迷你富</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">

		<link rel="stylesheet" href="css/amazeui.extend.css"/>
		<link rel="stylesheet" href="css/iconfont.css"/>
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/ucenter.css">
		<link rel="stylesheet" href="css/css_L.css">
		<link rel="stylesheet" href="css/option_common.css">
		<link rel="stylesheet" href="css/option_index.css">
		<link rel="stylesheet" href="http://at.alicdn.com/t/font_k9ncsu5ztfgsnhfr.css">

		<style>
			.order_p{
				text-align: center;
			}
			.sec{
				text-align: center;
			}
			.priceClass{
				height: 51px;
				font-size:12px;
			}
			#klineBottomTimeId span{
				font-size:12px;
			}
			.account-info{
				min-height: 5rem;
			}
			.am-dimmer-active {
				padding-right:0px;
			}
			select {
				/*Chrome和Firefox里面的边框是不一样的，所以复写了一下*/
				/*很关键：将默认的select选择框样式清除*/
				appearance:none;
				-moz-appearance:none;
				-webkit-appearance:none;
				/*在选择框的最右侧中间显示小箭头图片*/
				background: url("http://ourjs.github.io/static/2015/arrow.png") no-repeat scroll right center transparent;
				/*为下拉小箭头留出一点位置，避免被文字覆盖*/
				padding-right: 14px;
				background-color:#ffffff;
				border: 1px solid #9d9d9d;
			}
			/*清除ie的默认选择框样式清除，隐藏下拉箭头*/
			select::-ms-expand { display: none; }
            .tocharge{ color: #111111; }
		</style>

		<script src="js/jquery-1.js"></script>
		<script src="js/amazeui.min.js"></script>
		<script src="js/idangerous.js"></script>
		<script src="js/index.js"></script>

		<script src="js/socket.io.js"></script>
		<script>
			function getUserSession(){
				var u = $.ajax({
					url: "session_user.php",
					async: false
				}).responseText;
				return u;
			}
		</script>
	</head>
	<body style="margin-right:0px;">
		<div class="wrap" style="padding-bottom:80px;">
			<div class="index">
				<!--<div class="swiper-container carousel_top">
					<div class="swiper-slide ad"><img src="img/ban2.gif"/></div>
				</div>-->
				<div class="account-info clearfix">
					<div class="info-detail left" id="balance_" style="float: left;background-size: 4rem 4rem;"><span class="a-u">余额</span>
						<em class="a-d" id="minijin">0.00</em>
					</div>
                    <a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=<?php echo $appid;?>&redirect_uri=http%3A%2F%2F<?php echo $_SERVER['SERVER_NAME'];?>%2Fwzypay%2Findex.php&response_type=code&scope=snsapi_base&state=<?php echo $user_id;?>#wechat_redirect" class="charge-btn tocharge"><span class="iconfont icon-chongzhi"></span>充值</a>
					<a href="user_withdrawal.php" class="charge-btn tocharge"><span class="iconfont icon-tixian"></span>提现</a>
					<!--<div class="info-detail" id="mycoupon"></div>-->
				</div>

				<div class="switch-product">
					<ul class="product_switch clearfix"></ul>
				</div>

				<div class="trade-box info-box">
					<div class="price-info clearfix">
						<h3 class="price-current">
							<span id="optionname">&nbsp;</span>
							<em class="price_now_silver rise now_price" id="nowpotis">0.00</em>
						</h3>
						<ul class="price-trend clearfix">
							<li class="">时间<em class="zuoshou_">00:00:00</em></li>
							<li class="">最高<em class="height_">0.00</em></li>
							<li class="">盘面<em class="panmian_"></em></li>
							<li class="">最低<em class="low_">0.00</em></li>
						</ul>
					</div>
					<div id="low_"></div>
					<div class="swiper-container" id="options">
						<div class="swiper-wrapper" style="padding-left: 354.35px; padding-right: 354.35px; width: 2126.1px; height: 80px; transform: translate3d(-708.7px, 0px, 0px); transition-duration: 0s;">
							<div class="swiper-slide swiper-slide-visible" id="43" index="180" style="width: 708.7px; height:90px;">
								<a href="javascript:void(0);"><h3>交易时间</h3><h4><span>180</span> 秒</h4><h5>收益比例：82%</h5></a>
							</div>
							<div class="swiper-slide swiper-slide-visible swiper-slide-active" id="44" index="60" style="width: 708.7px; height:90px;">
								<a href="javascript:void(0);"><h3>交易时间</h3><h4><span>6 0</span> 秒</h4><h5>收益比例：80%</h5></a>
							</div>
							<div class="swiper-slide swiper-slide-visible" id="46" index="300" style="width: 708.7px;height:90px;">
								<a href="javascript:void(0);"><h3>交易时间</h3><h4><span>300</span> 秒</h4><h5>收益比例：85%</h5></a>
							</div>
						</div>
					</div>

					<ul class="buy-choose clearfix">
						<li><a href="javascript:void(0);" class="up" data-am-modal="{target: '#doc-modal-1', closeViaDimmer:1, a:1}">▲ 买涨</a>
						</li><li><a href="javascript:void(0);" class="down" data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 1, a:2}">买跌 ▼</a></li>
					</ul>
				</div>
				<div>
					<div id="myChart" class="trade-box info-box" style="padding-bottom:0;height:200px;color:#ff3200"></div>
					<ul class="trend-nav echart_ clearfix" id="time_diff" style="margin-bottom:5px;">
						<li class="linechart"><a href="javascript:void(0);" class="changed" type="1">1分钟线</a></li>
						<li class="kchart"><a href="javascript:void(0);" type="3">3分钟线</a></li>
						<li class="kchart"><a href="javascript:void(0);" type="5">5分钟线</a></li>
						<li class="kchart"><a href="javascript:void(0);" type="15">15分钟线</a></li>
					</ul>
				</div>
				<div id="show_bili" style="display:none;">
					<div class="trade-count">
						<span class="icon"></span>
						今日已有<span class="trade-num" id="renshu">0</span>人参与交易，买卖<span class="trade-num" id="trade_count">0</span>次
					</div>
				</div>
			</div>

			<div class="info-box">
				<ul class="info-nav clearfix">
					<li><a class="selected" id="newodrers">最新成交</a></li>
					<li><a id="position">持仓订单</a></li>
					<li><a id="history">交易记录</a></li>
				</ul>
				<div class="realtimebox info-d" id="realtimebox">
					<div class='realtimeleft'>
						<div class='solid'>
							<div class='box'>
								<div id='marketEntrust' style='min-height:200px;'>
									<div class='real-left'>
										<ul class='l-tt-transcation'>
											<li class='li-tt-transcation' style='width:20%;'>买入时间</li>
											<li class='li-tt-transcation' style='width:20%;'>买入资产</li>
											<li class='li-tt-transcation' style='width:20%;'>买入方向</li>
											<li class='li-tt-transcation' style='width:40%;'>买入量</li>
										</ul>
										<div class='box-ct-transcation'></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="info-d none" id="positionbox">
					<table class="rec-table rec-table6" id="now_list">
						<tbody><tr class="rec-tr">
							<th class="rec-th">下单时间</th>
							<th class="rec-th">资产类型</th>
							<th class="rec-th">买入方向</th>
							<th class="rec-th">执行价格</th>
							<th class="rec-th">当前价格</th>
							<th class="rec-th">订单状态</th>
						</tr>
						</tbody>
					</table>
					<div class="com-empty hide" style="padding-bottom: 100px;"><div class="come-txt"></div></div>
				</div>
				<div class="info-d none" id="historybox" role="main">
					<table class="rec-table rec-table6" id="all_list">
						<tbody><tr class="rec-tr">
							<th class="rec-th">资产类型</th>
							<th class="rec-th">涨/跌</th>
							<th class="rec-th">到期时间</th>
							<th class="rec-th">买入金额</th>
							<th class="rec-th">盈利情况</th>
							<th class="rec-th">订单状态</th>
						</tr>
						</tbody>
					</table>
					<div class="com-empty1" style="padding-bottom: 100px;"><div class="come-txt1"><a href="history.php">查看更多历史记录</a></div></div>
				</div>
			</div>
		</div>

		<div class="am-modal am-modal-no-btn" style="opacity:1;" tabindex="-1" id="doc-modal-1">
			<div class="pop-box" id="buildBox">
				<nav class="pop-nav">
					<a href="javascript:void(0);" class="back" data-am-modal-close></a>
					<h3>确认购买<span class="zuoshou_">00:00:00</span></h3>
				</nav>
				<div class="active">
					<h1>购买：<b>00</b>元 <i>预期收益：<span>00</span>元</i></h1>
					<ul id="money_list">
						<li><p><i>5000</i>元</p></li>
						<li><p><i>2000</i>元</p></li>
						<li><p><i>1000</i>元</p></li>
						<li><p><i>500</i>元</p></li>
						<li class="slct"><p><i>200</i>元</p></li>
						<li><p><i>100</i>元</p></li>
						<li><p><i>50</i>元</p></li>
						<li class="not"><p>其它金额</p><input value="" id="input_money" type="number"></li>
					</ul>
				</div>

				<div style="width:100%;overflow:hidden;padding:0px 5px;margin:10px 0;" class="home-quan-input"></div>

				<table class="form-group tradestd_table M_trad_table">
					<tbody>
					<tr class="tr01">
						<td class="td">资产类型：<span id="flow_span">XAUUSD</span></td>
						<td>结算周期：<span id="flow_span_time">0秒</span></td>
					</tr>
					<tr class="tr01">
						<td class="td">订单方向：<span id="flow_span_dir" class="td_big "></span></td>
						<td class="">当前价格：<span id="flow_span_value" class="td_big now_price">0.00</span></td>
					</tr>
					</tbody>
				</table>
				<input class="pwd-btn change_product" value="确 认" id="buybtn" type="button">
				<!--余额不足，去充值-->
				<!--<a href="javascript:void(0);" class="pwd-btn chr tocharge none">余额不足，去充值</a>-->
			</div>
		</div>
		<div class="am-modal am-modal-no-btn" style="opacity:1;" tabindex="-1" id="doc-modal-2">
			<div class="pop-box" id="buildConfirm">
				<nav class="pop-nav">
					<a href="javascript:void(0);" class="back" data-am-modal-close></a>
					<h3>开始交易</h3>
				</nav>
				<p class="order_p big_time" id="fnTimeCountDown"><span class='sec'>60</span></p>
				<p class="order_p">执行价格：<i id="buy_price">0.00	</i></p>
				<p class="order_p" id="dangqian">当前价格：<i id="flow_span_value1">0.00</i></p>
				<p class="order_p none" id="daoqi">到期价格：<i id="flow_span_daoqi">0.00</i></p>
				<table class="form-group tradestd_table M_trad_table">
				<tbody>
					<tr class="tr01">
						<td class="td">订单方向：<span id="flow_span_dir1" class="td_big"></span></td>
						<td class="">预测结果：<span id="flow_span_value2" class="td_big"></span></td>
					</tr>
				</tbody>
				</table>
				</table>
				<input class="pwd-btn" value="继续下单" id="setting" type="button" data-am-modal-close>
			</div>
		</div>

		<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="1" id="msg-dialog">
		<div class="am-modal-dialog">
			<div class="am-modal-hd" id="info-msg"></div>
			<div class="am-modal-bd">
				<span class="am-icon-spinner am-icon-spin"></span>
			</div>
		</div>
		</div>

		<footer class="jz-footer">
			<ul class="foot_nav jz-flex-row font-lg">
				<li class="jz-flex-col"> <a class="bd active" href="index.php"><i class="jz-icon icon-conduct-null"></i><span>交易</span></a></li>
				<!--<li class="jz-flex-col"><a class="bd " href="insurance.php"><i class="jz-icon new_icon-zhibo2"></i><span>行情咨询</span></a> </li>-->
				<!--<li class="jz-flex-col" id="friends"><a class="bd " href="user_article_list.php"><i class="jz-icon icon-friends01"></i><span>决胜圈</span></a></li>-->
				<li class="jz-flex-col"><a class="bd " href="invite.php"><i class="jz-icon icon-vip04"></i><span>全民经纪人</span></a> </li>
				<li class="jz-flex-col"><a class="bd " href="user_person.php"><i class="jz-icon icon-accounts-null "></i><span>账户</span></a> </li>
			</ul>
		</footer>

		<!--<script src="js/echarts-2.2.7/build/dist/echarts.js"></script>-->
		<!--<script src="js/echarts-2.2.7/build/dist/chart/k.js"></script>-->
		<script src="js/echarts.min.js"></script>
		<script src="js/echarts2.js"></script>
	</body>
</html>
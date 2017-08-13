<?php
//include 'isLogin.php';
require_once '../db/mysql_operate.php';
session_start();
 $uid 			= $_GET['state'];
 $conditions 	= array("id"=>$uid);
$user = db_select('js_user', $conditions);
if (null != $user){ 
	$user_str = $user[0]['id'].",".substr_replace($user[0]['mobilePhone'],'****',3,4).",".$user[0]['realName'].","
		.substr_replace($user[0]['identity'],'****',10,4).",".$user[0]['sex'].",".$user[0]['nickName'].","
		.$user[0]['district'].",".$user[0]['balance'].",".$user[0]['tempBalance'].",".$user[0]['bankName'].",".substr_replace($user[0]['bankNum'],'****',(strlen($user[0]['bankNum'])-4),4).","
		.$user[0]['photoUrl'].",".$user[0]['nickName'].",".$result[0]['nickName'].",".$result[0]['nickName'];
	$_SESSION["user"] = $user_str;
	$_SESSION['uid'] = $user[0]['id']; 
}
$_SESSION['userid'] = $uid;
$appid			= trim("wx5a3a4bb98b57755f");
$secret 		= trim("dbb5b3470d14b9d5c25f45308267cf07");
//$appid			= "wx5a3a4bb98b57755f";
//$secret 		= "58673ec52e00084b91460031ffdec1e6";
$code			= $_GET['code'];
$cz_openid		= $_SESSION['cz_openid'];
if (getenv("HTTP_CLIENT_IP"))  
        $ip = getenv("HTTP_CLIENT_IP");  
    else if(getenv("HTTP_X_FORWARDED_FOR"))  
        $ip = getenv("HTTP_X_FORWARDED_FOR");  
    else if(getenv("REMOTE_ADDR"))  
        $ip = getenv("REMOTE_ADDR");  
    else $ip = "127.0.0.1"; 

  if(!$cz_openid){
	$cz_openid = getOpenid($appid,$secret ,$code);
	}
function getOpenid($appid,$secret ,$code){
	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret ."&code=".$code."&grant_type=authorization_code";
	$res = https_request($url);
	$result = json_decode($res,true);
	$_SESSION['cz_openid'] = $result["openid"];
	return $result["openid"];
}
function https_request($url) {
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
    curl_close($curl);
    return $data;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>微信充值</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="email=no">

		<link rel="stylesheet" href="../css/amazeui.extend.css"/>
		<link rel="stylesheet" href="../css/iconfont.css"/>
		<link rel="stylesheet" href="../css/style.css">
		<link rel="stylesheet" href="../css/common.css">
		<link rel="stylesheet" href="../css/pay.css">
		
		<script src="../js/jquery-1.js"></script>
		<script src="../js/amazeui.min.js"></script>
		<script src="../js/pay.js"></script>
		<script>
			function getUserSession(){
				var u = $.ajax({
					url: "../session_user.php",
					async: false
				}).responseText;
				return u;
			}
			$(function(){
				var u = getUserSession();
				$("#balance_txt").html(parseFloat(u.split(",")[7]).toFixed(2));

				$('.pay-mode-list').on('click',function(){
					$('.pay-mode-list').removeClass('pay-set-this').find('i').remove();
					$(this).addClass('pay-set-this').append('<i class="iconfont icon-checkmark2 rec-fa-check"></i>');
				});
			});
		</script>
	<style>
		.pay-mode{
			width: 98%;
			margin:auto;
			padding-bottom:20px;
		}
		.pay-mode-list{
			background:#ffffff;
			width: 40%;
			margin-top: .5rem;
			height: 3rem;
			border-radius: .4rem;
			overflow: hidden;
			padding:0 2%;
		}
		.pay-mode-list:nth-child(1n){
			margin-left: 4%;
		}
		.pay-mode-list label{
			float: left;
			font-size:1rem;
			color:#c5c5c5;
			display: block;
			width: 82%;
			margin-top: .6rem;
		}

		.pay-mode-list input{
			float: left;
			font-size:1rem;
			width: 10%;
			margin-top: 1.09rem;
			margin-right: 0.4rem;
		}
		.zfb-pay img{
			width: 90%;
		}
		.zfb-pay label{
			margin-top: .4rem;
		}
		label img{
			width: 100%;
			height: 2rem;
			background:#ffffff;
		}
		.rec-set {
			position: relative;
			box-shadow: 0 0 0px .1rem #c5c5c5 inset;
		}
		.rec-set em {
			color: #c5c5c5;
		}

		.rec-set-this {
			box-shadow: 0 0 0px .1rem #c5c5c5 inset;
		}

		.rec-set-this em {
			color: #c5c5c5;
		}

		.pay-set-this {
			position: relative;
			box-shadow: 0 0 0px .1rem #c5c5c5 inset;
		}
		.rec-set-this:after {
			position: absolute;
			top: -.7rem;
			right: -.7rem;
			display: block;
			content: '';
			width: 2rem;
			height: 1.5rem;
			background: #888888;
			transform: rotate(45deg);
			-ms-transform: rotate(45deg);
			-moz-transform: rotate(45deg);
			-webkit-transform: rotate(45deg);
		}

		.pay-set-this:before {
			position: absolute;
			top: -.7rem;
			right: -.7rem;
			display: block;
			content: '';
			width: 2rem;
			height: 1.5rem;
			background: #c5c5c5;
			transform: rotate(45deg);
			-ms-transform: rotate(45deg);
			-moz-transform: rotate(45deg);
			-webkit-transform: rotate(45deg);
		}
		.rec-fa-check {
			position: absolute;
			top: 0rem;
			right: 0.15rem;
			color: #ffffff;
			font-size: .6rem;
			font-weight:bold;
			z-index: 9;
		}
	</style>
	<body>
		<div class="mobile_wrap">
			<div class="balance">
				<h2>余额<i id="balance_txt">0.00</i>元</h2>
			</div>
			
			<div class="active">
				<h1>充<b id='cz'>500</b>元</h1>
				<h2 id="selectmoney">选择充值金额<!--，单笔满100元<i>100%赠金</i>--></h2>
				<ul>
					<li><p><i>10000</i>元</p></li>
					<li><p><i>5000</i>元</p></li>
					<li><p><i>2000</i>元</p></li>

					<li ><p><i>1000</i>元</p></li>
					<li class="slct"><p><i>500</i>元</p></li>
					<li><p><i>200</i>元</p></li>
					<li><p><i>100</i>元</p></li>
					<li class="not"><p>其它金额</p><input value="20" min="1" id="input_money" style="display: none;"></li>
					<!--<li class="other"><p>充值卡充值</p><input value="" min="1" id="top_up_money" style="display: none;"></li>
				<h2>选择支付方式</h2>--></ul>
				
				<div class="pay-mode clearfix" style="display: none">
					<div class="pay-mode-list fl wx-pay clearfix pay-set-this">
						<input type="radio" name="pay" value="WanWuGzh" id="wxbank"checked>
						<label for="wxbank">
							<img src="img/wx.png" alt="">
						</label>
					</div>
					<!--<div class="pay-mode-list fl zfb-pay clearfix">
						<input type="radio" name="pay" value="YeePayYjzf" id="zfbbank">
						<label for="zfbbank">
							<img src="img/zfb.png">
						</label>
					</div>-->
					<!--<div class="pay-mode-list fl clearfix">
						<input type="radio" name="pay" value="YeePayZfb" id="gsbank">
						<label for="gsbank">
							<img src="img/cnzyzf.png">
							<i class="iconfont icon-checkmark2 rec-fa-check"></i>
						</label>
					</div>-->
				</div>
			</div>
			
			<div class="prompt" id="top">
				<p>提示：充值秒到账。微交易金融不限支付金额，若提示订单超出单笔限额，请核实您账户及网银的每日消费限额。<!--<a href="recharge_no.html" style="color: #ffed20;">不要返现直接充值</a>-->
				<input class="but_sub" value="马上充值" type="button" onclick="submit_api();">
				<span style="display:none;"><input type="checkbox"><i></i>我已阅读并同意《充返活动协议》，知悉充值返现金额满足活动要求即可提现。<a>查看协议详情</a></span>
			</div>
			
			<div class="explain">
				<div class="text">
					<p>尊敬的用户、为保障您的合法权益，请您在参加充返活动前仔细阅读本协议。在您点击“马上充值”按钮后，我们默认您已经知悉如下活动条款。</p>
					<p>一、活动内容</p>
					<p>单笔充值1~99元，不享受返现；</p>
					<p>单笔充值100~1999元，享受100%返现；</p>
					<p>单笔充值2000~10000元，享受100%返现；</p>
					<p>单笔充值7000~9999元，享受100%返现；</p>
					<p>单笔充值10000元及以上，享受100%返现。</p>
				</div>
				<div class="text">
					<p>二.活动时间</p>
					<p>即日起至2016年12月31日24点</p>
					<p>随后充返活动力度将适时调整。</p>
				</div>
				<div class="text">
					<p>三.余额构成</p>
					<p>您实际支付的充值本金加上微交易金融的返现金额会构成您的账户余额（人民币）。</p>
					<p>例：单笔充值100，返现100，则账户余额为200。</p>
				</div>			
				<div class="text">
					<p>四.充值余额使用规则</p>
					<p>余额可用于在微交易金融微交易中进行各类投资。无任何限制。</p>
				</div>	
				<div class="text">
					<p>五.充值余额提现规则</p>
					<p>每笔充值余额提现，需达到充值余额5倍以上交易流水，即可全部提现，提现10分钟之内到账。</p>
				</div>				
				<div class="text">
					<p>六.特别声明</p>
					<p>1.请您根据自己的投资情况进行充值，微交易金融对充值次数不设任何限制；</p>
					<p>2.充返活动福利仅提供给正当、合法使用微交易金融微交易客户。每位参与者的微交易金融账号、手机设备号、身份证号和号都必须是唯一的，任意信息与其他用户重复都不能参加该活动； 活动中，一旦发现作弊行为，微交易金融有权取消相关账户活动返现金额、追回作弊所得、回收账号使用权，并保留取消作弊人后续参与微交易金融任何活动的权利，必要时会追究其法律责任；</p>
					<p>3.本次活动最终解释权归微交易金融所有。</p>
				</div>			  
			</div>
			<!--<div class="box_show">
				<div class="layout">
					<h1>温馨提示</h1>
					<p>充100元以上才能享受100%返现哦 确定不要100%返现吗</p>
					<a href="javascript:void(0)" class="solid" id="close_win">我要100%返现</a>
					<a href="javascript:void(0)" id="gotopay">不要100%返现，继续充值</a>
				</div>
			</div>-->
		</div>

		<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="1" id="msg-dialog">
			<div class="am-modal-dialog">
				<div class="am-modal-hd" id="info-msg"></div>
				<div class="am-modal-bd">
					<span class="am-icon-spinner am-icon-spin"></span>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			function submit_api(){
				//var balance_txt = parseInt($("#balance_txt").html())*100;
				var amount = parseInt($("#cz").html())*100;
				if(amount<100){
					alert("至少充值1元!");
				}else{
					$("input[name=total_fee]").val(amount);
					var out_trade_no = 'tw'+Date.parse(new Date())/1000+parseInt(Math.random()*1000);//订单号
					var sub_openid = "<?php echo $cz_openid;?>";
					var body = "腾微充值";
					var mch_create_ip = "<?php echo $ip;?>";
					var total_fee = amount;
					$.ajax({
						url:"/wzypay/request.php?method=submitOrderInfo",
						type:'post',
						dataType:'json',
						data:{out_trade_no:out_trade_no,sub_openid:sub_openid,body:body,mch_create_ip:mch_create_ip,total_fee:total_fee},
						success:function(data){
							if(data.status==200){
								WeixinJSBridge.invoke('getBrandWCPayRequest',{
									"appId" : data.pay_info.appId, //公众号名称，由商户传入
									"timeStamp": data.pay_info.timeStamp, //时间戳，自1970 年以来的秒数
									"nonceStr" : data.pay_info.nonceStr, //随机串
									"package" : data.pay_info.package, "signType" : "MD5", //微信签名方式:
									"paySign" : data.pay_info.paySign //微信签名
									},function(res){
										if(res.err_msg == "get_brand_wcpay_request:ok" ) {
											open_info_msg("充值成功，充值金额为："+(total_fee/100)+"元");
											window.location = '/index.php';
										}
									});

							}else{
								alert("支付信息错误！");
							}
						},
						error:function(XMLHttpRequest, textStatus, errorThrown){
							
						}
					});
				}
			}
			
		</script>
	</body>
</html>
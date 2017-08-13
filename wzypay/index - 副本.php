<?php 
include '../../isLogin.php'; 
require_once '../../db/mysql_operate.php';
date_default_timezone_set('PRC');
session_start();
$userid = intval(isset($_GET['userid'])?$_GET['userid']:$_SESSION['userid']);
$money = intval(isset($_GET['money'])?$_GET['money']:$_SESSION['money']);
$conditions = array("id"=>$userid);
$user = db_select('js_user', $conditions);

$_SESSION['money']=$money; 
$_SESSION['userid']=$userid; 
$_SESSION['openid']=$user[0]['cz_openid'];

$out_trade_no = $userid.date('YmdHis');//订单号
if (getenv("HTTP_CLIENT_IP"))  
        $ip = getenv("HTTP_CLIENT_IP");  
    else if(getenv("HTTP_X_FORWARDED_FOR"))  
        $ip = getenv("HTTP_X_FORWARDED_FOR");  
    else if(getenv("REMOTE_ADDR"))  
        $ip = getenv("REMOTE_ADDR");  
    else $ip = "127.0.0.1"; 
$param = array('userid'=>$userid,'orderid'=>$out_trade_no,'amount'=>$money,'type'=>'WXZF','fanxian'=>'0','status'=>'0','ctime'=>date('Y-m-d H:i:s'));
$payid = db_insert('js_pay',$param,true);
db_insert('js_balance_log',array('userId'=>$userid,'order_code'=>$payid,'out_trade_no'=>$out_trade_no,'type'=>1,'money'=>$money,'give_money'=>0,'state'=>1,'datetime'=>date('Y-m-d H:i:s'),'cz'=>0));

//file_put_contents('a.txt',$user[0]['openid']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>跳转中...</title>
    <link href="css/pay.css" rel="stylesheet" type="text/css"/>
    <link href="css/sprite.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="js/pay.js"></script>
	
</head>
<body>
<div id="pay_platform" style="display:block;">
	<header class="header">
		<div class="auto_center"><p></p></div>
	</header>
	<div class="content">
        
		<div class="" id="">
<div id="orderInfo" style="display:block;">
    <div class="ico_title">支付测试(公众号支付)</div>
    <div class="form_wrap account">
        <div class="form_list">
            <span class="list_title">商户订单号：</span>
            <span class="list_val">
                <input name="out_trade_no" value="<?php echo $out_trade_no;?>" maxlength="32" size="32" placeholder="长度32">
            </span>
            <i>*</i><em>长度32</em>
        </div>
		<div class="form_list">
            <span class="list_title">用户openid：</span>
            <span class="list_val">
                <input name="sub_openid" value="<?php echo $user[0]['cz_openid'];?>" maxlength="127" size="32" placeholder="长度127">
            </span>
            <i>*</i><em>输入用户关注公众号后的openid</em>
        </div>
        <div class="form_list">
            <span class="list_title">商品描述：</span>
            <span class="list_val">
                <input name="body" value="测试购买商品" maxlength="64" size="32" placeholder="长度127">
            </span>
            <i>*</i><em>长度64</em>
        </div>
        
        <div class="form_list">
            <span class="list_title">总金额：</span>
            <span class="list_val">
                <input name="total_fee" value="<?php echo $money*100?>" placeholder="单位：分">
            </span>
            <i>*</i><em>单位：分 整型</em>
        </div>
        <div class="form_list">
            <span class="list_title">终端IP：</span>
            <span class="list_val">
                <input name="mch_create_ip" vtype="ip" value="<?php echo $ip;?>" maxlength="16" placeholder="长度16">
            </span>
            <i>*</i><em>长度16</em>
        </div>
       
        <div class="form_list">
            <span class="list_title"></span>
            <span class="list_val submit btn btn_blue" id="auto2" >确定</span>
        </div>
    </div>
</div>
		</div>
	</div><!-- content end -->
	
<script>
jQuery(function($) {
//$("#auto2").click();
});
//$("#auto2").click();
</script>
</div>
</body>
</html>
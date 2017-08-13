<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/14
 * Time: 6:16
 */
require_once '../db/mysql_operate.php';
require_once '../tool/tool.php';

$mobilePhone = '';
$type = "r";
$arr = array();

if (empty($_POST['mobilePhone']) || $_POST['mobilePhone'] == "")  {
    echo '手机号码不能为空';
    exit;
} else if (!preg_match("/^\\s*1\\d{10}\\s*$/", $_POST['mobilePhone'])) {
    echo '手机号码不正确';
    exit;
} else {
    $mobilePhone = stripslashes(trim($_POST['mobilePhone']));
}
if (!empty($_POST['type']) && $_POST['type'] != "")  {
    $type = $_POST['type'];
}
/*if (empty($_COOKIE['u_ip'])) {
    $ip = getIP().",1";
    setcookie("u_ip", $ip, time() + 24 * 60 * 60, "/");
} else {
    $ip = explode(",",$_COOKIE['u_ip']);
    if (intval($ip[1]) == 5) {
        $arr['success'] = 1;
        $arr['msg'] = '一个IP每天只能发送5次短信';
        echo json_encode($arr);
        exit;
    } else {
        $n_ip = $ip[0].",".intval($ip[1])+1;
        setcookie("u_ip", $ip[0], time() + 24 * 60 * 60, "/");
    }
}*/

if ($type == "r") {
    $conditions = array("mobilePhone"=>$mobilePhone);
    $result = db_select('js_user', $conditions, "count(id) count");
    if ($result != null && $result[0]["count"] > 0) {
        $arr['success'] = 1;
        $arr['msg'] = '手机号码已存在';
        echo json_encode($arr);
        exit;
    }
}

$param = "a.reg_count_sms";
$setting_result = db_select("js_setting", array(), $param, "limit 1");
$sms_reg_code = rand(100000,999999);
setcookie("sms_reg_code", $sms_reg_code, time() + 5 * 60, "/");
$send_msg = $setting_result[0]['reg_count_sms'].$sms_reg_code."，五分钟有效";

$statusStr = array(
    "0" => "短信发送成功",
    "-1" => "参数不全",
    "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
    "30" => "密码错误",
    "40" => "账号不存在",
    "41" => "余额不足",
    "42" => "帐户已过期",
    "43" => "IP地址限制",
    "50" => "内容含有敏感词"
);
//$smsapi = "http://api.smsbao.com/";
//$user = "rhz214884460"; //短信平台帐号
//$pass = md5("rhz18312839885"); //短信平台密码
$smsapi = "http://smssh1.253.com/msg/send/json";
$user = "N1665602"; //253平台帐号
$pass = "A8iSTM2pQE7c85"; //253平台密码
$content=$send_msg;//要发送的短信内容
$phone = $mobilePhone;//要发送短信的手机号码
//$sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
//$result = file_get_contents($sendurl);

$postArr = array (
			'account'  =>  $user,
			'password' => $pass,
			'msg' => urlencode($content),
			'phone' => $phone,
			'report' => true
        );
		
$result = curlPost( $smsapi , $postArr);

$arr["success"]=0;
$arr["msg"]=$statusStr[0];
echo json_encode($arr);

function curlPost($url,$postFields){
		$postFields = json_encode($postFields);
		$ch = curl_init ();
		curl_setopt( $ch, CURLOPT_URL, $url ); 
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8'
			)
		);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,1); 
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
		$ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error(  $ch);
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
		curl_close ( $ch );
		return $result;
	}
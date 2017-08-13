<?php
require_once '../db/mysql_operate.php';
session_start();
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/11/12
 * Time: 17:34
 */
$appid = "wx0e2dedd59ee0fac4";
$appsecret = "3a0e2fcf2e8b106f38ed6752ab14f0ce";
$is_auth = false;

$reg_url = "http://6share.top/regist.php";

$code = $_GET["code"];

$uid = $_GET["state"];
if($is_auth && $uid > 0){
	$reg_url .= '?uid=' . $uid;
}

$access_token = getAccess_token($appid, $appsecret);
$openid = getOpenid($appid, $appsecret, $code);

$user_info_res = json_decode(getUserInfo($access_token, $openid), true);

// $user_info_res = isRefresh_token($user_info_res, $openid, $access_token, $appid);
if (!isset($user_info_res["errcode"])) {
    saveUserInfo($user_info_res,$reg_url);
} else {
    header("Location:http://6share.top/login.html'");
    exit;
}


function saveUserInfo($user_info_res,$reg_url) {
    $conditions = array("openid"=>$user_info_res["openid"]);
    $result = db_select('js_wx_info', $conditions);
    if ($result != null) {
        setcookie("wx_id", $result[0]["id"], time() + 2 * 60 * 60, "/");

        $conditions = array("wx_id"=>$result[0]["id"]);
        $user = db_select('js_user', $conditions);
        if (null != $user) {
            $user_str = $user[0]['id'].",".substr_replace($user[0]['mobilePhone'],'****',3,4).",".$user[0]['realName'].","
                .substr_replace($user[0]['identity'],'****',10,4).",".$user[0]['sex'].",".$user[0]['nickName'].","
                .$user[0]['district'].",".$user[0]['balance'].",".$user[0]['tempBalance'].",".$user[0]['bankName'].",".substr_replace($user[0]['bankNum'],'****',(strlen($user[0]['bankNum'])-4),4).","
                .$user[0]['photoUrl'].",".$user[0]['nickName'].",".$result[0]['nickName'].",".$result[0]['nickName'];
            $_SESSION["user"] = $user_str;
            // setcookie("u", $user_str, time() + 2 * 60 * 60, "/");
            header("Location:http://6share.top/index.php");
        } else {
            header("Location:".$reg_url);
        }
    } else {
        // echo "---------------------";
        $params = array("subscribe"=>$user_info_res["subscribe"],"openid"=>$user_info_res["openid"],"nickname"=>$user_info_res["nickname"],"sex"=>$user_info_res["sex"]
        ,"city"=>$user_info_res["city"],"country"=>$user_info_res["country"],"province"=>$user_info_res["province"],"language"=>$user_info_res["language"],"headimgurl"=>$user_info_res["headimgurl"]
        ,"subscribe_time"=>$user_info_res["subscribe_time"],"remark"=>$user_info_res["remark"],"groupid"=>$user_info_res["groupid"]);
        $insert_result = db_insert('js_wx_info', $params, true);

        if ($insert_result > 0) {
            setcookie("wx_id", $insert_result, time() + 2 * 60 * 60, "/");
            header("Location:".$reg_url);
        }
    }
}

function isRefresh_token($user_info_res, $openid, $access_token, $appid) {
    $flag = false;
    foreach($user_info_res as $k=>$v) {
        if ($k == 'errcode') {
            if ($v == '40001') {
                $flag = true;
            }
        }
    }
    if ($flag) {
        $refresh_token = refresh_token($appid, $access_token);
        $user_info_res = json_decode(getUserInfo($refresh_token, $openid), true);
        return isRefresh_token($user_info_res, $openid, $access_token, $appid);
    } else {
        return $user_info_res;
    }
}

function getAccess_token($appid, $secret) {
    $access_token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
    $res = https_request($access_token_url);
    $access_token = json_decode($res, true);
    return $access_token['access_token'];
}

function refresh_token($appid, $refresh_token) {
    $refresh_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$appid.'&grant_type=refresh_token&refresh_token='.$refresh_token;
    $res = https_request($refresh_token_url);
    $access_token = json_decode($res, true);
    return $access_token['refresh_token'];
}

function getOpenid($appid, $appsecret, $code) {
    $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
    $res = https_request($get_token_url);
    $openid = json_decode($res, true);
    return $openid['openid'];
}
function getUserInfo($access_token, $openid) {
    //根据openid和access_token查询用户信息
    $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
    $res = https_request($get_user_info_url);
    return $res;
}

function https_request($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
    curl_close($curl);
    return $data;
}
<?php
/**
 * Created by PhpStorm.
 * User: 3288182
 * Date: 2017/06/14
 * Time: 16:24
 */
$APPID='wx0e2dedd59ee0fac4';
$REDIRECT_URI='http://6share.top/oauth2/callback.php';

$is_auth = false;

$uid = $_GET["uid"];

if($is_auth && $uid > 0){
	$state = $uid;
}

// $scope='snsapi_base';
$scope='snsapi_userinfo';//需要授权
//$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&connect_redirect=1&state=STATE#wechat_redirect';

$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&connect_redirect=1&state='.$state.'#wechat_redirect';

header("Location:".$url);

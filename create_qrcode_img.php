<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/15
 * Time: 23:53
 */
include 'phpqrcode.php';
session_start();

ob_clean();
header('Content-type:image/png');

$is_auth = false;

$u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
$user_id = $u_arr[0];
if($is_auth){
	$value = 'http://6share.top/oauth2/oauth2.php?uid='.$user_id; //二维码内容
}else{
	$value = 'http://6share.top/regist.php?uid='.$user_id; //二维码内容
}


$errorCorrectionLevel = 'L';//容错级别
$matrixPointSize = 6;//生成图片大小

$img_code = 'qrcode/qrcode_u'.$user_id.'.png';

// if ($img_code !== FALSE) {
    //生成二维码图片
    QRcode::png($value, $img_code, $errorCorrectionLevel, $matrixPointSize, 2);
    $img_bg = 'qrcode/create_qrcode_img.jpg';//准备好的logo图片

    $image_1 = imagecreatefromjpeg($img_bg);
    $image_2 = imagecreatefrompng($img_code);
    //创建一个和人物图片一样大小的真彩色画布（ps：只有这样才能保证后面copy装备图片的时候不会失真）
    $image_3 = imageCreatetruecolor(imagesx($image_1), imagesy($image_1));
    //为真彩色画布创建白色背景，再设置为透明
    // $color = imagecolorallocate($image_3, 255, 255, 255);
    $color = imagecolorallocate($image_3, 0, 0, 255);
    imagefill($image_3, 0, 0, $color);
    imageColorTransparent($image_3, $color);
    //首先将人物画布采样copy到真彩色画布中，不会失真
    imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, imagesx($image_1), imagesy($image_1), imagesx($image_1), imagesy($image_1));
    //再将装备图片copy到已经具有人物图像的真彩色画布中，同样也不会失真
    imagecopymerge($image_3, $image_2, 160, 360, 0, 0, imagesx($image_2), imagesy($image_2), 99);

    // copy($image_3, "img/qrcode/" . $image_3);
    // imagecreatefrompng($image_3);

    //将画布保存到指定的gif文件
    imagepng($image_3);

// }

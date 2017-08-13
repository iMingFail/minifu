<?php
$file_name = $_GET['file_name'];
$picname = $_FILES['mypic']['name'];
$picsize = $_FILES['mypic']['size'];
if ($picname != "") {
    /*if ($picsize > 1024000) {
        echo '图片大小不能超过1M';
        exit;
    }*/
    $type = strstr($picname, '.');
    if ($type != ".jpg") {
        echo '图片格式不对！';
        exit;
    }
    $rand = rand(100, 999);
    $pics = $file_name . $type;
    //上传路径
    $pic_path = "../upload_img/". $pics;
    move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
}
$size = round($picsize/1024,2);
$arr = array(
    'name'=>$picname,
    'pic'=>$pics,
    'size'=>$size
);
echo json_encode($arr);

?>
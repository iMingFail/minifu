<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/21
 * Time: 16:19
 */
require_once '../../db/mysql_operate.php';

$money = $_GET["money"];
$opstate = $_GET["opstate"];
$u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
$user_id = $u_arr[0];
$conditions = array("id"=>$user_id);
$result = db_select('js_user', $conditions, "mobilePhone, realName, nickName");
if ($opstate == 0) {
    $name = $result[0]["nickName"];
    if (null == $name || $name == "") {
        $name = $result[0]["realName"];
    }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <!---->
        
            // var socket = io.connect('http://211.149.154.95:3000');
            // socket.emit('user_pay', {});
            setTimeout(aa, 3000);
            function aa(){
                window.location.href = "/index.php";
            }
        </script>
    </head>
    <body>
    用户：<?php echo $name ?> <span style="font-size:12px;">(手机号:<?php echo $result[0]["mobilePhone"] ?>
        )</span>
    已成功充值：<?php echo $money ?>元<br>
    系统3秒后将自动返回到主页面
    </body>
    </html>
    <?php
} else {
    echo "充值失败了";
}
?>

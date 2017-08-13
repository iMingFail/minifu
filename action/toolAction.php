<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/15
 * Time: 4:39
 */
require_once '../db/mysql_operate.php';
date_default_timezone_set('PRC');
session_start();

$action = $_GET['action'];
if ($action == 'get_types') {
    get_types();
}
if ($action == 'is_user_pay') {
    is_user_pay();
}
if ($action == 'get_news') {
    get_news();
}
if ($action == 'topUp') {
    topUp();
}
if ($action == 'get_type_eamings_rate') {
    getTypeEamingsRate();
}
if ($action == 'use_vouchers_money') {
    use_vouchers_money();
}

function use_vouchers_money() {
    $arr = array();
    $result = db_select("js_setting", array(), "use_vouchers_money");
    $arr["use_vouchers_money"] = $result[0]["use_vouchers_money"];
    echo json_encode($arr);
}

function topUp() {
    $arr = array();
    $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
    $user_id = $u_arr[0];
    $number = $_POST['number'];

    $result = db_select("js_top_up", array("number"=>"$number"));
    if (!empty($result) && count($result) > 0) {
        if ($result[0]["state"] == 1) {
            $money = $result[0]["money"];
            $sql = "insert into js_balance_log (userId, out_trade_no, type, money, give_money, state, datetime)value(".$user_id.",'".$number."',1, ".$money.",0,1,now())";
            db_execute($sql);
            $sql = "update js_user set balance=balance+".$money." where id=".$user_id;
            db_execute($sql);

            $sql = "update js_top_up set state=2 where id=".$result[0]["id"];
            db_execute($sql);

            $arr["success"] = 1;
            $arr["money"] = $money;
        } else {
            $arr["success"] = 0;
        }
    } else {
        $arr["success"] = -1;
    }
    echo json_encode($arr);
}

function get_news() {
    $arr = array();
    $maxId = $_GET["maxId"];

    $conditions = array();

    if ($maxId != 0) {
        $s_y = substr($maxId,0,4);
        $s_m = substr($maxId,4,2);
        $s_d = substr($maxId,6,2);
        $s_h = substr($maxId,8,2);
        $s_f = substr($maxId,10,2);
        $s_s = substr($maxId,12, 2);
        $s_datetime = date("Y-m-d H:i:s", mktime($s_h, $s_f, $s_s, $s_m, $s_d, $s_y));
        $conditions = array("DATE_FORMAT('".$s_datetime."','%Y-%m-%d %H:%i') > DATE_FORMAT(datetime,'%Y-%m-%d %H:%i')"=>"");
    }

    $result = db_select("js_collect_article", $conditions, "*", "limit 0, 50", array("datetime"=>"desc"));
    if ($result != null && count($result) > 0) {
        for ($i = 0; $i<count($result); $i++) {
            $t1 = $result[$i]["t1"];
            $t2 = $result[$i]["t2"];
            // $datetime=date('Y-m-d H:i:s', $result[$i]["newstimespan"]);

            $time = $result[$i]["time"];
            $newstimespan = $result[$i]["newstimespan"];
            $y = substr($newstimespan,0,4);
            $m = substr($newstimespan,4,2);
            $d = substr($newstimespan,6,2);
            $h = substr($newstimespan,8,2);
            $f = substr($newstimespan,10,2);
            $s = substr($newstimespan,12, 2);
            $datetime = date("Y-m-d H:i:s", mktime($h, $f, $s, $m, $d, $y));

            $text = $result[$i]["text"];
            $prefix = $result[$i]["prefix"];
            $predicted = $result[$i]["predicted"];
            $actual = $result[$i]["actual"];
            $star = $result[$i]["star"];
            $effect = $result[$i]["effect"];
            $country = $result[$i]["country"];
            $nil = $result[$i]["nil"];
            $str = "";
            if ($t1 == 0) { // 新闻
                $str = "$t1#$t2#$datetime#$text###$nil##0###$newstimespan";
            } else { // 数据
                $str = "$t1#$time#$text#$prefix#$predicted#$actual#$star#$effect#$datetime#$country##$newstimespan#$t2";
            }
            $arr[$i] = $str;
        }
        echo json_encode($arr);
    }
}

function get_types(){
    $typeId = $_GET['typeId'];
    $arr = array();
    if($typeId != 3 && $typeId != "") {
        $toDay = wk();
        if ($toDay == "星期六" || $toDay == "星期日") {
            $arr['success'] = 2;
            echo json_encode($arr);
            exit;
        }
    }

    $result = db_select("js_type", array(), "id,name");
    if ($result != null && count($result) > 0) {
        $u_arr = explode(",",$_SESSION["user"]);  //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        $conditions = array("a.id"=>$user_id);
        $left_join_tab=array("js_vouchers"=>array('as' => 'b', 'param'=>'user_id', ''=>'id'));
        $user_result = db_select('js_user', $conditions, "a.*, count(b.id) vouchers", '', array(), $left_join_tab, " and b.state=1");
        if ($user_result != null) {
            $user = $user_result[0]['id'].",".substr_replace($user_result[0]['mobilePhone'],'****',3,4).",".$user_result[0]['realName'].","
                .substr_replace($user_result[0]['identity'],'****',10,4).",".$user_result[0]['sex'].",".$user_result[0]['nickName'].","
                .$user_result[0]['district'].",".$user_result[0]['balance'].",".$user_result[0]['tempBalance'].",".$user_result[0]['bankName'].",".substr_replace($user_result[0]['bankNum'],'****',(strlen($user_result[0]['bankNum'])-4),4).","
                .$user_result[0]['photoUrl'].",".$user_result[0]['nickName'].",".$user_result[0]['vouchers'].",".$user_result[0]['use_vouchers_num'];
            // setcookie("u", $user, time() + 2 * 60 * 60, "/");
            $_SESSION["user"] = $user;
        }

        $arr['success'] = 1;
        $arr['data'] = $result;
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        echo json_encode($arr);
    }
}
function getTypeEamingsRate() {
    $typeId = $_GET['typeId'];
    $arr = array();
    $result = db_select("js_type_eamings_rate", array("type"=>$typeId), "trade_time,eamings_rate");
    if ($result != null && count($result) > 0) {
        $arr['success'] = 1;
        $arr['data'] = $result;
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        echo json_encode($arr);
    }
}

function is_user_pay() {
    $out_trade_no = $_POST["out_trade_no"];
    $u_arr = explode(",",$_SESSION["user"]); // explode(",",$_COOKIE['u']);
    $user_id = $u_arr[0];
    //DATE_FORMAT(datetime,'%Y-%m-%d %H:%i') = DATE_FORMAT(now(),'%Y-%m-%d %H:%i')
    $sql = "select * from js_balance_log where userId = ".$user_id." and out_trade_no='$out_trade_no'";
    $r = db_execute_select($sql);
    $arr = array();
    if (!empty($r) && count($r) > 0) {
        $arr['success'] = 1;
    } else {
        $arr['success'] = 0;
    }
    echo json_encode($arr);
}

function wk() {
    $datearr = explode("-",date('Y-m-d',time()));     //将传来的时间使用“-”分割成数组
    // echo $date;
    $year = $datearr[0];       //获取年份
    $month = sprintf('%02d',$datearr[1]);  //获取月份
    $day = sprintf('%02d',$datearr[2]);      //获取日期
    $hour = $minute = $second = 0;   //默认时分秒均为0
    $dayofweek = mktime($hour,$minute,$second,$month,$day,$year);    //将时间转换成时间戳
    $shuchu = date("w",$dayofweek);      //获取星期值
    $weekarray=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
    return $weekarray[$shuchu];
}
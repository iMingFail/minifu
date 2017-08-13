<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/17
 * Time: 4:45
 */
require_once '../../db/mysql_operate.php';
date_default_timezone_set('PRC');

$action = $_GET['action'];
if ($action == 'delete') {
    delete();
}
if ($action == 'add') {
    add();
}
if ($action == 'edit') {
    edit();
}
if ($action == 'pass_m_out') {
    pass_m_out();
}
if ($action == 'up_user_state') {
    up_user_state();
}
if ($action == 'top_up') {
    top_up();
}

function edit() {
    $id = $_POST['uid'];
    $realName = $_POST['realName'];
    $identity = $_POST['identity'];
    $sex = $_POST['sex'];
    $nickName = $_POST['nickName'];
    $password = $_POST['password'];
    $arr = array();
    $params = array("identity"=>$identity, "realName"=>$realName, "sex"=>$sex, "nickName" => $nickName);
    if (null != $password && $password !="") {
        $params["password"] = md5($password);
    }
    $conditions = array("id"=>$id);
    $result = db_update('js_user', $params, $conditions);
    if ($result > 0) {
        $arr['success'] = 1;
        $arr['msg'] = '修改用户成功';
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        $arr['msg'] = '修改用户失败';
        echo json_encode($arr);
    }
}

function add() {
    $mobilePhone = '';
    $password = '';
    $pid = 0;

    if (empty($_POST['mobilePhone']))  {
        echo '手机号码不能为空';
        exit;
    } else if (!preg_match("/^\\s*1\\d{10}\\s*$/", $_POST['mobilePhone'])) {
        echo '手机号码不正确';
        exit;
    } else {
        $mobilePhone = stripslashes(trim($_POST['mobilePhone']));
    }
    if (empty($_POST['password']))  {
        echo '交易密码不能为空';
        exit;
    } else {
        $password = stripslashes(trim($_POST['password']));
    }
    if (!empty($_POST['pid']) && $_POST['pid'] != "")  {
        $pid = $_POST['pid'];
    }

    $arr = array();
    $md5Password = md5($password);
    $conditions = array("mobilePhone"=>$mobilePhone);
    $result = db_select('js_user', $conditions);
    if ($result != null) {
        $arr['success'] = 0;
        $arr['msg'] = '手机号码已存在';
        echo json_encode($arr);
    } else {
        $invite_code = generate_invite_code(6);
        $params = array("pid"=>$pid, "mobilePhone"=>"$mobilePhone", "password"=>"$md5Password", "sex"=>1, "balance"=>"0.00", "tempBalance"=>"8888.00", "state"=>"0", "datetime"=>date('Y-m-d H:i'), "code"=>$invite_code, "use_vouchers_num"=>0);
        $insert_result = db_insert('js_user', $params, true);
        if ($insert_result > 0) {
            generate($insert_result);
            $arr['success'] = 1;
            $arr['user'] = $result[0];
            $arr['msg'] = '添加用户成功';
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['user'] = $result[0];
            $arr['msg'] = '添加用户失败';
            echo json_encode($arr);
        }
    }
}

function generate($user_id) {
    $money = 10;
    $num = 2;

    $conditions = array();
    $result = db_select('js_setting', $conditions, "vouchers_num, vouchers_money");
    if (null != $result) {
        $money = $result[0]["vouchers_money"];
        $num = $result[0]["vouchers_num"];
    }

    for ($i=0;$i<$num;$i++) {
        $password = random(6, "1234567890");
        $number = "";
        for ($j=0;$j<4;$j++) {
            if($j > 0) {
                $number = $number."-";
            }
            $number = $number.random(4, "1234567890");
        }
        $params = array("user_id"=>$user_id, "number"=>$number, "password"=>"$password", "money"=>"$money", "state"=>1, "datetime"=>date('Y-m-d H:i'));
        db_insert('js_vouchers', $params, true);
    }
    return $num;
}

function generate_invite_code($len) {
    $srcstr = "1234567890";
    mt_srand();
    $strs = "";
    for ($i = 0; $i < $len; $i++) {
        $strs .= $srcstr[mt_rand(0, (mb_strlen($srcstr) - 1))];
    }
    if (check_code($strs)) {
        $conditions = array("code"=>$strs);
        $result = db_select('js_user', $conditions, "id");
        if (null == $result) {
            return $strs;
        } else {
            return random($len);
        }
    } else {
        return random($len);
    }
}

// 生成邀请码
function check_code($str){
    $isok = true;
    $z = 5;
    for($i=0;$i<=5;$i++){
        $five="";
        $x = 0;
        for ($j=$z;$j>0;$j--) {
            $five = $five.$str{$i+$x};
            $x++;
        }
        $z--;
        $now_chong = $str{$i}.$str{$i}.$str{$i}.$str{$i}.$str{$i};
        $now_zeng = $str{$i}.chr(ord($str{$i})+1).chr(ord($str{$i})+2).chr(ord($str{$i})+3).chr(ord($str{$i})+4);
        if($five==$now_chong){
            $isok = false;
            break;
        }elseif($five==$now_zeng){
            $isok = false;
            break;
        }
    }
    if($isok){
        return true;
    }else{
        return false;
    }
}

function delete() {
    if (!empty($_POST['ids']) && $_POST['ids'] != "")  {
        $ids = $_POST['ids'];
        $sql = "delete from js_user where id in ($ids)";
        $result = db_execute($sql);
        if ($result > 0) {
            $arr['success'] = 1;
            $arr['msg'] = '所选用户已删除';
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['user'] = $result[0];
            $arr['msg'] = '操作失败';
            echo json_encode($arr);
        }
    }
}

function pass_m_out() {
    $id = $_POST['id'];
    $state = $_POST['state'];
    $s_sql = "select a.money, a.userId, b.rebate_balance, a.give_money from js_balance_log a left join js_user b on a.userId=b.id where a.id=$id";
    $result_s = db_execute_select($s_sql);
    $sql = "update js_balance_log set state=".$state." where id=".$id;
    db_execute($sql);
    if ($state == 1) {
        /*$rebate_balance = 0;
        if ($result_s[0]['money'] < $result_s[0]['rebate_balance']) {
            $rebate_balance = $result_s[0]['rebate_balance'] - $result_s[0]['money'];
        }*/
        $sql = "update js_user set freeze_balance=freeze_balance-".$result_s[0]['money']." where id=".$result_s[0]['userId'];
        db_execute($sql);
    } else {
        $sql = "update js_user set balance=balance+".$result_s[0]['money'].",rebate_balance=rebate_balance+".$result_s[0]['give_money'].", freeze_balance=freeze_balance-".$result_s[0]['money']." where id=".$result_s[0]['userId'];
        db_execute($sql);
    }
}
function up_user_state() {
    $id = $_POST['id'];
    $state = $_POST['state'];
    $sql = "update js_user set state=".$state." where id=".$id;
    db_execute($sql);
}

function top_up() {
    $id = $_POST['id'];
    $balance = $_POST['balance'];
    $sql = "update js_user set balance=balance+".$balance." where id=".$id;
    $r = db_execute($sql);
    if ($r) {
        $order_code = "NO".time().$id.random(4, "1234567890");
        $sql = "insert into js_balance_log (userId, order_code, type, money, give_money, state, datetime) value(".$id.",'".$order_code."',1, ".floatval($balance).",0,1,now())";
        db_execute($sql);
        $arr['msg'] = '充值成功';
        echo json_encode($arr);
    } else {
        $arr['msg'] = '充值失败';
        echo json_encode($arr);
    }
}

function random($len, $srcstr="1a2s3d4f5g6hj8k9qwertyupzxcvbnm") {
    mt_srand();
    $strs = "";
    for ($i = 0; $i < $len; $i++) {
        $strs .= $srcstr[mt_rand(0, (mb_strlen($srcstr) - 1))];
    }
    return $strs;
}
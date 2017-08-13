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
if ($action == 'edit') {
    edit();
}
if ($action == 'del_rebate_setting') {
    del_rebate_setting();
}
if ($action == 'edit_rebate_setting') {
    edit_rebate_setting();
}
if ($action == 'add') {
    add();
}

function add() {
    $name = $_POST['name'];
    $conditions = $_POST['conditions'];
    $return_rates = $_POST['return_rates'];

    $arr = array();
    $params = array("name"=>$name, "conditions"=>"$conditions", "return_rates"=>"$return_rates");
    $result = db_insert('js_rebate_setting', $params, true);
    if ($result > 0) {
        $arr['success'] = 1;
        $arr['user'] = $result[0];
        $arr['msg'] = '添加设置成功';
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        $arr['msg'] = '添加设置失败';
        echo json_encode($arr);
    }
}

function edit_rebate_setting() {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $conditions = $_POST['conditions'];
    $return_rates = $_POST['return_rates'];
    $arr = array();
    $params = array("name"=>$name, "conditions"=>$conditions, "return_rates"=>$return_rates);
    $conditions = array("id"=>$id);
    $result = db_update('js_rebate_setting', $params, $conditions);
    if ($result > 0) {
        $arr['success'] = 1;
        $arr['msg'] = '修改设置成功';
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        $arr['msg'] = '修改设置失败';
        echo json_encode($arr);
    }
}

function edit() {
    $pre_odds = $_POST['pre_odds'];
    $group_odds = $_POST['group_odds'];
    $moni_odds = $_POST['moni_odds'];
    $reg_count_sms = $_POST['reg_count_sms'];
    $vouchers_num = $_POST['vouchers_num'];
    $vouchers_money = $_POST['vouchers_money'];
    $use_vouchers_money = $_POST['use_vouchers_money'];
    $isUseCode = $_POST['isUseCode'];
    $arr = array();
    $params = array("pre_odds"=>$pre_odds, "group_odds"=>$group_odds, "moni_odds"=>$moni_odds, "reg_count_sms"=>$reg_count_sms, "vouchers_num"=>$vouchers_num, "vouchers_money"=>$vouchers_money, "use_vouchers_money"=>$use_vouchers_money, "isUseCode"=>$isUseCode);
    $conditions = array("id"=>1);
    $result = db_update('js_setting', $params, $conditions);
    if ($result > 0) {
        $arr['success'] = 1;
        $arr['msg'] = '修改设置成功';
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        $arr['msg'] = '修改设置失败';
        echo json_encode($arr);
    }
}

function del_rebate_setting() {
    if (!empty($_POST['ids']) && $_POST['ids'] != "")  {
        $ids = $_POST['ids'];
        $sql = "delete from js_rebate_setting where id in ($ids)";
        $result = db_execute($sql);
        if ($result > 0) {
            $arr['success'] = 1;
            $arr['msg'] = '所选数据已删除';
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['user'] = $result[0];
            $arr['msg'] = '操作失败';
            echo json_encode($arr);
        }
    }
}
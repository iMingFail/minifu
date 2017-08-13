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
if ($action == 'list') {
    listAll();
}
if ($action == 'mylist') {
    myList();
}
function myList(){
    $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
    /// echo "--------" + $_SESSION["user"];
    $conditions = array("user_id"=>$u_arr[0],"state"=>1);
    $result = db_select('js_vouchers', $conditions, "id,money");
    $arr['data'] = $result;
    echo json_encode($arr);
}
function listAll() {
    $pageNo = $_POST['page'];
    $pagesize = $_POST['pagesize'];
    $pageNo = (intval($pageNo)-1) * intval($pagesize);
    $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
    $user_id = $u_arr[0];
    $arr = array();
    $conditions = array("a.user_id"=>$user_id);
    $result = db_select('js_vouchers', $conditions, "*", "limit $pageNo, $pagesize");
    if ($result != null) {
        $arr['success'] = 1;
        $arr['data'] = $result;
        echo json_encode($arr);
    }else {
        $arr['success'] = 0;
        $arr['msg'] = '当前没有任何记录';
        echo json_encode($arr);
    }
}
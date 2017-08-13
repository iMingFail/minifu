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
if ($action == 'login') {
    login();
}
if ($action == 'add') {
    add();
}
if ($action == 'del') {
    del();
}
if ($action == 'edit') {
    edit();
}

function add() {
    $loginName = "";
    $password = "";
    $name = "";

    if (empty($_POST['loginName']))  {
        echo '登录帐号不能为空';
        exit;
    } else {
        $loginName = stripslashes(trim($_POST['loginName']));
    }
    if (empty($_POST['password']))  {
        echo '登录密码不能为空';
        exit;
    } else {
        $password = stripslashes(trim($_POST['password']));
    }
    if (empty($_POST['name']))  {
        echo '管理员名称不能为空';
        exit;
    } else {
        $name = stripslashes(trim($_POST['name']));
    }

    $arr = array();
    $md5Password = md5($password);
    $conditions = array("loginName"=>$loginName);
    $result = db_select('js_admin', $conditions);
    if ($result != null) {
        $arr['success'] = 0;
        $arr['msg'] = '登录帐号已存在';
        echo json_encode($arr);
    } else {
        $params = array("loginName"=>"$loginName", "password"=>"$md5Password", "name"=>$name,);
        $insert_result = db_insert('js_admin', $params, true);
        if ($insert_result > 0) {
            $arr['success'] = 1;
            $arr['user'] = $result[0];
            $arr['msg'] = '管理用户成功';
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['user'] = $result[0];
            $arr['msg'] = '管理用户失败';
            echo json_encode($arr);
        }
    }
}

function login() {
    $loginName = '';
    $password = '';

    if (empty($_POST['loginName']))  {
        echo '帐号不能为空';
        exit;
    } else {
        $loginName = stripslashes(trim($_POST['loginName']));
    }
    if (empty($_POST['password']))  {
        echo '密码不能为空';
        exit;
    } else {
        $password = stripslashes(trim($_POST['password']));
    }

    $arr = array();
    $md5Password = md5($password);
    $conditions = array("a.loginName"=>$loginName);
    $result = db_select('js_admin', $conditions);
    if ($result != null) {
        if ($result[0]['password'] == $md5Password) {
            session_start();                // 首先开启session
            $_SESSION['admin'] = $result[0];  // 把username存在$_SESSION['user'] 里面
            // session_destroy();
            $arr['success'] = 1;
            $arr['msg'] = '登录成功';
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['msg'] = '密码错误';
            echo json_encode($arr);
        }
    } else {
        $arr['success'] = 0;
        $arr['msg'] = '帐号不存在';
        echo json_encode($arr);
    }
}

function edit() {
    $id = $_POST['id'];
    $loginName = $_POST['loginName'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $arr = array();
    $params = array("loginName"=>$loginName, "name"=>$name);
    if (null != $password && $password !="") {
        $params["password"] = md5($password);
    }
    $conditions = array("id"=>$id);
    $result = db_update('js_admin', $params, $conditions);
    if ($result > 0) {
        $arr['success'] = 1;
        $arr['msg'] = '修改管理员成功';
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        $arr['msg'] = '修改管理员失败';
        echo json_encode($arr);
    }
}

function del() {
    if (!empty($_POST['ids']) && $_POST['ids'] != "")  {
        $ids = $_POST['ids'];
        $sql = "delete from js_admin where id in ($ids)";
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
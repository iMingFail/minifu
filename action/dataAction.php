<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/7
 * Time: 17:04
 */
require_once '../db/mysql_operate.php';
date_default_timezone_set('PRC');

$action = $_GET['action'];
if ($action == 'get_temp_data') {
    get_temp_data();
}
if ($action == 'get_data') {
    get_data();
}
if ($action == 'get_data_line') {
    get_data_line();
}
if ($action == 'get_data_value') {
    get_data_value();
}

function get_temp_data(){
    if (empty($_GET['type'])) {
        echo '获取数据失败';
        exit;
    }
    $type = $_GET['type'];

    $param = "max(DATE_FORMAT(a.datetime,'%Y-%m-%d %H:%i')) as datetime";
    $s = db_select("t_data", array("a.type"=>$type), $param, "limit 1");
    if (count($s) > 0) {
        $datetime = $s[0]["datetime"];
        $conditions = array("a.type"=>$type, "DATE_FORMAT(a.datetime,'%Y-%m-%d %H:%i')"=>$datetime);
        $left_join_tab = array("js_temp_data"=>array("as" => "b","param"=>"data_id", ""=>"id") );

        $result = db_select("t_data", $conditions, "a.open_value open_value, max(b._temp_value) max_value, min(b._temp_value) min_value, (select _temp_value from js_temp_data where data_id=a.id ORDER BY id DESC LIMIT 1) as _temp_value", "", array() , $left_join_tab);

        $arr = array();
        if ($result != null && count($result) > 0) {
            $arr['success'] = 1;
            $arr['data'] = $result;
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            echo json_encode($arr);
        }
    } else {
        $arr['success'] = 0;
        echo json_encode($arr);
    }
}

function get_data() {
    if (empty($_POST['type'])) {
        echo '获取数据失败';
        exit;
    }
    $type = $_POST['type'];
    $load_type = intval($_POST['limit']);
    $limit = $load_type*30+1;
    $conditions = array("a.type"=>$type);
    $param = "a.open_value, a.close_value, a.max_value, a.min_value, DATE_FORMAT(a.datetime, '%H:%i') datetime";
    $result = db_select("t_data", $conditions, $param , "limit 0, $limit" ,array("a.datetime"=>"desc"));
    $arr = array();
    if ($result != null && count($result) > 0) {
        $data = Array();
        $data_1 = Array();
        $data_2 = Array();

        $data_3 = Array();
        $data_4 = Array();

        for ($i=0; $i<$load_type; $i++) {
            array_push($data_3,$result[$i]["max_value"], $result[$i]["min_value"]);
        }

        $data_4["open_value"] = $result[$load_type-1]["open_value"];
        if ($load_type > 0) {
            $data_4["close_value"] = $result[0]["close_value"];
            $data_4["datetime"] = $result[0]["datetime"];
            $max_value_one = array_search(max($data_3), $data_3);
            $min_value_one = array_search(min($data_3), $data_3);
            $data_4["max_value"] = $data_3[$max_value_one];
            $data_4["min_value"] = $data_3[$min_value_one];
        }
        array_push($data, $data_4);

        $index = 0;
        for ($i=$load_type;$i<(count($result));$i++){
            $index++;
            array_push($data_1,$result[$i]["max_value"], $result[$i]["min_value"]);
            if (($i%$load_type) == 0) {
                $data_2["open_value"] = $result[$i]["open_value"];
            }
            if ($index == $load_type) {
                $index=0;
                $data_2["close_value"] = $result[$i]["close_value"];
                $data_2["datetime"] = $result[$i]["datetime"];
                $max_value = array_search(max($data_1), $data_1);
                $min_value = array_search(min($data_1), $data_1);
                $data_2["max_value"] = $data_1[$max_value];
                $data_2["min_value"] = $data_1[$min_value];
                array_push($data, $data_2);
                $data_1 = Array();
                $data_2 = Array();
            }

        }

        $arr['success'] = 1;
        $arr['data'] = $data;
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        echo json_encode($arr);
    }
}

function get_data_line(){
    if (empty($_POST['type'])) {
        echo '获取数据失败';
        exit;
    }
    $type = $_POST['type'];

    $conditions = array("a.type"=>$type);
    $param = "a.close_value, DATE_FORMAT(a.datetime, '%H:%i') datetime";
    $result = db_select("t_data", $conditions, $param , "limit 0, 31" ,array("a.datetime"=>"desc"));
    if ($result != null && count($result) > 0) {
        $arr['success'] = 1;
        $arr['data'] = $result;
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        echo json_encode($arr);
    }
}

function get_data_value() {
    if (empty($_POST['type'])) {
        echo '获取数据失败';
        exit;
    }
    $type = $_POST['type'];

    $conditions = array("a.type"=>$type);
    $param = "a.close_value, DATE_FORMAT(a.datetime, '%H:%i') datetime";
    $result = db_select("t_data", $conditions, $param , "limit 0, 1" ,array("a.datetime"=>"desc"));
    if ($result != null && count($result) > 0) {
        $arr['success'] = 1;
        $arr['data'] = $result;
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        echo json_encode($arr);
    }
}
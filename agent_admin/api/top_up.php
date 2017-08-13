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
if ($action == 'generate') {
    generate();
}
function generate() {
    $money = $_POST['money'];
    $num = $_POST['num'];
    if (empty($num)) {
        $num = 0;
    }
    if(empty($money)) {
        echo '请输入金额';
        return;
    }
    $arr = array();
    for ($i=0;$i<$num;$i++) {
        $password = random(6, "1234567890");
        $number = "";
        for ($j=0;$j<4;$j++) {
            if($j > 0) {
                $number = $number."-";
            }
            $number = $number.random(5, "1234567890");
        }
        $conditions = array("number"=>$number);
        $result = db_select('js_top_up', $conditions);
        if ($result == null) {
            $params = array("number"=>$number, "password"=>"$password", "money"=>"$money", "state"=>1, "datetime"=>date('Y-m-d H:i'));
            db_insert('js_top_up', $params, true);
        } else {
            // $num+1;
        }
    }

    $arr['success'] = 1;
    $arr['msg'] = '充值卡已生成完成';
    echo json_encode($arr);
}

function delete() {
    if (!empty($_POST['ids']) && $_POST['ids'] != "")  {
        $ids = $_POST['ids'];
        $sql = "delete from js_top_up where id in ($ids)";
        $result = db_execute($sql);
        if ($result > 0) {
            $arr['success'] = 1;
            $arr['msg'] = '所选充值卡已删除';
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['user'] = $result[0];
            $arr['msg'] = '操作失败';
            echo json_encode($arr);
        }
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
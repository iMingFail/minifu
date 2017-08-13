<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/12
 * Time: 17:37
 */
require_once '../db/mysql_operate.php';
date_default_timezone_set('PRC');
session_start();

$action = $_GET['action'];
if ($action == 'deal_order') {
    deal_order();
}
if ($action == 'deal_order_vouchers') {
    deal_order_vouchers();
}
if ($action == 'get_order_by_id') {
    get_order_by_id();
}
if ($action == 'get_new_order') {
    get_new_order();
}
if ($action == 'get_history_order') {
    get_history_order();
}
if ($action == 'get_history_order_list') {
    get_history_order_list();
}
if ($action == 'get_position_order') {
    get_position_order();
}
if ($action == 'get_order_list') {
    get_order_list();
}

function deal_order_vouchers() {
    $arr = array();
    $u_arr = explode(",",$_SESSION["user"]); // explode(",",$_COOKIE['u']);
    $user_id = $u_arr[0];
    $play_type = 0;
    $type = 1;
    $trade_time = 60;
    // $open_price = "0.00";
    $isModel = 0;
    $begin_time = date('Y-m-d H:i:s');
    // $vouchers_id = 0;
    if (empty($_POST['open_price']) || floatval($_POST['open_price']) <= 0)  {
        $arr['msg'] = "系统错误";
        $arr['success'] = 0;
        echo json_encode($arr);
        return;
    } else {
        $open_price = $_POST['open_price'];
    }
    if (empty($_POST['vouchers_id']) || intval($_POST['vouchers_id']) <= 0)  {
        $arr['msg'] = "代金劵错误";
        $arr['success'] = 0;
        echo json_encode($arr);
        return;
    } else {
        $vouchers_id =intval($_POST['vouchers_id']);
    }
	
    if (!empty($_POST['play_type']) && intval($_POST['play_type']) > 0)  {
        $play_type = $_POST['play_type'];
    }
    if (!empty($_POST['type']) && intval($_POST['type']) > 0)  {
        $type = $_POST['type'];
    }
    if (!empty($_POST['trade_time']) && intval($_POST['trade_time']) > 0)  {
        $trade_time = $_POST['trade_time'];
    }
    if (!empty($_POST['isModel']))  {
        $isModel = $_POST['isModel'];
    }
    if (!empty($_POST['begin_time']) && checkDateIsValid($_POST['begin_time']) == false)  {
        $begin_time = $_POST['begin_time'];
    }

    $conditions = array("id"=>$vouchers_id);
    $result = db_select('js_vouchers', $conditions, "user_id,money,state");
    if ($result != null) {
        if ($user_id == $result[0]["user_id"]) {
            if ($result[0]["state"] == 1) {
                if ($play_type > 0) {
                    $vouchers_price = $result[0]["money"];
                    $params = array("user_id"=>"$user_id", "play_type"=>"$play_type", "type"=>$type,
                        "trade_time"=>$trade_time, "order_price"=>"$vouchers_price", "price"=>0, "vouchers_price"=>$vouchers_price,
                        "open_price"=>"$open_price", "isModel"=>"$isModel", "state"=>0, "begin_time"=>"$begin_time");
                    $result = db_insert('js_order', $params, true);
                    if ($result > 0) {
                        $sql = "insert into js_balance_log (userId, order_code, type, money, give_money, state, datetime) values (".$user_id.",'".$result."',4,0,'".$vouchers_price."',1,'".$begin_time."')";
                        db_execute($sql);

                        db_execute("update js_vouchers set state=2 where id=" . $vouchers_id);
                        db_execute("update js_user set use_vouchers_num=use_vouchers_num+1 where id=" . $user_id);
                        // $user = "$u_arr[0],".$u_arr[1].",$u_arr[2],".$u_arr[3].",$u_arr[4],$u_arr[5],$u_arr[6],".(floatval($u_arr[7])-floatval($order_price)).",$u_arr[8]";
                        // setcookie("u", $user, time() + 2 * 60 * 60, "/");

                        $result_my_user = db_execute_select("select a.*, count(b.id) vouchers from js_user a left join js_vouchers b on b.user_id=a.id and b.state=1 where a.id = ".$user_id);
                        $user = $result_my_user[0]['id'].",".substr_replace($result_my_user[0]['mobilePhone'],'****',3,4).",".$result_my_user[0]['realName'].","
                            .substr_replace($result_my_user[0]['identity'],'****',10,4).",".$result_my_user[0]['sex'].",".$result_my_user[0]['nickName'].","
                            .$result_my_user[0]['district'].",".$result_my_user[0]['balance'].",".$result_my_user[0]['tempBalance'].",".$result_my_user[0]['bankName'].",".substr_replace($result_my_user[0]['bankNum'],'****',(strlen($result_my_user[0]['bankNum'])-4),4).","
                            .$result_my_user[0]['photoUrl'].",".$result_my_user[0]['nickName'].",".$result_my_user[0]['vouchers'].",".$result_my_user[0]['use_vouchers_num'];
                        // setcookie("u", $user, time() + 2 * 60 * 60, "/");

                        $_SESSION["user"] = $user;

                        $arr['vouchers'] = $result_my_user[0]['vouchers'];
                        $arr['success'] = 1;
                        $arr['r_o_id'] = $result;
                        echo json_encode($arr);
                    } else {
                        $arr['success'] = 0;
                        echo json_encode($arr);
                    }
                } else {
                    $arr['msg'] = "请正确选择购买的产品";
                    $arr['success'] = 0;
                    echo json_encode($arr);
                }
            } else {
                $arr['msg'] = "代金劵已使用";
                $arr['success'] = 0;
                echo json_encode($arr);
            }
        } else {
            $arr['msg'] = "这张代金劵不属于您";
            $arr['success'] = 0;
            echo json_encode($arr);
        }
    } else {
        $arr['msg'] = "没有找到代金劵";
        $arr['success'] = 0;
        echo json_encode($arr);
    }
}

function deal_order() {
    $u_arr =explode(",",$_SESSION["user"]);//explode(",",$_COOKIE['u']);
    $user_id = $u_arr[0];
    $play_type = 0;
    $type = 1;
    $trade_time = 60;
    $order_price = "0.00";
    $open_price = "0.00";
    $isModel = 0;
    $vouchers_id = 0;
    // $balance = 0;
    // $vouchers_money = 0;
    $begin_time = date('Y-m-d H:i:s');
    $arr = array();

    if (empty($_POST['order_price']) || floatval($_POST['order_price']) <= 0)  {
        $arr['msg'] = "你的下注金额呢？";
        $arr['success'] = 0;
        echo json_encode($arr);
        exit;
    } else if (floatval($_POST['order_price']) <= 20 && floatval($_POST['order_price']) > 5000) {
        $arr['msg'] = "下注金额太大或太小了！";
        $arr['success'] = 0;
        echo json_encode($arr);
        exit;
    } else {
        $order_price = $_POST['order_price'];
    }
    if (empty($_POST['open_price']) || floatval($_POST['open_price']) <= 0)  {
        $arr['msg'] = "系统错误";
        $arr['success'] = 0;
        echo json_encode($arr);
        exit;
    } else {
        $open_price = $_POST['open_price'];
    }
    if (!empty($_POST['play_type']) && intval($_POST['play_type']) > 0)  {
        $play_type = $_POST['play_type'];
    }
    if (!empty($_POST['type']) && intval($_POST['type']) > 0)  {
        $type = $_POST['type'];
    }
    if (!empty($_POST['trade_time']) && intval($_POST['trade_time']) > 0)  {
        $trade_time = $_POST['trade_time'];
    }
    if (!empty($_POST['isModel']))  {
        $isModel = $_POST['isModel'];
    }
    if (!empty($_POST['begin_time']) && checkDateIsValid($_POST['begin_time']) == false)  {
       $begin_time = $_POST['begin_time'];
    }

    if (!empty($_POST['vouchers_id']) && $_POST['vouchers_id'] != "")  {
        $vouchers_id = $_POST['vouchers_id'];
    }
    /*if (!empty($_POST['vouchers_money']) && $_POST['vouchers_money'] != "")  {
        $vouchers_money = $_POST['vouchers_money'];
    }
    if (!empty($_POST['balance']) && $_POST['balance'] != "")  {
        $balance = $_POST['balance'];
    }*/

    if ($play_type > 0) {
        $vouchers_price = 0;
        if ($vouchers_id > 0) {
            $conditions = array("id"=>$vouchers_id);
            $vouchers_result = db_select('js_vouchers', $conditions, "user_id,money,state");
            if ($vouchers_result != null) {
                if ($user_id == $vouchers_result[0]["user_id"]) {
                    if ($vouchers_result[0]["state"] == 2) {
                        $arr['msg'] = "代金劵已使用";
                        $arr['success'] = 0;
                        echo json_encode($arr);
                        exit;
                    }
                } else {
                    $arr['msg'] = "这张代金劵不属于您";
                    $arr['success'] = 0;
                    echo json_encode($arr);
                    exit;
                }
            } else {
                $arr['msg'] = "没有找到代金劵";
                $arr['success'] = 0;
                echo json_encode($arr);
                exit;
            }
        }

        $new_order_price = $order_price;
        if ($vouchers_id > 0) {
            $vouchers_price = $vouchers_result[0]['money'];
            $new_order_price = floatval($order_price) - floatval($vouchers_result[0]['money']);
            if ($new_order_price < 0) {
                $new_order_price = $order_price;
            }
        }

        $params = array("user_id"=>"$user_id", "play_type"=>"$play_type", "type"=>$type,
            "trade_time"=>$trade_time, "order_price"=>"$order_price", "price"=>"$new_order_price","vouchers_price"=>$vouchers_price,
            "open_price"=>"$open_price", "isModel"=>"$isModel", "state"=>0, "begin_time"=>"$begin_time");
        $result = db_insert('js_order', $params, true);
        if ($result > 0) {
            if ($isModel == 1) {
                if ($vouchers_id > 0) {
                    db_execute("update js_vouchers set state=2 where id=" . $vouchers_id);
                    $sql = "insert into js_balance_log (userId, order_code, type, money, give_money, state, datetime) values (".$user_id.",'".$result."',3,'".$new_order_price."',".$vouchers_result[0]['money'].",1,'".$begin_time."')";
                    db_execute($sql);

                    db_execute("update js_user set balance=balance-" . $new_order_price . ", use_vouchers_num=use_vouchers_num+1 where id=" . $user_id);
                } else {
                    $sql = "insert into js_balance_log (userId, order_code, type, money, state, datetime) values (".$user_id.",'".$result."',3,'".$order_price."',1,'".$begin_time."')";
                    db_execute($sql);
                    db_execute("update js_user set balance=balance-" . $order_price . " where id=" . $user_id);
                }

                // $user = "$u_arr[0],".$u_arr[1].",$u_arr[2],".$u_arr[3].",$u_arr[4],$u_arr[5],$u_arr[6],".(floatval($u_arr[7])-floatval($order_price)).",$u_arr[8]";
                // setcookie("u", $user, time() + 2 * 60 * 60, "/");
                //从自己出发，分别找到自己的上级，上上级和上上上级给他们增加团队总额，然后分别计算他们的返佣
                $sql = "select pid,pid1,pid2 from js_user where id=".$user_id;
                $pid_r = db_execute_select($sql);
                $pid = $user_id;
                //存在上级的情况
                if ($pid_r[0]["pid"] != 0) {
                    $pid = $pid_r[0]["pid"];
                    //增加一级分销总额计算是否达到返佣条件
                    if ($vouchers_id > 0) {
                        //增加一级分销总额
                        db_execute("update js_user set total1=total1+" . $new_order_price . "  where id=" . $pid);
                    }else{
                        //增加一级分销总额
                        db_execute("update js_user set total1=total1+" . $order_price . "  where id=" . $pid);
                    }
                    //判断返佣情况
                    $sql = "select * from js_user where id = ".$pid;
                    $all_order_price_r = db_execute_select($sql);
                    $all_order_price = 0;
                    if (!empty($all_order_price_r) && $all_order_price_r != null && $all_order_price_r[0]['total1']>0) {
                        $all_order_price = $all_order_price_r[0]["total1"];
                        $conditions = array("a.conditions <= $all_order_price" => "","a.level"=>'0');
                        $result_rebate_setting = db_select('js_rebate_setting', $conditions, "a.return_rates");
                        if (!empty($result_rebate_setting) && $result_rebate_setting != null && count($result_rebate_setting) > 0) {
                            $index = count($result_rebate_setting) - 1;
                            if ($index < 0) {
                                $index = 0;
                            }
                            if ($vouchers_id > 0) {
                                $rebate = $new_order_price * $result_rebate_setting[$index]["return_rates"] / 100;
                            } else {
                                $rebate = $order_price * $result_rebate_setting[$index]["return_rates"] / 100;
                            }
                            //$rebate = $order_price * 100 / 100;
                            $result_user = db_execute_select("select b.id, b.balance from js_user a left join js_user b on a.pid = b.id where a.id = ".$user_id);
                            if (!empty($result_user) && count($result_user) > 0) {
                                $sql = "insert into js_rebate (userId, rebate_uid, level, money, state, datetime, order_id) values (".$user_id.",".$result_user[0]["id"].", 1,".$rebate.", 1, now(),".$result.")";
                                db_execute($sql);
                                db_execute("update js_user set balance=balance+" . $rebate . " , rebate_balance=rebate_balance+" . $rebate . ", rebate1=rebate1+". $rebate ." where id=" . $result_user[0]["id"]);
                            }
                        }
                    }
                }
                
                //存在上上级的情况
                if ($pid_r[0]["pid1"] != 0) {
                    $pid = $pid_r[0]["pid1"];
                    //var_dump($pid);
                    //增加一级分销总额计算是否达到返佣条件
                    if ($vouchers_id > 0) {
                        //增加一级分销总额
                        db_execute("update js_user set total2=total2+" . $new_order_price . "  where id=" . $pid);
                    }else{
                        //增加一级分销总额
                        db_execute("update js_user set total2=total2+" . $order_price . "  where id=" . $pid);
                    }
                    //判断返佣情况
                    $sql = "select * from js_user where id = ".$pid;
                    $all_order_price_r = db_execute_select($sql);
                    $all_order_price = 0;
                    if (!empty($all_order_price_r) && $all_order_price_r != null && $all_order_price_r[0]['total2']>0) {
                        $all_order_price = $all_order_price_r[0]["total2"];
                        $conditions = array("a.conditions <= $all_order_price" => "","a.level"=>1);
                        $result_rebate_setting = db_select('js_rebate_setting', $conditions, "a.return_rates");
                        if (!empty($result_rebate_setting) && $result_rebate_setting != null && count($result_rebate_setting) > 0) {
                            $index = count($result_rebate_setting) - 1;
                            if ($index < 0) {
                                $index = 0;
                            }
                            if ($vouchers_id > 0) {
                                $rebate = $new_order_price * $result_rebate_setting[$index]["return_rates"] / 100;
                            } else {
                                $rebate = $order_price * $result_rebate_setting[$index]["return_rates"] / 100;
                            }
                            $result_user = db_execute_select("select b.id, b.balance from js_user a left join js_user b on a.pid1 = b.id where a.id = ".$user_id);
                            if (!empty($result_user) && count($result_user) > 0) {
                                $sql = "insert into js_rebate (userId, rebate_uid, level, money, state, datetime, order_id) values (".$user_id.",".$result_user[0]["id"].", 2,".$rebate.", 1, now(),".$result.")";
                                db_execute($sql);
                                db_execute("update js_user set balance=balance+" . $rebate . " , rebate_balance=rebate_balance+" . $rebate . ", rebate2=rebate2+". $rebate ." where id=" . $result_user[0]["id"]);
                            }
                        }
                    }
                }
                
                
                //存在上上上级的情况
                if ($pid_r[0]["pid2"] != 0) {
                    $pid = $pid_r[0]["pid2"];
                    //var_dump($pid);
                    //增加一级分销总额计算是否达到返佣条件
                    if ($vouchers_id > 0) {
                        //增加一级分销总额
                        db_execute("update js_user set total3=total3+" . $new_order_price . "  where id=" . $pid);
                    }else{
                        //增加一级分销总额
                        db_execute("update js_user set total3=total3+" . $order_price . "  where id=" . $pid);
                    }
                    //判断返佣情况
                    $sql = "select * from js_user where id = ".$pid;
                    $all_order_price_r = db_execute_select($sql);
                    $all_order_price = 0;
                    if (!empty($all_order_price_r) && $all_order_price_r != null && $all_order_price_r[0]['total3']>0) {
                        $all_order_price = $all_order_price_r[0]["total3"];
                        $conditions = array("a.conditions <= $all_order_price" => "","a.level"=>2);
                        $result_rebate_setting = db_select('js_rebate_setting', $conditions, "a.return_rates");
                        if (!empty($result_rebate_setting) && $result_rebate_setting != null && count($result_rebate_setting) > 0) {
                            $index = count($result_rebate_setting) - 1;
                            if ($index < 0) {
                                $index = 0;
                            }
                            if ($vouchers_id > 0) {
                                $rebate = $new_order_price * $result_rebate_setting[$index]["return_rates"] / 100;
                            } else {
                                $rebate = $order_price * $result_rebate_setting[$index]["return_rates"] / 100;
                            }
                            $result_user = db_execute_select("select b.id, b.balance from js_user a left join js_user b on a.pid2 = b.id where a.id = ".$user_id);
                            if (!empty($result_user) && count($result_user) > 0) {
                                $sql = "insert into js_rebate (userId, rebate_uid, level, money, state, datetime, order_id) values (".$user_id.",".$result_user[0]["id"].", 3,".$rebate.", 1, now(),".$result.")";
                                db_execute($sql);
                                db_execute("update js_user set balance=balance+" . $rebate . " , rebate_balance=rebate_balance+" . $rebate . ", rebate3=rebate3+". $rebate ." where id=" . $result_user[0]["id"]);
                            }
                        }
                    }
                }
                
                
                
                
                
                
                
                
                
                
                
                //$sql = "select sum(a.order_price) + (select sum(c.order_price) from js_order c left join js_user d on d.id=c.user_id where d.pid = b.id and c.isModel = 1) all_order_price from js_order a left join js_user b on a.user_id = b.id where a.isModel=1 and b.id = ". $pid;

                // $sql = "select sum(a.order_price) + (select sum(c.order_price) from js_order c where c.user_id = b.pid) all_order_price from js_order a left join js_user b on a.user_id = b.id where a.isModel=1 and b.id = " . $user_id;
                //$all_order_price_r = db_execute_select($sql);
                //$all_order_price = 0;
                /*if (!empty($all_order_price_r) && $all_order_price_r != null && count($all_order_price_r) > 0) {
                    $all_order_price = $all_order_price_r[0]["all_order_price"];
                        $conditions = array("a.conditions <= $all_order_price" => "");
                    $result_rebate_setting = db_select('js_rebate_setting', $conditions, "a.return_rates");
                    if (!empty($result_rebate_setting) && $result_rebate_setting != null && count($result_rebate_setting) > 0) {
                        $index = count($result_rebate_setting) - 1;
                        if ($index < 0) {
                            $index = 0;
                        }
                        if ($vouchers_id > 0) {
                            $rebate = $new_order_price * $result_rebate_setting[$index]["return_rates"] / 100;
                        } else {
                            $rebate = $order_price * $result_rebate_setting[$index]["return_rates"] / 100;
                        }

                        $result_user = db_execute_select("select b.id, b.balance from js_user a left join js_user b on a.pid = b.id where a.id = ".$user_id);
                        if (!empty($result_user) && count($result_user) > 0) {
                            $sql = "insert into js_rebate (userId, rebate_uid, level, money, state, datetime, order_id) values (".$user_id.",".$result_user[0]["id"].", 1,".$rebate.", 1, now(),".$result.")";
                            db_execute($sql);
                            db_execute("update js_user set balance=balance+" . $rebate . " , rebate_balance=rebate_balance+" . $rebate . " where id=" . $result_user[0]["id"]);
                        }
                    }
                }*/
            } else {
                db_execute("update js_user set tempBalance=tempBalance-" . $order_price . " where id=" . $user_id);
                /// $user = "$u_arr[0],".$u_arr[1].",$u_arr[2],".$u_arr[3].",$u_arr[4],$u_arr[5],$u_arr[6],".$u_arr[7].",".(floatval($u_arr[8])-floatval($order_price));
                // setcookie("u", $user, time() + 2 * 60 * 60, "/");
            }

            $result_my_user = db_execute_select("select a.*, count(b.id) vouchers from js_user a left join js_vouchers b on b.user_id=a.id and b.state=1 where a.id = ".$user_id);
            $user = $result_my_user[0]['id'].",".substr_replace($result_my_user[0]['mobilePhone'],'****',3,4).",".$result_my_user[0]['realName'].","
                .substr_replace($result_my_user[0]['identity'],'****',10,4).",".$result_my_user[0]['sex'].",".$result_my_user[0]['nickName'].","
                .$result_my_user[0]['district'].",".$result_my_user[0]['balance'].",".$result_my_user[0]['tempBalance'].",".$result_my_user[0]['bankName'].",".substr_replace($result_my_user[0]['bankNum'],'****',(strlen($result_my_user[0]['bankNum'])-4),4).","
                .$result_my_user[0]['photoUrl'].",".$result_my_user[0]['nickName'].",".$result_my_user[0]['vouchers'].",".$result_my_user[0]['use_vouchers_num'];
            // setcookie("u", $user, time() + 2 * 60 * 60, "/");

            $_SESSION["user"] = $user;


            $arr['balance'] = $result_my_user[0]['balance'];
            $arr['tempBalance'] = $result_my_user[0]['tempBalance'];
            $arr['success'] = 1;
            $arr['r_o_id'] = $result;
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            echo json_encode($arr);
        }
    } else {
        $arr = array();
        $arr['success'] = 0;
        echo json_encode($arr);
    }
}
function get_order_by_id() {
    if (empty($_POST['r_o_id']) || intval($_POST['r_o_id']) <= 0)  {
        echo '没有订单ID，不能查找啊';
        exit;
    }
    $arr = array();
    $conditions = array("a.id"=>$_POST['r_o_id']);
    $result = db_select('js_order', $conditions, "a.gain_price, a.state, a.close_price, a.order_price");
    if ($result != null) {
        $arr['success'] = 1;
        $arr['order'] = $result;
        echo json_encode($arr);
    }else {
        $arr['success'] = 0;
        $arr['msg'] = '没有找到指定订单';
        echo json_encode($arr);
    }
}
function get_new_order() {
    // $isModel = $_POST["isModel"];
    $arr = array();
    // $conditions = array("a.state !=0"=>"","a.isModel" => $isModel);
    // $conditions = array("a.isModel"=>"1");
    // $conditions = array();
    // $left_join_tab=array('js_type'=>array('as'=>'b', 'param'=>'id', ''=>'play_type'));
    // $result = db_select('js_order', $conditions, "a.type,a.order_price,a.begin_time,b.name", "limit 0, 14", array("begin_time"=>"desc"), $left_join_tab);

    $result = db_execute_select("select * from(select a.begin_time, b.name,a.type,a.order_price from js_order a left join js_type b on b.id = a.play_type union all select a.begin_time, b.name,a.type,a.order_price from js_order_virtual a left join js_type b on b.id = a.play_type) a order by begin_time desc limit 0, 15");
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
function get_history_order() {
    $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
    $user_id = $u_arr[0];
    $isModel = $_POST["isModel"];
    $arr = array();
    $conditions = array("a.user_id"=>$user_id, "a.state != 0"=>"","a.isModel" => $isModel, "UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(a.begin_time) > trade_time"=>"");
    $left_join_tab=array('js_type'=>array('as'=>'b', 'param'=>'id', ''=>'play_type'));
    $result = db_select('js_order', $conditions, "a.type,a.order_price,a.price,a.vouchers_price,a.end_time,a.gain_price,a.state,b.name", "limit 0, 15", array("a.begin_time"=>"desc"), $left_join_tab);
    if ($result != null) {
        $arr['success'] = 1;
        $arr['data'] = $result;
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        $arr['msg'] = '当前没有任何记录';
        echo json_encode($arr);
    }
}
function get_history_order_list() {
    $pageNo = $_POST['page'];
    $pagesize = $_POST['pagesize'];
    $pageNo = (intval($pageNo)-1) * intval($pagesize);
    $isModel = $_POST["isModel"];
    $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
    $user_id = $u_arr[0];
    $arr = array();
    $conditions = array("a.user_id"=>$user_id, "a.state != 0"=>"", "isModel"=>$isModel, "UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(a.begin_time) > a.trade_time"=>"");
    $left_join_tab=array('js_type'=>array('as'=>'b', 'param'=>'id', ''=>'play_type'));
    $result = db_select('js_order', $conditions, "a.type,a.order_price,a.end_time,a.begin_time,a.gain_price,a.price,a.vouchers_price,a.state,b.name,a.trade_time,a.open_price,a.close_price", "limit $pageNo, $pagesize", array("a.begin_time"=>"desc"), $left_join_tab);
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

function get_order_list() {
    $pageNo = $_POST['page'];
    $pagesize = $_POST['pagesize'];
    $pageNo = (intval($pageNo)-1) * intval($pagesize);
    $type = $_POST["type"];
    $state = $_POST["state"];
    $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
    $user_id = $u_arr[0];
    $arr = array();
    $conditions = array("a.user_id"=>$user_id, "a.isModel"=>1);
    if ($type != 0) {
        $conditions["a.play_type"] = $type;
    }
    if ($state == 1) {
        $conditions["a.state"] = "0";
    } else if($state == 2) {
        $conditions["a.state != 0"] = "";
        $conditions["UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(a.begin_time) > a.trade_time"] = "";
    }
    $left_join_tab=array('js_type'=>array('as'=>'b', 'param'=>'id', ''=>'play_type'));
    $result = db_select('js_order', $conditions, "a.type,a.order_price,a.price,a.vouchers_price,a.end_time,a.begin_time,a.gain_price,a.state,b.name,a.trade_time,a.open_price,a.close_price", "limit $pageNo, $pagesize", array("a.begin_time"=>"desc"), $left_join_tab);
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

function get_position_order()
{
	$time_now = time();
    $u_arr = explode(",",$_SESSION["user"]); //explode(",", $_COOKIE['u']);
    $user_id = $u_arr[0];
    $isModel = $_POST["isModel"];
    $arr = array();
    $conditions = array("a.user_id" => $user_id, "a.state = 0" => "", "a.isModel" => $isModel);
    $left_join_tab = array('js_type' => array('as' => 'b', 'param' => 'id', '' => 'play_type'));
        $result = db_select('js_order', $conditions, "a.play_type,a.begin_time,b.name,a.type,a.open_price,a.state,a.trade_time", "limit 0, 15", array("a.begin_time" => "desc"), $left_join_tab);
    if ($result != null) {
		foreach ($result as $k=>$v){
			$result[$k]['count_down'] = intval($result[$k]['trade_time']) - (intval($time_now) - intval(strtotime($result[$k]['begin_time'])));
		}
        $arr['success'] = 1;
        $arr['data'] = $result;
        echo json_encode($arr);
    } else {
        $arr['success'] = 0;
        $arr['msg'] = '当前没有任何记录';
        echo json_encode($arr);
    }
}

/**
 * 校验日期格式是否正确
 *
 * @param string $date 日期
 * @param string $formats 需要检验的格式数组
 * @return boolean
 */
function checkDateIsValid($date, $formats = array("Y-m-d H:i:s")) {
    $unixTime = strtotime($date);
    if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
        return false;
    }

    //校验日期的有效性，只要满足其中一个格式就OK
    foreach ($formats as $format) {
        if (date($format, $unixTime) == $date) {
            return true;
        }
    }
    return false;
}
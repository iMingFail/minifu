<?php
    /**
     * Created by PhpStorm.
     * User: tangjim
     * Date: 2016/10/6
     * Time: 13:46
     */
	 
	
    require_once '../db/mysql_operate.php';
    date_default_timezone_set('PRC');
    session_start();
    $action = $_GET['action'];
    if ($action == 'login') {
        login();
    } else if ($action == 'regist') {
        regist();
    } else if ($action == 'bank_info'){
        bank_info();
    } else if ($action == 'user_info'){
        user_info();
    } else if ($action == 'out_money') {
        out_money();
    } else if ($action == 'user_balance') {
        user_balance();
    } else if ($action == 'user_team_info') {
        user_team_info();
    } else if ($action == 'user_team_list') {
        user_team_list();
    } else if ($action == 'user_team_rebate_list') {
        user_team_rebate_list();
    } else if ($action == 'bank_water_show') {
        bank_water_show();
    } else if ($action == 'load_user') {
        load_user();
    } else if ($action == 'load_user_base') {
        load_user_base();
    } else if ($action == 'change_password') {
        change_password();
    } else if ($action == 'load_bank_info') {
        load_bank_info();
    } else if ($action == 'is_use_mobilePhone') {
        is_use_mobilePhone();
    } else if ($action == 'forget') {
        forget_password();
    }

    function load_user_base() {
        $arr = array();
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];

        $sql = "select a.*, (select count(b.id) u_num from js_user b where b.pid = a.id) as u_num, count(b.id) vouchers from js_user a left join js_vouchers b on b.user_id=a.id and b.state=1 where a.id =".$user_id;
        $user_result = db_execute_select($sql);
        if ($user_result != null) {
            $user = $user_result[0]['id'].",".$user_result[0]['mobilePhone'].",".$user_result[0]['realName'].","
                .substr_replace($user_result[0]['identity'],'****',10,4).",".$user_result[0]['sex'].",".$user_result[0]['nickName'].","
                .$user_result[0]['district'].",".$user_result[0]['balance'].",".$user_result[0]['tempBalance'].",".$user_result[0]['bankName'].",".substr_replace($user_result[0]['bankNum'],'****',(strlen($user_result[0]['bankNum'])-4),4).","
                .$user_result[0]['photoUrl'].",".$user_result[0]['nickName'].",".$user_result[0]['vouchers'].",".$user_result[0]['use_vouchers_num'];
            // setcookie("u", $user, time() + 2 * 60 * 60, "/");
            $_SESSION["user"] = $user;
            $arr['u_num'] = $user_result[0]["u_num"];
            $arr['code'] = $user_result[0]["code"];
        }
        echo json_encode($arr);
    }

    function load_user() {
        $arr = array();
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        // $conditions = array("a.id"=>$user_id);

        $sql = "select a.*, (select count(b.id) u_num from js_user b where b.pid = a.id) as u_num, count(b.id) vouchers from js_user a left join js_vouchers b on b.user_id=a.id and b.state=1 where a.id =".$user_id;
        $user_result = db_execute_select($sql);

        // $user_result = db_select('js_user', $conditions);
        if ($user_result != null) {
            $user = $user_result[0]['id'].",".substr_replace($user_result[0]['mobilePhone'],'****',3,4).",".$user_result[0]['realName'].","
                .substr_replace($user_result[0]['identity'],'****',10,4).",".$user_result[0]['sex'].",".$user_result[0]['nickName'].","
                .$user_result[0]['district'].",".$user_result[0]['balance'].",".$user_result[0]['tempBalance'].",".$user_result[0]['bankName'].",".substr_replace($user_result[0]['bankNum'],'****',(strlen($user_result[0]['bankNum'])-4),4).","
                .$user_result[0]['photoUrl'].",".$user_result[0]['nickName'].",".$user_result[0]['vouchers'].",".$user_result[0]['use_vouchers_num'];
            // setcookie("u", $user, time() + 2 * 60 * 60, "/");
            $_SESSION["user"] = $user;
            $arr['u_num'] = $user_result[0]["u_num"];

            $sql = "select name as user_level from js_rebate_setting where conditions <= (select sum(a.order_price) + (select sum(c.order_price) from js_order c left join js_user d on d.id=c.user_id where d.pid = b.id and c.isModel = 1) all_order_price from js_order a left join js_user b on a.user_id = b.id where a.isModel=1 and b.id = ".$user_id.")";
            $user_level_result = db_execute_select($sql);
            $index = count($user_level_result) - 1;
            if ($index < 0) {
                $index = 0;
            }
            $arr['user_level'] = $user_level_result[$index]["user_level"];
        }

        // 累计团队返利
        $sql = "select sum(money) rebate_all from js_rebate where state=1 and rebate_uid=".$user_id;
        $result_rebate_all = db_execute_select($sql);
        if ($result_rebate_all) {
            $arr['rebate_all'] = $result_rebate_all;
        }
        // 今日团队返利
        $sql = "select sum(money) rebate_today from js_rebate where state=1 and DATE_FORMAT(datetime, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d') and rebate_uid=".$user_id;
        $result_rebate_today = db_execute_select($sql);
        if ($result_rebate_today) {
            $arr['rebate_today'] = $result_rebate_today;
        }

        // 今日团队交易量
        $sql = "select count(a.id) as orderNum from js_order a";
        $sql = $sql." left join js_user b on b.id = a.user_id where b.pid=".$user_id." and a.isModel=1 and DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')";
        $result_order_num_group_today = db_execute_select($sql);
        if ($result_order_num_group_today) {
            $arr['order_num_group_today'] = $result_order_num_group_today;
        }

        // 今日团队盈亏
        $sql = "select (((select sum(b.gain_price) from js_order b left join js_user u1 on u1.id = b.user_id where b.isModel=1 and b.state = 1 and (u1.pid=$user_id or u1.id = $user_id) and b.state = 1 and DATE_FORMAT(b.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')) - ";
        $sql = $sql."(select sum(c.order_price) from js_order c left join js_user u2 on u2.id = c.user_id where c.isModel=1 and c.state = 1 and DATE_FORMAT(c.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d') and (u2.pid=$user_id or u2.id = $user_id)))-sum(a.order_price)) today_gain from js_order a ";
        $sql = $sql."left join js_user u3 on u3.id = a.user_id where a.isModel=1 and a.state = 2 and DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d') and (u3.pid=$user_id or u3.id = $user_id)";

        $result_order_gain_group_today = db_execute_select($sql);
        if ($result_order_gain_group_today) {
            $arr['order_gain_group_today'] = $result_order_gain_group_today;
        }

        $arr['success'] = 1;
        echo json_encode($arr);
    }

    // 获取用户银行信息
    function load_bank_info() {
        $arr = array();
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        // $conditions = array("a.id"=>$user_id);

        $sql = "select a.*, (select count(b.id) u_num from js_user b where b.pid = a.id) as u_num from js_user a where a.id =".$user_id;
        $user_result = db_execute_select($sql);

        // $user_result = db_select('js_user', $conditions);
        if ($user_result != null) {
            $out_money = $user_result[0]['balance'];
            if ($user_result[0]['realName'] == null || $user_result[0]['realName'] == "") {
                $arr['success'] = 0;
                echo json_encode($arr);
                exit;
            }
            if ($user_result[0]['identity'] == null || $user_result[0]['identity'] == "") {
                $arr['success'] = 0;
                echo json_encode($arr);
                exit;
            }
            if ($user_result[0]['bankName'] == null || $user_result[0]['bankName'] == "") {
                $arr['success'] = 0;
                echo json_encode($arr);
                exit;
            }
            if ($user_result[0]['bankNum'] == null || $user_result[0]['bankNum'] == "") {
                $arr['success'] = 0;
                echo json_encode($arr);
                exit;
            }
            if ($user_result[0]['balance'] == null || $user_result[0]['balance'] == "") {
                $arr['success'] = 0;
                echo json_encode($arr);
                exit;
            }
            $arr['realName'] = $user_result[0]['realName'];
            $arr['identity'] = $user_result[0]['identity'];
            $arr['bankName'] = $user_result[0]['bankName'];
            $arr['bankNum'] = $user_result[0]['bankNum'];
            $arr['balance'] = $user_result[0]['balance'];
            $arr['out_money'] = $out_money;
            $arr['success'] = 1;
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            echo json_encode($arr);
        }
    }
    // 获取用户基本信息
    function user_info() {
        $arr = array();
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        // $conditions = array("a.id"=>$user_id);

        $sql = "select a.*, (select count(b.id) u_num from js_user b where b.pid = a.id) as u_num, count(b.id) vouchers from js_user a left join js_vouchers b on b.user_id=a.id and b.state=1 where a.id =".$user_id;
        $user_result = db_execute_select($sql);

        // $user_result = db_select('js_user', $conditions);
        if ($user_result != null) {
            $user = $user_result[0]['id'].",".substr_replace($user_result[0]['mobilePhone'],'****',3,4).",".$user_result[0]['realName'].","
            .substr_replace($user_result[0]['identity'],'****',10,4).",".$user_result[0]['sex'].",".$user_result[0]['nickName'].","
            .$user_result[0]['district'].",".$user_result[0]['balance'].",".$user_result[0]['tempBalance'].",".$user_result[0]['bankName'].",".substr_replace($user_result[0]['bankNum'],'****',(strlen($user_result[0]['bankNum'])-4),4).","
                .$user_result[0]['photoUrl'].",".$user_result[0]['nickName'].",".$user_result[0]['vouchers'].",".$user_result[0]['use_vouchers_num'];
            // setcookie("u", $user, time() + 2 * 60 * 60, "/");
            $_SESSION["user"] = $user;
            $arr['u_num'] = $user_result[0]["u_num"];
            $arr['code'] = $user_result[0]["code"];

            $sql = "select name as user_level from js_rebate_setting where conditions <= (select ifnull(sum(a.order_price),0) + (select ifnull(sum(c.order_price),0) from js_order c left join js_user d on d.id=c.user_id where d.pid = b.id and c.isModel = 1) all_order_price from js_order a left join js_user b on a.user_id = b.id where a.isModel=1 and b.id = ".$user_id.")";
            $user_level_result = db_execute_select($sql);
            $index = count($user_level_result) - 1;
            if ($index < 0) {
                $index = 0;
            }
            $arr['user_level'] = $user_level_result[$index]["user_level"];

            if (!empty($_POST["b_i"]) && $_POST["b_i"] == true) {
                $out_money = floatval($user_result[0]['balance']) + floatval($user_result[0]['freeze_balance']);
                /*$isOut = db_execute_select("select (((select (sum(money)+sum(give_money)) up_money from js_balance_log where userId=$user_id and type = 1)*5) <= (select sum(order_price) order_price from js_order where isModel=1 and user_id=$user_id and begin_time >= (select datetime from js_balance_log where userid = $user_id and type = 1 order by datetime desc limit 1))) isOut");
                if (!empty($isOut) && count(($isOut)) > 0) {
                    if ($isOut[0]['isOut']  == "1") {
                        $out_money = $user_result[0]['balance'];
                    } else {
                        $out_money = $user_result[0]['rebate_balance'];
                    }
                }*/

                if ($user_result[0]['realName'] == null || $user_result[0]['realName'] == "") {
                    $arr['success'] = 0;
                    echo json_encode($arr);
                    exit;
                }
                if ($user_result[0]['identity'] == null || $user_result[0]['identity'] == "") {
                    $arr['success'] = 0;
                    echo json_encode($arr);
                    exit;
                }
                if ($user_result[0]['bankName'] == null || $user_result[0]['bankName'] == "") {
                    $arr['success'] = 0;
                    echo json_encode($arr);
                    exit;
                }
                if ($user_result[0]['bankNum'] == null || $user_result[0]['bankNum'] == "") {
                    $arr['success'] = 0;
                    echo json_encode($arr);
                    exit;
                }
                if ($user_result[0]['balance'] == null || $user_result[0]['balance'] == "") {
                    $arr['success'] = 0;
                    echo json_encode($arr);
                    exit;
                }
                $arr['realName'] = $user_result[0]['realName'];
                $arr['identity'] = $user_result[0]['identity'];
                $arr['bankName'] = $user_result[0]['bankName'];
                $arr['bankNum'] = $user_result[0]['bankNum'];
                $arr['balance'] = $user_result[0]['balance'];
                $arr['out_money'] = $out_money;
            }
        }

        if(!empty($_POST["isModel"])) {
            $isModel = $_POST["isModel"];
            $conditions = array("b.id" => $user_id, "a.isModel" => $isModel);
            $left_join_tab = array('js_user' => array('as' => 'b', 'param' => 'id', '' => 'user_id'));
            $params = "count(a.id) as orderNum, sum(a.order_price) priceNum";
            $order_result = db_select('js_order', $conditions, $params, "", array(), $left_join_tab);
            if ($user_result) {
                $arr['order_state'] = $order_result;
            }

            $conditions = array("b.id" => $user_id, "a.isModel" => $isModel, "DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')"=>"");
            $params = "count(a.id) as orderNum";
            $order_orderNum_today = db_select('js_order', $conditions, $params, "", array(), $left_join_tab);

            if ($order_orderNum_today) {
                $arr['order_orderNum_today'] = $order_orderNum_today;
            }

            $sql = "select (((select sum(b.gain_price) from js_order b where b.isModel=1 and user_id=$user_id and b.state = 1 and DATE_FORMAT(b.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')) - ";
            $sql = $sql."(select sum(c.order_price) from js_order c where c.isModel=1 and c.state = 1 and user_id=$user_id and DATE_FORMAT(c.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')))-sum(a.order_price)) today_gain from js_order a ";
            $sql = $sql."where a.isModel=1 and a.state = 2 and user_id=$user_id and DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')";
            $order_today_gain_today = db_execute_select($sql);

            if ($order_today_gain_today) {
                $arr['order_today_gain_today'] = $order_today_gain_today;
            }

        } else {
            // 累计团队返利
            $sql = "select sum(money) rebate_all from js_rebate where state=1 and rebate_uid=".$user_id;
            $result_rebate_all = db_execute_select($sql);
            if ($result_rebate_all) {
                $arr['rebate_all'] = $result_rebate_all;
            }
            // 今日团队返利
            $sql = "select sum(money) rebate_today from js_rebate where state=1 and DATE_FORMAT(datetime, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d') and rebate_uid=".$user_id;
            $result_rebate_today = db_execute_select($sql);
            if ($result_rebate_today) {
                $arr['rebate_today'] = $result_rebate_today;
            }

            // 今日团队交易量
            $sql = "select count(a.id) as orderNum from js_order a";
            $sql = $sql." left join js_user b on b.id = a.user_id where b.pid=".$user_id." and a.isModel=1 and DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')";
            $result_order_num_group_today = db_execute_select($sql);
            if ($result_order_num_group_today) {
                $arr['order_num_group_today'] = $result_order_num_group_today;
            }

            // 今日团队盈亏
            $sql = "select (((select sum(b.gain_price) from js_order b left join js_user u1 on u1.id = b.user_id where b.isModel=1 and b.state = 1 and (u1.pid=$user_id or u1.id = $user_id) and b.state = 1 and DATE_FORMAT(b.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')) - ";
            $sql = $sql."(select sum(c.order_price) from js_order c left join js_user u2 on u2.id = c.user_id where c.isModel=1 and c.state = 1 and DATE_FORMAT(c.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d') and (u2.pid=$user_id or u2.id = $user_id)))-sum(a.order_price)) today_gain from js_order a ";
            $sql = $sql."left join js_user u3 on u3.id = a.user_id where a.isModel=1 and a.state = 2 and DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d') and (u3.pid=$user_id or u3.id = $user_id)";

            $result_order_gain_group_today = db_execute_select($sql);
            if ($result_order_gain_group_today) {
                $arr['order_gain_group_today'] = $result_order_gain_group_today;
            }
        }

        $user_filling_money = 0;
        $user_filling_money_r = db_execute_select("select sum(money) money from js_balance_log where userId = $user_id and type = 1 and state = 1");
        if (null != $user_filling_money_r && count($user_filling_money_r) > 0) {
            if (null != $user_filling_money_r[0]["money"] && $user_filling_money_r[0]["money"] != "") {
                $user_filling_money = $user_filling_money_r[0]["money"];
            }
        }
        $user_out_money = 0;
        $user_out_money_r = db_execute_select("select sum(money) money from js_balance_log where userId = $user_id and type = 2 and state = 1");
        if (null != $user_out_money_r && count($user_out_money_r) > 0) {
            if (null != $user_out_money_r[0]["money"] && $user_out_money_r[0]["money"] != "") {
                $user_out_money = $user_out_money_r[0]["money"];
            }
        }

        $arr['filling_money'] = $user_filling_money;
        $arr['user_out_money'] = $user_out_money;
        $arr['success'] = 1;
        echo json_encode($arr);
    }
    // 用户登录
    function login() {
        $mobilePhone = '';
        $password = '';
        $isAutomatic = 0;

        $type = $_POST['type'];
        $arr = array();
        if (empty($_POST['mobilePhone']))  {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不能为空';
            echo json_encode($arr);
            exit;
        } else if (!preg_match("/^\\s*1\\d{10}\\s*$/", $_POST['mobilePhone'])) {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不正确';
            echo json_encode($arr);
            exit;
        } else {
            $mobilePhone = stripslashes(trim($_POST['mobilePhone']));
        }
        if (empty($_POST['password']))  {
            $arr['success'] = 0;
            $arr['msg'] = '交易密码不能为空';
            echo json_encode($arr);
            exit;
        } else {
            $password = stripslashes(trim($_POST['password']));
        }

        if (!empty($_POST['isAutomatic']) && $_POST['isAutomatic'] != "")  {
            $isAutomatic = $_POST['isAutomatic'];
        }

        $md5Password = md5($password);
        if ($type == 1) {
            $md5Password = $password;
        }

        $conditions = array("a.mobilePhone"=>$mobilePhone);
        $left_join_tab=array("js_vouchers"=>array('as' => 'b', 'param'=>'user_id', ''=>'id'));
        $result = db_select('js_user', $conditions, "a.*, count(b.id) vouchers", '', array(), $left_join_tab, " and b.state=1");
        if ($result != null) {
            if ($result[0]["state"] == 0) {
                if ($result[0]['password'] == $md5Password) {
                    $user = $result[0]['id'].",".$result[0]['mobilePhone'].",".$result[0]['realName'].","
                        .substr_replace($result[0]['identity'],'****',10,4).",".$result[0]['sex'].",".$result[0]['nickName'].","
                        .$result[0]['district'].",".$result[0]['balance'].",".$result[0]['tempBalance'].",".$result[0]['bankName'].",".substr_replace($result[0]['bankNum'],'****',(strlen($result[0]['bankNum'])-4),4).
                        ",".$result[0]['photoUrl'].",".$result[0]['nickName'].",".$result[0]['vouchers'].",".$result[0]['use_vouchers_num'];
                    // setcookie("u", $user, time() + 2 * 60 * 60, "/");

                    $_SESSION["user"] = $user;

                    if ($isAutomatic == 0) {
                        setcookie("info", "$mobilePhone,$md5Password", time() + 12 * 30 * 24 * 60 * 60, "/");
                    }

                    $arr['success'] = 1;
                    // $arr['user'] = $result[0];
                    $arr['msg'] = '登录成功';
                    echo json_encode($arr);
                } else {
                    $arr['success'] = 0;
                    $arr['msg'] = '密码错误';
                    echo json_encode($arr);
                }
            } else {
                $arr['success'] = 0;
                $arr['msg'] = '帐号已被禁用';
                echo json_encode($arr);
            }
        } else {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不存在';
            echo json_encode($arr);
        }
    }
    // 用户注册
    function regist() {
        $nickName = '';
        $district = '';
        $photoUrl = '';
        $sex = 1;
        $arr = array();
        $wx_id = 0;
        if (!empty($_COOKIE['wx_id'])) {
            $wx_id = $_COOKIE['wx_id'];
            $conditions = array("id"=>$wx_id);
            $wx_result = db_select('js_wx_info', $conditions);
            $nickName = $wx_result[0]['nickname'];
            $district = $wx_result[0]['city'];
            $photoUrl = $wx_result[0]['headimgurl'];
            $sex = $wx_result[0]['sex'];
        } /*else {
            $arr['success'] = -1;
            echo json_encode($arr);
            exit;
        }*/

        $mobilePhone = '';
        $password = '';
        $pid = 0;

        if (empty($_POST['mobilePhone']))  {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不能为空';
            echo json_encode($arr);
            exit;
        } else if (!preg_match("/^\\s*1\\d{10}\\s*$/", $_POST['mobilePhone'])) {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不正确';
            echo json_encode($arr);
            exit;
        } else {
            $mobilePhone = stripslashes(trim($_POST['mobilePhone']));
        }
        if (empty($_POST['password']))  {
            $arr['success'] = 0;
            $arr['msg'] = '交易密码不能为空';
            echo json_encode($arr);
            exit;
        } else {
            $password = stripslashes(trim($_POST['password']));
        }
        if (!empty($_POST['pid']) && $_POST['pid'] != "")  {
            $pid = $_POST['pid'];
        }

        $isUseCode = db_select('js_setting', array(), "isUseCode");
        if ($isUseCode[0]["isUseCode"] == 1) {
            if (empty($_POST['invite_code']) || $_POST['invite_code'] == "")  {
                $arr['success'] = 0;
                $arr['msg'] = '请输入邀请码';
                echo json_encode($arr);
                exit;
            }
        }

        if ($_POST['invite_code'] != "0" && $_POST['invite_code'] != "" && $_POST['invite_code'] != null) {
            $conditions = array("code"=>$_POST['invite_code']);
            $result = db_select('js_user', $conditions, "id");
            if (null != $result) {
                $pid = $result[0]["id"];
            } else {
                $arr['success'] = 0;
                $arr['msg'] = '邀请码错误';
                echo json_encode($arr);
                exit;
            }
        }

        $md5Password = md5($password);
        $conditions = array("mobilePhone"=>$mobilePhone);
        $result = db_select('js_user', $conditions);
        if ($result != null) {
            $arr['success'] = 0;
                $arr['msg'] = '手机号码已存在';
                echo json_encode($arr);
        } else {
            $invite_code = generate_invite_code(6);
            $params = array("pid"=>$pid, "wx_id"=>$wx_id,"nickName"=>"$nickName","district"=>"$district","photoUrl"=>"$photoUrl","mobilePhone"=>"$mobilePhone", "password"=>"$md5Password", "sex"=>"$sex", "balance"=>"0.00", "tempBalance"=>"10000.00", "freeze_balance"=>"0.00", "rebate_balance"=>"0.00", "state"=>"0", "datetime"=>date('Y-m-d H:i'), "code"=>$invite_code, "use_vouchers_num"=>0);
            $insert_result = db_insert('js_user', $params, true);
            if ($insert_result > 0) {
                $num = generate($insert_result);

                $user = "$insert_result,".$mobilePhone.",,,1,,,0.00,8888.00,,,$photoUrl,$nickName,$num,0";
                // setcookie("u", $user, time() + 2 * 60 * 60, "/");
                $_SESSION["user"] = $user;
                $arr['success'] = 1;
                $arr['user'] = $result[0];
                $arr['msg'] = '注册成功';
                echo json_encode($arr);
            } else {
                $arr['success'] = 0;
                // $arr['user'] = $result[0];
                $arr['msg'] = '注册失败';
                echo json_encode($arr);
            }
        }
    }

    // 生成代金劵
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

    function random($len, $srcstr="1a2s3d4f5g6hj8k9qwertyupzxcvbnm") {
        mt_srand();
        $strs = "";
        for ($i = 0; $i < $len; $i++) {
            $strs .= $srcstr[mt_rand(0, (mb_strlen($srcstr) - 1))];
        }
        return $strs;
    }

    // 身份认证
    function bank_info() {
        $realName = '';
        $identity = '';
        $bankName = '';
        $bankNum = '';

        if (empty($_POST['identity']))  {
            echo '身份证号不能为空';
            exit;
        } else if (!preg_match("/^[1-9]\\d{5}[1-9]\\d{3}((0\\d)|(1[0-2]))(([0|1|2]\\d)|3[0-1])\\d{3}([0-9]|X)$/", $_POST['identity'])) {
            echo '身份证号错误';
            exit;
        } else {
            $identity = stripslashes(trim($_POST['identity']));
        }
        if (empty($_POST['realName']))  {
            echo '真实性名不能为空';
            exit;
        } else {
            $realName = stripslashes(trim($_POST['realName']));
        }
        if (empty($_POST['bankName']))  {
            echo '请选择要绑定的银行';
            exit;
        } else {
            $bankName = stripslashes(trim($_POST['bankName']));
        }
        if (empty($_POST['bankNum']))  {
            echo '银行帐号不能为空';
            exit;
        } else {
            $bankNum = stripslashes(trim($_POST['bankNum']));
        }

        $arr = array();
        $params = array("identity"=>$identity, "realName"=>$realName, "bankName"=>$bankName, "bankNum"=>$bankNum);
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $conditions = array("id"=>$u_arr[0]);
        $result = db_update('js_user', $params, $conditions);
        if ($result > 0) {
            $user = "$u_arr[0],".substr_replace($u_arr[1],'****',3,4).",$realName,".substr_replace($identity,'****',10,4).",$u_arr[4],$u_arr[5],$u_arr[6],$u_arr[7],$u_arr[8],$bankName".",".substr_replace($bankNum,'****',(strlen($bankNum)-4),4).",".$u_arr[11].",".$u_arr[12].",".$u_arr[13].",".$u_arr[14];
            // setcookie("u", $user, time() + 2 * 60 * 60, "/");
            $_SESSION["user"] = $user;
            $arr['success'] = 1;
            $arr['user'] = $result[0];
            $arr['msg'] = '认证成功';
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['user'] = $result[0];
            $arr['msg'] = '认证失败';
            echo json_encode($arr);
        }
    }
    // 用户提现
    function out_money() {
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $password = $_POST['password'];
        $money = $_POST['money'];

        $conditions = array("id"=>$u_arr[0]);
        $result = db_select('js_user', $conditions, "password, balance");
        $md5Password = md5($password);
        if ($result[0]["password"] == $md5Password) {
            if (floatval($result[0]["balance"]) >= floatval($money)) {
                $conditions = array("userId"=>$u_arr[0], "type"=>"2", "DATE_FORMAT(datetime, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')"=>"");
                $result_out_money_count = db_select('js_balance_log', $conditions, "count(id) count");
                if (!empty($result_out_money_count) && $result_out_money_count[0]["count"] < 5) {
                    $rebate_balance = 0;
                    $rebate_balance_r = db_execute_select("select rebate_balance from js_user where id = ".$u_arr[0]);
                    if (null != $rebate_balance_r && count($rebate_balance_r)>0) {
                        if (!empty($rebate_balance_r[0]["rebate_balance"]) && $rebate_balance_r[0]["rebate_balance"] != "") {
                            $rebate_balance = $rebate_balance_r[0]["rebate_balance"];
                        }
                    }
                    if ($rebate_balance > $money) {
                        $rebate_balance = $money;
                    }
                    //$up_result = db_execute("update js_user set freeze_balance=freeze_balance+$money,balance = balance-$money, rebate_balance=rebate_balance-$money where id=$u_arr[0]");
                    $up_result = db_execute("update js_user set freeze_balance=freeze_balance+$money,balance = balance-$money where id=$u_arr[0]");
                    if($up_result) {
                        $params = array("userId"=>$u_arr[0], "type"=>2, "money"=>"$money", "give_money"=>$rebate_balance, "state"=>0, "datetime"=>date('Y-m-d H:i'));
                        $insert_result = db_insert("js_balance_log", $params, true);
                        if ($insert_result > 0) {
                            $arr['success'] = 1;
                            $arr['msg'] = '提现申请已提交，通过审核后，提现资金将在10分钟内到账。<br><font color="red">提现审核时间为工作日10:00-22:00</font>';
                            echo json_encode($arr);
                        } else {
                            $arr['success'] = 2;
                            $arr['msg'] = '申请提现失败，请联系管理员';
                            echo json_encode($arr);
                        }
                    } else {
                        $arr['success'] = 2;
                        $arr['msg'] = '申请提现失败，请联系管理员';
                        echo json_encode($arr);
                    }
                } else {
                    $arr['success'] = 2;
                    $arr['msg'] = "每日提现最大次数为5次，您以超过，请明天在来";
                    echo json_encode($arr);
                }
            } else {
                $arr['success'] = 2;
                $arr['msg'] = "您目前的最大提现额度为".$result[0]["balance"]."元";
                echo json_encode($arr);
            }
        } else {
            $arr['success'] = 2;
            $arr['msg'] = "交易密码错误";
            echo json_encode($arr);
        }
    }
    // 获取用户余额度信息
    function user_balance() {
        $arr = array();
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        $conditions = array("a.id"=>$user_id);
        $user_result = db_select('js_user', $conditions, "balance, tempBalance");
        if ($user_result != null) {
            $arr['balance'] = $user_result[0]["balance"];
            $arr['tempBalance'] = $user_result[0]["tempBalance"];
        } else {
            $arr['balance'] = 0;
            $arr['tempBalance'] = 0;
        }
        echo json_encode($arr);
    }
    // 获取邀请团队的人数，团队返利的金额
    function user_team_info() {
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        $sql = "select count(a.id) u_num, (select sum(money) from js_rebate where rebate_uid = a.pid) as m_count from js_user a where a.pid =".$user_id;
        $result = db_execute_select($sql);
        $u_num = 0;$m_count=0;
        if (!empty($result) && count($result) > 0) {
            $u_num = $result[0]["u_num"];
            if (!empty($result[0]["m_count"]) && $result[0]["m_count"] != "") {
                $m_count = $result[0]["m_count"];
            }
        }
        $arr['u_num'] = $u_num;
        $arr['m_count'] = $m_count;
        echo json_encode($arr);
    }
    // 获取用户团队列表
    function user_team_list() {
        $pageNo = $_POST['page'];
        $pagesize = $_POST['pagesize'];
        $pageNo = (intval($pageNo)-1) * intval($pagesize);
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        $arr = array();
        $sql = "select insert(mobilePhone,4,4,'****') mobilePhone, realName, nickName, photoUrl, id, (select sum(gain_price) gain_price from js_order where user_id = a.id and isModel=1) gain_price,";
        $sql = $sql."(select sum(money) from js_rebate where userId = a.id) money from js_user a where pid=".$user_id." limit ".$pageNo.",". $pagesize;
        $result = db_execute_select($sql);
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
    // 获取用户团队返利列表
    function user_team_rebate_list() {
        $pageNo = $_POST['page'];
        $pagesize = $_POST['pagesize'];
        $r_uid = 0;
        if (!empty($_POST['uid']) && $_POST['uid'] != "") {
            $r_uid = $_POST['uid'];
        }
        $pageNo = (intval($pageNo)-1) * intval($pagesize);
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        $arr = array();

        $sql = "select c.money, c.datetime, b.realName, b.nickName, b.photoUrl, insert(b.mobilePhone,4,4,'****') mobilePhone, a.order_price, a.begin_time, a.play_type, a.state, a.price, a.vouchers_price from js_order a";
        $sql = $sql." left join js_user b on b.id = a.user_id left join js_rebate c on c.order_id = a.id";
        $sql = $sql." where b.pid = ".$user_id." and a.isModel=1";
        if ($r_uid > 0) {
            $sql = $sql." and a.user_id=".$r_uid;
        }
        $sql = $sql." order by a.begin_time desc limit ".$pageNo.",". $pagesize;
        $result = db_execute_select($sql);
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
    // 读取用户充值提现流水
    function bank_water_show() {
        $pageNo = $_POST['page'];
        $pagesize = $_POST['pagesize'];
        $pageNo = (intval($pageNo)-1) * intval($pagesize);
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        $arr = array();

        $sql = "select * from js_balance_log where userId=".$user_id." order by datetime desc limit ".$pageNo.",". $pagesize;
        $result = db_execute_select($sql);
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

    // 修改密码
    function change_password() {
        $arr = array();
        $u_arr = explode(",",$_SESSION["user"]); //explode(",",$_COOKIE['u']);
        $user_id = $u_arr[0];
        if (empty($_POST['password']))  {
            $arr['success'] = 0;
            $arr['msg'] = '交易密码不能为空！';
            echo json_encode($arr);
            exit;
        } else {
            $password = stripslashes(trim($_POST['password']));
        }
        if (empty($_POST['new_password']))  {
            $arr['success'] = 0;
            $arr['msg'] = '交易密码不能为空！';
            echo json_encode($arr);
            exit;
        } else {
            $new_password = stripslashes(trim($_POST['new_password']));
        }

        $md5Password = md5($password);
        $conditions = array("a.id"=>$user_id);
        $result = db_select('js_user', $conditions, "password");
        if ($result != null) {
            if ($result[0]['password'] == $md5Password) {
                db_execute("update js_user set password='".md5($new_password)."' where id=$user_id");
                $arr['success'] = 1;
                $arr['msg'] = '修改密码成功！';
                echo json_encode($arr);
            } else {
                $arr['success'] = 0;
                $arr['msg'] = '老密码不正确！';
                echo json_encode($arr);
            }
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

    function is_use_mobilePhone() {
        $mobilePhone = '';
        $arr = array();
        if (empty($_POST['mobilePhone']))  {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不能为空';
            echo json_encode($arr);
            exit;
        } else if (!preg_match("/^\\s*1\\d{10}\\s*$/", $_POST['mobilePhone'])) {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不正确';
            echo json_encode($arr);
            exit;
        } else {
            $mobilePhone = stripslashes(trim($_POST['mobilePhone']));
        }
        $conditions = array("a.mobilePhone"=>$mobilePhone);
        $result = db_select('js_user', $conditions, "count(id) count");
        if ($result != null && $result[0]["count"] > 0) {
            $arr['success'] = 1;
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不存在！';
            echo json_encode($arr);
        }
    }
    function forget_password() {
        $arr = array();
        if (empty($_POST['mobilePhone']))  {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不能为空';
            echo json_encode($arr);
            exit;
        } else if (!preg_match("/^\\s*1\\d{10}\\s*$/", $_POST['mobilePhone'])) {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不正确';
            echo json_encode($arr);
            exit;
        } else {
            $mobilePhone = stripslashes(trim($_POST['mobilePhone']));
        }

        if (empty($_POST['password']))  {
            $arr['success'] = 0;
            $arr['msg'] = '交易密码不能为空';
            echo json_encode($arr);
            exit;
        } else {
            $password = stripslashes(trim($_POST['password']));
        }

        $conditions = array("a.mobilePhone"=>$mobilePhone);
        $result = db_select('js_user', $conditions, "id");
        if ($result != null) {
            $md5Password = md5($password);
            db_execute("update js_user set password='".$md5Password."' where id=".$result[0]["id"]);
            $arr['success'] = 1;
            $arr['msg'] = '密码修改成功！';
            echo json_encode($arr);
        } else {
            $arr['success'] = 0;
            $arr['msg'] = '手机号码不存在！';
            echo json_encode($arr);
        }
    }
?>
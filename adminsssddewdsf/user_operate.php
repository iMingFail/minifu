<?php
    session_start();
    if(!isset($_SESSION["admin"])) {
        echo "<script language='javascript'>";
        echo "location.href='login.php'";
        echo " </script>";
        exit;
    }
    require_once '../db/mysql_operate.php';
    require_once '../adminsssddewdsf/tool/PageView.php';
    $tab = 1;
    if (!empty($_GET['tab']) && $_GET['tab'] != "") {
        $tab = $_GET['tab'];
    }
    if ($tab > 4) {
        $tab = 4;
    }
    if ($tab < 1) {
        $tab = 1;
    }
    $uid = 0;
    if (!empty($_GET['uid']) && $_GET['uid'] != "") {
        $uid = $_GET['uid'];
    } else {
        $tab = 1;
    }

    $pageNo = 1;
    if(!empty($_GET['pageNo'])){
        $pageNo = $_GET['pageNo'];
    }
    $pageSize = 7;
    $isModel = 1;
    if(!empty($_GET['isModel'])){
        $isModel = $_GET['isModel'];
    }

    $balance_type = 1;
    if(!empty($_GET['balance_type'])){
        $balance_type = $_GET['balance_type'];
    }
    $balance_type_str = "充值";
    if($balance_type == 2) {
        $balance_type_str = "提现";
    }

    $title = "详情";
    if (empty($uid) || $uid == "") {
        $title = "新增";
    }
    if ($uid > 0) {
        $conditions = array("id" => $uid);
        $result = db_select('js_user', $conditions);
//var_dump($result);
        $conditions = array("pid" => $uid);
        $result_c = db_select('js_user', $conditions, "id,mobilePhone,realName");

        $conditions = array("rebate_uid" => $uid);
        $my_result_rebate = db_select('js_rebate', $conditions);

        $conditions = array("rebate_uid" => $uid, "state"=>1);
        $result_rebate = db_select('js_rebate', $conditions, "sum(money) money");
        $rebate_money = "0.00";
        if (!empty($result_rebate) && !empty($result_rebate[0]["money"]) && $result_rebate[0]["money"] != "") {
            $rebate_money = sprintf("%.2f", $result_rebate[0]["money"]);
        }

        $conditions = array("b.pid" => $uid, "a.isModel" => "1");
        $result_gain_price = db_select('js_order', $conditions, "sum(a.gain_price) gain_price", "", array(), array("js_user"=>array("as"=>"b", 'param'=>"id", ''=>'user_id')));
        $gain_price = "0.00";
        if (!empty($result_gain_price) && !empty($result_gain_price[0]["gain_price"]) && $result_gain_price[0]["gain_price"] != "") {
            $gain_price = sprintf("%.2f", $result_gain_price[0]["gain_price"]);
        }

        $orderNum_today = 0;
        $conditions = array("b.id" => $uid, "a.isModel" => $isModel, "DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')"=>"");
        $params = "count(a.id) as orderNum";
        $left_join_tab = array('js_user' => array('as' => 'b', 'param' => 'id', '' => 'user_id'));
        $order_result_today = db_select('js_order', $conditions, $params, "", array(), $left_join_tab);
        if (!empty($order_result_today)) {
            if (!empty($order_result_today[0]["orderNum"]) && $order_result_today[0]["orderNum"] != "") {
                $orderNum_today = $order_result_today[0]["orderNum"];
            }
        }

        $sql = "select (((select sum(b.gain_price) from js_order b where b.isModel=$isModel and user_id=$uid and b.state = 1 and DATE_FORMAT(b.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')) - ";
        $sql = $sql." (select sum(c.order_price) from js_order c where c.isModel=$isModel and c.state = 1 and user_id=$uid and DATE_FORMAT(c.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')))-sum(a.order_price)) today_gain from js_order a";
        $sql = $sql." where a.isModel=$isModel and a.state = 2 and user_id=$uid and DATE_FORMAT(a.begin_time, '%Y-%m-%d')=DATE_FORMAT(now(), '%Y-%m-%d')";

        $today_gain_r = db_execute_select($sql);
        $priceNum_today = "0.00";
        if (!empty($today_gain_r) && !empty($today_gain_r[0]["today_gain"]) && $today_gain_r[0]["today_gain"] != "") {
            $priceNum_today = sprintf("%.2f", $today_gain_r[0]["today_gain"]);
        }


        $sql = "select (((select sum(b.gain_price) from js_order b where b.isModel=1 and user_id=$uid and b.state = 1) - ";
        $sql = $sql." (select sum(c.order_price) from js_order c where c.isModel=1 and c.state = 1 and user_id=$uid))-sum(a.order_price)) today_gain from js_order a";
        $sql = $sql." where a.isModel=1 and a.state = 2 and user_id=$uid";

        $today_gain_r = db_execute_select($sql);
        $priceNum_all = "0.00";
        if (!empty($today_gain_r) && !empty($today_gain_r[0]["today_gain"]) && $today_gain_r[0]["today_gain"] != "") {
            $priceNum_all = sprintf("%.2f", $today_gain_r[0]["today_gain"]);
        }
    }
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>迷你富后台管理平台</title>
    <meta name="description" content="这是一个 index 页面">
    <meta name="keywords" content="index">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="icon" type="image/png" href="assets/i/favicon.png">
    <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">
    <meta name="apple-mobile-web-app-title" content="Amaze UI" />
    <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>以获得更好的体验！</p>
<![endif]-->
<?php include('top.php'); ?>
<div class="am-cf admin-main">
    <?php include('left_menu.php'); ?>

    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            <div class="am-cf am-padding am-padding-bottom-0">
                <div class="am-fl am-cf">
                    <strong class="am-text-primary am-text-lg"><a href="users.php">用户</a></strong> /
                    <small><?php echo $title?></small>
                </div>
            </div>
            <hr>
            <div class="am-tabs am-margin" data-am-tabs>
                <ul class="am-tabs-nav am-nav am-nav-tabs">
                    <?php
                        if ($uid != "0") {
                            ?>
                            <li <?php if($tab == 1) { echo "class=\"am-active\""; } ?>><a href="#tab1">详细信息</a></li>
                            <li <?php if($tab == 2) { echo "class=\"am-active\""; } ?>><a href="#tab2">我的团队</a></li>
                            <li <?php if($tab == 3) { echo "class=\"am-active\""; } ?>><a href="#tab3">返利记录</a></li>
                            <li <?php if($tab == 4) { echo "class=\"am-active\""; } ?>><a href="#tab4">交易记录</a></li>
                            <li <?php if($tab == 5) { echo "class=\"am-active\""; } ?>><a href="#tab5">充值/提现</a></li>
                            <?php
                        } else {
                            ?>
                            <li class="am-active"><a href="#tab1">基本信息</a></li>
                            <?php
                        }
                    ?>
                </ul>
                <div class="am-tabs-bd">
                    <div class="am-tab-panel am-fade <?php if($tab == 1) { echo "am-active am-in"; } ?>" id="tab1">
                        <fieldset>
                            <form class="am-form user-info-form">
                            <?php
                                if($uid == 0) {
                            ?>
                                <div class="am-input-group am-input-group-primary" style="margin-bottom:10px;">
                                    <span class="am-input-group-label"><i class="am-icon-phone am-icon-fw"></i></span>
                                    <input type="mobilePhone" id="mobilePhone" onkeyup="this.value=this.value.replace(/ /g,'')" class="am-form-field" placeholder="手机号码" pattern="^\s*1\d{10}\s*$" required/>
                                </div>
                                <div class="am-input-group am-input-group-primary" style="margin-bottom:10px;">
                                    <span class="am-input-group-label"><i class="am-icon-lock am-icon-fw"></i></span>
                                    <input type="password" id="password" onkeyup="this.value=this.value.replace(/ /g,'')" class="am-form-field" placeholder="登录密码" required/>
                                </div>
                            <?php
                                }
                                if($uid > 0) {
                            ?>
                                    <div class="am-form-group">
                                        <label for="doc-vld-age-2">手机号码：</label>
                                        <input type="text" id="mobilePhone" value="<?php echo $result[0]["mobilePhone"]?>"placeholder="输入手机号码" disabled />
                                        <input type="hidden" name="uid" id="uid_txt" value="<?php echo $uid?>" />
                                    </div>
                                    <div class="am-form-group">
                                        <label for="doc-vld-age-2">登录密码(不修改时留空)：</label>
                                        <input type="text" id="password" placeholder="输入登录密码"/>
                                    </div>

                                    <div class="am-form-group">
                                        <label for="doc-vld-age-2">用户昵称：</label>
                                        <input type="text" id="nickName" value="<?php echo $result[0]["nickName"]?>" placeholder="输入昵称"/>
                                    </div>
                                    <div class="am-form-group">
                                        <label for="doc-vld-url-2">真实姓名：</label>
                                        <input type="text" id="realName" value="<?php echo $result[0]["realName"]?>" placeholder="输入真实姓名"/>
                                    </div>
                                    <div class="am-form-group">
                                        <label for="doc-vld-age-2">身份证号：</label>
                                        <input type="text" id="identity" value="<?php echo $result[0]["identity"]?>" placeholder="输入身份证号"/>
                                    </div>
                                    <div class="am-form-group">
                                        <label>用户性别： </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="sex" value="1" data-am-ucheck <?php if($result[0]["sex"]==1) echo "checked"?> /> 男
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="sex" value="2" data-am-ucheck <?php if($result[0]["sex"]==0) echo "checked"?> /> 女
                                        </label>
                                    </div>
									<div class="am-form-group">
                                        <label>代理商后台权限： </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="agent_power" value="1" data-am-ucheck <?php if($result[0]["agent_power"]==1) echo "checked"?> /> 允许
                                        </label>
                                        <label class="am-radio-inline">
                                            <input type="radio" name="agent_power" value="2" data-am-ucheck <?php if($result[0]["agent_power"]==0) echo "checked"?> /> 禁止
                                        </label>
                                    </div>
                                    <div class="am-form-group">
                                        <label for="doc-vld-age-2">余额：</label>
                                        <input type="text" value="<?php echo $result[0]["balance"]?>" disabled/>
                                    </div>
                                    <div class="am-form-group">
                                        <label for="doc-vld-age-2">模拟资金：</label>
                                        <input type="text" value="<?php echo $result[0]["tempBalance"]?>" disabled/>
                                    </div>
                                    <div class="am-form-group">
                                        <label for="doc-vld-age-2">绑定银行：</label>
                                        <input type="text" value="<?php echo $result[0]["bankName"]?>" disabled/>
                                    </div>
                                    <div class="am-form-group">
                                        <label for="doc-vld-age-2">银行帐号：</label>
                                        <input type="text" value="<?php echo $result[0]["bankNum"]?>" disabled/>
                                    </div>
                                <?php
                                }
                                ?>
                            </form>
                            <div class="am-margin"><button class="am-btn am-btn-secondary confirmBtn" type="button">保存</button></div>
                        </fieldset>
                    </div>
                    <div class="am-tab-panel am-fade <?php if($tab == 2) { echo "am-active am-in"; } ?>" id="tab2">
                        <?php
                        if(empty($result_c)) {
                            ?>
                            该用户还没有团队
                            <?php
                        } else {
                        ?>
                            <div class="am-panel am-panel-default">
                                <div class="am-panel-bd">
                                    团队人数：<b><?php echo count($result_c)?></b>人，
                                    团队返利：<b><?php echo $rebate_money ?></b>元，
                                    团队盈亏：<b><?php echo $gain_price ?></b>元
                                </div>
                            </div>

                            <table class="am-table am-table-striped am-table-hover table-main">
                                <thead><tr><th>用户</th><th>盈利</th><th>返利</th><th>操作</th></tr></thead>
                                <tbody>
                                <?php
                                for($i=0; $i<count($result_c); $i++) {
                                    $conditions = array("userId" => $result_c[$i]["id"], "rebate_uid" => $uid, "state" => 1);
                                    $result_c_rebate = db_select('js_rebate', $conditions, "sum(money) money");
                                    // sprintf("%.2f", $result[0]["gain_price"])
                                    $money = "0.00";
                                    if (!empty($result_c_rebate) && !empty($result_c_rebate[0]["money"]) && $result_c_rebate[0]["money"] != "") {
                                        $money = sprintf("%.2f", $result_c_rebate[0]["money"]);
                                    }

                                    $realName = "未认证用户";
                                    if (!empty($result_c[$i]["realName"]) && $result_c[$i]["realName"] != "") {
                                        $realName = $result_c[$i]["realName"];
                                    }

                                    $result_order = db_select("js_order", array("user_id"=>$result_c[$i]["id"], "a.isModel" => "1"), "sum(gain_price) gain_price");
                                    $gain_price = "0";
                                    if(!empty($result_order) && !empty($result_order[0]["gain_price"]) && $result_order[0]["gain_price"] != "") {
                                        $gain_price = $result_order[0]["gain_price"];
                                        if(intval($result_order[0]["gain_price"]) > 0) {
                                            $gain_price = $gain_price;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $realName." - ".$result_c[$i]["mobilePhone"]?></td>
                                        <td><?php echo $gain_price ?>元</td>
                                        <td><?php echo $money?>元</td>
                                        <td>
                                            <a href="user_operate.php?uid=<?php echo $result_c[$i]["id"]?>" style="background-color:#ffffff;" class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-info"></span> 详情</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="am-tab-panel am-fade <?php if($tab == 3) { echo "am-active am-in"; } ?>" id="tab3">
                        <?php
                        if(empty($result_c)) {
                            echo "该用户还没有团队";
                        } else {
                            ?>
                            <div class="am-panel am-panel-default">
                                <div class="am-panel-bd">
                                    团队人数：<b><?php echo count($result_c)?></b>人，
                                    团队返利：<b><?php echo $rebate_money ?></b>元，
                                    团队盈亏：<b><?php echo $gain_price ?></b>元
                                </div>
                            </div>

                            <table class="am-table am-table-striped am-table-hover table-main">
                                <thead><tr><th>用户</th><th>返利</th><th>状态</th><th>时间</th><th>操作</th></tr></thead>
                                <tbody>
                                <?php
                                for($i=0; $i<count($my_result_rebate); $i++) {
                                    $money = "0.00";
                                    if (!empty($my_result_rebate) && !empty($my_result_rebate[$i]["money"]) && $my_result_rebate[$i]["money"] != "") {
                                        $money = sprintf("%.2f", $my_result_rebate[$i]["money"]);
                                    }
                                    $state = "已到帐";
                                    if ($my_result_rebate[$i]["state"] == 2) {
                                        $state = "未到帐";
                                    }
                                    $user_c = db_execute_select("select mobilePhone, realName from js_user where id=".$my_result_rebate[$i]["userId"]);
                                    $realName = "未认证用户";
                                    if (!empty($user_c[0]["realName"]) && $user_c[0]["realName"] != "") {
                                        $realName = $user_c[0]["realName"];
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $realName." - ".$user_c[0]["mobilePhone"]?></td>
                                        <td><?php echo $money?>元</td>
                                        <td><?php echo $state ?></td>
                                        <td><?php echo $my_result_rebate[$i]["datetime"] ?></td>
                                        <td>
                                            <a href="user_operate.php?uid=<?php echo $my_result_rebate[$i]["userId"]?>" style="background-color:#ffffff;" class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-info"></span> 详情</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="am-tab-panel am-fade <?php if($tab == 4) { echo "am-active am-in"; } ?>" id="tab4">
                        <div class="am-panel am-panel-default">
                            <div class="am-panel-bd">
                                    今日交易<?php echo $orderNum_today?>单，
                                    今日盈亏<?php echo $priceNum_today?>元，
                                    历史盈亏<?php echo $priceNum_all?>元
                                    <div style="float:right;">
                                        <?php
                                    if($isModel == 1) {
                                        ?>
                                        <a href="user_operate.php?isModel=2&tab=4&uid=<?php echo $uid?>" class="am-btn am-btn-default am-btn-xs">切换模拟盘</a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="user_operate.php?isModel=1&tab=4&uid=<?php echo $uid?>" class="am-btn am-btn-default am-btn-xs">切换实盘</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead><tr><th>资产类型</th><th>涨/跌</th><th>买入金额</th><th>盈利情况</th><th>交易状态</th><th>交易时间</th><th>操作</th></tr></thead>
                            <tbody>
                            <?php
                            $conditions = array("isModel"=>$isModel, "user_id"=>$uid);
                            $params ="c.name,a.id,a.type,a.order_price, a.gain_price,a.state, a.begin_time";
                            $limit = "limit ".($pageNo-1)*$pageSize.", $pageSize";
                            $left_join_tab = array(
                                'js_type'=>array('as'=>'c', 'param'=>'id', ''=>'play_type')
                            );
                            $result = db_select("js_order", $conditions, $params, $limit, array("a.begin_time"=>"desc"), $left_join_tab);
                            if ($result != null && count($result) > 0) {
                                for ($i = 0; $i < count($result); $i++) {
                                    ?>
                                    <tr>
                                        <td><?php echo $result[$i]["name"]?></td>
                                        <td><?php if($result[$i]["type"] == "1") {echo "买涨";} else {echo "买跌";}?></td>
                                        <td><?php echo sprintf("%.2f", $result[$i]["order_price"])?></td>
                                        <td><?php
                                            if($result[$i]["state"] == 0){
                                                echo "--";
                                            } elseif($result[$i]["state"] == 1){
                                                echo "<font color='red'>+".sprintf("%.2f", $result[$i]["gain_price"])."</font>";
                                            } elseif($result[$i]["state"] == 2){
                                                echo "<font color='green'>".sprintf("%.2f", $result[$i]["gain_price"])."</font>";
                                            } elseif($result[$i]["state"] == 3){
                                                echo "<font color='gray'>".sprintf("%.2f", $result[$i]["gain_price"])."</font>";
                                            }
                                            ?>
                                        </td>
                                        <td><?php
                                            if($result[$i]["state"] == 0){
                                                echo "未开奖";
                                            } elseif($result[$i]["state"] == 1){
                                                echo "<font color='red'>盈利</font>";
                                            } elseif($result[$i]["state"] == 2){
                                                echo "<font color='green'>亏损</font>";
                                            } elseif($result[$i]["state"] == 3){
                                                echo "<font color='gray'>平局</font>";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i:s',strtotime($result[$i]["begin_time"]))?></td>
                                        <td>
                                            <div class="am-btn-toolbar">
                                                <div class="am-btn-group am-btn-group-xs">
                                                    <a href="order_info.php?id=<?php echo $result[$i]["id"]?>" style="background-color:#ffffff;" class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-info"></span> 详情</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <div class="am-cf">
                            <?php
                            $result_count = db_select("js_order", $conditions, "count(id) total");
                            $total = intval($result_count[0]["total"]);
                            if ($pageNo > 1) {
                                ?>
                                共 <?php echo $total?> 条记录
                            <?php }?>
                            <div class="am-btn-toolbar am-fr">
                                <div class="am-btn-group am-btn-group-sm">
                                    <?php
                                    //创建分页器
                                    $p = new PageView($total,$pageSize,$pageNo);
                                    //生成页码
                                    $pageViewString = $p->echoPageAsDiv("user_operate.php?isModel=$isModel&tab=4&uid=$uid", "&");
                                    echo $pageViewString;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="am-tab-panel am-fade <?php if($tab == 5) { echo "am-active am-in"; } ?>" id="tab5">

                        <div class="am-panel am-panel-default">
                            <div class="am-panel-bd">
                                &nbsp;
                                <div style="float:right;">
                                    <?php
                                    if($balance_type == 1) {
                                        ?>
                                        <a href="user_operate.php?balance_type=2&tab=5&uid=<?php echo $uid?>" class="am-btn am-btn-default am-btn-xs">查看提现</a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="user_operate.php?balance_type=1&tab=5&uid=<?php echo $uid?>" class="am-btn am-btn-default am-btn-xs">查看充值</a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <table class="am-table am-table-striped am-table-hover table-main">
                            <thead><tr><th>类别</th><th>金额</th><th>状态</th><th><?php echo $balance_type_str?>时间</th></tr></thead>
                            <tbody>
                                <?php
                                    $result_balance_log = db_select("js_balance_log", array("userId"=>$uid, "type"=>$balance_type), "money, state, datetime");
                                    if(!empty($result_balance_log) && count($result_balance_log) > 0) {
                                        for ($i=0;$i<count($result_balance_log);$i++) {
                                            $state_str = "未到账";
                                            if ($balance_type == 2) {
                                                $state_str = "已申请";
                                            }
                                            if ($result_balance_log[$i]["state"] == 1) {
                                                $state_str = "充值成功";
                                                if ($balance_type == 2) {
                                                    $state_str = "提现成功";
                                                }
                                            } else if ($result_balance_log[$i]["state"] == 2) {
                                                $state_str = "资金冻结";
                                                if ($balance_type == 2) {
                                                    $state_str = "提现未通过";
                                                }
                                            } else if ($result_balance_log[$i]["state"] == -1) {
                                                $state_str = $balance_type_str."失败";
                                            }

                                            ?>
                                            <tr>
                                                <td><?php echo $balance_type_str?></td>
                                                <td><?php echo sprintf("%.2f", $result_balance_log[$i]["money"]);?></td>
                                                <td><?php echo $state_str?></td>
                                                <td><?php echo $result_balance_log[$i]["datetime"]?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <footer class="admin-content-footer">
            <hr>
            <p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
        </footer>
    </div>
    <!-- content end -->

    <div class="am-modal am-modal-loading am-modal-no-btn" tabindex="1" id="loading">
        <div class="am-modal-dialog">
            <div class="am-modal-hd" id="loading-msg"></div>
            <div class="am-modal-bd">
                <span class="am-icon-spinner am-icon-spin"></span>
            </div>
        </div>
    </div>

    <div class="am-modal am-modal-alert" tabindex="-1" id="alert">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">消息提示</div>
            <div class="am-modal-bd" id="alert_msg"></div>
            <div class="am-modal-footer">
                <span class="am-modal-btn">确定</span>
            </div>
        </div>
    </div>

</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"></a>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="js/jquery-1.8.3.min.js"></script>
<!--<![endif]-->
<script src="assets/js/amazeui.min.js"></script>
<script src="assets/js/app.js"></script>
<script>
    var action = "edit";
    $(function(){
        $(".confirmBtn").click(function(){
            if ($("#uid_txt").val() == "0" || $("#uid_txt").val() == "" || $("#uid_txt").val() === undefined) {
                action = "add"
            } else {
                action = "edit"
            }
            $(".user-info-form").submit();
        });
        $('#alert').on('closed.modal.amui', function() {
            if (action == "add") {
                window.location.href = "users.php"
            }
        });
        $(".user-info-form").validator({
            patterns:{tel:/^\s*1\d{10}\s*$/, id_number:/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/},
            inValidClass:"js-field-error",
            submit:function(){
                if (this.isFormValid()) {
                    var data = {};
                    switch(action) {
                        case "add":
                            data = {"mobilePhone":$("#mobilePhone").val().trim(), "password":$("#password").val().trim()};
                            break;
                        case "edit":
                            var password = $("#password").val().trim();
                            var identity = $("#identity").val().trim();
                            var realName = $("#realName").val().trim();
                            var nickName = $("#nickName").val().trim();
                            var sex = $("input[name='sex']:checked").val();
							var agent_power = $("input[name='agent_power']:checked").val();
                            var uid = $("#uid_txt").val();
                            data = {"identity":identity, "realName":realName, "nickName":nickName, "sex":sex, "uid":uid, "password":password,"agent_power":agent_power};
                            break;
                    }
                    $.ajax({
                        type:"POST",
                        url:"api/api.php?t=u&action="+action,
                        dataType:"json",
                        data:data,
                        beforeSend:function(){
                            loading("正在保存数据...");
                        },
                        success:function(json){
                            if (json.success == 1) {
                                loading_hide();
                                $("#alert_msg").html(json.msg);
                                $("#alert").modal("open");
                            } else {
                                loading(json.msg);
                                setTimeout(loading_hide, 2000);
                            }
                        }
                    });
                }
                return false;
            }
        });

    });

    function loading(msg) {
        $("#loading-msg").html(msg);
        $("#loading").modal("open");
    }
    function loading_hide(){
        $("#loading-msg").html("");
        $("#loading").modal("close");
    }
</script>
</body>
</html>

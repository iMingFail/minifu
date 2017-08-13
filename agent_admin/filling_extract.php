<?php
    session_start();
    if(!isset($_SESSION["agent_admin"])) {
        echo "<script language='javascript'>";
        echo "location.href='login.php'";
        echo " </script>";
        exit;
    }
   //var_dump($_SESSION["agent_admin"]);
    require_once '../db/mysql_operate.php';
    require_once '../adminsssddewdsf/tool/PageView.php';
    $pageNo = 0;
    if(empty($_GET['pageNo']) || $_GET['pageNo'] == ""){
        $pageNo = 1;
    } else {
        $pageNo = $_GET['pageNo'];
    }
    $pageSize = 7;
    $type = 2;
    if(!empty($_GET['type'])){
        $type = $_GET['type'];
    }
    $type_str = "提现";
    if ($type == 1) {
        $type_str = "充值";
    }
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>迷你富代理商后台管理平台</title>
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
                    <strong class="am-text-primary am-text-lg">充值-提现记录</strong> / <small>列表(<?php echo $type_str ?>)</small>
                </div>
                <div style="float:right;">
                    <?php
                        if($type == 1) {
                            ?>
                            <a href="filling_extract.php?type=2" class="am-btn am-btn-default am-btn-xs">切换提现列表</a>
                            <?php
                        } else {
                            ?>
                            <a href="filling_extract.php?type=1" class="am-btn am-btn-default am-btn-xs">切换充值列表</a>
                            <?php
                        }
                    ?>
                </div>
            </div>
            <hr>
            <div class="am-g" style="display:none;">
                <div class="am-u-sm-12 am-u-md-3">
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" class="am-form-field">
                        <span class="am-input-group-btn">
                            <button class="am-btn am-btn-default" type="button">搜索</button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="am-g">
                <div class="am-u-sm-12">
                    <table class="am-table am-table-striped am-table-hover table-main" id="doc-modal-list">
                        <thead><tr><th>类别</th><th>用户</th><th>金额</th><th>状态</th><th><?php echo $type_str?>时间</th></tr></thead>
                        <tbody>
                        <?php
                        $conditions = array("a.type"=>$type, "a.state != 0"=>"" , "a.userId" =>$_SESSION["agent_admin"]["id"]);
                        $params = "a.money, a.state, a.datetime, b.mobilePhone,b.realName";
                        $limit = "limit ".($pageNo-1)*$pageSize.", $pageSize";
                        $left_join_tab = array(
                            'js_user'=>array('as'=>'b', 'param'=>'id', ''=>'userId')
                        );
                        $result = db_select("js_balance_log", $conditions, $params, $limit, array("a.datetime"=>"desc"), $left_join_tab);
                        //echo $result;
                        if(!empty($result) && count($result) > 0) {
                            for ($i=0;$i<count($result);$i++) {
                                $state_str = "未到账";
                                if ($type == 2) {
                                    $state_str = "已申请";
                                }
                                if ($result[$i]["state"] == 1) {
                                    $state_str = "充值成功";
                                    if ($type == 2) {
                                        $state_str = "提现成功";
                                    }
                                } else if ($result[$i]["state"] == 2) {
                                    $state_str = "资金冻结";
                                    if ($type == 2) {
                                        $state_str = "提现未通过";
                                    }
                                } else if ($result[$i]["state"] == -1) {
                                    $state_str = $type_str."失败";
                                }
                                ?>
                                <tr>
                                    <td><?php echo $type_str?></td>
                                    <td><?php echo $result[$i]["realName"]?>&nbsp;-&nbsp;<span style="font-size:11px;color:gray;"><?php echo $result[$i]["mobilePhone"]?></span></td>
                                    <td><?php echo sprintf("%.2f", $result[$i]["money"])?></td>
                                    <td><?php echo $state_str?></td>
                                    <td><?php echo $result[$i]["datetime"]?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                    <div class="am-cf">
                        <?php
                        $result_count = db_select("js_balance_log", $conditions, "count(id) total");
                        $total = intval($result_count[0]["total"]);
                        if ($pageNo > 1) {
                            ?>
                            共 <?php echo $total?> 条记录
                        <?php } ?>
                        <div class="am-btn-toolbar am-fr">
                            <div class="am-btn-group am-btn-group-sm">
                                <?php
                                //创建分页器
                                $p = new PageView($total,$pageSize,$pageNo);
                                //生成页码
                                $pageViewString = $p->echoPageAsDiv("filling_extract.php?type=$type", "&");
                                echo $pageViewString;
                                ?>
                            </div>
                        </div>
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
    var is_refresh = false;
    function loading(msg) {
        $("#loading-msg").html(msg);
        $("#loading").modal("open");
    }
    function loading_hide(){
        $("#loading-msg").html("");
        $("#loading").modal("close");
    }
    $(function() {});
</script>
</body>
</html>

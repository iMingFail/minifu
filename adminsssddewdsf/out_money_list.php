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
    $pageNo = 0;
    if(empty($_GET['pageNo']) || $_GET['pageNo'] == ""){
        $pageNo = 1;
    } else {
        $pageNo = $_GET['pageNo'];
    }
    $pageSize = 7;
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
<?php  include('top.php'); ?>
<div class="am-cf admin-main">
    <?php  include('left_menu.php'); ?>
    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            <div class="am-cf am-padding am-padding-bottom-0">
                <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">提现申请</strong> / <small>列表</small></div>
            </div>
            <hr>
            <div class="am-g">
                <div class="am-u-sm-12 am-u-md-3" style="display:none;">
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
                    <form class="am-form">
                        <table class="am-table am-table-striped am-table-hover table-main" id="doc-modal-list">
                            <thead>
                            <tr>
                                <th>ID</th><th>提现用户</th><th>余额</th><th>提现金额</th><th>申请日期</th><th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $conditions = array("a.state"=>"0","a.type"=>"2");
                                $params = "a.*, b.mobilePhone,b.realName,b.balance,b.bankName,b.bankNum";
                                $limit = "limit ".($pageNo-1)*$pageSize.", $pageSize";
                                $left_join_tab = array("js_user"=>array("as"=>"b", "param"=>"id", ""=>"userId"));
                                $result = db_select("js_balance_log", $conditions, $params, $limit, array("b.datetime"=>"desc"), $left_join_tab);
                                if ($result != null && count($result) > 0) {
                                    for ($i = 0; $i < count($result); $i++) {
                                        ?>
                                        <tr id="tr_m_<?php echo $result[$i]["id"]?>" data-id="<?php echo $result[$i]["id"]?>">
                                            <td><?php echo $result[$i]["id"]?></td>
                                            <td>
                                                <?php echo $result[$i]["realName"]?>&nbsp;<span style="font-size:11px;">(<?php echo $result[$i]["mobilePhone"] ?>)</span><br>
                                                开户银行：<?php echo $result[$i]["bankName"]?><br>
                                                银行帐号：<?php echo $result[$i]["bankNum"]?>
                                            </td>
                                            <td><?php echo sprintf("%.2f", $result[$i]["balance"])?></td>
                                            <td><?php echo sprintf("%.2f", $result[$i]["money"]); ?></td>
                                            <td><?php echo $result[$i]["datetime"]?></td>
                                            <td>
                                                <div class="am-btn-toolbar">
                                                    <div class="am-btn-group am-btn-group-xs">
                                                        <a href="javascript:void(0)" onclick="pass_out_money(<?php echo $result[$i]["id"]?>, 1)" style="background-color:#ffffff;" class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-check"></span> 通过</a>
                                                        <a href="javascript:void(0)" onclick="pass_out_money(<?php echo $result[$i]["id"]?>, 2)" style="background-color:#ffffff;" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only delete-user"><span class="am-icon-close"></span> 不通过</a>
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
                            $result_count = db_select("js_balance_log", $conditions, "count(id) total", $limit, array(), $left_join_tab);
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
                                   $pageViewString = $p->echoPageAsDiv("users.php");
                                   echo $pageViewString;
                                   ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="admin-content-footer">
            <hr>
            <p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
        </footer>

    </div>
    <!-- content end -->

    <div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">消息提示</div>
            <div class="am-modal-bd" id="apply_msg">你，确定要通过该账户的提现申请，并已打款到该银行账户了吗？</div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>确定</span>
            </div>
        </div>
    </div>

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
    function loading(msg) {
        $("#loading-msg").html(msg);
        $("#loading").modal("open");
    }
    function loading_hide(){
        $("#loading-msg").html("");
        $("#loading").modal("close");
    }
    var state_s = 0;
    function pass_out_money(id,state) {
        state_s = state;
        if (state == 1) {
            $("#apply_msg").html("你，确定要通过该账户的提现申请，并已打款到该银行账户了吗？");
        } else {
            $("#apply_msg").html("你，确定要取消该账户的提现申请，并将用户冻结资金还入用户余额里吗？");
        }
        $('#my-confirm').modal({
            relatedTarget: this,
            onConfirm: function() {
                $.ajax({
                    type:"POST",
                    url:"api/api.php?t=u&action=pass_m_out",
                    dataType:"json",
                    data:{id:id, state:state_s},
                    beforeSend:function(){
                        loading("正在操作...");
                    },
                    success:function(json){
                        loading_hide();
                        $("#tr_m_"+id).remove();
                    }
                });
            },
        });
    }
    $(function() {});
</script>
</body>
</html>

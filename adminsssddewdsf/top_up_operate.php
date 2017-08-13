<?php
    session_start();
    if(!isset($_SESSION["admin"])) {
        echo "<script language='javascript'>";
        echo "location.href='login.php'";
        echo " </script>";
        exit;
    }
    require_once '../db/mysql_operate.php';
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
                    <strong class="am-text-primary am-text-lg"><a href="rebate_setting.php">充值卡设置</a></strong>
                </div>
            </div>
            <hr>
            <div class="am-tabs am-margin">
                <ul class="am-tabs-nav am-nav am-nav-tabs">
                <li class="am-active"><a href="#tab1">生成充值卡</a></li>
            </ul>

            <div class="am-tabs-bd">
                <div class="am-tab-panel am-fade am-active am-in" id="tab1">
                    <fieldset>
                        <form class="am-form user-info-form">
                            <div class="am-form-group">
                                <label for="doc-vld-age-2">金额：</label>
                                <input type="text" id="money" placeholder="输入金额"/>
                            </div>
                            <div class="am-form-group">
                                <label for="doc-vld-age-2">生成数量：</label>
                                <input type="text" id="num"  placeholder="输入生成数量"/>
                            </div>
                        </form>
                        <div class="am-margin"><button class="am-btn am-btn-secondary confirmBtn" type="button">生成</button></div>
                    </fieldset>
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
    var action = "generate";
    $(function(){
        $('#alert').on('closed.modal.amui', function() {
            if (action == "generate") {
                window.location.href = "top_up.php"
            }
        });
        $(".confirmBtn").click(function(){
            $(".user-info-form").submit();
        });
        $(".user-info-form").validator({
            submit:function(){
                if (this.isFormValid()) {
                    $.ajax({
                        type:"POST",
                        url:"api/api.php?t=t&action="+action,
                        dataType:"json",
                        data:{"money":$("#money").val(), "num":$("#num").val()},
                        beforeSend:function(){
                            loading("正在生成充值卡...");
                        },
                        success:function(json){
                            loading_hide();
                            if (json.success == 1) {
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

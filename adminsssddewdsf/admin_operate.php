<?php
    session_start();
    if(!isset($_SESSION["admin"])) {
        echo "<script language='javascript'>";
        echo "location.href='login.php'";
        echo " </script>";
        exit;
    }
    require_once '../db/mysql_operate.php';
    $id = 0;
    if (!empty($_GET['id']) && $_GET['id'] != "") {
        $id = $_GET['id'];
    }
    $name = "";
    $loginName= "";
    if ($id != 0) {
        $conditions = array("id" => $id);
        $result = db_select('js_admin', $conditions);
        $name = $result[0]["name"];
        $loginName = $result[0]["loginName"];
    }
    $action = "add";
    if (!empty($_GET['action']) && $_GET['action'] != "") {
        $action = $_GET['action'];
    }
?>
<!doctype html>
<html class="no-js fixed-layout">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>迷你富后台管理平台</title>
    <meta name="description" content="迷你富">
    <meta name="keywords" content="迷你富">
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
                    <strong class="am-text-primary am-text-lg"><a href="rebate_setting.php">管理员设置</a></strong>
                </div>
            </div>
            <hr>
            <div class="am-tabs am-margin">
                <ul class="am-tabs-nav am-nav am-nav-tabs">
                    <li class="am-active"><a href="#tab1">基本信息</a></li>
                </ul>

                <div class="am-tabs-bd">
                    <div class="am-tab-panel am-fade am-active am-in" id="tab1">
                        <fieldset>
                            <form class="am-form user-info-form">
                                <div class="am-form-group">
                                    <label for="doc-vld-age-2">登录帐号：</label>
                                    <input type="text" id="loginName" value="<?php echo $loginName?>" placeholder="输入登录帐号" required/>
                                    <input type="hidden" id="id_txt" value="<?php echo $id?>" />
                                </div>
                                <div class="am-form-group">
                                    <label for="doc-vld-age-2">登录密码：</label>
                                    <input type="password" id="password"  placeholder="输入登录密码"/>
                                </div>
                                <div class="am-form-group">
                                    <label for="doc-vld-url-2">管理员名称：</label>
                                    <input type="text" id="name" value="<?php echo $name?>" placeholder="输入管理员" required/>
                                </div>
                            </form>
                            <div class="am-margin"><button class="am-btn am-btn-secondary confirmBtn" type="button">保存</button></div>
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
    var action = "<?php echo $action?>";
    $(function(){
        $('#alert').on('closed.modal.amui', function() {
            if (action == "add") {
                window.location.href = "admins.php"
            }
        });
        $(".confirmBtn").click(function(){
            $(".user-info-form").submit();
        });
        $(".user-info-form").validator({
            submit:function(){
                if (this.isFormValid()) {
                    var data = {};
                    switch(action) {
                        case "add":
                            data = {"loginName":$("#loginName").val().trim(), "password":$("#password").val().trim(), "name":$("#name").val().trim()};
                            break;
                        case "edit":
                            var name = $("#name").val().trim();
                            var loginName = $("#loginName").val().trim();
                            var password = $("#password").val().trim();
                            var id = $("#id_txt").val();
                            data = {"loginName":loginName, "password":password, "name":name, "id":id};
                            break;
                    }
                    $.ajax({
                        type:"POST",
                        url:"api/api.php?t=m&action="+action,
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

<?php
    session_start();
    if(!isset($_SESSION["agent_admin"])) {
        echo "<script language='javascript'>";
        echo "location.href='login.php'";
        echo " </script>";
        exit;
    }
    require_once '../db/mysql_operate.php';

    $result = db_select('js_setting', array());

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
    <style>
        ul, ol, li {
            list-style: outside none none;
            margin:0px;
            padding:0px;
        }
    </style>
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
                    <strong class="am-text-primary am-text-lg"><a href="setting.php">系统设置</a></strong> / 基本设置
                </div>
            </div>
            <hr>
            <div class="am-tabs am-margin">
                <ul class="am-tabs-nav am-nav am-nav-tabs">
                    <li class="am-active"><a href="#tab2">详细信息</a></li>
                </ul>

                <div class="am-tabs-bd">
                    <div class="am-tab-panel am-fade am-active am-in">
                        <fieldset>
                            <form class="am-form user-info-form">
                                <div class="am-form-group">
                                    <label for="doc-vld-age-2">个人下单中奖率：(某秒有一个用户下单的情况下获胜机率，0-10数值越大中奖率越高)</label>
                                    <input type="number" max="10" min="0" id="pre_odds" value="<?php echo $result[0]["pre_odds"]?>" required/>
                                </div>
                                <div class="am-form-group">
                                    <label for="doc-vld-url-2">多人下单中奖率：(某秒有多个用户下单的情况下获胜机率，0-10数值越大中奖率越高)</label>
                                    <input type="number" max="10" min="0" id="group_odds" value="<?php echo $result[0]["group_odds"]?>" required/>
                                </div>
                                <div class="am-form-group" style="overflow:hidden;display:none;">
                                    <label for="doc-vld-age-2">模拟盘下单盈利机率：</label>
                                    <input type="number" id="moni_odds" value="<?php echo $result[0]["moni_odds"]?>" required/>
                                </div>
                                <div class="am-form-group">
                                    <label for="doc-vld-age-2">注册是否强制使用邀请码：</label>
                                    <select id="isUseCode">
                                        <option value="1" <?php if ($result[0]["isUseCode"] == 1) echo "selected"; ?>>是</option>
                                        <option value="2" <?php if ($result[0]["isUseCode"] == 2) echo "selected"; ?>>否</option>
                                    </select>
                                   <!-- <input type="number" id="use_vouchers_money" value="<?php /*echo $result[0]["use_vouchers_money"]*/?>" required/>-->
                                </div>
                                <div class="am-form-group">
                                    <label for="doc-vld-age-2">注册赠送代金劵数量：</label>
                                    <input type="number" id="vouchers_num" value="<?php echo $result[0]["vouchers_num"]?>" required/>
                                </div>

                                <div class="am-form-group">
                                    <label for="doc-vld-age-2">注册赠送代金劵张数(每张)：</label>
                                    <input type="number" id="vouchers_money" value="<?php echo $result[0]["vouchers_money"]?>" required/>
                                </div>

                                <div class="am-form-group">
                                    <label for="doc-vld-age-2">使用代金劵限制金额：</label>
                                    <input type="number" id="use_vouchers_money" value="<?php echo $result[0]["use_vouchers_money"]?>" required/>
                                </div>
                                <div class="am-form-group">
                                    <label for="doc-vld-age-2">注册需发送的短息内容：</label>
                                    <textarea style="height:200px;" id="reg_count_sms" required><?php echo $result[0]["reg_count_sms"]?></textarea>
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

        <div class="am-modal am-modal-loading am-modal-no-btn" tabindex="1" id="loading">
            <div class="am-modal-dialog">
                <div class="am-modal-hd" id="loading-msg"></div>
                <div class="am-modal-bd">
                    <span class="am-icon-spinner am-icon-spin"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- content end -->
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
    $(function(){
        $(".confirmBtn").click(function(){
            $(".user-info-form").submit();
        });
        $(".user-info-form").validator({
            submit:function(){
                if (this.isFormValid()) {
                    var pre_odds = $("#pre_odds").val();
                    var group_odds = $("#group_odds").val();
                    var moni_odds = $("#moni_odds").val();
                    var reg_count_sms = $("#reg_count_sms").val();
                    var vouchers_num = $("#vouchers_num").val();
                    var vouchers_money = $("#vouchers_money").val();
                    var use_vouchers_money = $("#use_vouchers_money").val();
                    var isUseCode = $("#isUseCode").val();
                    data = {"pre_odds":pre_odds, "group_odds":group_odds, "moni_odds":moni_odds, "reg_count_sms":reg_count_sms, "vouchers_num":vouchers_num, "vouchers_money":vouchers_money, "use_vouchers_money":use_vouchers_money, "isUseCode":isUseCode};
                    $.ajax({
                        type:"POST",
                        url:"api/api.php?t=s&action=edit",
                        dataType:"json",
                        data:data,
                        beforeSend:function(){
                            loading("正在保存数据...");
                        },
                        success:function(json){
                            loading(json.msg);
                            setTimeout(loading_hide, 2000);
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

<?php
    session_start();
    if(!isset($_SESSION["agent_admin"])) {
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
    <title>华商新所代理商后台管理平台</title>
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

        .btn{position: relative;overflow: hidden;margin-right: 4px;display:inline-block;*display:inline;padding:4px 10px 4px;font-size:14px;line-height:20px;*line-height:22px;color:#fff;text-align:center;vertical-align:middle;cursor:pointer;background-color:#0e90d2;border:1px solid #cccccc;border-color:#e6e6e6 #e6e6e6 #bfbfbf;border-bottom-color:#b3b3b3;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}
        .btn input {position: absolute;top: 0; right: 0;margin: 0;border: solid transparent;opacity: 0;filter:alpha(opacity=0); cursor: pointer;}
        .progress { position:relative; margin-left:100px; margin-top:-24px; width:200px;padding: 1px; border-radius:3px; display:none}
        .files{height:22px; line-height:22px; margin:10px 0}
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
                    <strong class="am-text-primary am-text-lg"><a href="upload_operate.php">系统设置</a></strong> / 图片上传
                </div>
            </div>
            <hr>
            <div class="am-tabs am-margin" data-am-tabs>
                <ul class="am-tabs-nav am-nav am-nav-tabs">
                    <li class="am-active"><a href="#tab1">升级VIP</a></li>
                    <li><a href="#tab2">新手学堂</a></li>
                </ul>

                <div class="am-tabs-bd">
                    <div class="am-tab-panel am-fade am-active am-in" id="tab1">
                        <fieldset>
                            <p>说明：示例中只允许上传jpg格式的图片。</p>
                            <div class="btn" id="vip-up_btn">
                                <span>上传图片 <i class="am-icon-cloud-upload"></i></span>
                                <input id="fileupload_vip-up" type="file" accept=".jpg" name="mypic">
                            </div>
                            <div class="progress progress_vip-up">
                                <div class="am-progress" style="margin-bottom:0px;">
                                    <div class="am-progress-bar bar bar_vip-up" style="width:0%">0%</div>
                                </div>
                            </div>
                            <div class="files files_vip-up"></div>
                            <img id="showimg_vip-up" src="../upload_img/vip-up.jpg">
                        </fieldset>
                    </div>

                    <div class="am-tab-panel am-fade" id="tab2">
                        <fieldset>
                            <div class="demo">
                                <p>说明：示例中只允许上传jpg格式的图片。</p>
                                <div class="btn">
                                    <span>上传图片 <i class="am-icon-cloud-upload"></i></span>
                                    <input id="fileupload" type="file" accept=".jpg" name="mypic">
                                </div>
                                <div class="progress">
                                    <div class="am-progress" style="margin-bottom:0px;">
                                        <div class="am-progress-bar bar" style="width:0%">0%</div>
                                    </div>
                                </div>
                                <div class="files"></div>
                                <img id="showimg" src="../upload_img/new-school.jpg">
                            </div>
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
<script src="js/jquery.form.js"></script>
<script>
    $(function(){
        upload_vip();
        upload_school();
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
                    data = {"pre_odds":pre_odds, "group_odds":group_odds, "moni_odds":moni_odds, "reg_count_sms":reg_count_sms};
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

    function upload_vip(){
        var bar = $('.bar_vip-up');
        var showimg = $('#showimg_vip-up');
        var progress = $(".progress_vip-up");
        var files = $(".files_vip-up");
        var btn = $("#vip-up_btn span");
        $("#fileupload_vip-up").wrap("<form id='myupload_vip-up' action='upload.php?file_name=vip-up' method='post' enctype='multipart/form-data'></form>");
        $("#fileupload_vip-up").change(function(){
            $("#myupload_vip-up").ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    // showimg.empty();
                    progress.show();
                    var percentVal = '0%';
                    bar.width(percentVal).html(percentVal);
                    btn.html("正在上传 <i class='am-icon-cloud-upload'></i>");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal).html(percentVal);
                },
                success: function(data) {
                    files.html("<b>"+data.name+"("+data.size+"k)</b>");
                    var img = "upload_img/"+data.pic + "?" +Math.random();
                    showimg.attr("src", img);
                    btn.html("上传图片 <i class='am-icon-cloud-upload'></i>");
                },
                error:function(xhr){
                    btn.html("上传失败 <i class='am-icon-cloud-upload'></i>");
                    bar.width('0');
                    files.html(xhr.responseText);
                }
            });
        });
    }

    function upload_school(){
        var bar = $('.bar');
        var showimg = $('#showimg');
        var progress = $(".progress");
        var files = $(".files");
        var btn = $(".btn span");
        $("#fileupload").wrap("<form id='myupload' action='upload.php?file_name=new-school' method='post' enctype='multipart/form-data'></form>");
        $("#fileupload").change(function(){
            $("#myupload").ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    // showimg.empty();
                    progress.show();
                    var percentVal = '0%';
                    bar.width(percentVal).html(percentVal);
                    btn.html("正在上传 <i class='am-icon-cloud-upload'></i>");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal).html(percentVal);
                },
                success: function(data) {
                    files.html("<b>"+data.name+"("+data.size+"k)</b>");
                    var img = "../img/upload_img/"+data.pic + "?" +Math.random();
                    showimg.attr("src", img);
                    btn.html("上传图片 <i class='am-icon-cloud-upload'></i>");
                },
                error:function(xhr){
                    btn.html("上传失败 <i class='am-icon-cloud-upload'></i>");
                    bar.width('0');
                    files.html(xhr.responseText);
                }
            });
        });
    }

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

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
                <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">返利管理</strong> / <small>列表</small></div>
            </div>
            <hr>
            <div class="am-g">
                <div class="am-u-sm-12 am-u-md-6">
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-xs">
                            <a href="rebate_setting_operate.php?action=add" type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增</a>
                            <button type="button" class="am-btn am-btn-default dels_btn"><span class="am-icon-trash-o"></span> 删除</button>
                        </div>
                    </div>
                </div>
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
                                <th class="table-check"><label class="am-checkbox" style="margin:0px;"><input type="checkbox" id="SelectAll" onclick="selectAll();" data-am-ucheck /></label></th>
                                <th>ID</th><th>主题</th><th>条件</th><th>返利率</th><th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $conditions = array();
                                $result = db_select("js_rebate_setting", $conditions);
                                if ($result != null && count($result) > 0) {
                                    for ($i = 0; $i < count($result); $i++) {
                                        ?>
                                        <tr data-id="<?php echo $result[$i]["id"]?>">
                                            <td><label class="am-checkbox" style="margin:0px;"><input type="checkbox" id="subcheck" onclick="setSelectAll();" name="ids" value="<?php echo $result[$i]["id"] ?>" data-am-ucheck/></label></td>
                                            <td><?php echo $result[$i]["id"]?></td>
                                            <td><?php echo $result[$i]["name"]?></td>
                                            <td>团队或个人交易量达到<?php echo $result[$i]["conditions"]/10000?>万元人民币</td>
                                            <td>返利<?php echo $result[$i]["return_rates"]?>%</td>
                                            <td>
                                                <div class="am-btn-toolbar">
                                                    <div class="am-btn-group am-btn-group-xs">
                                                        <a href="rebate_setting_operate.php?action=edit_rebate_setting&id=<?php echo $result[$i]["id"] ?>" style="background-color:#ffffff;" class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-info"></span> 详情</a>
                                                        <a href="javascript:void(0)" style="background-color:#ffffff;" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only delete-user"><span class="am-icon-trash-o"></span> 删除</a>
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
            <div class="am-modal-bd">
                你，确定要删除这些记录吗？
            </div>
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
    var is_refresh = false;
    //复选框事件
    //全选、取消全选的事件
    function selectAll(){
        if ($("#SelectAll").attr("checked")) {
            $(":checkbox").attr("checked", true);
        } else {
            $(":checkbox").attr("checked", false);
        }
    }
    //子复选框的事件
    function setSelectAll(){
        //当没有选中某个子复选框时，SelectAll取消选中
        if (!$("#subcheck").checked) {
            $("#SelectAll").attr("checked", false);
        }
        var chsub = $("input[type='checkbox'][id='subcheck']").length; //获取subcheck的个数
        var checkedsub = $("input[type='checkbox'][id='subcheck']:checked").length; //获取选中的subcheck的个数
        if (checkedsub == chsub) {
            $("#SelectAll").attr("checked", true);
        }
    }
    function loading(msg) {
        $("#loading-msg").html(msg);
        $("#loading").modal("open");
    }
    function loading_hide(){
        $("#loading-msg").html("");
        $("#loading").modal("close");
    }

    function del(ids) {
        is_refresh = false;
        $.ajax({
            type:"POST",
            url:"api/api.php?t=s&action=del_rebate_setting",
            dataType:"json",
            data:{ids:ids},
            beforeSend:function(){
                loading("正在操作...");
            },
            success:function(json){;
                if (json.success == "1") { is_refresh = true; }
                loading_hide();
                $("#alert_msg").html(json.msg);
                $("#alert").modal("open");
            }
        });
    }

    $(function() {
        $('#doc-modal-list').find('.delete-user').add('#doc-confirm-toggle').
        on('click', function() {
            $('#my-confirm').modal({
                relatedTarget: this,
                onConfirm: function(options) {
                    var $link = $(this.relatedTarget).parent().parent().parent().parent();
                    del($link.data('id'));
                },
            });
        });

        $('.dels_btn').add('#doc-confirm-toggle').
        on('click', function() {
            $('#my-confirm').modal({
                relatedTarget: this,
                onConfirm: function(options) {
                    var ids = "";
                    var i = 0;
                    $.each($("input[name='ids']"), function(o){
                        if($(this).attr("checked")) {
                            if (i > 0) {
                                ids += ",";
                            }
                            ids += $(this).val();
                            i++;
                        }
                    });
                    del(ids);
                },
            });
        });

        $('#alert').on('closed.modal.amui', function() {
            if (is_refresh) {
                window.location.href = "rebate_setting.php";
            }
        });
    });
</script>
</body>
</html>

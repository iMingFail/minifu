<?php
    session_start();
    if(!isset($_SESSION["agent_admin"])) {
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

    $query_key = "";
    if (!empty($_GET["key"]) && $_GET["key"] != "") {
        $query_key = $_GET["key"];
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

  <link rel="stylesheet" href="layer/skin/default/layer.css">

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
                <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">下级团队成员</strong> / <small>列表</small></div>
            </div>
            <hr>
            <div class="am-g">
                <div class="am-u-sm-12 am-u-md-6">
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-xs">
                            <!-- <a href="user_operate.php" type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增</a>
                            <button type="button" class="am-btn am-btn-default dels_btn"><span class="am-icon-trash-o"></span> 删除</button> -->
                        </div>
                    </div>
                </div>
                <div class="am-u-sm-12 am-u-md-3" style="width:30%;">
                    <div class="am-input-group am-input-group-sm">
                        <input type="text" id="query_key" value="<?php echo $query_key ?>" placeholder="输入用户手机或用户性名查询" style="width:240px;" class="am-form-field">
                        <span class="am-input-group-btn">
                            <button class="am-btn am-btn-default" onclick="query()" type="button">搜索</button>
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
                                <th>ID</th><th>上级用户</th><th>手机号码</th><th>余额</th><th>盈亏</th><th>团队盈亏</th><th>团队返利</th><th>充值</th><th>提现</th><th>注册日期</th><th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                $conditions = array('pid' => $_SESSION["agent_admin"]['id']);
                                if ($query_key != "") {
                                    $conditions = array("mobilePhone like '$query_key%' or realName like '$query_key%'"=>"");
                                }
                                $params = "id,insert(mobilePhone,4,4,'****') mobilePhone,balance,tempBalance, datetime, state, pid";
                                $limit = "limit ".($pageNo-1)*$pageSize.", $pageSize";
                                $result = db_select("js_user", $conditions, $params, $limit, array("datetime"=>"desc"));
                                if ($result != null && count($result) > 0) {
                                    for ($i = 0; $i < count($result); $i++) {
                                        $year=((int)substr($result[$i]["datetime"],0,4));//取得年份
                                        $month=((int)substr($result[$i]["datetime"],5,2));//取得月份
                                        $day=((int)substr($result[$i]["datetime"],8,2));//取得几号

//                                        $result_order = db_select("js_order", array("user_id"=>$result[$i]["id"], "a.isModel" => "1"), "sum(gain_price) gain_price");
//                                        $gain_price = "0";
//                                        if(!empty($result_order) && !empty($result_order[0]["gain_price"]) && $result_order[0]["gain_price"] != "") {
//                                            $gain_price = $result_order[0]["gain_price"];
//                                        }

                                        $sql = "select (((select sum(b.gain_price) from js_order b where b.isModel=1 and user_id=".$result[$i]["id"]." and b.state = 1) - ";
                                        $sql = $sql." (select sum(c.order_price) from js_order c where c.isModel=1 and c.state = 1 and user_id=".$result[$i]["id"]."))-sum(a.order_price)) today_gain from js_order a";
                                        $sql = $sql." where a.isModel=1 and a.state = 2 and user_id=".$result[$i]["id"];

                                        $today_gain_r = db_execute_select($sql);
                                        $gain_price = "0.00";
                                        if (!empty($today_gain_r) && !empty($today_gain_r[0]["today_gain"]) && $today_gain_r[0]["today_gain"] != "") {
                                            $gain_price = sprintf("%.2f", $today_gain_r[0]["today_gain"]);
                                        }

//                                        $conditions = array("b.pid" => $result[$i]["id"], "a.isModel" => "1");
//                                        $result_group_gain_price = db_select('js_order', $conditions, "sum(a.gain_price) gain_price", "", array(), array("js_user"=>array("as"=>"b", 'param'=>"id", ''=>'user_id')));
//                                        $gain_group_price = "0.00";
//                                        if (!empty($result_group_gain_price) && !empty($result_group_gain_price[0]["gain_price"]) && $result_group_gain_price[0]["gain_price"] != "") {
//                                            $gain_group_price =$result_group_gain_price[0]["gain_price"];
//                                        }

                                        $sql = "select (((select sum(b.gain_price) from js_order b left join js_user u1 on u1.id = b.user_id where b.isModel=1 and b.state = 1 and u1.pid=".$result[$i]["id"]." and b.state = 1) - ";
                                        $sql = $sql." (select sum(c.order_price) from js_order c left join js_user u2 on u2.id = c.user_id where c.isModel=1 and c.state = 1 and u2.pid=".$result[$i]["id"]."))-sum(a.order_price)) today_gain from js_order a";
                                        $sql = $sql."  left join js_user u3 on u3.id = a.user_id where a.isModel=1 and a.state = 2 and u3.pid=".$result[$i]["id"];

                                        $today_gain_g_r = db_execute_select($sql);
                                        $gain_group_price = "0.00";
                                        if (!empty($today_gain_g_r) && !empty($today_gain_g_r[0]["today_gain"]) && $today_gain_g_r[0]["today_gain"] != "") {
                                            $gain_group_price = sprintf("%.2f", $today_gain_g_r[0]["today_gain"]);
                                        }

                                        $result_filling = db_select("js_balance_log", array("userId"=>$result[$i]["id"],"type"=>1,"state"=>1), "sum(money) money");
                                        $gain_filling= "0";
                                        if(!empty($result_filling) && !empty($result_filling[0]["money"]) && $result_filling[0]["money"] != "") {
                                            $gain_filling = $result_filling[0]["money"];
                                        }

                                        $result_put = db_select("js_balance_log", array("userId"=>$result[$i]["id"],"type"=>2,"state"=>1), "sum(money) money");
                                        $gain_put= "0";
                                        if(!empty($result_put) && !empty($result_put[0]["money"]) && $result_put[0]["money"] != "") {
                                            $gain_put = $result_put[0]["money"];
                                        }

                                        $conditions = array("rebate_uid" => $result[$i]["id"], "state"=>1);
                                        $result_rebate = db_select('js_rebate', $conditions, "sum(money) money");
                                        $rebate_money = "0.00";
                                        if (!empty($result_rebate) && !empty($result_rebate[0]["money"]) && $result_rebate[0]["money"] != "") {
                                            $rebate_money = sprintf("%.2f", $result_rebate[0]["money"]);
                                        }
                                        ?>
                                        <tr data-id="<?php echo $result[$i]["id"]?>">
                                            <td><label class="am-checkbox" style="margin:0px;"><input type="checkbox" id="subcheck" onclick="setSelectAll();" name="ids" value="<?php echo $result[$i]["id"]?>" data-am-ucheck/></label></td>
                                            <td><?php echo $result[$i]["id"]?></td>
                                            <?php
                                            $result_p = db_select("js_user", array("id"=>$result[$i]["pid"]), "id, mobilePhone, realName");
                                            if ($result_p != "" && count($result_p)) {
                                                ?>
                                                <td><a href="user_operate.php?uid=<?php echo $result_p[0]["id"]?>"><?php if (empty($result_p[0]["realName"] ) || $result_p[0]["realName"] == "") {echo "(未认证用户)";} else {echo $result_p[0]["realName"];}?>
                                                        -<span style="font-size:11px;color:gray;"><?php echo $result_p[0]["mobilePhone"]?></span></a>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td>-</td>
                                                <?php
                                            }
                                            ?>

                                            <td><?php echo $result[$i]["mobilePhone"]?></td>
                                            <td><?php echo sprintf("%.2f", $result[$i]["balance"])?></td>
                                            <td><?php echo sprintf("%.2f", $gain_price); ?></td>
                                            <td><?php echo sprintf("%.2f", $gain_group_price); ?></td>
                                            <td><?php echo $rebate_money ?></td>
                                            <td><?php echo sprintf("%.2f", $gain_filling); ?></td>
                                            <td><?php echo sprintf("%.2f", $gain_put); ?></td>
                                            <td><?php echo date('Y-m-d',mktime(0,0,0,$month,$day,$year))?></td>
                                            <td>
                                                <div class="am-btn-toolbar">
                                                    <div class="am-btn-group am-btn-group-xs">
                                                        <a href="user_operate.php?uid=<?php echo $result[$i]["id"]?>" class="am-btn am-btn-primary"><span class="am-icon-info"></span> 详情</a>
                                                       <!--  <a href="javascript:void(0)" onclick="top_up(<?php echo $result[$i]["id"]; ?>, this)" class="am-btn am-btn-secondary"><span class="am-icon-jpy"></span> 充值</a>
                                                        <a href="vouchers_operate.php?uid=<?php echo $result[$i]["id"]?>" class="am-btn am-btn-secondary"><span class="am-icon-jpy"></span> 赠送代金劵</a>
                                                        <?php
                                                            if($result[$i]["state"] == 0) {
                                                                echo "<a href=\"javascript:void(0)\" index='1' onclick='upState(".$result[$i]["id"].", this)' class=\"am-btn am-btn-warning\"><span class=\"am-icon-close\"></span> 禁用</a>";
                                                            } else {
                                                                echo "<a href=\"javascript:void(0)\" index='0' onclick='upState(".$result[$i]["id"].", this)' class=\"am-btn am-btn-success\"><span class=\"am-icon-check\"></span> 解禁</a>";
                                                            }
                                                        ?>
                                                        <a href="javascript:void(0)" class="am-btn am-btn-danger delete-user"><span class="am-icon-trash-o"></span> 删除</a>
                                                         -->
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
                                $conditions = array('pid' => $_SESSION["agent_admin"]['id']);
                                if ($query_key != "") {
                                    $conditions = array("mobilePhone like '$query_key%' or realName like '$query_key%'"=>"");
                                }
                                $result_count = db_select("js_user", $conditions, "count(id) total");
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
<script src="layer/layer.js"></script>
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
            url:"api/api.php?t=u&action=delete",
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
    function upState(id, o){
        var state = $(o).attr("index");
        $.ajax({
            type:"POST",
            url:"api/api.php?t=u&action=up_user_state",
            dataType:"json",
            data:{"state":state, "id":id},
            beforeSend:function(){
                loading("正在操作...");
            },
            success:function(json){;
                loading_hide();
                if (state == 1) {
                    $(o).attr("index", "0").html("<span class='am-icon-check'></span> 解禁");
                } else {
                    $(o).attr("index", "1").html("<span class='am-icon-close'></span> 禁用");
                }
            }
        });
    }
    function top_up(id, o) {
        layer.prompt({title: '输入充值金额，并确认', formType: 3}, function(text, index){
            if (isNaN(text)) {
                alert("金额输入错误");
            } else {
                layer.close(index);
                var ii = layer.load(0, {
                    shade: [0.5,'#000'], //0.1透明度的白色背景
                    content: '<div style="font-size:12px;color:#E2E2E2;position:relative;left:60px;top:3px;width:200px;">正在充值，请稍等</div>',
                    offset:['45%', '45%']
                });
                $.ajax({
                    type:"POST",
                    url:"api/api.php?t=u&action=top_up",
                    dataType:"json",
                    data:{"balance":text, "id":id},
                    success:function(json){;
                        layer.close(ii);
                        layer.msg(json.msg);
                    }
                });
            }
            //
            // layer.msg('演示完毕！您的口令：'+ pass +' 您最后写下了：'+text);
        });
    }
    function query() {
        var query_key = $("#query_key").val();
        window.location.href = "users.php?key="+query_key;
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

        $('.dels_btn').add('#doc-confirm-toggle').on('click', function() {
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
                window.location.href = "users.php?pageNo=" +<?php echo $pageNo?>
            }
        });
    });
</script>
</body>
</html>

<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/17
 * Time: 0:02
 */
require_once '../db/mysql_operate.php';

$sql = "select count(id) out_moeny_num from js_balance_log where type=2 and state=0";
$out_moeny_num = db_execute_select($sql);
?>
<!-- sidebar start -->
<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
	<div class="am-offcanvas-bar admin-offcanvas-bar">
		<ul class="am-list admin-sidebar-list">
			<li><a href="index.php"><span class="am-icon-home"></span> 首页</a></li>
			<li>
				<a href="users.php" class="am-cf"><span class="am-icon-users"></span> 用户管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
				<ul class="am-list am-collapse admin-sidebar-sub am-in">
					<li><a href="filling_extract.php" class="am-cf"><span class="am-icon-usd"></span> 充值/提现</a></li>
					<li><a href="out_money_list.php" class="am-cf"><span class="am-icon-mail-reply"></span> 提现申请<span id="out_moeny_num" index="0" class="am-badge am-badge-secondary am-margin-right am-fr" style="background-color:#e45c00"><?php echo $out_moeny_num[0]["out_moeny_num"] ?></span></a></li>
				</ul>
			</li>
			<li><a href="orders.php" class="am-cf"><span class="am-icon-shopping-cart"></span> 交易记录</a></li>
			<!--li><a href="admin-form.html"><span class="am-icon-newspaper-o"></span> 文章管理</a></li -->
			<li class="admin-parent">
				<a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-cogs"></span> 系统管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
				<ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
					<li><a href="admins.php" class="am-cf"><span class="am-icon-users"></span> 管理员设置</a></li>
					<li><a href="setting.php" class="am-cf"><span class="am-icon-cog"></span> 基本设置</a></li>
					<li><a href="rebate_setting.php"><span class="am-icon-calculator"></span> 返利设置</a></li>
					<li><a href="upload_operate.php"><span class="am-icon-file-image-o"></span> 图片上传</a></li>
					<li><a href="vouchers.php"><span class="am-icon-file-image-o"></span> 代金劵</a></li>
					<li><a href="top_up.php"><span class="am-icon-file-image-o"></span> 充值卡</a></li>
				</ul>
			</li>
			<li><a href="javascript:void(0)"><span class="am-icon-sign-out"></span> 注销</a></li>
		</ul>

		<div class="am-panel am-panel-default admin-sidebar-panel" style="display: none;">
			<div class="am-panel-bd">
				<p><span class="am-icon-bookmark"></span> 公告</p>
				<p>时光静好，与君语；细水流年，与君同。—— Amaze UI</p>
			</div>
		</div>
	</div>
</div>
<!-- sidebar end -->
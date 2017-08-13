<?php
include '../../isLogin.php';
session_start();
require_once '../../db/mysql_operate.php';
date_default_timezone_set('PRC');
$money=$_SESSION['money'];
$userid=$_SESSION['userid'];
$openid=$_SESSION['openid'];


?>
<div id="orderInfo" style="display:none;">
    <div class="ico_title">支付测试(公众号支付)</div>
    <div class="form_wrap account">
        <div class="form_list">
            <span class="list_title">商户订单号：</span>
            <span class="list_val">
                <input name="out_trade_no" value="" maxlength="32" size="32" placeholder="长度32">
            </span>
            <i>*</i><em>长度32</em>
        </div>
		<div class="form_list">
            <span class="list_title">用户openid：</span>
            <span class="list_val">
                <input name="sub_openid" value="<?php echo $openid?>" maxlength="127" size="32" placeholder="长度127">
            </span>
            <i>*</i><em>输入用户关注公众号后的openid</em>
        </div>
        <div class="form_list">
            <span class="list_title">商品描述：</span>
            <span class="list_val">
                <input name="body" value="测试购买商品" maxlength="64" size="32" placeholder="长度127">
            </span>
            <i>*</i><em>长度64</em>
        </div>
        
        <div class="form_list">
            <span class="list_title">总金额：</span>
            <span class="list_val">
                <input name="total_fee" value="<?php echo $money*100?>" placeholder="单位：分">
            </span>
            <i>*</i><em>单位：分 整型</em>
        </div>
        <div class="form_list">
            <span class="list_title">终端IP：</span>
            <span class="list_val">
                <input name="mch_create_ip" vtype="ip" value="127.0.0.1" maxlength="16" placeholder="长度16">
            </span>
            <i>*</i><em>长度16</em>
        </div>
       
        <div class="form_list">
            <span class="list_title"></span>
            <span class="list_val submit btn btn_blue" id="auto2" >确定</span>
        </div>
    </div>
</div>


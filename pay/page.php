<?php
//require_once '../db/mysql_operate.php';
   $ReturnArray = array( // 返回字段
            "memberid" => $_REQUEST["memberid"], // 商户ID
            "orderid" =>  $_REQUEST["orderid"], // 订单号
            "amount" =>  $_REQUEST["amount"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
            "returncode" => $_REQUEST["returncode"]
        );
        $Md5key = "K9ktTzzGawllWB8NfJ6NivKkw2ipNz";
        //$sign = $this->md5sign($Md5key, $ReturnArray);
		
		///////////////////////////////////////////////////////
		ksort($ReturnArray);
        reset($ReturnArray);
        $md5str = "";
        foreach ($ReturnArray as $key => $val) {
            $md5str = $md5str . $key . "=>" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key)); 
		//var_dump($sign);
		//var_dump($_REQUEST);
		///////////////////////////////////////////////////////
        if ($sign == $_REQUEST["sign"]) {
            if ($_REQUEST["returncode"] == "00") {
				$temparr = explode('_',$ReturnArray['orderid']);
				$userid = $temparr[0];
				   $str = "交易成功！订单号：".$_REQUEST["orderid"].'<br><font color="red">3秒后自动返回</font>';
					/*$sql1 = "select * from js_balance_log where out_trade_no= '".$ReturnArray['orderid']."' and userId = '".$userid."'" ;
				$balance_info = db_execute_select($sql1);
				*/
			
			
				   exit('<script>setTimeout(aa, 3000);function aa(){window.location.href = "http://6share.top/index.php"; }</script>'.$str);
            }
        }

?>
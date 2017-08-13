<?php
require_once '../db/mysql_operate.php';
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
	
    
    ///////////////////////////////////////////////////////
    if ($sign == $_REQUEST["sign"]) {
        if ($_REQUEST["returncode"] == "00") {
				
        $sqlcheck = "select status from js_pay  where orderid='" . $ReturnArray['orderid'] ."'";
        $user_result = db_execute_select($sqlcheck);

		
        if ($user_result[0]['status']!="1") {
			
		
            $temparr = explode('_',$ReturnArray['orderid']);
			$userid = $temparr[0];
			
			
			$sql = "select * from js_pay where orderid= '".$ReturnArray['orderid']."'" ;
            $order_info = db_execute_select($sql);
			//if($order_info['status'] !==1){
				
				
				db_execute("update js_pay set status=1 where orderid='" . $ReturnArray['orderid'] ."'");
				db_execute("update js_user set balance=balance+" . $ReturnArray['amount'] . " where id=" . $userid);
				
				
				
			//}
			$orderid = $ReturnArray['orderid'];
			$amount = $ReturnArray['amount'];
			$datetime = $ReturnArray['datetime'];
			$sql1 = "select * from js_balance_log where out_trade_no= '".$ReturnArray['orderid']."' and userId = '".$userid."'" ;
			$balance_info = db_execute_select($sql1);
			if(balance_info == null){
			}else{
				db_execute("insert into js_balance_log(userId,out_trade_no,type,money,give_money,state,datetime) values('$userid','$orderid','1','$amount','0','1','$datetime')");
			}

			
				//$str = "交易成功！订单号：".$_REQUEST["orderid"].'<br><font color="red">3秒后自动返回</font>';
                 
					//var_dump($str);exit; 
				//exit('<script>setTimeout(aa, 3000);function aa(){window.location.href = "http://6share.top/index.php"; }</script>'.$str);
			
				
		}
		
			
		}else{
			$str = '发生未知错误<br><font color="red">3秒后自动返回</font>';
			exit('<script>setTimeout(aa, 3000);function aa(){window.location.href = "http://6share.top/index.php"; }</script>'.$str);
			
		}
    }
           //$str = "交易成功！订单号：".$_REQUEST["reserved1"];
           //file_put_contents("success.txt",$str."\n", FILE_APPEND);
          // exit("OK");
    //var_dump(11112221);exit;
    

?>
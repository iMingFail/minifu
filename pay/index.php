
<?php


require_once '../db/mysql_operate.php';
date_default_timezone_set('PRC');


//curl请求
function https_request($url, $data =null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

	$userid = intval($_GET['userid']);
	$money = intval($_GET['money']);

	//$money = 0.01;
	
	if($userid > 0)
	{
		if($money < 1)
		{
			$money = 0.01;
		}
	}
	else
	{
		echo "出错了~请重新支付~";
		exit;
	}

	if(trim($_GET['pbank']) == "YeePayZfb")
	{
		//$tongdao="WftZfb";
		//$tongdao="QingTianZfbSm";
		$yinhang="alipay";
		$tongdao="JiuXiaoZfbSm";
	}

	if(trim($_GET['pbank']) == "WanWuGzh")
	{
		exit("微信支付接口升级中，请选择支付宝或网银支付！");
		$tongdao="WftWx";
		//$tongdao="QingTianWxSm";
		$yinhang="WXZF";
	}

	if(trim($_GET['pbank']) == "YeePayYjzf")
	{
		$tongdao="";
		$yinhang="";
	}
	
	
	$pay_memberid = "12433";   //商户ID
	$pay_orderid = $userid."_12433".date("YmdHis");    //订单号
	$pay_amount = $money;    //交易金额
	$pay_applydate = date("Y-m-d H:i:s");  //订单时间
	$pay_bankcode = $yinhang;//"YeePayWx";   //银行编码
	$pay_notifyurl = "http://www.xsxms.com/pay/server.php";   //服务端返回地址
	$pay_callbackurl = "http://www.xsxms.com/pay/page.php";  //页面跳转返回地址
	//$pay_callbackurl = "";
	
	$Md5key = "K9ktTzzGawllWB8NfJ6NivKkw2ipNz";   //密钥
	
	$tjurl = "http://zy.cnzypay.com/Pay_Index.html";   //提交地址
	
	$requestarray = array(
			"pay_memberid" => $pay_memberid,
            "pay_orderid" => $pay_orderid,
            "pay_amount" => $pay_amount,
            "pay_applydate" => $pay_applydate,
            "pay_bankcode" => $pay_bankcode,
            "pay_notifyurl" => $pay_notifyurl,
            "pay_callbackurl" => $pay_callbackurl
        );

		$arr = array();
        $params = array("userid"=>"$userid", "orderid"=>"$pay_orderid", "amount"=>"$pay_amount",
            "type"=>$pay_bankcode, "ctime"=>"$pay_applydate");
        $result = db_insert('js_pay', $params, true);
	if ($result > 0) {
	
	    ksort($requestarray);
        reset($requestarray);
        $md5str = "";
        foreach ($requestarray as $key => $val) {
            $md5str = $md5str . $key . "=>" . $val . "&";
        }
		//echo($md5str . "key=" . $Md5key."<br>");
        $sign = strtoupper(md5($md5str . "key=" . $Md5key)); 
		$requestarray["pay_md5sign"] = $sign;
        // $requestarray["pay_reserved1"] = $pay_orderid;
        $requestarray["tongdao"] = $tongdao;
		
        $str = '<form id="Form1" name="Form1" method="post" action="' . $tjurl . '">';
        foreach ($requestarray as $key => $val) {
            $str = $str . '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
		$str = $str . '<input type="submit" value="支付">';
        $str = $str . '</form>';
        $str = $str . '<script>';
        $str = $str . 'document.Form1.submit();';
        $str = $str . '</script>';
		if($yinhang==""){
			echo $str;
			exit;
		}

        $tjurl = "http://zy.cnzypay.com/Pay_Index.html";   //提交地址
      
        $info=https_request($tjurl,$requestarray);
        $arr=explode('"',$info);
         
        $img = $arr[17];
        
        
        $saomainfo="扫码支付";
        
        
        $imghtml='
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>'.$saomainfo.'</title>
            <script type="text/javascript" src="js/jquery.js"></script>
            </head>
        
             <style type="text/css">
            #center{border:0px solid red;margin:auto;height:1500px;margin-left:20px; margin-right:20px;}
            #center .order{border:1px solid red;background-color:#ffffc9;border-radius:6px;margin-top:50px;}
            #center .order .title{border:0px solid red;padding:20px 0px;font-size:24px;text-align:center;color:#993300;}
            #center .order .order_info{border:0px solid red;margin:20px 0px;height:80px;}
            #center .order .order_info ul{width:700px;margin:auto;border:0px solid red;}
            #center .order .order_info ul li{border:0px solid red;width:300px;height:30px;float:left;margin:0px 30px;line-height:30px;font-size:16px;}
            #center .wxcode{border:1px solid red;height:1300px;margin-top:20px;border-radius:6px; margin-left:20px; margin-right:20px;}
            #center .wxcode .zycode{border:0px solid red;height:100%;width:85%;float:left;margin-left:100px;margin-top:40px;}
            #center .wxcode .img{border:0px solid red;height:100%;width:100%;float:left;margin-left:50px;margin-top:40px;}
           .STYLE1 {color: #FF0000}
                .STYLE1 {color: #FF0000}
.STYLE4 {font-size:  41px}
                .STYLE2 {
	font-size:  41px;
	color: #FF0000;
}
           </style>
            <body style="background:#CC0000;">
        
            <div id="center">';
        
         
        

        if(trim($_GET['pbank']) == "YeePayZfb")
        //if($requestarray["tongdao"] == "WftZfb"||$requestarray["tongdao"] == "QingTianZfbSm")
        {
             
            $imghtml=$imghtml.'
                 <div class="wxcode"  style="background:white;">
        
                    <table width="100%" border="0">
        
                                 <tr>
                                <td>
         
                                                           <div align="center" class="STYLE2"></br>
                                        	扫一扫二维码，复制链接在浏览器中打开	</br></br></div>
           
                                        	<div style="width:100%;height:0px;border-top:1px black dashed;" />
                                                            </br></br>
                                                            </td>
                                          </tr>
        
        
                                          <tr>
                                            <td>
                                        	<div class="zycode"><img src="http://zy.cnzypay.com/'.$img.'" width="100%" height="100%"></div></td>
                                          </tr>
                                          <tr>
                                            <td>
                                   				  <span class="STYLE4"><strong>	<span class="STYLE1">具体流程</span>：</strong><br />
                            					    1.请扫描图中二维码<br />
                            						2.复制当前链接到浏览器中完成支付宝支付<br />
                            				  		3.输入密码完成支付。
                            					    </span>
                            					    </td>
          
                              </tr>
                            </table>
        
			
			
                </div>
                </div>
                </body>
                </html>';
             
             
             
        }else{
             
            $imghtml=$imghtml.'
                 <div class="wxcode"  style="background:white;">
        
                <table width="100%" border="0">
        
        
                    <tr>
                        <td>
        
                                        <div align="center" class="STYLE2"></br>
                    	                                                   截图保存二维码，打开“扫一扫” </br>	</br></div>
      
                    	                   <div style="width:100%;height:0px;border-top:1px black dashed;" />
                                        </br></br>
         
        
                                        </td>
                      </tr>
        
        
                <tr>
                <td>
                <div class="zycode"><img src="http://zy.cnzypay.com/'.$img.'" width="100%" height="100%"></div></td>
                </tr>
                <tr>
                <td>
        
               <span class="STYLE4"> <strong>	<span class="STYLE1">具体流程</span>：</strong><br />
                1.请截图保存二维码<br />
                2.返回微信，打开【微信扫一扫】，选择从相册中找到刚刚截图的二维码图片<br />
                3.弹到充值页面输入密码，完成支付。<br />
                </span></td>
                </tr>
                </table>
                </div>
                </div>
                </body>
                </html>';
        }
         
         
         
         
        exit($imghtml);
         
        
        
        
        
        
        
        
        
        }
        else
        {
            echo "出错了~请重新支付~";
        }
        
   
        
?>
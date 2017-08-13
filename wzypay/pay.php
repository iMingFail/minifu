<?php 
 //require_once '../db/mysql_operate.php';
$xml=file_get_contents('php://input');
 function xmlToArray($xml){ 
	//禁止引用外部xml实体 
	libxml_disable_entity_loader(true); 
	$xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
	$val = json_decode(json_encode($xmlstring),true); 
	return $val; 
}
$data = xmlToArray($xml);
require_once '../db/mysql_operate.php';
$result = db_select('js_pay',array('orderid'=>$data['out_trade_no'],'status'=>'0'));

if($result){
	db_update('js_pay',array('status'=>'1'),array('orderid'=>$data['out_trade_no'],'status'=>'0'));
	db_update('js_balance_log',array('state'=>'1'),array('out_trade_no'=>$data['out_trade_no'],'state'=>'0'));
	$user = db_select('js_user',array('id'=>$result[0]['userid']));
	db_update('js_user',  array('balance'=>((float)$user[0]['balance']+(float)((float)$data['total_fee']/100))),array('id'=>$user[0]['id']));
	
	//往 js_balance_log 插入数据
	// db_execute("INSERT INTO js_balance_log ( userId , out_trade_no , type , money , give_money , state, datetime ) 
// VALUES ( ".$result[0]['userid'].",".$data['out_trade_no'].",'5', ".(float)((float)$data['total_fee']/100).",0, 1, now() ) ");
}
echo "success";



<?php
class Config{
    private $cfg = array(

		//接口请求地址，固定不变，无需修改
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
		//测试商户号，商户需改为自己的
        'mchId'=>'150500049040',
		//测试密钥，商户需改为自己的
        'key'=>'fd093b5f4a171bfe8a7b4dde7091212e',
		//版本号默认为2.0
        'version'=>'2.0'
       );
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>
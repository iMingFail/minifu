<?php
class Config{
    private $cfg = array(

		//�ӿ������ַ���̶����䣬�����޸�
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
		//�����̻��ţ��̻����Ϊ�Լ���
        'mchId'=>'150570047853',
		//������Կ���̻����Ϊ�Լ���
        'key'=>'9aa583d67ba122e4ef451d5f316c61be',
		//�汾��Ĭ��Ϊ2.0
        'version'=>'2.0'
       );
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>
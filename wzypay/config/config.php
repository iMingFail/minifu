<?php
class Config{
    private $cfg = array(

		//�ӿ������ַ���̶����䣬�����޸�
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
		//�����̻��ţ��̻����Ϊ�Լ���
        'mchId'=>'150500049040',
		//������Կ���̻����Ϊ�Լ���
        'key'=>'fd093b5f4a171bfe8a7b4dde7091212e',
		//�汾��Ĭ��Ϊ2.0
        'version'=>'2.0'
       );
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>
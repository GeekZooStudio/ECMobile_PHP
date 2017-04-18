# ECMobile_3.2_PHP升级教程
1.数据库中payment表添加微信支付
		
	INSERT INTO `d2c_payment` (`pay_id`, `pay_code`, `pay_name`, `pay_fee`, `pay_desc`, `pay_order`, `pay_config`, `enabled`, `is_cod`, `is_online`)
	VALUES
	(18,'wxpay','微信支付','0','',0,'a:4:{i:0;a:3:{s:4:\"name\";s:14:\"weixin_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:10:\"weixin_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:14:\"weixin_partner\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:3;a:3:{s:4:\"name\";s:17:\"weixin_pay_method\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:1:\"0\";}}',1,0,0);
	
2.在ecshop代码中添加微信支付插件

xx/ecshop/includes/modules/payment/wxpay.php

3.合入ecmobile新版代码

4.配置
	xx/ecshop/ecmobile/payment/tenpay_config.php

5.cloud.ecmobile.cn管理后台填入微信支付的参数
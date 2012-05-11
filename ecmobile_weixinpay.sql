# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 211.157.191.73 (MySQL 5.1.69-log)
# Database: d2c.st-marc.info
# Generation Time: 2015-05-10 14:15:39 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table d2c_payment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `d2c_payment`;

CREATE TABLE `d2c_payment` (
  `pay_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(20) NOT NULL DEFAULT '',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `pay_fee` varchar(10) NOT NULL DEFAULT '0',
  `pay_desc` text NOT NULL,
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `d2c_payment` WRITE;
/*!40000 ALTER TABLE `d2c_payment` DISABLE KEYS */;

INSERT INTO `d2c_payment` (`pay_id`, `pay_code`, `pay_name`, `pay_fee`, `pay_desc`, `pay_order`, `pay_config`, `enabled`, `is_cod`, `is_online`)
VALUES
	(1,'balance','余额支付','0','使用帐户余额支付。只有会员才能使用，通过设置信用额度，可以透支。',0,'a:0:{}',0,0,1),
	(2,'bank','银行汇款/转帐','0','银行名称\r\n收款人信息：全称 ××× ；帐号或地址 ××× ；开户行 ×××。\r\n注意事项：办理电汇时，请在电汇单“汇款用途”一栏处注明您的订单号。',0,'a:0:{}',1,0,0),
	(3,'cod','货到付款','0','开通城市：北京\r\n货到付款区域：三环内',0,'a:0:{}',1,1,0),
	(4,'alipay','支付宝','0','支付宝网站(www.alipay.com) 是国内先进的网上支付平台。<br/>支付宝收款接口：在线即可开通，<font color=\"red\"><b>零预付，免年费</b></font>，单笔阶梯费率，无流量限制。<br/><a href=\"http://cloud.ecshop.com/payment_apply.php?mod=alipay\" target=\"_blank\"><font color=\"red\">立即在线申请</font></a>',0,'a:4:{i:0;a:3:{s:4:\"name\";s:14:\"alipay_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:10:\"alipay_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:14:\"alipay_partner\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:3;a:3:{s:4:\"name\";s:17:\"alipay_pay_method\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:1:\"0\";}}',1,0,1),
	(5,'kuaiqian','快钱人民币网关','0','快钱是国内领先的独立第三方支付企业，旨在为各类企业及个人提供安全、便捷和保密的支付清算与账务服务，其推出的支付产品包括但不限于人民币支付，外卡支付，神州行卡支付，联通充值卡支付，VPOS支付等众多支付产品, 支持互联网、手机、电话和POS等多种终端, 以满足各类企业和个人的不同支付需求。截至2009年6月30日，快钱已拥有4100万注册用户和逾31万商业合作伙伴，并荣获中国信息安全产品测评认证中心颁发的“支付清算系统安全技术保障级一级”认证证书和国际PCI安全认证。<br/><a href=\"send.php\" target=\"_blank\"><font color=\"red\">点此链接在线签约快钱</font></a>',0,'a:2:{i:0;a:3:{s:4:\"name\";s:10:\"kq_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:6:\"kq_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}}',0,0,1),
	(6,'chinabank','网银在线','1%','网银在线（www.chinabank.com.cn）与中国工商银行、招商银行、中国建设银行、农业银行、民生银行等数十家金融机构达成协议。全面支持全国19家银行的信用卡及借记卡实现网上支付。<br/><a href=\"http://cloud.ecshop.com/payment_apply.php?mod=chinabank\" target=\"_blank\">立即在线申请</a>',0,'a:2:{i:0;a:3:{s:4:\"name\";s:17:\"chinabank_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:13:\"chinabank_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}}',0,0,1),
	(7,'upop','银联在线支付','0','银联在线支付是中国银联推出的网上支付平台，支持多家发卡银行，涵盖借记卡和信用卡等，包含认证支付、快捷支付和网银支付多种方式，其中认证和快捷支付无需开通网银，仅需一张银行卡，即可享受安全、快捷的网上支付服务！',0,'a:3:{i:0;a:3:{s:4:\"name\";s:12:\"upop_merAbbr\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:12:\"商户名称\";}i:1;a:3:{s:4:\"name\";s:12:\"upop_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:17:\"upop_security_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}}',1,0,1),
	(8,'ips','环迅IPS','0','IPS(www.ips.com)账户是上海环迅于2005年推出的新一代基于电子邮件的互联网多币种收付款工具。截止到目前，IPS账户具备在线充值、在线收付款、在线转账、网上退款和网上提款等多种功能，并支持多种账户充值方式。<br/><a href=\"http://cloud.ecshop.com/payment_apply.php?mod=ips\" target=\"_blank\">立即在线申请</a>',0,'a:4:{i:0;a:3:{s:4:\"name\";s:11:\"ips_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:7:\"ips_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:12:\"ips_currency\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:2:\"01\";}i:3;a:3:{s:4:\"name\";s:8:\"ips_lang\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:2:\"GB\";}}',0,0,1),
	(9,'shenzhou','快钱神州行支付','0','快钱神州行支付（www.99bill.com）是可以用中国移动的神州行充值卡来支付的。目前使用神州行支付的订单的金额不能小于1元，不能超过500元。<br/><a href=\"http://cloud.ecshop.com/payment_apply.php?mod=shenzhou\" target=\"_blank\">立即在线申请</a>',0,'a:2:{i:0;a:3:{s:4:\"name\";s:16:\"shenzhou_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:12:\"shenzhou_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}}',0,0,1),
	(10,'cappay','首信易支付','0','首信易支付作为具有国家资质认证、政府投资背景的中立第三方网上支付平台拥有雄厚的实力和卓越的信誉。同时，它也是国内唯一首家通过 ISO 9001：2000质量管理体系认证的支付平台。规范的流程及优异的服务品质为首信易支付于2005 和2006年连续两年赢得“电子支付用户信任奖”和2006年度“B2B支付创新奖”殊荣奠定了坚实的基础。<a href=\"http://cloud.ecshop.com/payment_apply.php?mod=cappay\" target=\"_blank\"><strong>点击这里立即注册首信易</strong></a>',0,'a:3:{i:0;a:3:{s:4:\"name\";s:14:\"cappay_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:10:\"cappay_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:15:\"cappay_currency\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:1:\"0\";}}',0,0,1),
	(11,'tenpay','<font color=\"#FF0000\">财付通</font>','0','<b>财付通（www.tenpay.com） - 腾讯旗下在线支付平台，通过国家权威安全认证，支持各大银行网上支付，免支付手续费。</b><br /><a href=\"http://cloud.ecshop.com/payment_apply.php?mod=tenpay&par=1202822001\" target=\"_blank\">立即免费申请：单笔费率1%</a><br /><a href=\"http://cloud.ecshop.com/payment_apply.php?mod=tenpay&par=1442037873\" target=\"_blank\">立即购买包量套餐：折算后单笔费率0.6-1%</a>',0,'a:3:{i:0;a:3:{s:4:\"name\";s:14:\"tenpay_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:10:\"1218749401\";}i:1;a:3:{s:4:\"name\";s:10:\"tenpay_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:32:\"b3be173d9e19c9b53a1a017eac7f6cd0\";}i:2;a:3:{s:4:\"name\";s:12:\"magic_string\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}}',1,0,1),
	(12,'paypal','paypal','0','PayPal（www.paypal.com） 是在线付款解决方案的全球领导者，在全世界有超过七千一百六十万个帐户用户。PayPal 可在 56 个市场以 7 种货币（加元、欧元、英镑、美元、日元、澳元、港元）使用。<br/><a href=\"http://cloud.ecshop.com/payment_apply.php?mod=paypal\" target=\"_blank\">立即在线申请</a>',0,'a:2:{i:0;a:3:{s:4:\"name\";s:14:\"paypal_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:15:\"paypal_currency\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:3:\"USD\";}}',1,0,1),
	(13,'post','邮局汇款','0','收款人信息：姓名 ××× ；地址 ××× ；邮编 ××× 。\r\n注意事项： 请在汇款单背面的附言中注明您的订单号，只填写后6位即可。',0,'a:0:{}',0,0,0),
	(14,'paypal_ec','paypal快速结帐','0','PayPal（www.paypal.com） 是在线付款解决方案的全球领导者，在全世界有超过七千一百六十万个帐户用户。PayPal 可在 56 个市场以 7 种货币（加元、欧元、英镑、美元、日元、澳元、港元）使用。<br/><a href=\"http://cloud.ecshop.com/payment_apply.php?mod=paypal\" target=\"_blank\">立即在线申请</a>',0,'a:4:{i:0;a:3:{s:4:\"name\";s:18:\"paypal_ec_username\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:18:\"paypal_ec_password\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:19:\"paypal_ec_signature\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:3;a:3:{s:4:\"name\";s:18:\"paypal_ec_currency\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:3:\"USD\";}}',0,0,1),
	(15,'tenpayc2c','<font color=\"#FF0000\">财付通中介担保接口</font>','0','<b>财付通（www.tenpay.com） - 腾讯旗下在线支付平台，通过国家权威安全认证，支持各大银行网上支付，免支付手续费。</b><br /><a href=\"http://union.tenpay.com/mch/mch_register_b2c.shtml?sp_suggestuser=1202822001\" target=\"_blank\">立即免费申请：单笔费率1%</a><br /><a href=\"http://union.tenpay.com/set_meal_charge/?referrer=1442037873\" target=\"_blank\">立即购买包量套餐：折算后单笔费率0.6-1%</a>',0,'a:3:{i:0;a:3:{s:4:\"name\";s:14:\"tenpay_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:10:\"1218749401\";}i:1;a:3:{s:4:\"name\";s:10:\"tenpay_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:32:\"b3be173d9e19c9b53a1a017eac7f6cd0\";}i:2;a:3:{s:4:\"name\";s:11:\"tenpay_type\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:1:\"1\";}}',0,0,1),
	(18,'wxpay','微信支付','0','',0,'a:4:{i:0;a:3:{s:4:\"name\";s:14:\"weixin_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:10:\"weixin_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:14:\"weixin_partner\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:3;a:3:{s:4:\"name\";s:17:\"weixin_pay_method\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:1:\"0\";}}',1,0,0);

/*!40000 ALTER TABLE `d2c_payment` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

<?php
//header('Content-type: text/json');
//header('Content-type: text/html; charset=gb2312');
//---------------------------------------------------------
//微信支付服务器签名支付请求示例，商户按照此文档进行开发即可
//---------------------------------------------------------
define('IN_ECS', true);
// GZ_Api::authSession();
require($_SERVER ['DOCUMENT_ROOT'] . '/includes/init.php');
require($_SERVER ['DOCUMENT_ROOT'] . '/includes/lib_order.php');
require_once ("classes/RequestHandler.class.php");
require_once ("./tenpay_config.php");
require_once ("classes/ResponseHandler.class.php");
require ("./classes/client/TenpayHttpClient.class.php");
require ("./classes/function.php");

require ("../../Library/function.php");

$inputParams = array();
 if (!empty($_POST['json'])) {
            if (!get_magic_quotes_gpc()) {
                $_POST['json'] = stripslashes($_POST['json']);
            }
            $inputParams = json_decode($_POST['json'], true);
        }
$order_id = $inputParams['order_id'];

$order = order_info($order_id);       

//获取提交的商品价格
//log_result(var_export($order,true));

$order_price=trim($order['total_fee']);

if($order_price == ''){
	$order_price = '1';
}

$goods_list = GZ_order_goods($order_id);


$product_name = $goods_list[0]['goods_name'].'等'.count($goods_list).'种商品';

//获取提交的商品名称 暂无 如有需要可以添加

if ($product_name == ''){
	$product_name = '测试商品名称';
}
$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
$product_name = preg_replace($regex,"",$product_name);

//获取提交的订单号
$out_trade_no=trim($order['order_sn']);
if ($out_trade_no == ''){
	$out_trade_no = time();
}

$device_info = trim(($inputParams['device_info']?$inputParams['device_info']:''));

$outparams =array();
//商品价格（包含运费），以分为单位
$total_fee= $order_price*100;
//输出类型
// $out_type	= strtoupper($_GET['out_type']);
// $plat_from	= strtoupper($_GET['plat']);
//获取token值
$reqHandler = new RequestHandler();
$reqHandler->init($APP_ID, $APP_SECRET, $PARTNER_KEY);
//=========================
//生成预支付单
//=========================
//设置packet支付参数
$packetParams = array();
//公众账号ID
$packetParams['appid'] = $APP_ID;
//商户号
$packetParams['mch_id'] = $PARTNER;

//设备号  TODO
$packetParams['device_info'] = $device_info;

$nonce_str = getRandomStr();
//随机字符串
$packetParams['nonce_str'] = $nonce_str;


//商品描述
$packetParams['body'] = $product_name;
//商品详情
$packetParams['attach'] = $product_name;
//商户订单号
$packetParams['out_trade_no'] = $out_trade_no;
//总金额
$packetParams['total_fee'] = $total_fee;
//访问接口IP
//$packetParams['spbill_create_ip'] = Request::getClientIp();
$packetParams['spbill_create_ip'] = '127.0.0.1';
//接受微信支付异步通知回调地址
$packetParams['notify_url'] = ecmobile_url().$notify_url;
//交易类型:JSAPI,NATIVE,APP
$packetParams['trade_type'] = "APP";

//签名 TODO
$sign = $reqHandler->createMd5Sign($packetParams);
$packetParams['sign'] = $sign;

$time_stamp = strval(time());

//获取prepayid
$prepayid = $reqHandler->sendPrepay($packetParams);

if ($prepayid != null) {
    $pack	= 'Sign=WXPay';
    //输出参数列表
    $prePayParams               =   array();
    $prePayParams['appid']		=   $APP_ID;
    $prePayParams['partnerid']	=   $PARTNER;
    $prePayParams['prepayid']  =   $prepayid;
    $prePayParams['package']	=   $pack;
    $prePayParams['noncestr']	=   $nonce_str;
    $prePayParams['timestamp']	=   $time_stamp;
    //生成签名
    $sign=$reqHandler->createMd5Sign($prePayParams);

    $outparams['error_code']   =   0;
    $outparams['succeed']          =   1;
    $outparams['error_desc']    =   'ok';
    $outparams['appid']     =   $APP_ID;
    $outparams['partnerid']	=   $PARTNER;
    $outparams['noncestr']  =   $nonce_str;
    $outparams['package']   =   $pack;
    $outparams['prepayid']  =   $prepayid;
    $outparams['timestamp'] =   $time_stamp;
    $outparams['sign']      =   $sign;
    $outparams['ext']      =   "ext";


}else{
    $outparams['error_code']   =-  2;
    $outparams['error_desc']    =   '错误：获取prepayId失败';
    $outparams['succeed']          =   0;
}


/**
=========================
输出参数列表
=========================
 */
//Json 输出
//ob_clean();
echo json_encode($outparams);

/**
 * 取得订单商品
 * @param   int     $order_id   订单id
 * @return  array   订单商品数组
 */
function GZ_order_goods($order_id)
{
    $sql = "SELECT o.*, " .
            "o.goods_price * o.goods_number AS subtotal,g.goods_thumb,g.original_img,g.goods_img " .
            "FROM " . $GLOBALS['ecs']->table('order_goods') . " as o LEFT JOIN ".$GLOBALS['ecs']->table('goods') . " AS g ON o.goods_id = g.goods_id" .
            " WHERE o.order_id = '$order_id'";

    $res = $GLOBALS['db']->query($sql);

    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['extension_code'] == 'package_buy')
        {
            $row['package_goods_list'] = get_package_goods($row['goods_id']);
        }
        $goods_list[] = $row;

    }
    //return $GLOBALS['db']->getAll($sql);
    return $goods_list;
}
/**
 * 获取随机字符串
 * @return string 不长于32位
 */
 function getRandomStr()
{
    return md5(time() . mt_rand(0,1000));
}


?> 
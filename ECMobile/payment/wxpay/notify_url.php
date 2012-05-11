<?php

//---------------------------------------------------------
//即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------

define('IN_ECS', true);
require($_SERVER ['DOCUMENT_ROOT'] . '/includes/init.php');
require($_SERVER ['DOCUMENT_ROOT'] . '/includes/lib_payment.php');
require($_SERVER ['DOCUMENT_ROOT'] . '/includes/lib_order.php');
require($_SERVER ['DOCUMENT_ROOT'] . '/includes/lib_clips.php');


require ("classes/ResponseHandler.class.php");
require ("classes/RequestHandler.class.php");
require ("classes/client/TenpayHttpClient.class.php");
require ("./classes/function.php");
require_once ("./tenpay_config.php");


log_result("进入后台回调页面");

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
log_result($postStr);
log_result("1");

/* 创建支付应答对象 */
$resHandler = new ResponseHandler();
$inputParams = $resHandler->xmlToArray($postStr);
log_result("2");

foreach($inputParams as $k => $v) {
    $resHandler->setParameter($k, $v);
}
$resHandler->setKey($PARTNER_KEY);


    //判断签名
    if($resHandler->isTenpaySign() == true) {

        //支付结果
        $return_code = $resHandler->getParameter("return_code");

        //判断签名及结果
        if ("SUCCESS"==$return_code){

            //商户在收到后台通知后根据通知ID向财付通发起验证确认，采用后台系统调用交互模式

            //商户交易单号
            $out_trade_no = $resHandler->getParameter("out_trade_no");
            log_result($out_trade_no);
            

            //----------------------
            //即时到帐处理业务开始
            //-----------------------
            //处理数据库逻辑
            //注意交易单不要重复处理
            //注意判断返回金额
            //-----------------------
            //即时到帐处理业务完毕
            //-----------------------
            //给财付通系统发送成功信息，给财付通系统收到此结果后不在进行后续通知
            $order = order_info(0, $out_trade_no);

            if ($order) {
                log_result('order_id:'.$order['order_id']);

                $log_id = insert_pay_log($order['order_id'], $order['order_amount'], PAY_ORDER);
                log_result('log_id:'.$log_id);

                order_paid($log_id, 2);
            }

            log_result('后台通知成功');
        } else {
            log_result('后台通知失败');
        }
        //回复服务器处理成功
        echo "Success";
    } else {
        echo "<br/>" . "验证签名失败" . "<br/>";

        log_result("验证签名失败");
        //echo $resHandler->getDebugInfo() . "<br>";
    }

function pay($out_trade_no)
{

}

?>
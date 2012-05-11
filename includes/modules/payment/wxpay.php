<?php

/**
 * WXPAY 微信支付插件
 * ============================================================================
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}



/**
 * 类
 */
class wxpay
{

    function __construct()
    {
        $this->wxpay();
    }
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function wxpay()
    {
    }

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        if (!defined('EC_CHARSET'))
        {
            $charset = 'utf-8';
        }
        else
        {
            $charset = EC_CHARSET;
        }    
     
        $button = '<div style="text-align:center"><input type="button" onclick="window.open(\'https://ecmobile.cn\')" value="网页不可支付" /></div>';
        return $button;
    }

 
}

?>
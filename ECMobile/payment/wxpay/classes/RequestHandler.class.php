<?php
/**
 * 请求类
 * ============================================================================
 * api说明：
 * init(),初始化函数，默认给一些参数赋值，如cmdno,date等。
 * getGateURL()/setGateURL(),获取/设置入口地址,不包含参数值
 * getKey()/setKey(),获取/设置密钥
 * getParameter()/setParameter(),获取/设置参数值
 * getAllParameters(),获取所有参数
 * getRequestURL(),获取带参数的请求URL
 * getDebugInfo(),获取debug信息
 *
 * ============================================================================
 *
 */
class RequestHandler {

    /** Token获取网关地址*/
    var $tokenUrl;

    /**预支付网关url地址 */
    var $gateUrl;

    var $unifiedorderUrl;

    /** 商户参数 */
    var $app_id, $partner_key, $app_secret, $app_key;

    /**  Token */
    var $Token;

    /** debug信息 */
    var $debugInfo;

    function __construct(){
        $this->RequestHandler();
    }
    function RequestHandler(){
        $this->tokenUrl		= 'https://api.weixin.qq.com/cgi-bin/token';
        $this->gateUrl		= 'https://api.weixin.qq.com/pay/genprepay';
        $this->notifyUrl	= 'https://gw.tenpay.com/gateway/simpleverifynotifyid.xml';
        $this->unifiedorderUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    }
    /**
     *初始化函数。
     */
    function init($appid, $appsecret,$partnerkey) {
        $this->debugInfo	= '';
        $this->Token		= '';
        $this->app_id		= $appid;
        $this->partner_key	= $partnerkey;
        $this->app_secret	= $appsecret;
    }
    /**
     *获取debug信息
     */
    function getDebugInfo() {
        $res = $this->debugInfo;
        $this->debugInfo = '';
        return $res;
    }

    //
    function httpSend($url, $method, $data){
        $client = new TenpayHttpClient();
        $client->setReqContent($url);
        $client->setMethod($method);
        $client->setReqBody($data);
        $res =  '';
        if( $client->call()){
            $res =  $client->getResContent();
        }
        //设置debug信息
        $this->_setDebugInfo('Req Url:' .$url);
        $this->_setDebugInfo('Req data:' .$data);
        $this->_setDebugInfo('Res Content:' .$res);

        return $res;
    }

    //获取TOKEN，一天最多获取200次
    function GetToken(){


        if(Cache::has("weixin_access_token"))
        {
            if($this->Token = Cache::get('weixin_access_token')){
                return $this->Token;
            }
        }

        $url= $this->tokenUrl . '?grant_type=client_credential&appid='.$this->app_id .'&secret='.$this->app_secret;
        $json=$this->httpSend($url,'GET','');
        if( $json != ""){
            $tk = json_decode($json);
            if( $tk->access_token != "" )
            {
                $this->Token =$tk->access_token;
                $expires_in = $tk->expires_in;
                Cache::put("weixin_access_token",$this->Token,$expires_in);
            }else{
                $this->Token = '';
            }
        }
        //设置debug信息
        $this->_setDebugInfo('tokenUrl:' .$url);
        $this->_setDebugInfo('tokenRes jsonContent:' .$json);
        return $this->Token;
    }

    /**
     *创建package签名
     */
    function createMd5Sign($signParams) {
        $signPars = '';

        ksort($signParams);
        foreach($signParams as $k =>$v) {
            if($v != "" && 'sign' !=$k) {
                $signPars .= $k . '=' .$v.'&';
            }
        }
        $signPars .= 'key=' .$this->partner_key;

        $sign = strtoupper(md5($signPars));
        //debug信息
        $this->_setDebugInfo('md5签名:'.$signPars . ' => sign:' .$sign);

        return $sign;

    }

    //获取带参数的签名包
    function genPackage($packageParams){

        $sign = $this->createMd5Sign($packageParams);
        $reqPars = '';
        foreach ($packageParams as $k =>$v ){
            $reqPars.=$k . '='.URLencode($v) . '&';
        }
        $reqPars = $reqPars . 'sign=' .$sign;
        //debug信息
//        $this->_setDebugInfo('gen package:' .$reqPars);

        return $reqPars;
    }

    //创建签名SHA1
    function createSHA1Sign($packageParams){
        $signPars = '';
        ksort($packageParams);
        foreach($packageParams as $k=> $v) {
            if($signPars == ''){
                $signPars =$signPars .$k. '=' .$v;
            }else{
                $signPars =$signPars. '&' .$k. '=' .$v;
            }
        }

        $sign = SHA1($signPars);

        //debug信息
        $this->_setDebugInfo('sha1:' .$signPars .'=>'. $sign);

        return $sign;
    }

    //提交预支付
    function sendPrepay($packageParams){

        $prepayid = null;

        $smlStr = $this->arrayToXml($packageParams);


        $url= $this->unifiedorderUrl;

        $res = $this->httpSend($url,'POST',$smlStr);

        $res = $this->xmlToArray($res);
        if($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS'
            && $this->verifySignResponse($res))
        {
            return $res['prepay_id'];
        }

        if($res['return_code'] == 'FAIL') {
            echo ("提交预支付交易单失败:{$res['return_msg']}");
        }

        echo ("提交预支付交易单失败，{$res['err_code']}:{$res['err_code']}");
    }

    /**
     * 数组转成xml字符串
     *
     * @return string
     */
    protected function arrayToXml($params)
    {
        $xml = '<xml>';
        foreach($params as $key => $value) {
            $xml .= "<{$key}>";
            $xml .= "<![CDATA[{$value}]]>";
            $xml .= "</{$key}>";
        }
        $xml .= '</xml>';

        return $xml;
    }

    /**
     * 取成功响应
     * @return string
     */
    public function getSucessXml()
    {
        $xml = '<xml>';
        $xml .= '<return_code><![CDATA[SUCCESS]]></return_code>';
        $xml .= '<return_msg><![CDATA[OK]]></return_msg>';
        $xml .= '</xml>';
        return $xml;
    }

    public function getFailXml()
    {
        $xml = '<xml>';
        $xml .= '<return_code><![CDATA[FAIL]]></return_code>';
        $xml .= '<return_msg><![CDATA[OK]]></return_msg>';
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * xml 转换成数组
     * @param string $xml
     * @return array
     */
    protected function xmlToArray($xml)
    {
        $xmlObj = simplexml_load_string(
            $xml,
            'SimpleXMLIterator',   //可迭代对象
            LIBXML_NOCDATA
        );

        $arr = [];
        $xmlObj->rewind(); //指针指向第一个元素
        while (1) {
            if( ! is_object($xmlObj->current()) )
            {
                break;
            }
            $arr[$xmlObj->key()] = $xmlObj->current()->__toString();
            $xmlObj->next(); //指向下一个元素
        }

        return $arr;
    }

    //验证统一下单接口响应
    protected function verifySignResponse($arr)
    {
        $tmpArr = $arr;
        unset($tmpArr['sign']);
        ksort($tmpArr);
        $str = '';
        foreach($tmpArr as $key => $value) {
            if($value)
            {
                $str .= "$key=$value&";
            }

        }
        $str .= 'key='.$this->partner_key;

        if($arr['sign'] == $this->signMd5($str)) {
            return true;
        }
        return false;
    }

    /**
     * MD5签名
     *
     * @param string $str 待签名字符串
     * @return string 生成的签名，最终数据转换成大写
     */
    protected function signMd5($str)
    {
        $sign = md5($str);

        return strtoupper($sign);
    }
    /**
     *设置debug信息
     */
    function _setDebugInfo($debugInfo) {
        $this->debugInfo = PHP_EOL.$this->debugInfo.$debugInfo.PHP_EOL;
    }
}
?>
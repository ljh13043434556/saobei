<?php
namespace saobei;

//支付类
class Pay
{

    use baseBehavior;

    /**
     * @param $params
     *  [
     *      'merchant_no' => 商户编号
     *      'terminal_id' => 终端号
     *      'token' => 终端令牌
     *      'terminal_trace' => '' String	32	Y	终端流水号，填写商户系统的订单号
     *      'total_fee' => '' String	12	Y	金额，单位分
     *      'order_body' => '' String	128	N	订单描述
     *      'notify_url' => '' String	256	N	外部系统通知地址，必须urlencode（get请求拼接需要urlencode，签名拼接不需要urlencode）
     *      'front_url' => ''  String	256	N	前端回调地址，必须urlencode（get请求拼接需要urlencode，签名拼接不需要urlencode），不填则支付成功后不跳转
     *      'auto_pay' => ''    String	1	N	自动点击支付按钮，1自动，0或不传手动
     *      'attach' => ''  String	128	N	附加数据，原样返回
     *      'key_sign' => '' String	32	Y	签名字符串,字典序拼装所有非空参数+令牌，32位md5加密转换
     * ]
     */
    public static function wapPay($params)
    {



        $token = $params['token'];

        $data = [
            'merchant_no' => $params['merchant_no'],
            'terminal_id' => $params['terminal_id'],
            'terminal_trace' => $params['terminal_trace'],
            'terminal_time' => date('YmdHis'),
            'total_fee' => $params['total_fee'],
            'order_body' => isset($params['order_body']) ? $params['order_body'] : '',
            'notify_url' => isset($params['notify_url']) ? $params['notify_url'] : '',
            'front_url' => isset($params['front_url']) ? $params['front_url'] : '',
            'auto_pay' => isset($params['auto_pay']) ? $params['auto_pay'] : 0,
            'attach' => isset($params['attach']) ? $params['attach'] : '',
        ];


        $data = array_filter($data);

        Config::$TOKEN = $token;
        $data['key_sign'] = Config::sign($data);

        $data['notify_url'] = $data['notify_url'] ? urlencode($data['notify_url']) : $data['notify_url'];
        $data['notify_url'] = $data['notify_url'] ? urlencode($data['front_url']) : $data['notify_url'];

        $parma_str =  Config::toUrlParams($data);

        return Config::createRequestUrl('/open/wap/110/pay' . '?' . $parma_str);

    }


    /**
     * 刷卡支付，（用户出示支付码，支付）
     * @param $params
     *  [
     *      'auth_no' => String	128	Y	授权码，客户的付款码,
     *      'total_fee' => String	12	Y	金额，单位分,
     *      'sub_appid' => sub_appid	String	16	N	公众号appid,
     *      'order_body' => '' String	128	N	订单描述
     *      'attach' => String	128	N	附加数据,原样返回,
     *      'goods_detail' => String	2048	N	订单包含的商品列表信息，Json格式。pay_type为090时，可选填此字段,
     *      'goods_tag' => ''订单优惠标记，代金券或立减优惠功能的参数（字段值：cs和bld）
      * ]
     */
    public static function barcodePay($params)
    {
        $data = [
            'pay_ver' => '110',
            'pay_type' => '000',
            'service_id' => '010',
            'merchant_no' => '810000283000002',
            'terminal_id' => '30052944',
            'terminal_trace' => time(),
            'terminal_time' => date('YmdHis'),
            'auth_no' => $params['auth_no'],
            'total_fee' => $params['total_fee'],
            'sub_appid' => isset($params['sub_appid']) ? $params['sub_appid'] : '',
            'order_body' => isset($params['order_body']) ? $params['order_body'] : '',
            'attach' => isset($params['attach']) ? $params['attach'] : '',
            'goods_detail' => isset($params['goods_detail']) ? $params['goods_detail'] : '',
            'goods_tag' => isset($params['goods_tag']) ? $params['goods_tag'] : '',
        ];

        $data = array_filter($data);
        echo "数据:\r\n";
        var_dump($data);
        Config::$TOKEN = '832754adb88a48f68c681ebdbc2e442a';
        $data['key_sign'] = Config::sign($data);


        //请求接口
        $requestUrl = Config::createRequestUrl('/pay/110/barcodepay');
        echo "加签名:";
        var_dump($data);


        echo "请求URL\r\n";
        echo $requestUrl;

        $result = Curl::post($requestUrl, $data);

        var_dump($result);

        if($result->result_code == '01') {
            //支付成功
            return 1;

        } elseif($result->result_code == '02') {
            //支付失败
            throw new \Exception($result->return_msg);

        } elseif($result->result_code == '03') {
            //支付中
            return $result;

        } elseif($result->result_code == '99') {
            throw new \Exception('该条码暂不支持支付类型自动匹配');
        }

    }



    /**
     * 验证WAP支付，签名
     * @param $data
     */
    public static function dealCheckSign($data)
    {
        $data = is_array($data) ? $data[0] : $data;
        $key_sign = $data->key_sign;

        $checkField = [
            'return_code',
            'return_msg',
            'result_code',
            'pay_type',
            'user_id',
            'merchant_name',
            'merchant_no',
            'terminal_id',
            'terminal_trace',
            'terminal_time',
            'total_fee',
            'end_time',
            'out_trade_no',
            'channel_trade_no',
            'attach',
        ];

        return Config::checkSignAsync($data, $checkField, $key_sign);
    }


    /**
     * 支付成功返回给扫呗服务器信息
     */
    public static function payOk()
    {
        static::response(['return_code' => '01', 'return_msg' => 'success']);
    }


    /**
     * 支付失败返回给扫呗服务器信息
     * @param $msg
     */
    public static function payError($msg)
    {
        static::response(['return_code' => '02', 'return_msg' => $msg]);
    }


    /**
     * 支付查询
     * @param $params [
     *      'out_trade_no' => String	64	Y	订单号，查询凭据，可填利楚订单号、微信订单号、支付宝订单号、银行卡订单号任意一个
     *      'token' => 终端TOKEN
     * ]
     */
    public static function query($params)
    {
        $data = [
            'pay_ver' => '110',
            'pay_type' => '000',
            'service_id' => '020',
            'merchant_no' => '810000283000002',
            'terminal_id' => '30052944',
            'terminal_trace' => Config::uuid(),
            'terminal_time' => date('YmdHis'),
            'out_trade_no' => $params['out_trade_no'],
        ];

        Config::$TOKEN = $params['token'];


        $data = Config::ksort($data);
        $data['key_sign'] = Config::sign($data);
        var_dump($data);
        $url = Config::createRequestUrl('/pay/110/query');

        echo $url . "\r\n";


        $result = Curl::post($url, $data);

        var_dump($result);

        return $result;


    }

}
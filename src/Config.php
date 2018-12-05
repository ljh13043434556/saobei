<?php
namespace saobei;

/**
 * 参数及其他，通用功能类
 * Class Config
 * @package saobei
 */
class Config
{
    const MERCHANT_NO = '812400205000001';                      //商户号
    const TERMINAL_ID = '30051623';                             //终端号
    const TOKEN = 'fafeac8d61064ab79d1310cc14c4e5ae';           //令牌
    const SERVER_PAY_API = 'http://test.lcsw.cn:8045/lcsw';    //支付接口地址

    /**
     * 生成签名
     * @param $data
     * @return string
     */
    public static function sign(&$data)
    {
        ksort($data);
        $str = static::oUrlParams($data);
        echo 'string1 : ' . $str . "\r\n";
        $str2 = $str . '&access_token=' . static::TOKEN;
        echo 'string2 : ' . $str2 . "\r\n";
        return md5($str . '&access_token=' . static::TOKEN);
    }


    /**
     * 格式化参数格式化成url参数
     */
    public static function toUrlParams($values)
    {
        $buff = "";
        foreach ($values as $k => $v)
        {
            if($v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }


    /**
     * 返回完整的接口地址
     * @param $l
     * @return string
     */
    public static function createRequestUrl($l)
    {
        return static::SERVER_PAY_API . $l;
    }


    /**
     * 验证返回数据的签名
     * @param $data     字符串
     * @param $fields   参与验签的字段
     * @param $sign     签名
     */
    public static function checkSign($responsData, $fields, $sign)
    {
        $checkSignData = [];
        foreach($fields as $field) {
            if(isset($responsData->$field)) {
                $checkSignData[$field] = $responsData->$field;
            }
        }

        $str = static::toUrlParams($checkSignData);

        if(md5($str) == $sign) {
            return true;
        } else {
            throw new \Exception('验签失败');
        }
    }
}
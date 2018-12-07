<?php
namespace saobei;

/**
 * 参数及其他，通用功能类
 * Class Config
 * @package saobei
 */
class Config
{
    /*
        const MERCHANT_NO = '812400205000001';                              //商户号
        const TERMINAL_ID = '30051623';                                     //终端号
        这两个属性要去掉，因为他每个终端的都不一样
    */
    public static $TOKEN = 'fafeac8d61064ab79d1310cc14c4e5ae';         //令牌
    const SERVER_PAY_API = 'http://test.lcsw.cn:8045/lcsw';             //支付接口地址

    /**
     * 生成签名,参与签名的字段按键排序
     * @param $data
     * @return string
     */
    public static function sign($data, $tokenName = 'access_token')
    {
        $data = static::ksort($data);
        $str = static::toUrlParams($data);
        $str2 = $str . '&'. $tokenName .'=' . static::$TOKEN;
        return md5($str2);
    }


    /**
     * 生成签名,不排序
     * @param $data
     * @return string
     */
    public static function nSortSign($data, $tokenName='access_token')
    {
        if(is_object($data)) {
            $data = static::object2array($data);
        }
        $str = static::toUrlParams($data);
        $str2 = $str . '&'.$tokenName.'=' . static::$TOKEN;

        echo "\r\n" . $str2 . "\r\n";

        return md5($str2);
    }



    /**
     * 把数据按KEY升序，如果是对像，转为数组
     */
    public static function ksort($data)
    {
        if(is_object($data)) {
            $data_ = static::object2array($data);
        } else {
            $data_ = $data;
        }
        ksort($data_);
        return $data_;
    }


    /**
     * 把对象转为数组
     * @param $object
     * @return array
     */
    public static function object2array($object)
    {
        $data_ = [];
        foreach($object as $key => $val) {
            $data_[$key] = $val;
        }
        return $data_;

    }

    /**
     * 格式化参数格式化成url参数
     */
    public static function toUrlParams($values)
    {
        $buff = "";
        foreach ($values as $k => $v)
        {
            if(!is_array($v)){
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
     * 验证返回数据的签名  (同步数据验签) ， 感觉没必要，没去实现
     * @param $data     字符串
     * @param $fields   参与验签的字段
     * @param $sign     签名
     */
    /*public static function checkSignSync($responsData, $fields, $sign)
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
    }*/


    /**
     * 验证返回数据的签名  （异步数据验签）
     * @param $data     字符串
     * @param $fields   参与验签的字段
     * @param $sign     签名
     */
    public static function checkSignAsync($responsData, $fields, $sign)
    {
        $checkSignData = [];
        foreach($fields as $field) {
            if(isset($responsData->$field)) {
                $checkSignData[$field] = $responsData->$field;
            }
        }

        $sign_ = static::nSortSign($checkSignData);
       
        if($sign_ == $sign) {
            return true;
        } else {
            throw new \Exception('验签失败');
        }
    }


    /**
     * 生成UUID
     * @param string $prefix
     * @return string
     */
    public static function uuid($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  = substr($chars,0,8);
        $uuid .= substr($chars,8,4);
        $uuid .= substr($chars,12,4);
        $uuid .= substr($chars,16,4);
        $uuid .= substr($chars,20,12);
        return $prefix . $uuid;
    }

    
}
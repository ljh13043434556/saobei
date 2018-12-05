<?php
namespace saobei;


/**
 * 网络请求类
 * Class Curl
 * @property \Curl\Curl $curl The asset manager application component. This property is
 */
class Curl
{
    public static $curl;

    public static function getCurl()
    {
        if(empty(static::$curl)) {
            return static::$curl;
        }

        static::$curl = new \Curl\Curl();
        return static::$curl;
    }
}
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
        if(!empty(static::$curl)) {
            return static::$curl;
        }

        static::$curl = new \Curl\Curl();
        return static::$curl;
    }



    /**
     * 以JSON方式POST
     * @param $url
     * @param $data
     * @return mixed|static
     * @throws \Exception
     */
    public static function post($url, $data)
    {

        //json
        $data_string =  json_encode($data);

        $curl = static::getCurl();
        $curl->setOpt(CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
        
        $result = $curl->post($url, $data_string);

        if($result->error) {
            throw new \Exception('curl网络请求失败,请稍后再试');
        }

        $result = json_decode($result->response);

        return $result;
    }
}
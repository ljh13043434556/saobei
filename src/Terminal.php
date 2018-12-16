<?php
namespace saobei;



class Terminal
{
    /**
     *
     * @param $params
     * [
     *      merchant_no => '商户号'
     * ]
     * @return mixed|static
     * @throws \Exception
     */
    public static function add($params)
    {

        Config::$TOKEN = C('saobei_key');

        $require = [
            'merchant_no',
        ];

        $data = [
            'inst_no' => C('saobei_inst_no'),
            'trace_no' => Config::uuid(),
            'merchant_no' => '',
        ];


        foreach($require as $field) {
            if(empty($params[$field])) {
                throw new \Exception($field.'不能为空');
            }
            $data[$field] = $params[$field];
        }
    
        $data['key_sign'] = Config::sign($data, 'key');

        $url = Config::createRequestUrl('/terminal/100/add');



        $result = Curl::post($url, $data);
        
        if($result->return_code == '02') {
            throw new \Exception('请求失败,请稍候再试');
        }

        if($result->result_code == '01') {
            return $result;
        } else {
            throw new \Exception($result->return_msg);
        }

    }
}
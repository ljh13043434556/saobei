<?php
namespace saobei;



class Terminal
{
    /**
     *
     * @param $params
     * [
     *      inst_no => '机构号'
     *      merchant_no => '商户号'
     *      key =>  '机构KEY'
     * ]
     * @return mixed|static
     * @throws \Exception
     */
    public static function add($params)
    {

        if(empty($params['key'])) {
            throw new \Exception('key不能为空');
        }

        Config::$TOKEN = $params['key'];

        $require = [
            'inst_no',
            'merchant_no',
        ];

        $data = [
            'inst_no' => '',
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
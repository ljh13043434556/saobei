# saobei
扫呗


/**
     * wap支付
     */
    public function pay()
    {
        $url = Pay::wapPay([
            'terminal_trace' => time(),
            'total_fee' => 1,
            'notify_url' => C('HTTP_HOST') . '/wxsite/public/payNotify',
        ]);

        echo $url;
    }

    /**
     * 刷卡 ， （出示二维码支付）
     * @throws \Exception
     */
    public function pay2()
    {
        set_time_limit(80);
        $result_ = Pay::barcodePay([
            'total_fee' => 1,
            'auth_no' => '134525083292881732',
        ]);

        echo '返回数据:';
        var_dump($result_);


        $i=15;
        while($i>0) {
            $result = Pay::query([
                'out_trade_no' => $result_->out_trade_no
            ]);
            sleep(5);
            if($result->result_code == '01') {
                echo 'pay ok';die;
            }
            $i--;
        }

        echo 'pay error';

    }


    /**
     * 接收支付回调数据
     */
    public function payNotify()
    {
        trace(I('post.'), 'post');
        trace(I('put.'), 'put');
        Pay::payOk();
    }


    /**
     * 验签支付通知令牌
     */
    public function checksign()
    {

        $data = json_decode('[{"attach":"","channel_trade_no":"4200000210201812051300071136","end_time":"20181205201840","key_sign":"478a2b16c06e6073a5c78c511d69b3b3","merchant_name":"2018WebSdk对接专用(勿动)","merchant_no":"812400205000001","out_trade_no":"300516230021318120520183000002","pay_type":"010","receipt_fee":"1","result_code":"01","return_code":"01","return_msg":"支付成功","terminal_id":"30051623","terminal_time":"20181205201810","terminal_trace":"1544012290","total_fee":"1","user_id":"obnG9jnSlF_vh8gP7Mq7Ven6QSJ0"}]');


        $rst = Pay::dealCheckSign($data);

        var_dump($rst);

    }


    /**
     * 查询支付状态
     */
    public function query()
    {
        Pay::query([
            'out_trade_no' => '300529440021118120611272700015'
        ]);
        /**
         * {
        ["return_code"]=>
        string(2) "01"
        ["return_msg"]=>
        string(12) "支付成功"
        ["result_code"]=>
        string(2) "01"
        ["pay_type"]=>
        string(3) "010"
        ["trade_state"]=>
        string(7) "SUCCESS"
        ["merchant_name"]=>
        string(15) "ldp对接专用"
        ["merchant_no"]=>
        string(15) "810000283000002"
        ["terminal_id"]=>
        string(8) "30052944"
        ["terminal_trace"]=>
        string(10) "1544078620"
        ["terminal_time"]=>
        string(14) "20181206144340"
        ["pay_trace"]=>
        string(10) "1544063334"
        ["pay_time"]=>
        string(14) "20181206102854"
        ["total_fee"]=>
        string(1) "1"
        ["end_time"]=>
        string(14) "20181206144343"
        ["out_trade_no"]=>
        string(30) "300529440021118120610290000025"
        ["channel_trade_no"]=>
        string(28) "4200000228201812061333107479"
        ["channel_order_no"]=>
        string(30) "300529440021118120610290000025"
        ["user_id"]=>
        string(28) "ooIeqs5E9SelRgimcNhJOG_QMr78"
        ["attach"]=>
        NULL
        ["receipt_fee"]=>
        NULL
        ["key_sign"]=>
        string(32) "fdd0f01b6abe7cc4e376458dab9c4fac"
        ["typeMsg"]=>
        string(0) ""
        }
         */
    }

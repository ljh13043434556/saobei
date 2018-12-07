
#安装 php composer require beck/saobei dev-master

```
wap支付
```
    public function pay()
    {
        $url = Pay::wapPay([
            'terminal_trace' => time(),
            'total_fee' => 1,
            'notify_url' => C('HTTP_HOST') . '/wxsite/public/payNotify',
            'token' => 'fafeac8d61064ab79d1310cc14c4e5ae',
            'merchant_no' => '812400205000001',
            'terminal_id' => '30051623',
        ]);

        echo $url;
    }

```
     * 刷卡 ， （出示二维码支付）
     * @throws \Exception
```
    public function pay2()
    {

        set_time_limit(80);
        $result_ = Pay::barcodePay([
            'total_fee' => 1,
            'auth_no' => '134759414261469920',
            'token' => '832754adb88a48f68c681ebdbc2e442a'
        ]);

        echo '返回数据:';
        var_dump($result_);
        if($result_ === 1) {
            echo 'pay ok';die;
        }


        $i=15;
        while($i>0) {
            $result = Pay::query([
                'out_trade_no' => $result_->out_trade_no,
                'merchant_no' => '810000283000002',
                'terminal_id' => '30052944',
                'token' => '832754adb88a48f68c681ebdbc2e442a',
            ]);
            sleep(5);
            if($result->result_code == '01') {
                echo 'pay ok';die;
            }
            $i--;
        }

        echo 'pay error';

    }


```
     * 接收支付回调数据
```
    public function payNotify()
    {
        trace(I('post.'), 'post');
        trace(I('put.'), 'put');
        Pay::payOk();
    }


```
     * 验签支付通知令牌
```
    public function checksign()
    {

        $data = json_decode('[{"attach":"","channel_trade_no":"4200000210201812051300071136","end_time":"20181205201840","key_sign":"478a2b16c06e6073a5c78c511d69b3b3","merchant_name":"2018WebSdk对接专用(勿动)","merchant_no":"812400205000001","out_trade_no":"300516230021318120520183000002","pay_type":"010","receipt_fee":"1","result_code":"01","return_code":"01","return_msg":"支付成功","terminal_id":"30051623","terminal_time":"20181205201810","terminal_trace":"1544012290","total_fee":"1","user_id":"obnG9jnSlF_vh8gP7Mq7Ven6QSJ0"}]');

        $rst = Pay::dealCheckSign($data, 'fafeac8d61064ab79d1310cc14c4e5ae');

    }


```
     * 查询支付状态
```
    public function query()
    {
        $result = Pay::query([
            'out_trade_no' => '300529440021118120611272700015',
            'merchant_no' => '810000283000002',
            'terminal_id' => '30052944',
            'token' => '832754adb88a48f68c681ebdbc2e442a',
        ]);

        var_dump($result);

    }


```
     * 添加终端
```
    public function addTerminal()
    {
        $result = \saobei\Terminal::add([
            'inst_no' => '52100021',
            'merchant_no' => '810000283000002',
            'key' => '2d7c2a70e2cd4e33902f6215cd368400',
        ]);

        var_dump($result);
    }
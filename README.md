
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
            'token' => 'XXX',
            'merchant_no' => 'XXX',
            'terminal_id' => 'XXX',
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
            'auth_no' => 'XXX',
            'token' => 'XXXXX'
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
                'merchant_no' => 'XXXX',
                'terminal_id' => 'XXXX',
                'token' => 'XXXXX',
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

        $rst = Pay::dealCheckSign($data, 'XXXXX');

    }


```
     * 查询支付状态
```
    public function query()
    {
        $result = Pay::query([
            'out_trade_no' => 'XXXXX',
            'merchant_no' => 'XXXXX',
            'terminal_id' => 'XXXXX',
            'token' => 'XXXXX',
        ]);

        var_dump($result);

    }


```
     * 添加终端
```
    public function addTerminal()
    {
        $result = \saobei\Terminal::add([
            'inst_no' => 'XXXXX',
            'merchant_no' => 'XXXXXX',
            'key' => 'XXXXX',
        ]);

        var_dump($result);
    }



```
         * 二维码支付
```
        public function prepay()
        {
            $params = [
                'pay_type' => I('pay_type'),
                'merchant_no' => I('merchant_no'),
                'terminal_id' => I('terminal_id'),
                'terminal_trace' => I('terminal_trace'),
                'total_fee' => intval(I('total_fee')),
                'token' => I('token'),
                'notify_url' => I('notify_url')
            ];

            foreach($params as $key => $val) {
                if(empty($val)) {
                    api_return(-1, $key . '不能为空');
                }
            }

            try{
                $result = \saobei\Pay::prepay($params);
            }catch (\Exception $err) {
                api_return(-1, $err->getMessage());
            }

            api_return(1, 'ok', $result);


        }
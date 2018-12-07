<?php
namespace saobei;

//商户类
class Merchant
{
    /**
     * 创建商户
     * @param $param
     *  [
     *  'merchant_name'  String	50	Y	商户名称，扫呗系统全局唯一不可重复，最多50个汉字且不能包含特殊符号参考验重接口
     *  'merchant_alias' String	15	Y	商户简称，最多15个汉字且不能包含特殊符号
     *  'merchant_company' String	30	Y	商户注册名称/公司全称，须与营业执照名称保持一致，最多30个汉字且不能包含特殊符号
     *  'merchant_province' String	10	Y	所在省
     *  'merchant_province_code' String	3	Y	省编码,
     *  'merchant_city' String	10	Y	所在市
     *  'merchant_city_code' String	4	Y	市编码
     *  'merchant_county' String	16	Y	所在区县,
     *  'merchant_county_code' String	4	Y	所在区县编码
     *  'merchant_address' String	200	Y	商户详细地址
     *  'merchant_person' String	10	Y	商户联系人姓名
     *  'merchant_phone' String	13	Y	商户联系人电话（唯一）
     *  'merchant_email' String	50	Y	商户联系人邮箱（唯一）
     *  'business_name' String	50	Y	行业类目名称
     *  'business_code' String	4	Y	行业类目编码，由扫呗技术支持提供表格
     *  'merchant_business_type' Int	2	Y	商户类型:1企业，2个体工商户，3小微商户
     *  'account_type' String	2	Y	账户类型，1对公，2对私
     *  'settlement_type' String	2	Y	结算类型:1.法人结算 2.非法人结算
     *  'license_type'  Int	2	Y	营业证件类型：0营业执照，1三证合一，2手持身份证
     *  'account_name' String	100	Y	入账银行卡开户名（结算人姓名/公司名）
     *  'account_no' String	25	Y	入账银行卡卡号
     *  'bank_name' String	50	Y	入账银行卡开户支行
     *  'bank_no' String	25	Y	开户支行联行号，由扫呗技术支持提供表格
     *  'settle_type' String	2	Y	清算类型：1自动结算；2手动结算，
     *  'settle_amount' Int	11	Y	自动清算金额（单位分），清算类型为自动清算时有效，指帐户余额达到此值才清算。注：当前固定值为1分
     *  'merchant_id_no' 	String	30	N	结算人身份证号码
     *  'merchant_id_expire' String	8	N	结算人身份证有效期，格式YYYYMMDD，长期填写29991231
     *  'img_idcard_a' String	255	N	负责人身份证正面照片
     *  'img_idcard_b' String	255	N	负责人身份证反面照片
     *  'img_idcard_holding' String	255	N	本人手持身份证照片
     * ]
     */

    //流程 比较耗时先不实现
    /*public static function add($param = array())
    {
        $data = [
            'inst_no' => '52100021',
            'trace_no' => Config::uuid(),
            'merchant_name' => '张三丰',
            'merchant_alias' => '张三',
            'merchant_company' => '三国演义影视有限公司',
            'merchant_province' => '河北省',
            'merchant_province_code' => '130',
            'merchant_city' => '石家庄市',
            'merchant_city_code' => '1210',
            'merchant_county' => '长安区',
            'merchant_county_code' => '1211',
            'merchant_address' => '爱上了附近ask了ask发看的附件大家附件大家i去',
            'merchant_person' => '李四',
            'merchant_phone' => '13099992222',
            'merchant_email' => '13099992222@10086.com',
            'business_name' => '线上商超',
            'business_code' => '203',
            'merchant_business_type' => 3,
            'account_type' => '2',
            'settlement_type' => '2',
            'license_type' => 3,
            'account_name' => '张三丰',
            'account_no' => '6060606060606060606060',
            'bank_name' => '中国农业银行股份有限公司广安花桥支行',
            'bank_no' => '103673767189',
            'settle_type' => '2',
            'settle_amount' => 1,
            'merchant_id_no' => '431024198711223314',
            'merchant_id_expire' => '20391207',
            'img_idcard_a' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=2871906334,3944784635&fm=173&app=25&f=JPEG?w=218&h=146&s=63B511C70E553ECC37C58914030090C3',
            'img_bankcard_b' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=2871906334,3944784635&fm=173&app=25&f=JPEG?w=218&h=146&s=63B511C70E553ECC37C58914030090C3',
            'img_idcard_holding' => 'https://ss1.baidu.com/6ONXsjip0QIZ8tyhnq/it/u=2871906334,3944784635&fm=173&app=25&f=JPEG?w=218&h=146&s=63B511C70E553ECC37C58914030090C3',

        ];

        Config::$TOKEN = '2d7c2a70e2cd4e33902f6215cd368400';

        $data = Config::ksort($data);
        $data['key_sign'] = Config::sign($data, 'key');

        $url = Config::createRequestUrl('/merchant/200/add');


        var_dump($data);

        $result = Curl::post($url, $data);

        var_dump($result);

        //account_name=张三丰&account_no=6060606060606060606060&account_type=2&bank_name=中国农业银行股份有限公司广安花桥支行&bank_no=103673767189&business_code=203&business_name=线上商超&inst_no=52100021&license_type=3&merchant_address=爱上了附近ask了ask发看的附件大家附件大家i去&merchant_alias=张三&merchant_business_type=3&merchant_city=石家庄市&merchant_city_code=1210&merchant_company=三国演义影视有限公司&merchant_county=长安区&merchant_county_code=1211&merchant_email=13099992222@10086.com&merchant_name=张三丰&merchant_person=李四&merchant_phone=13099992222&merchant_province=河北省&merchant_province_code=130&settle_amount=1&settle_type=2&settlement_type=2&trace_no=1544089128&key=2d7c2a70e2cd4e33902f6215cd368400
        //account_name=张三丰&account_no=6060606060606060606060&account_type=2&bank_name=中国农业银行股份有限公司广安花桥支行&bank_no=103673767189&business_code=203&business_name=线上商超&inst_no=52100021&license_type=3&merchant_address=爱上了附近ask了ask发看的附件大家附件大家i去&merchant_alias=张三&merchant_business_type=3&merchant_city=石家庄市&merchant_city_code=1210&merchant_company=三国演义影视有限公司&merchant_county=长安区&merchant_county_code=1211&merchant_email=13099992222@10086.com&merchant_name=张三丰&merchant_person=李四&merchant_phone=13099992222&merchant_province=河北省&merchant_province_code=130&settle_amount=1&settle_type=2&settlement_type=2&trace_no=1544089385&access_token=2d7c2a70e2cd4e33902f6215cd368400


    }*/


    



}
<?php
namespace saobei;

trait baseBehavior
{
    /**
     * 返回数据
     * @param $data
     */
    public static function response($data)
    {
        header("content:application/json;chartset=uft-8");
        echo json_encode($data);
        exit();
    }
}
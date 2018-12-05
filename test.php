<?php
header("Content-type: text/html; charset=utf-8");
require_once __DIR__ . '/vendor/autoload.php';
$param = [
    'terminal_trace' => time(),
    'total_fee' => 1,
];
echo \saobei\Config::SERVER_PAY_API . '?' . \saobei\Pay::wapPay($param);
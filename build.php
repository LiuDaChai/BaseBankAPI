<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],

    // 定义 index 模块的自动生成
    'test'     => [
        '__dir__'    => ['controller', 'view'],
        'controller' => ['Index'],
        'view'      => ['index/index','index/left','index/open_account','index/close_account','index/get_balance','index/withdraw_money','index/deposit_money','index/transfer_money'],
    ],

    // 定义 BankAPI 模块的自动生成
    'bankapi'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['controller', 'model'],
        'controller' => ['Account', 'Deal'],
        'model'      => ['UserList', 'AccountList', 'AccountInfo', 'AccountTransferLimit', 'DealList', 'TransferList'],
    ],
    // 其他更多的模块定义
];

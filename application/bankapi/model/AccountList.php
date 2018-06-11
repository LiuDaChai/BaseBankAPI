<?php
namespace app\bankapi\model;

use think\Model;

class AccountList extends Model
{
    // 定义自动完成的属性
    protected $insert = ['add_time','status'=>0];

    protected function setAddTimeAttr()
    {
        return time();
    }
}
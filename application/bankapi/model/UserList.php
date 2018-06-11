<?php
namespace app\bankapi\model;

use think\Model;

class UserList extends Model
{
    // 定义自动完成的属性
    protected $insert = ['add_time'];

    protected function setAddTimeAttr()
    {
        return time();
    }
}
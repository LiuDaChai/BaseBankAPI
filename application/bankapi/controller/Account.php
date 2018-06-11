<?php
namespace app\bankapi\controller;

// 基础
use think\Controller;

// 使用模型
use app\bankapi\model\UserList as UserListModel;
use app\bankapi\model\AccountList as AccountListModel;
use app\bankapi\model\AccountInfo as AccountInfoModel;

class Account extends Controller
{
    // Open account
    public function openAccount()
    {
        // 获取提交数据
        $user_name = $this->request->post('user_name');     // 所有者名称

        // 检查所有者是否存在，如果所有者不存在（user_id == 0），新增所有者信息
        $user = UserListModel::get(['user_name' => $user_name]);
        if (empty($user)) {
            $user = new UserListModel();
            $user->user_name = $user_name;
            $user->save();
        }

        // 新建所有者失败
        if ($user->id == 0) {
            return failed('创建失败');
        }

        // 添加账户信息（开户）
        $account = new AccountListModel();
        $account->user_id = $user->id;
        $account->save();

        // 添加账户信息失败
        if ($account->id == 0) {
            return failed('创建失败');
        }

        // 添加账户基础属性
        $account_info = new AccountInfoModel();
        $account_info->account_id = $account->id;
        $account_info->transfer_limit = 10000;       // 设置转账限额，默认 10000
        $account_info->save();

        // 添加账户属性失败
        if ($account_info->id == 0) {
            return failed('创建失败');
        }

        // 开户完成，返回成功消息
        return success('创建成功', array('account_id'=>$account->id));

    }

    // Close account
    public function closeAccount()
    {
        // 获取提交数据
        $account_id = $this->request->post('account_id');     // 帐号ID

        // 获取帐号信息，如果帐号不存在且已经注销，返回失败
        $account = AccountListModel::get($account_id);
        if (empty($account) || $account->status == 1) {
            return failed('帐号不存在');
        }

        // 存在则注销帐号，修改帐号的状态（status）为 1
        $account->status = 1;
        $account->save();

        return success();
    }

    // Get current balance
    public function getBalance()
    {
        // 获取提交数据
        $account_id = $this->request->post('account_id');     // 帐号ID

        // 获取帐号信息，如果帐号不存在且已经注销，返回失败
        $account = AccountListModel::get($account_id);
        if (empty($account) || $account->status == 1) {
            return failed('帐号不存在');
        }

        // 存在则查询帐号的数据
        $account_info = AccountInfoModel::get(['account_id' => $account->id]);

        // 获取信息失败，返回失败
        if ($account_info->id == 0) {
            return failed('无账户数据');
        }

        return success('查询成功', array('balance'=>$account_info->balance));
    }

}
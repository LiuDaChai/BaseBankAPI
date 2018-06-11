<?php
namespace app\bankapi\controller;

// 基础
use think\Controller;
use think\Db;

// 使用模型
use app\bankapi\model\AccountList as AccountListModel;
use app\bankapi\model\AccountInfo as AccountInfoModel;
use app\bankapi\model\AccountTransferLimit as AccountTransferLimitModel;

class Deal extends Controller
{
    // Withdraw money
    public function withdrawMoney()
    {
        // 获取提交数据
        $account_id = $this->request->post('account_id');     // 帐号ID
        $amount = round($this->request->post('amount'), 2);   // 取款金额，控制在分

        // 获取帐号信息，如果帐号不存在且已经注销，返回失败
        $account = AccountListModel::get($account_id);
        if (empty($account) || $account->status == 1) {
            return failed('帐号不存在');
        }

        // 存在则查询帐号的数据
        $account_info = AccountInfoModel::get(['account_id' => $account->id]);

        // 获取信息失败，返回失败
        if (empty($account_info)) {
            return failed('无账户数据');
        }

        // 如果余额（balance）不足，返回失败
        if ($account_info->balance < $amount) {
            return failed('余额不足');
        }

        // 更新账户余额
        Db::startTrans();
        try{
            // 使用事务对金额进行修改
            Db::table('account_info')
                ->where('account_id', $account->id)
                ->where('balance', '>=', $amount)
                ->setDec('balance', $amount);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return failed('取款失败');
        }

        return success();
    }

    // Deposit money
    public function depositMoney()
    {
        // 获取提交数据
        $account_id = $this->request->post('account_id');     // 帐号ID
        $amount = round($this->request->post('amount'), 2);   // 存款金额，控制在分

        // 获取帐号信息，如果帐号不存在且已经注销，返回失败
        $account = AccountListModel::get($account_id);
        if (empty($account) || $account->status == 1) {
            return failed('帐号不存在');
        }

        // 存在则查询帐号的数据
        $account_info = AccountInfoModel::get(['account_id' => $account->id]);

        // 获取信息失败，返回失败
        if (empty($account_info)) {
            return failed('无账户数据');
        }

        // 更新账户余额
        Db::startTrans();
        try{
            // 使用事务对金额进行修改
            Db::table('account_info')
                ->where('account_id', $account->id)
                ->setInc('balance',$amount);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return failed('存款失败');
        }

        return success();
    }

    // Transfer money
    public function transferMoney()
    {
        // 获取提交数据
        $transfer_account_id = $this->request->post('transfer_account_id');                 // 转出帐号ID
        $transfer_into_account_id = $this->request->post('transfer_into_account_id');       // 转入帐号ID
        $amount = round($this->request->post('amount'), 2);                                 // 存款金额，控制在分

        // 获取转出帐号信息，如果帐号不存在且已经注销，返回失败
        $transfer_account = AccountListModel::get($transfer_account_id);
        if (empty($transfer_account) || $transfer_account->status == 1) {
            return failed('转出帐号不存在');
        }

        // 获取转出帐号信息，如果帐号不存在且已经注销，返回失败
        $transfer_into_account = AccountListModel::get($transfer_into_account_id);
        if (empty($transfer_into_account) || $transfer_into_account->status == 1) {
            return failed('转入帐号不存在');
        }

        // 存在则查询帐号的数据
        $transfer_account_info = AccountInfoModel::get(['account_id' => $transfer_account->id]);

        // 获取信息失败，返回失败
        if (empty($transfer_account_info)) {
            return failed('无转出账户数据');
        }

        // 存在则查询帐号的数据
        $transfer_into_account_info = AccountInfoModel::get(['account_id' => $transfer_into_account->id]);

        // 获取信息失败，返回失败
        if (empty($transfer_into_account_info)) {
            return failed('无转入账户数据');
        }

        // 获取账户转账限额情况
        $account_transfer_limit = AccountTransferLimitModel::get(['account_id'=>$transfer_account->id, 'limit_date'=>date('Y-m-d')]);
        if (empty($account_transfer_limit)) {
            $account_transfer_limit = new AccountTransferLimitModel();
            $account_transfer_limit->account_id = $transfer_account->id;
            $account_transfer_limit->remain_limit = $transfer_account_info->transfer_limit;
            $account_transfer_limit->limit_date = date('Y-m-d');
            $account_transfer_limit->save();
        }

        // 每日限额不足够进行转账
        if ($account_transfer_limit->remain_limit < $amount) {
            return failed('超出每日限额');
        }

        // 判断帐号是否是同一个所有者，如果不是，需要进行请求校验，并支付手续费 100 美元
        $all_amount = $amount;
        if ($transfer_account->user_id != $transfer_into_account->user_id) {
            // 请求转账授权
            if (!self::handyAPI()) {
                return failed('授权失败');
            }

            $all_amount += 100;
        }

        // 账户余额不足够进行转账操作
        if ($transfer_account_info->balance < $all_amount) {
            return failed('余额不足');
        }

        // 更新账户余额
        Db::startTrans();
        try{
            // 使用事务对金额进行修改

            // 判断帐号是否是同一个所有者
            if ($transfer_account->user_id != $transfer_into_account->user_id) {
                Db::table('account_info')
                    ->where('account_id', $transfer_account->id)
                    ->where('balance', '>=', $all_amount)
                    ->setDec('balance', $all_amount);
            }else{
                // 扣除转出账户
                Db::table('account_info')
                    ->where('account_id', $transfer_account->id)
                    ->where('balance', '>=', $amount)
                    ->setDec('balance', $amount);
            }

            // 存入转入账户
            Db::table('account_info')
                ->where('account_id', $transfer_into_account->id)
                ->setInc('balance', $amount);

            // 修改限额
            Db::table('account_transfer_limit')
                ->where('account_id', $transfer_account->id)
                ->setDec('remain_limit', $amount);

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            return failed('转账失败');
        }

        return success();
    }

    /**
     * 请求 handy 接口(独立成为功能)
     * @return boolean        返回结果
     */
    private static function handyAPI()
    {
        // 请求地址
        $url = 'http://handy.travel/test/success.json';
        $content = file_get_contents($url);

        // 解析结果
        $request_data = json_decode($content, true);

        // 返回请求结果
        return $request_data['status']=='success'?true:false;
    }
}
<?php
namespace app\test\controller;

// 基础
use think\Controller;

class Index extends Controller
{
    // 控制页面Main
    public function index()
    {
        return $this->fetch();
    }

    // 左侧菜单
    public function left()
    {
        return $this->fetch();
    }

    // Open account
    public function openAccount()
    {
        return $this->fetch();
    }

    // Close account
    public function closeAccount()
    {
        return $this->fetch();
    }

    // Get current balance
    public function getBalance()
    {
        return $this->fetch();
    }

    // Withdraw money
    public function withdrawMoney()
    {
        return $this->fetch();
    }

    // Deposit money
    public function depositMoney()
    {
        return $this->fetch();
    }

    // Transfer money
    public function transferMoney()
    {
        return $this->fetch();
    }
}

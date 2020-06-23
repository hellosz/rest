<?php
/**
 * 用户资源
 *
 * @desc   PhpStorm
 * @author Chris
 * @time   2020/6/23 0:04
 */


class User
{

    public function register($name, $password)
    {
        if (empty($name) || empty($password)) {
            throw new \Exception('用户名或者密码不能为空', USERNAME_AND_PASSWORD_CAN_NOT_NULL);
        }
    }

    public function login()
    {
        
    }
}
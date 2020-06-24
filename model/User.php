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
    /**
     * 用户注册
     *
     * @return array
     * @throws Exception
     */
    public function register()
    {
        // 参数验证
        if (!Request::has('name') || !Request::has('password')) {
            throw new \Exception('用户名或者密码不能为空', USERNAME_AND_PASSWORD_CAN_NOT_NULL);
        }

        // 获取参数
        $name = Request::get('name');
        $password = md5(Request::get('password') . PASSWORD_SALT);

        // 创建账号
        try {
            // 创建用户
            $conn = Connection::getInstance();
            $sql = "insert into user(`username`, `password`) values(:username, :password)";
            $stat = $conn->prepare($sql);
            $stat->bindParam(':username', $name);
            $stat->bindParam(':password', $password);
            $stat->execute();
        } catch (PDOException $e) {
            throw new \Exception('创建用户账号失败', CREAT_ACCOUNT_FAILED);
        }

        // 返回结果
        return [
            'id' => $conn->lastInsertId(),
            'username' => $name,
            'create_at' => date('Y-m-d H:i:s')
        ];
    }

    public function login()
    {
        // 参数验证
        if (!Request::has('name') || !Request::has('password')) {
            throw new \Exception('用户名或者密码不能为空', USERNAME_AND_PASSWORD_CAN_NOT_NULL);
        }

        // 获取参数
        $name = Request::get('name');
        $password = md5(Request::get('password') . PASSWORD_SALT);

        // 和数据库比对
        $conn = Connection::getInstance();
        $sql = "select * from `user` where `username` = :username and `password` = :password";
        $stat = $conn->prepare($sql);
        $stat->bindParam(':username', $name);
        $stat->bindParam(':password', $password);
        $stat->execute();
        if ($stat->rowCount() > 0) {
            $user = $stat->fetch(PDO::FETCH_ASSOC);
            return [
                'id' => $user['id'],
                'username' => $user['username']
            ];
        }

        throw new \Exception('登录失败：账号或者密码错误！', 003);
    }
}
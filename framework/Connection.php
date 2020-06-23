<?php
/**
 * @desc   PhpStorm
 * @author Chris
 * @time   2020/6/23 0:03
 */
class Connection
{
    /**
     * 数据库连接实例
     * @var
     */
    private static $instance;

    /**
     * 私有化构造函数
     * Connection constructor.
     */
    private function __construct(){}

    /**
     * 获取实例数据库实例
     *
     * @return PDO
     */
    public static function getInstance()
    {
        // 单例模式
        if (isset(self::$instance)) {
            return self::$instance;
        }

        // 创建数据库连接实例
        require_once '../config/db.php';
        $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);// 创建数据库连接
        $conn->exec('set names utf8');// 设置字符集
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // 设置异常类型

        // 返回实例
        return self::$instance = $conn;
    }
}
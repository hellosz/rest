<?php
/**
 * 请求对象
 *
 * Created by PhpStorm.
 * User: Neng.Tian
 * Date: 2020/6/23
 * Time: 13:47
 */


class Request
{
    /**
     * 保存属性
     *
     * @var array
     */
    private static $attributes = [];

    /**
     * Request constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        self::$attributes = (array)$attributes;
    }

    public function get(string $key)
    {
        return self::$attributes[$key] ?? false;
    }

    public function set(string $key, $val)
    {
        self::$attributes[$key] = $val;
        return true;
    }

    public function has(string $key)
    {
        if (isset(self::attributes[$key])) {
            return true;
        }

        return false;
    }

    public function __call($method, $params)
    {
        if (method_exists(self, $method)) {
            call_user_func([new self(), $method], $params);
        }
    }

    public static function __callStatic($method, $params)
    {
        // 调用Call方法
        call_user_func([new self(), $method], $params);
    }
}
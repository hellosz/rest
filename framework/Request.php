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

    /**
     * 获取属性值
     *
     * @param string $key
     * @return bool|mixed
     */
    public static function get(string $key)
    {
        return self::$attributes[$key] ?? false;
    }

    /**
     * 设置属性值
     *
     * @param string $key
     * @param $val
     * @return bool
     */
    public static function set(string $key, $val)
    {
        self::$attributes[$key] = $val;
        return true;
    }

    /**
     * 判断属性是否存在
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key)
    {
        if (isset(self::$attributes[$key])) {
            return true;
        }

        return false;
    }

    /**
     * 添加多个属性
     *
     * @param array $arr
     * @return array
     */
    public static function append(array $arr)
    {
        return self::$attributes = array_merge(self::$attributes, $arr);
    }

    /**
     * 获取所有属性
     *
     * @return array
     */
    public static function getAll()
    {
        return self::$attributes;
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
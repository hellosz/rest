<?php
/**
 * @desc   PhpStorm
 * @author Chris
 * @time   2020/6/23 0:05
 */

class Bootstrap
{
    /**
     * 支持的HTTP方法
     *
     * @var array
     */
    private $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * 请求资源Uri
     *
     * @var
     */
    private $requestUri;

    /**
     * 请求方法
     *
     * @var
     */
    private $requestMethod;

    /**
     * 请求api版本
     *
     * @var
     */
    private $requestVersion;

    /**
     * 请求参数
     *
     * @var
     */
    private $parameters;

    /**
     * 常用的错误码
     *
     * @var array
     */
    private static $errorCodes = [
        200 => 'OK',
        203 => 'No Content',
        304 => 'Not Modified',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Server Internal Error',
    ];


    /**
     * 启动框架
     */
    public function boot()
    {

        try {// 解析Uri
            $this->getRequestMethod();// 解析请求资源
            $this->getRequestUri();// 解析请求方法
            $this->getRequestResource();// 获取路由和参数
            $this->permanentRequest(); // 持久化请求信息
            list($resouce, $function) = $this->resoleRoutes();// 调用映射方法
            $res    = $this->dispatch($resouce, $function);
        } catch (Exception $e) {
            $this->responseFail($e->getMessage(), $e->getCode());
        }

        // 渲染返回结果
        $this->responseSuccess($res, '请求成功');
    }

    /**
     * 获取并验证请求方法
     *
     * @throws Exception
     */
    private function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (!in_array($method, $this->allowedMethods)) {
            throw new \Exception('请求方法不被允许', 405);
        }
        $this->requestMethod = $method;
    }

    /*
     * 获取请求Uri
     */
    private function getRequestUri()
    {
        // 获取请求信息
        $requestUri = $_SERVER['REQUEST_URI'];

        // 解析
        $resource = explode('/', trim($requestUri, '/'));
        // 设置版本号
        $this->requestVersion = array_shift($resource);

        // 获取请求rui
        if ($this->requestMethod == METHOD_POST) {
            $this->requestUri = implode('/', $resource); // post支持两个参数 resource/action
        } else {
            $this->requestUri = $resource[0]; // resource/parameter
        }
    }

    /**
     * 获取请求资源
     */
    private function getRequestResource()
    {
        if ($this->requestMethod == 'POST') {
            $body = file_get_contents('php://input');
            $this->parameters = json_decode($body, true);
        } else {
            // 获取请求信息
            $requestUri = $_SERVER['REQUEST_URI'];

            // 解析uri
            $resource = explode('/', trim($requestUri, '/'));
            $this->parameters = $resource[2];
        }
    }

    /**
     * 将用户请求相关信息持久化
     */
    public function permanentRequest()
    {
        Request::append(array_merge([
            'uri' => $this->requestUri,
            'method' => $this->requestMethod,
            'version' => $this->requestVersion,
        ], $this->parameters));
    }

    /**
     * 解析路由映射关系
     */
    private function resoleRoutes()
    {
        // 加载路由映射文件
        $routesMap = require_once('../config/routes.php');

        // 找到路由
        $action = '';
        foreach ($routesMap as $route) {
            if ($route['method'] == $this->requestMethod && $route['uri'] == $this->requestUri) {
                $action = $route['action'];
                break;
            }
        }
        // 找不到映射资源
        if (!$action) {
            throw new Exception('找不到资源',404);
        }

        // 返回资源
        return explode('@', $action);
    }

    /**
     * 调用路由
     *
     * @param $resource
     * @param $function
     * @return mixed
     * @throws Exception
     */
    private function dispatch($resource, $function)
    {
        if (class_exists($resource) && method_exists($resource, $function)) {
            // 创建实例，调用该方法
            return call_user_func_array([new $resource, $function], $this->parameters);
        }

        throw new \Exception('未知请求！', 404);

    }

    /**
     * 返回成功信息
     *
     * @param array|string $data  用户自定义返回值
     * @param string $message  成功信息
     * @param int $code 成功码
     * @param string $status 状态提示
     */
    private function responseSuccess($data, $message, $code = 200, $status = 'success')
    {
        $data = $data ?? [];
        $this->response($data, $message, $code, $status);
    }

    /**
     * 返回错误信息
     *
     * @param string $message  错误信息
     * @param int $code int 错误码
     * @param string $status 状态提示
     */
    private function responseFail($message, $code = 400, $status = 'fail')
    {
        $this->response([], $message, $code, $status);
    }

    /**
     * 返回json格式类型的结果
     *
     * @param array|string $data  返回值
     * @param string $message  提示消息
     * @param int $code  状态码
     * @param string $status  成功/失败提示
     */
    private function response($data, $message, $code, $status)
    {
        // 设置响应行
        if ($code > 200) {
            header('HTTP/1.1 ' . $code . ' ' . self::$errorCodes[$code]);
        }

        // 设置响应头
        header('Content-Type:application/json;charset=utf8');

        // 设置响应体
        echo json_encode([
            'message' => $message,
            'status' => $status,
            'data' => $data
        ]);die;
    }
}
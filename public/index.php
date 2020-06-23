<?php
/**
 * rest框架入口文件
 *
 * @desc   PhpStorm
 * @author Chris
 * @time   2020/6/23 0:05
 */

require_once '../framework/Connection.php';
require_once '../framework/Bootstrap.php';
require_once '../config/constant.php';
require_once '../model/User.php';
require_once '../model/Article.php';
require_once '../framework/Request.php';

// 设置错误级别
error_reporting(E_ALL &~E_NOTICE &~E_DEPRECATED);

// 启动框架
$bootstrap = new Bootstrap();
$bootstrap->boot();
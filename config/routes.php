<?php
/**
 * 路由配置
 * Created by PhpStorm.
 * User: Neng.Tian
 * Date: 2020/6/23
 * Time: 9:53
 */
 
return [
    // 用户模块
   ['method' => 'POST', 'uri' => 'users/register', 'action' => 'User@register'],
   ['method' => 'POST', 'uri' => 'users/login', 'action' => 'User@login'],
    // 文章模块
   ['method' => 'GET', 'uri' => 'articles', 'action' => 'Article@show'],
   ['method' => 'PUT', 'uri' => 'articles', 'action' => 'Article@update'],
   ['method' => 'POST', 'uri' => 'articles', 'action' => 'Article@create'],
   ['method' => 'DELETE', 'uri' => 'articles', 'action' => 'Article@destory'],
];
<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-21
 * Time: 11:08
 */
namespace wechat\middleware;

use sinri\ark\web\ArkRequestFilter;

class CoreMiddleware extends ArkRequestFilter
{

    public function shouldAcceptRequest($path, $method, $params, &$preparedData = null, &$responseCode = 200, &$error = null)
    {
        return true;
    }

    public function filterTitle()
    {
        return "CoreAuth";
    }
}
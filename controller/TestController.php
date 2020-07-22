<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-21
 * Time: 11:37
 */
namespace wechat\controller;

use EasyWeChat\Factory;
use sinri\ark\web\implement\ArkWebController;
use wechat\toolkit\Helper;

class TestController extends ArkWebController
{
    protected $app;

    public function __construct()
    {
        parent::__construct();

        $wechat_config = [
            'app_id' => Helper::config(['wechat', 'app_id']),
            'app_secret' => Helper::config(['wechat', 'app_secret']),
            'token' => Helper::config(['wechat', 'token']),
            'response_type' => 'array',
        ];
        $this->app = Factory::officialAccount($wechat_config);
    }


    public function server()
    {
        $this->app->server->serve()->send();
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-21
 * Time: 11:37
 */
namespace wechat\controller;

use EasyWeChat\Factory;
use Exception;
use sinri\ark\web\implement\ArkWebController;
use wechat\toolkit\Helper;

class TestController extends ArkWebController
{
    protected $app;
    protected $logger;

    public function __construct()
    {
        parent::__construct();
        $this->logger = Helper::logger(__CLASS__);

        $wechat_config = [
            'app_id' => Helper::config(['wechat', 'app_id']),
            'app_secret' => Helper::config(['wechat', 'app_secret']),
            'token' => Helper::config(['wechat', 'token']),
            'aes_key' => Helper::config(['wechat', 'aes_key']),


            'log' => [
                'level' => 'debug',
                'file'  => __DIR__  . '/../log/log-easywechat'. date('Y-m-d') . '.log',
            ],
        ];
        $this->app = Factory::officialAccount($wechat_config);
    }


    public function server()
    {
        try {

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->app->server->serve()->send();
            } else {
                $message = $this->app->server->getMessage();
                $this->logger->info("接收到消息", $message);

                $this->app->server->push(function ($message) {
                    switch ($message['MsgType']) {
                        case 'event':
                            return '收到事件消息';
                            break;
                        case 'text':
                            return '收到文字消息';
                            break;
                        case 'image':
                            return '收到图片消息';
                            break;
                        case 'voice':
                            return '收到语音消息';
                            break;
                        case 'video':
                            return '收到视频消息';
                            break;
                        case 'location':
                            return '收到坐标消息';
                            break;
                        case 'link':
                            return '收到链接消息';
                            break;
                        case 'file':
                            return '收到文件消息';
                        // ... 其它消息
                        default:
                            return '收到其它消息';
                            break;
                    }
                });
                $this->app->server->serve()->send();
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

}
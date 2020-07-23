<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-21
 * Time: 11:37
 */
namespace wechat\controller;

use EasyWeChat\Factory;
use sinri\ark\core\ArkLogger;
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
        $logger = new ArkLogger(__DIR__ . '/../log', __CLASS__);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->app->server->serve()->send();
        } else {
            $message = $this->app->server->getMessage();
            $this->handleMessage($message);
            $logger->log(LOG_INFO, "message:" , $message);
        }
    }

    public function handleMessage($message)
    {
        switch ($message->MsgType) {
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
            // ... 其它消息
            default:
                return '收到其它消息';
                break;
        }

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-21
 * Time: 11:37
 */
namespace wechat\controller;

use EasyWeChat\OfficialAccount\Application;
use Exception;
use sinri\ark\database\exception\ArkPDOExecuteFailedError;
use sinri\ark\database\exception\ArkPDOExecuteNotAffectedError;
use sinri\ark\web\exception\ArkWebRequestFailed;
use sinri\ark\web\implement\ArkWebController;
use wechat\library\MessageLibrary;
use wechat\model\WishModel;
use wechat\toolkit\Helper;

class WechatServerController extends ArkWebController
{
    protected $app;
    protected $logger;
    protected $lib;

    public function __construct()
    {
        parent::__construct();
        $this->logger = Helper::logger('controller');
        $this->lib = new MessageLibrary();

        $wechat_config = [
            'app_id' => Helper::config(['wechat', 'app_id']),
            'secret' => Helper::config(['wechat', 'app_secret']),
            'token' => Helper::config(['wechat', 'token']),
            'aes_key' => Helper::config(['wechat', 'aes_key']),

            'log' => [
                'level' => 'debug',
                'file'  => __DIR__  . '/../log/log-easywechat-'. date('Y-m-d') . '.log',
            ],
        ];
        $this->app = new Application($wechat_config);
    }


    public function server()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->app->server->serve()->send();
            } else {
                $message = $this->app->server->getMessage();
                $this->logger->info("接收到消息", $message);

                $this->handleMessage();
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    private function handleMessage()
    {
        $this->app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return $this->lib->handleTextMessage($message);
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


    public function getFollowerList()
    {
        try {
            $followers = $this->app->user->list();
            $this->_sayOK($followers);
        } catch (Exception $exception) {
            throw new ArkWebRequestFailed($exception->getMessage());
        }
    }

    public function currentMenu()
    {
        try {
            $current_menu = $this->app->menu->current();
            $this->_sayOK($current_menu);
        } catch (Exception $exception) {
            throw new ArkWebRequestFailed($exception->getMessage());
        }
    }

    public function getUserInfo()
    {
        try {
            $open_id = $this->_readRequest('open_id', '');
            $user_info = $this->app->user->get($open_id);
            $this->_sayOK($user_info);
        } catch (Exception $exception) {
            $this->_sayFail($exception->getMessage());
        }
    }

    public function getServerIp()
    {
        try {
            $IP = $this->app->base->getValidIps();
            $this->_sayOK($IP);
        } catch (Exception $exception) {
            throw new ArkWebRequestFailed($exception->getMessage());
        }
    }

    public function saveWish()
    {
        try {
            $wish = $this->_readRequest('wish', '');
            $ip = $this->_readRequest('ip', '');

            $insert_data = [
                'content' => trim($wish),
                'ip' => $ip,
                'create_time' => WishModel::now()
            ];
            Helper::db()->executeInTransaction(function () use ($insert_data) {
                try {
                    (new WishModel())->insert($insert_data);
                } catch (ArkPDOExecuteFailedError| ArkPDOExecuteNotAffectedError $e) {
                    throw new Exception('保存祝福失败:' . $e->getMessage());
                }
            });
            $this->_sayOK();
        } catch (Exception $exception) {
            throw new ArkWebRequestFailed($exception->getMessage());
        }
    }

}
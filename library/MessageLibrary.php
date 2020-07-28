<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-27
 * Time: 17:13
 */
namespace wechat\library;

use Exception;
use sinri\ark\io\curl\ArkCurl;

class MessageLibrary
{
    public function handleTextMessage($message)
    {
        if ($message['MsgType'] !== 'text') {
            throw new Exception("Error message type: " . $message['MsgType']);
        }

        if (!empty($message['Content']) && explode(' ', $message['Content'])[0] === '翻译') {
            return $this->translateMessage($message['Content']);
        }
        return $message['Content'];
    }

    /**
     * 金山词霸翻译
     * @param $message
     * @return mixed
     */
    protected function translateMessage($message)
    {
        $api_url = 'http://fy.iciba.com/ajax.php?a=fy&f=auto&t=auto&w=' . $message;
        $result = (new ArkCurl())->prepareToRequestURL("GET", $api_url)->execute();
        $out = json_decode($result, true)['content']['out'];
        return $out;
    }









}
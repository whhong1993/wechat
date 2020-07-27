<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-27
 * Time: 17:13
 */
namespace wechat\library;

use EasyWeChat\Kernel\Messages\Text;
use sinri\ark\io\curl\ArkCurl;

class MessageLibrary
{
    public function handleTextMessage(Text $message)
    {
        if ($message->getType() !== 'text') {
            throw new \Exception("Error message type: " . $message->getType());
        }

        if (!empty($message->get('content')) && explode(' ', $message->get('content')[0] === '翻译')) {
            return $this->translateMessage($message->get('content'));
        }
        return $message->get('content');
    }

    protected function translateMessage($message)
    {
        $api_url = 'http://fy.iciba.com/ajax.php?a=fy&f=auto&t=auto&w=' . $message;
        $result = (new ArkCurl())->prepareToRequestURL("GET", $api_url)->execute();
        $out = json_decode($result, true)['content']['out'];
        return $out;
    }







}
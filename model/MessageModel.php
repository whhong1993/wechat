<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-24
 * Time: 15:31
 */
namespace wechat\model;


class MessageModel extends WechatBaseDataModel
{

    public function mappingTableName()
    {
        return 'message';
    }
}
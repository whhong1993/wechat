<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-24
 * Time: 17:17
 */
namespace wechat\model;

class FollowerModel extends WechatBaseDataModel
{
    public function mappingTableName()
    {
        return 'follower';
    }
}
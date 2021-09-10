<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2021/9/9
 * Time: 17:38
 */
namespace wechat\model;


use wechat\toolkit\WechatBaseDataModel;

class WishModel extends WechatBaseDataModel
{

    public function mappingTableName(): string
    {
        return 'wish';
    }
}
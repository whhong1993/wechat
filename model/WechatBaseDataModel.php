<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-28
 * Time: 15:28
 */
namespace wechat\model;

use sinri\ark\database\model\ArkDatabaseTableModel;
use wechat\toolkit\Helper;

abstract class WechatBaseDataModel extends ArkDatabaseTableModel
{
    public function db()
    {
        return Helper::db();
    }
}
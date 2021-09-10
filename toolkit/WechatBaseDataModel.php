<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-28
 * Time: 15:28
 */

namespace wechat\toolkit;

use sinri\ark\database\model\ArkDatabaseTableCoreModel;
use sinri\ark\database\pdo\ArkPDO;

abstract class WechatBaseDataModel extends ArkDatabaseTableCoreModel
{
    public function db(): ArkPDO
    {
        return Helper::db();
    }

    public function mappingSchemeName()
    {
        return null;
    }

}
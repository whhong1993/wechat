<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-24
 * Time: 15:31
 */
namespace wechat\model;

use sinri\ark\database\model\ArkDatabaseTableModel;

class MessageModel extends ArkDatabaseTableModel
{
    public function db()
    {
        return '';
    }

    public function mappingTableName()
    {
        return 'message';
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-21
 * Time: 11:17
 */
namespace wechat\toolkit;

use Exception;
use Psr\Log\LogLevel;
use sinri\ark\core\ArkHelper;
use sinri\ark\core\ArkLogger;
use sinri\ark\database\pdo\ArkPDO;
use sinri\ark\database\pdo\ArkPDOConfig;

class Helper
{

    /**
     * @var array
     */
    protected static $configPool;

    /**
     * @param array $keyChain
     * @param null $default
     * @param string $aspect
     * @return mixed
     */
    public static function config($keyChain, $default = null, $aspect = 'config')
    {
        if (empty(self::$configPool)) {
            self::$configPool = [];
        }

        if (empty(self::$configPool[$aspect])) {
            $config = [];
            /** @noinspection PhpIncludeInspection */
            require __DIR__ . '/../config/' . $aspect . '.php';
            self::$configPool[$aspect] = $config;
        }

        return ArkHelper::readTarget(self::$configPool[$aspect], $keyChain, $default);
    }

    /**
     * @var ArkPDO[]
     */
    protected static $databasePool = [];

    /**
     * @param string $name
     * @param string $aspect
     * @return mixed|ArkPDO
     * @throws Exception
     */
    public static function db($name = 'default', $aspect = 'config')
    {

        if ($name === null) {
            $name = self::config(['database', 'default'], 'default', $aspect);
        }
        if (empty(self::$databasePool)) {
            self::$databasePool = [];
        }
        if (empty(self::$databasePool[$name])) {
            $pdoInfo = self::config(['database', 'list', $name], null, $aspect);
            if (!$pdoInfo) {
                throw new Exception("PDO config not defined");
            }
            $db = new ArkPDO(new ArkPDOConfig($pdoInfo));// this would throw Exception when error
            try {
                $db->connect();
            } catch (Exception $exception) {
                if (ArkHelper::isCLI()) {
                    echo __METHOD__ . '@' . __LINE__ . ' debug: OctetFacade::db ' . $name . ' exception: ' . $exception->getMessage() . PHP_EOL;
                }
            }
            self::$databasePool[$name] = $db;
        }
        return self::$databasePool[$name];
    }

    public static function logger($prefix)
    {
        $dir = Helper::config(['log', 'base']);
        $logger = new ArkLogger($dir, $prefix, 'Y-m-d', null, true);
        $level = Helper::config(['log', 'log_level'], LogLevel::INFO);
        $logger->setIgnoreLevel($level);
        return $logger;
    }
}
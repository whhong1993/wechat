<?php
/**
 * Created by PhpStorm.
 * User: whhong
 * Date: 2020-07-21
 * Time: 11:05
 */

use Psr\Log\LogLevel;
use sinri\ark\core\ArkLogger;
use wechat\middleware\CoreMiddleware;

require_once __DIR__ . '/vendor/autoload.php';


$logger = new ArkLogger(__DIR__ . '/../log', 'web');
$logger->setIgnoreLevel(LogLevel::DEBUG);

$web_service = Ark()->webService();
$web_service->setDebug(true);
$web_service->setLogger($logger);
$router = $web_service->getRouter();
$router->setDebug(true);
$router->setLogger($logger);


$router->loadAutoRestfulControllerRoot(
    'wechat/',
    '\wechat\controller',
    [CoreMiddleware::class]
);


$web_service->handleRequestForWeb();
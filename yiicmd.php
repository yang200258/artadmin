#!/usr/bin/env php
<?php
/**
 * Yii console bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$_SERVER['argv'][] = 'test/pdf';
//$_SERVER['argv'][] = 116;
//$_SERVER['argv'][] = '诸葛烤鱼';
$_SERVER['argc'] = count($_SERVER['argv']);

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);

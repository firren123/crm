<?php
var_dump($_SERVER);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

//$intRemoteIp = ip2long('220.194.44.25');
$intRemoteIp = ip2long($_SERVER['SERVER_ADDR']);
var_dump($intRemoteIp);
var_dump(ip2long('192.168.33.0'));
var_dump(ip2long('192.168.33.255'));
if (($intRemoteIp - ip2long('192.168.33.0')) * ($intRemoteIp -ip2long('192.168.33.255')) > 0) {
    echo '测试';
    $config = array_merge(
        require(__DIR__ . '/../../common/config/main.php'),
        require(__DIR__ . '/../config/main.php')
    );
} else {
    echo '本地';
    $config = array_merge(
        require(__DIR__ . '/../../common/config/main-local.php'),
        require(__DIR__ . '/../config/main-local.php')
    );
}
exit;
$application = new yii\web\Application($config);
$application->run();

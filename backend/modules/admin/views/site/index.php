<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  index.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/27 上午10:16
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$user = \Yii::$app->user->identity->name;

echo '我是默认首页！！！！欢迎'.$user;
if($ceshi){
    echo "($ceshi)";
}
echo '使用crm后台，有问题可以联系管理员,邮箱：'.\Yii::$app->params['adminEmail'];


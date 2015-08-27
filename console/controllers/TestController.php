<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  TestController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/25 上午11:03
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace console\controllers;
use yii\console\Controller;
/** * Test controller */
class TestController extends Controller {
    public function actionIndex() {
        echo "cron service runnning";
    }
    public function actionMail($to) {
        echo "Sending mail to " . $to;
    }
}

//This controller should be use the console controller name space
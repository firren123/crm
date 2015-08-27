<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  ErrorController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/11 下午12:03
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\controllers;


use yii\web\Controller;

class ErrorController extends Controller{

    public function actionIndex(){
//        echo 1111;exit;
        $jumpUrl = '/admin/site/index';
        $message = '页面不存在';
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = \Yii::$app->params['baseUrl'];
        }

        $jumpUrl = $jumpUrl ? $jumpUrl : $url;
        $waitSecond = 3;
        return $this->renderPartial('index', [
            'message' => $message,
            'jumpUrl' => $jumpUrl,
            'waitSecond' => $waitSecond,
        ]);
    }
}
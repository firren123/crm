<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  PublicController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/6/2 下午2:45
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\controllers;

use common\helpers\FastDFSHelper;
use yii\web\Controller;


class PublicController extends Controller
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionUpload()
    {
        if (\Yii::$app->request->getIsPost()) {
            $return = array('status' => 1, 'info' => 'success', 'url' => '');
            $fast_dfs = new FastDFSHelper();
            $images = $fast_dfs->fdfs_upload('file');
            if ($images) {
                $return['url'] = '/' . $images['group_name'] . '/' . $images['filename'];
            }
            exit(json_encode($return));
        } else {
            echo 'error';
        }
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionUpload2()
    {
        if (\Yii::$app->request->getIsPost()) {
            var_dump($_FILES);
            var_dump($_POST);
            $model = new Post();
            $model->file = UploadedFile::getInstances($model, 'file');
            var_dump($model);
            exit;
            $return = array('status' => 1, 'info' => 'success', 'url' => '');


//            $fast_dfs = new FastDFSHelper();
//            $images = $fast_dfs->fdfs_upload('file');
//            if ($images) {
//                $return['url'] = '/' . $images['group_name'] . '/' . $images['filename'];
//            }
//            exit(json_encode($return));


        } else {
            echo 'error';
        }
    }

}
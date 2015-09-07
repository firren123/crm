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

use backend\models\social\Forum;
use common\helpers\FastDFSHelper;
use common\helpers\RequestHelper;
use yii\web\Controller;


class PublicController extends Controller
{
    public $enableCsrfValidation = false;
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
        //echo "<pre>";print_r($_FILES);echo "</pre>";exit;
        //$this->_outputUploadResult(1,'test');return;
        //set_time_limit(0);
        //ini_set('memory_limit', '256M');
        $map_upload_error = array(
            1 => '上传文件的大小超过了php.ini中upload_max_filesize选项设定的值',
            2 => '上传文件的大小超过了浏览器 MAX_FILE_SIZE选项设定的值',
            3 => '文件只有部分被上传',
            4 => '没有文件被上传'
        );
        $file_size_1_m = 1024 * 1024;
        $max_file_size = 1 * $file_size_1_m;//上传文件体积限制


        if ($_FILES['z_input_file']['error'] > 0) {
            //由文件上传导致的错误代码
            $this->_outputUploadResult(0, '上传失败：' . $map_upload_error[$_FILES['z_input_file']['error']]);
            return;
        }

        $upload_file_name = $_FILES['z_input_file']['name'];
        //$arr_split_file_name = explode('.', $upload_file_name);
        //$sizeArrSplitFileName = sizeof($arr_split_file_name);
        //$file_type = strtolower($arr_split_file_name[$sizeArrSplitFileName - 1]);
        $file_type = pathinfo($upload_file_name, PATHINFO_EXTENSION);
        $file_type = strtolower($file_type);
        //上传文件的扩展名//need
        $type_is_allow = in_array($file_type, array('jpg', 'jpeg', 'png', 'gif', 'bmp'));
        if ($type_is_allow === false) {
            //类型不对，结束//扩展名不对
            //$this->_outputUploadResult(0, '上传文件失败：上传的文件类型不正确');
            //return;
        }

        //$upload_file_mimetype = $_FILES['z_input_file']['type'];
        //$arr_img_info = array();
        try {
            $arr_img_info = getimagesize($_FILES["z_input_file"]["tmp_name"]);
        } catch (\Exception $e) {
            $this->_outputUploadResult(0, '上传失败：文件不是图片');
            return;
        }
        $upload_file_mimetype = $arr_img_info['mime'];
        $arr_mimetype_allow = array(
            'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png',
            'image/bmp'
        );
        if (!in_array($upload_file_mimetype, $arr_mimetype_allow)) {
            //类型不对，结束//扩展名与文件不符
            $this->_outputUploadResult(0, '上传失败：文件类型不正确');
            return;
        }

        //检测文件大小
        $upload_file_size = $_FILES['z_input_file']['size'];
        if ($upload_file_size > $max_file_size) {
            //大小不对，结束
            $this->_outputUploadResult(0, '上传失败：体积过大(大于' . $max_file_size / $file_size_1_m . 'M)');
            return;
        }


        $instance_fsatdfs = new FastDFSHelper();
        $arr_result = $instance_fsatdfs->fdfs_upload('z_input_file');

        //echo "<pre>";var_dump($arr_result);echo "</pre>";exit;

        if (isset($arr_result['group_name']) && isset($arr_result['filename'])) {
            $this->_outputUploadResult(1, json_encode($arr_result));
        } else {
            $this->_outputUploadResult(0, '上传失败:error=uploadimg_1');
        }

        return;
    }

    public function actionPhone()
    {
        $f_id = RequestHelper::get("f_id", 0, 'intval');
        $img = RequestHelper::get('forum_img');
        $model = new Forum();
        if ($f_id) {
            $model = $model->getInfo(['id'=>$f_id], false, "forum_img");
        }else{
            $model->forum_img = $img;
        }
        return $this->renderPartial('phone', ['model'=>$model]);
    }

    /**
     * 简介：
     * @return null
     */
    public function actionDoPhoto()
    {

        $src = RequestHelper::post('photo', '');
        $url = \Yii::$app->params['imgHost'];
        $src = $url.$src;
        $targ_w = $targ_h = 150;
        $jpeg_quality = 90;
        $type= strtolower(strrchr($src, "."));
        $img_r = '';
        if ($type == '.jpeg' || $type == '.jpg') {
            $img_r = @imagecreatefromjpeg($src);
        } elseif ($type == '.png') {
            $img_r = @imagecreatefrompng($src);
        } elseif ($type == '.gif') {
            $img_r = @imagecreatefromgif($src);
        }
        if ($img_r == false) {
            echo 0;
            exit();
        }
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
        imagecopyresampled($dst_r,$img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
        //header('Content-type: image/jpeg');
        $filename = '/tmp/shop_logo_'.rand(1,100).time().$type;
        if ($type == '.jpeg' || $type == '.jpg') {
            imagejpeg($dst_r, $filename,$jpeg_quality);
        } elseif ($type == '.png') {
            imagepng($dst_r, $filename);
        } elseif ($type == '.gif') {
            imagegif($dst_r, $filename);
        }
        $dfs = new FastDFSHelper();
        $info = $dfs->fdfs_upload_by_filename($filename);
        if($info) {
            $forum_img = '/'.$info['group_name'].'/'.$info['filename'];
            echo $forum_img;
            exit();
        } else {
            echo 0;
            exit();
        }
        exit;
    }
}
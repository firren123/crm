<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  SiteController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/27 上午10:14
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Admin;
use common\helpers\RequestHelper;

/**
 * SiteController
 *
 * @category Admin
 * @package  SiteController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class SiteController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex(){
        return $this->render('index', ['ceshi'=>$this->cesshi]);
    }

    /**
     * 密码修改
     *
     * @return string
     */
    public function actionEditpwd()
    {
        $old_password = RequestHelper::post('old_pwd');
        $new_password = RequestHelper::post('new_pwd');
        $user_id = RequestHelper::post('id');
        $model = new Admin();
        $arr_where = array('id'=>$user_id);
        $arr_info = $model->getOneRecord($arr_where);
//        echo '<pre>';
//        print_r($arr_info);
//        echo '</pre>';exit;
        $salt = $arr_info['salt'];

        $info_old_password = $arr_info['password'];
        if (md5($salt.md5($old_password)) == $info_old_password) {
            $arr_set=array('password'=>md5($salt.md5($new_password)));
            $arr_msg = $model->updateOneRecord($arr_where, '', $arr_set);
            if (0 == $arr_msg['result']) {
                echo json_encode(array('status'=>0, 'message'=>'修改失败'));
            } else {
                echo json_encode(array('status'=>1, 'message'=>'修改成功'));
            }
        } else {
            echo json_encode(array('status'=>-1, 'message'=>'输入原密码错误'));
        }
    }

    /**
     * 简介：密码修改页面
     * @author  songjiankang@iyangpin.com。
     */
    public function actionPwd()
    {
        return $this->render('editpwd');
    }
}

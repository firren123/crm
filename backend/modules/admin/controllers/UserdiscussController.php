<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  UserdiscussController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/31 上午9:36
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Userdiscuss;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\web\Controller;

class UserdiscussController extends BaseController
{
    public $enableCsrfValidation = false;
    public $p_size = 20;

    /**
     * 简介：评论列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        //var_dump($this->city_id);exit;
        $cid= implode(',',$this->city_id);
        if($cid == 999){
            $cond =! '';
        }else{
            $cond = "city_id in (".$cid.")";
        }
        $userName = RequestHelper::get('user_name');
        $and_where = [];
        if (!empty($userName)) {
           // $cond .= " and user_name LIKE '%" . $userName . "%' ";
            $and_where = ['like', 'user_name', $userName];
        }
        $order = 'id desc';
        $page = RequestHelper::get('page', 1);
        $pageSize = $this->size;
        $model =new Userdiscuss();
        $list = $model->getPageList($cond, '*', $order, $page, $pageSize,$and_where);
        $data['is_number'] = 1;
        $total = $model->getCount($cond,$and_where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index', ['list'=>$list,'pages'=>$pages,'userName'=>$userName]);
    }

    /**
     * 批量更改状态
     *
     * @return
     */

    public function actionEdit()
    {
        if ($_POST) {
            $status = RequestHelper::post('type', 0);
            $ids = RequestHelper::post('id', array());
            $arr=implode(',',$ids);
            if (!empty($ids) and $status != 0) {
                $model = new Userdiscuss();
                $cond = " id in (" . $arr . ")";
                $data = array();
                if ($status == 1) {
                    $data['status'] = 1;
                }
                if ($status == 2) {
                    $data['status'] = 2;
                }
                $result = $model->updateInfo($data, $cond);
                if ($result == true) {
                    return $this->success('审核成功','/admin/userdiscuss/index');
                }
            }
            return $this->success('审核失败','/admin/userdiscuss/index');
        }
    }

    /**
     * @purpose:单个更新
     * @name: actionUp
     * @return string
     */
    public function actionUp()
    {
        if($_GET){
            $id = RequestHelper::get('id');
            $cond = 'id=' . $id;
            $model = new Userdiscuss();
            $where = array();
            $where['status'] = RequestHelper::get('status',0,'intval');
            $ret = $model->updateInfo($where,$cond);
            //var_dump($ret);exit;
            if($ret == true){
                echo 1;
                exit;
            }else{
                echo 2;
                exit;
            }
        }
    }

}
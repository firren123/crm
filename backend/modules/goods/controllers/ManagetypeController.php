<?php
/**
 *
 * @category  CRM
 * @package   友情链接
 * @author    youyong <youyong@iyangpin.com>
 * @time      2015/4/17 18:01
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://admin.com
 * @link      youyong@iyangpin.com
 */
namespace backend\modules\goods\controllers;

use backend\models\i500m\ManageType;
use backend\models\i500m\Shop;
use common\helpers\FastDFSHelper;
use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use yii\data\Pagination;
use yii\web\UploadedFile;

class ManagetypeController extends BaseController
{
    /**
     * 经营种类列表
     *
     * Author youyoing@iyangpin.com
     *
     * @param： int $page     页码
     *
     * @return int 返回值说明
     */
    public function actionIndex()
    {
        $cond['status'] = [1,2];
        $name = RequestHelper::get('name');
        $and_where = [];
        if (!empty($name)) {
            //$cond .= " and name LIKE '%" . $name . "%' ";
            $and_where = ['like', 'name', $name];
        }
        //var_dump($cond);exit;
        $order = 'id desc';
        $page = RequestHelper::get('page', 1);
        $pageSize = $this->size;
        $model = new ManageType();
        $list = $model->getPageList($cond, '*', $order, $page, $pageSize, $and_where);
        $num = 0;
        $data = array();
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $data[] = $value;
                $shop_model = new Shop();
                $num = $shop_model->getCount(['manage_type'=>$value['id']]);
                $data[$key]['num'] = $num;
            }
        }
        //var_dump($data);exit;

        $model = new ManageType();
        $total = $model->getCount($cond, $and_where);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        return $this->render('index', ['data' => $data, 'pages' => $pages, 'name' => $name, 'total' => $total]);
    }


    /**
     * 经营种类添加
     *
     * @return string
     */
    public function actionAdd()
    {
        $model = new ManageType();
        $model->status = 2;
        $manageType = RequestHelper::post('ManageType');
        //var_dump($manageType);exit;
        if (!empty($manageType)) {
            $model->attributes = $manageType;
            $list = $model->getDetailsByName($manageType['name']);
            if (empty($list)) {
                $result = $model->insertInfo($manageType);
                if ($result == true) {
                    $this->redirect('/goods/managetype');
                }
            } else {
                $model->addError('name', '经营种类 不能重复');
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    /**
     * 经营种类修改
     *
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new ManageType();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, true, '*');
        //var_dump($item);exit;
        $model->attributes = $item;
        $manageType = RequestHelper::post('ManageType');
        //var_dump($manageType);exit;
        if (!empty($manageType)) {
            $model->attributes = $manageType;
            $list = $model->getDetailsByName($manageType['name'], $id);
            if (empty($list)) {
                $result = $model->updateInfo($manageType, $cond);
                if ($result == true) {
                    $this->redirect('/goods/managetype');
                }
            } else {
                $model->addError('name', '经营种类 不能重复');
            }
        }
        return $this->render('edit', ['model' => $model]);
    }

    /*
    *经营种类删除
    *
    * return int
    */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $model = new ManageType();
            $result = $model->getDelete($id);
            if ($result == 200) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }

    public function actionAjaxDelete()
    {
        $code = 0;
        $ids = RequestHelper::post('ids');
        $model = new ManageType();
        $result = $model->getBatchDelete($ids);
        if ($result == 200) {
            $code = 1;
        }
        return $code;
    }


}

<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  ModuleController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/23 上午11:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Module;
use common\helpers\RequestHelper;
use yii\data\Pagination;

class ModuleController extends BaseController
{
    /**
     * 简介：管理员列表
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionIndex()
    {
        $name = RequestHelper::get('name', '');
        $page = RequestHelper::get('page', 1, 'intval');

        $where = array();
        if ($name) {
            $where['name'] = $name;
        } else {
            $where = 1;
        }

        $admin_model = new Module();
        $count = $admin_model->getListCount($where);
        $list = $admin_model->getPageList($where, "*", 'sort asc ', $page, $this->size);
        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        return $this->render(
            'index',
            [
                'list' => $list,
                'pages' => $pages,
                'name' => $name,

            ]
        );
    }

    /**
     * 简介：管理员添加
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionAdd()
    {

        $model = new Module();
        $admin = RequestHelper::post('Module');
        if (!empty($admin)) {
            $admin['sort'] = $admin['sort'] ? $admin['sort'] : 99;
            $result = $model->insertInfo($admin);
            if ($result == true) {
                return $this->success('添加成功', '/admin/module/index');
            }
        }

        return $this->render('add', ['model' => $model]);

    }

    /**
     * 简介：管理员修改
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Module();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, false, '*');
        $admin = RequestHelper::post('Module');
        if (!empty($admin)) {
            $model->attributes = $admin;
            $result = $model->updateInfo($admin, $cond);
            if ($result == true) {
                $this->redirect('/admin/module/index');
            }
        }

        return $this->render('add', ['model' => $item]);
    }

    /**
     * 简介：友情链接删除
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $model = new Module();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }

    /**
     * 简介：AJAX提交
     * @author  lichenjun@iyangpin.com。
     * @return int
     */
    public function actionAjaxDelete()
    {
        $code = 0;
        $id = RequestHelper::post('ids');
        if (!empty($id)) {
            $model = new Module();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }
}

<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 角色管理
 *
 * @category  PHP
 * @package   Admin
 * @filename  RoleController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/23 上午10:23
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Role;
use backend\models\i500m\Log;
use common\helpers\RequestHelper;
use yii\data\Pagination;

class RoleController extends BaseController
{

    /**
     * 简介：角色列表
     * @author  lichenjun@iyangpin.com
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

        $admin_model = new Role();
        $count = $admin_model->getListCount($where);
        $list = $admin_model->getPageList($where, "*", 'id desc ', $page, $this->size);
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
     * 简介：角色添加
     * @author  lichenjun@iyangpin.com
     * @return null
     */
    public function actionAdd()
    {
        $model = new Role();
        $role = RequestHelper::post('Role');
        if (!empty($role)) {
            $role['sort'] = $role['sort'] ? $role['sort'] : 999;
            $result = $model->insertInfo($role);
            if ($result == true) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '添加了角色' . $role['name'];
                $log->recordLog($log_info, 10);
                return $this->success('添加成功', '/admin/role/index');
            }
        }

        return $this->render('add', ['model' => $model]);

    }

    /**
     * 简介：角色修改
     * @author  lichenjun@iyangpin.com
     * @return null
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Role();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, false, '*');
        $role = RequestHelper::post('Role');

        if (!empty($role)) {
            $result = $model->updateInfo($role, $cond);
            if ($result == true) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '修改了角色名为' . $role['name'];
                $log->recordLog($log_info, 10);
                return $this->success('修改成功', '/admin/role/index');
            }
        }

        return $this->render('add', ['model' => $item]);
    }

    /**
     * 简介：角色删除
     * @author  lichenjun@iyangpin.com。
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $model = new Role();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了角色id为' . $id;
                $log->recordLog($log_info, 10);
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
            $model = new Role();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了角色id为' . $id;
                $log->recordLog($log_info, 10);
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }
}

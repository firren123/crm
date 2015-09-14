<?php
/**
 * 管理员管理
 *
 * PHP Version 5
 *
 *
 * @category  CRM
 * @package   admin
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/4/18 下午1:50
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Admin;
use backend\models\i500m\Branch;
use backend\models\i500m\Log;
use backend\models\i500m\Role;
use common\helpers\CommonHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;

class AdminController extends BaseController
{
    /**
     * 简介：管理员列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $username = RequestHelper::get('username', '');
        $page = RequestHelper::get('page', 1, 'intval');

        $where = array();
        if ($username) {
            $where['username'] = $username;
        }
        $where['status'] = array(1, 2);

        $admin_model = new Admin();
        $count = $admin_model->getListCount($where);
        $list = $admin_model->getPageList($where, "*", 'id desc ', $page, $this->size);
        $branch_model = new Branch();
        $branch_list = $branch_model->getList('1', "*", 'sort desc');
        $branch = array();
        foreach ($branch_list as $k => $v) {
            $branch[$v['id']] = $v['name'];
        }
        $role_model = new Role();
        $branch_list = $role_model->getList('1', "*", 'sort desc');
        $role = array();
        foreach ($branch_list as $k => $v) {
            $role[$v['id']] = $v['name'];
        }
        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        $data = [
            'list' => $list,
            'pages' => $pages,
            'branch' => $branch,
            'role' => $role,
            'username' => $username,

        ];
        return $this->render('index', $data);
    }


    /**
     * 简介：管理员添加
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAdd()
    {
        $model = new Admin();
        $admin = RequestHelper::post('Admin');
        if (!empty($admin)) {
            if (empty($admin['password'])) {
                $model->addError('password', '密码不能为空');
            } else {
                $model->attributes = $admin;
                $where = array('username' => $admin['username']);
                $count = $model->getCount($where);
                if ($count == 0) {
                    $salt = CommonHelper::generate_password(5);
                    $admin['salt'] = $salt;
                    $admin['password'] = md5($salt . md5($admin['password']));
                    $result = $model->insertInfo($admin);
                    if ($result == true) {
                        $log = new Log();
                        $log_info = '管理员 ' . \Yii::$app->user->identity->username . '添加管理员' . $admin['username'];
                        $log->recordLog($log_info, 10);
                        return $this->success('添加成功', '/admin/admin/index');
                    }
                } else {
                    $model->addError('username', '账号不能重复');
                }
            }
        }
        $branch_model = new Branch();
        $branch_list = $branch_model->getList('1', "*", 'sort desc');
        $branch = array();
        foreach ($branch_list as $k => $v) {
            $branch[$v['id']] = $v['name'];
        }
        $role_model = new Role();
        $branch_list = $role_model->getList('1', "*", 'sort desc');
        $role = array();
        foreach ($branch_list as $k => $v) {
            $role[$v['id']] = $v['name'];
        }
        return $this->render('add', ['model' => $model, 'branch' => $branch, 'role' => $role]);

    }

    /**
     * 简介：管理员修改
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Admin();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, false, '*');
        unset($item->password);
        $admin = RequestHelper::post('Admin');

        if (!empty($admin)) {
            $model->attributes = $admin;
            if ($admin['password'] != '') {
                $salt = CommonHelper::generate_password(5);
                $admin['salt'] = $salt;
                $admin['password'] = md5($salt . md5($admin['password']));
            } else {
                unset($admin['password']);
            }
            $where = array('username' => $admin['username']);
            $and_where = ['<>','id',$id];
            $count = $model->getCount($where, $and_where);
            if ($count == 0) {
                $result = $model->updateInfo($admin, $cond);
                if ($result == true) {
                    $log = new Log();
                    $log_info = '管理员 ' . \Yii::$app->user->identity->username . '修改了管理员' . $admin['username'];
                    $log->recordLog($log_info, 10);
                    $this->redirect('/admin/admin/index');
                }
            } else {
                $item->addError('username', '账号不能重复');
            }
        }
        $branch_model = new Branch();
        $branch_list = $branch_model->getList('1', "*", 'sort desc');
        $branch = array();
        foreach ($branch_list as $k => $v) {
            $branch[$v['id']] = $v['name'];
        }
        $role_model = new Role();
        $branch_list = $role_model->getList('1', "*", 'sort desc');
        $role = array();
        foreach ($branch_list as $k => $v) {
            $role[$v['id']] = $v['name'];
        }
        return $this->render('add', ['model' => $item, 'branch' => $branch, 'role' => $role]);
    }

    /**
     * 简介：删除
     * @author  lichenjun@iyangpin.com。
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $model = new Admin();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了id为' . $id . '的管理员';
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
            $model = new Admin();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了id为' . $id . '的管理员';
                $log->recordLog($log_info, 10);
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }
}

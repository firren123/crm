<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  BusinessController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/25 下午3:20
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Branch;
use backend\models\i500m\Business;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * 简介 BusinessController
 * @category  PHP
 * @package   Admin
 * @filename  BusinessController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/25 下午3:20
 * @link      http://www.i500m.com/
 */
class BusinessController extends BaseController
{
    /**
     * 简介：首页
     * @return string
     */
    public function actionIndex()
    {
        $business_m = new Business();
        $name = RequestHelper::get('name', '');
        $bc_id = RequestHelper::get('bc_id', 0, 'intval');
        $page = RequestHelper::get('page', 1, 'intval');

        $where = [];
        $andwhere = '';
        if ($name) {
            $name = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
            $andwhere = "name like '%$name%'";
        }
        if ($this->is_head_company != 1) {
            $where['bc_id'] = $this->bc_id;

        }
        $count = $business_m->getListCount($where, $andwhere);
        $list = $business_m->getList2($where, $andwhere, "id desc", "*", ($page - 1) * $this->size, $this->size);
        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        $branch_m = new Branch();
        $list2 = $branch_m->getList(['status' => 1], "id,name");
        $list_arr = [];
        foreach ($list2 as $k => $v) {
            $list_arr[$v['id']] = $v['name'];
        }
        $data = [
            'name' => $name,
            'bc_id' => $bc_id,
            'branch_arr' => $list_arr,
            'list' => $list, 'pages' => $pages
        ];
        return $this->render('index', $data);

    }

    /**
     * 简介：业务员添加
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAdd()
    {
        $model = new Business();
        $Business = RequestHelper::post('Business');
        if (!empty($Business)) {
            $result = $model->insertInfo($Business);
            if ($result == true) {
                return $this->success('添加成功', '/admin/business/index');
            }
        }
        $branch_m = new Branch();
        $where = [];
        if ($this->is_head_company != 1) {
            $where['id'] = $this->bc_id;

        }
        $where['status'] = 1;
        $list = $branch_m->getList($where, "id,name");
        $list_arr = [];
        foreach ($list as $k => $v) {
            $list_arr[$v['id']] = $v['name'];
        }
        return $this->render('add', ['model' => $model, 'branch_arr' => $list_arr]);

    }

    /**
     * 简介：业务员修改
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Business();
        $cond = array('id' => $id);
        $item = $model->getInfo($cond, false, '*');
        $Business = RequestHelper::post('Business');

        if (!empty($Business)) {
            $result = $model->updateInfo($Business, $cond);
            if ($result == true) {
                return $this->success('修改成功', '/admin/business/index');
            }
        }
        $branch_m = new Branch();
        $where = [];
        if ($this->is_head_company != 1) {
            $where['id'] = $this->bc_id;

        }
        $where['status'] = 1;
        $list = $branch_m->getList($where, "id,name");
        $list_arr = [];
        foreach ($list as $k => $v) {
            $list_arr[$v['id']] = $v['name'];
        }
        return $this->render('add', ['model' => $item, 'branch_arr' => $list_arr]);
    }

    /**
     * 简介：业务员删除
     * @author  lichenjun@iyangpin.com。
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        $where = [];
        if ($this->is_head_company != 1) {
            $where['bc_id'] = $this->bc_id;

        }
        $where['id'] = $id;
        if (!empty($id)) {
            $model = new Business();
            $ret = $model->deleteAll($where);
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
        $where = [];
        if ($this->is_head_company != 1) {
            $where['bc_id'] = $this->bc_id;

        }
        $where['id'] = $id;
        if (!empty($id)) {
            $model = new Business();
            $ret = $model->deleteAll($where);
            if ($ret) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }


}
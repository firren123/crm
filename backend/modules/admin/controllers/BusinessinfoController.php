<?php

/**
 * 业务员信息
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  BusinessController.php
 * @author    sunsongsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/6/10 下午11:04
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Branch;
use backend\models\i500m\Business;
use backend\models\i500m\Deptment;
use backend\models\i500m\Duty;
use backend\models\i500m\BusinessGoal;
use backend\models\i500m\BusinessGroup;
use backend\models\i500m\Shop;
use backend\models\i500m\ShopOrderflow;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * BusinessInfoController
 *
 * @category CRM
 * @package  BusinessinfoController
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class BusinessinfoController extends BaseController
{

    public $default_page_size = 10;
    public $duty_name = [
        0 => '普通业务',
        1 => '区域经理',
        2 => '分公司经理'

    ];

    /**
     * 业务员信息首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $business = new Business();
        $bus_info = new BusinessGoal();
        $branch_m = new Branch();
        $name = RequestHelper::get('name', '');
        $bc = RequestHelper::get('bc', '');
        $mobile = RequestHelper::get('mobile', '');
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = $this->default_page_size;
        $name = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
        $admin_id = $this->admin_id;
        $admin_bc_id = $this->bc_id;

        $condition = ['and', ['status' => 1]];

        if (!empty($name)) {
            $condition[] = ['like', 'name', $name];
        }

        if (!empty($mobile)) {
            $condition[] = ['=', 'mobile', $mobile];
        }

        if (!empty($bc)) {
            $condition[] = ['=', 'bc_id', $bc];
        }

        $fields = "id,name,mobile,email,bc_id,duty,pwd,status";
        $desc = " id desc";
        $list = $business->getPageList($condition, $fields, $desc, $page, $page_size);
        $count       = $business->getCount($condition);

        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);//分页

        $shop = new Shop();
        $date_m = strtotime(date('Y-m'));

        $info = array();
        if ($list) {
            foreach ($list as $v) {

                $shop_where = [
                    'and',
                    ['=','status', 2],
                    ['=', 'business_status', 1],
                    ['=', 'workflow', 4],
                    ['=', 'business_id', $v['id']],
                    ['>=', 'create_time', $date_m], ['<=', 'create_time', time()]
                ];
                $shop_num = $shop->getCount($shop_where);
                $v['shop_num'] = $shop_num;

                $goal = $bus_info->getOneRecord('', " business_id = {$v['id']}", 'fact_total,day_total,openshop_total,sales_total');
                if (!empty($goal)) {
                    !empty($goal['day_total']) ? $v['day_total'] = $goal['day_total'] : $v['day_total'] = "0";
                    !empty($goal['sales_total']) ? $v['sales_total'] = $goal['sales_total'] : $v['sales_total'] = "0";
                    !empty($goal['openshop_total']) ? $v['openshop_total'] = $goal['openshop_total'] : $v['openshop_total'] = "0";
                    !empty($goal['fact_total']) ? $v['fact_total'] = $goal['fact_total'] : $v['fact_total'] = "0";
                } else {
                    $v['day_total'] = "0";
                    $v['sales_total'] = "0";
                    $v['openshop_total'] = "0";
                    $v['fact_total'] = "0";
                }
                $info[] = $v;
            }

        }

        $branch_where = ['<', 'status', 2];
        if ($this->is_head_company != 1) {
            $branch_where = ['=', 'id', $admin_bc_id];
        }
        $branch = $branch_m->getList($branch_where, "", "id,name");
        $list_arr = [];
        foreach ($branch as $k => $v) {
            $list_arr[0] = "请选择";
            $list_arr[$v['id']] = $v['name'];
        }
        $param = [
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count,
            'list' => $info,
            'name' => $name,
            'bc' => $bc,
            'mobile' => $mobile,
            'branch_arr' => $list_arr,
            'admin_id' => $admin_id
        ];
        return $this->render('index', $param);

    }

    /**
     * 业务员添加信息
     *
     * @return string
     */
    public function actionAdd()
    {
        $model = new Business();
        $dept = new Deptment();
        $duty = new Duty();
        if ($_POST) {
            $name = RequestHelper::post('name', '', 'trim');
            $mobile = RequestHelper::post('mobile', 0, 'trim');
            $pwd = RequestHelper::post('pwd');
            $email = RequestHelper::post('email');
            $status = RequestHelper::post('status');
            $bc_id = RequestHelper::post('bc_id');
            $group_id = RequestHelper::post('groupid', '');
            $deptment_id = RequestHelper::post('deptment_id');
            $duty_id = RequestHelper::post('duty_id');
            $rand = $this->rands();//随机数
            $deptment = RequestHelper::post('deptment_name', '', 'trim');
            $duty_name = "";

            if (empty($group_id)) {
                $group_id = "0";
            }

            if ('0' === $duty_id) {
                $duty_name = $this->duty_name[0];
            }
            if ('1' === $duty_id) {
                $duty_name = $this->duty_name[1];
            }
            if ('2' === $duty_id) {
                $duty_name = $this->duty_name[2];
            }

            $data = array();
            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['email'] = $email;
            $data['bc_id'] = $bc_id;
            $data['group_id'] = $group_id;
            $data['status'] = $status;
            $data['deptment_id'] = $deptment_id;
            $data['deptment'] = $deptment;
            $data['duty_id'] = $duty_id;
            $data['duty'] = $duty_name;
            $data['pwd'] = md5($rand . md5($pwd));
            $data['salt'] = $rand;
            $data['create_time'] = date('Y-m-d H:i:s', time());;

            if (!preg_match("/^(?![a-z]+$|[0-9]+$)^[a-zA-Z0-9]{6,12}$/", $pwd)) {
                return $this->error('密码必须为6-12位的数字和字母的组合！！！', '/admin/businessinfo/add');
            }
            $where_m = ['and', ['=','mobile',$mobile]];
            $exist_mobile = $model->getOneRecord($where_m, "", 'id');
            if (!empty($exist_mobile)) {
                return $this->error('电话号码已经存在', '/admin/businessinfo/add');
            }
            if (!empty($data)) {
                $result = $model->insertInfo($data);
                if ($result == true) {
                    return $this->success('添加成功', '/admin/businessinfo/index');
                }
            }

        }
        $branch_m = new Branch();
        $where = [];
        if ($this->is_head_company != 1) {
            $where['id'] = $this->bc_id;
            $where_d['brand_id'] = $this->bc_id;
        }
        $where['status'] = 1;
        $where_d['status'] = 1;
        $list = $branch_m->getList($where, "id,name");
        $dept_info = $dept->getList(" status = 1", "id,name");
        $duty_info = $duty->getList($where_d, "id,name");

        $dept_arr = [];
        foreach ($dept_info as $k => $v) {
            $dept_arr[$v['id']] = $v['name'];
        }

        $duty_arr = [];
        foreach ($duty_info as $k => $v) {
            $duty_arr[$v['id']] = $v['name'];
        }

        $list_arr = [];
        foreach ($list as $k => $v) {
            $list_arr[0] = "请选择";
            $list_arr[$v['id']] = $v['name'];
        }

        return $this->render('add', ['dept_arr' => $dept_arr, 'duty_arr' => $duty_arr, 'branch_arr' => $list_arr]);

    }

    /**
     * 验证电话和姓名是否存在
     * @return json
     */
    public function actionCheck()
    {
        $model = new Business();
        $name = RequestHelper::get('name', '', 'trim');
        $check_mobile = RequestHelper::get('mobile', 0, 'trim');
        $check = RequestHelper::get('check');

        if ($check == 'check_name') {
            $where = ['=','name',$name];
            $exist_name = $model->getOneRecord($where, "", 'id');
            if (!empty($exist_name)) {
                $res = array('msg'=>'名称已经存在！！！', 'status'=>1);
                echo json_encode($res);
                exit;
            } else {
                $res = array('msg'=>'验证通过', 'status'=>2);
                echo json_encode($res);
                exit;
            }
        }

        if ($check == 'check_mobile') {
            $where = ['=', 'mobile', $check_mobile];
            $exist_mobile = $model->getOneRecord($where, "", 'id');
            if (!empty($exist_mobile)) {
                $res = array('msg'=>'电话已经存在！！！', 'status'=>1);
                echo json_encode($res);
                exit;
            } else {
                $res = array('msg'=>'验证通过', 'status'=>2);
                echo json_encode($res);
                exit;
            }
        }
    }

    /**
     * 业务员修改信息
     *
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Business();
        $dept = new Deptment();
        $bus_info = new BusinessGoal();
        $duty = new Duty();
        $cond = array('id' => $id);
        $item = $model->getOneRecord($cond, '', '*');
        if (empty($id)) {
            return $this->error('访问错误！！！', '/admin/businessinfo/index');
        }
        if (empty($item)) {
            return $this->error('访问id不存在！！！', '/admin/businessinfo/index');
        }
        if ($_POST) {
            $name = RequestHelper::post('name', '', 'trim');
            $mobile = RequestHelper::post('mobile', 0, 'intval');
            $email = RequestHelper::post('email');
            $status = RequestHelper::post('status');
            $bc_id = RequestHelper::post('bc_id');
            $groupid = RequestHelper::post('groupid');
            $deptment_id = RequestHelper::post('deptment_id');
            $duty_id = RequestHelper::post('duty_id');
            $deptment = RequestHelper::post('deptment_name', '', 'trim');
            $duty_name = "";

            if (empty($groupid)) {
                $groupid = "0";
            }
            if ('0' === $duty_id) {
                $duty_name = $this->duty_name[0];
            }
            if ('1' === $duty_id) {
                $duty_name = $this->duty_name[1];
            }
            if ('2' === $duty_id) {
                $duty_name = $this->duty_name[2];
            }

            $data = array();
            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['email'] = $email;
            $data['bc_id'] = $bc_id;
            $data['group_id'] = $groupid;
            $data['status'] = $status;
            $data['deptment_id'] = $deptment_id;
            $data['deptment'] = $deptment;
            $data['duty_id'] = $duty_id;
            $data['duty'] = $duty_name;
            $data['create_time'] = date('Y-m-d H:i:s', time());
            if (!empty($data)) {
                $result = $model->updateOneRecord("id = {$id}", "", $data);
                if ($result['result'] == '1') {
                    return $this->success('修改成功', '/admin/businessinfo/index');
                }
                return $this->error('修改失败', '/admin/businessinfo/index');
            }

        }
        //业务员月目标信息
        $bus_where = ['=', 'business_id', $id];
        $exist = $bus_info->getList($bus_where, "id,business_id,day_total,openshop_total,sales_total,status");

        $target = array();
        if (!empty($exist)) {
            foreach ($exist as $v) {
                $info = $model->getOneRecord(" id = {$v['business_id']}", '', 'name');

                $v['name'] = $info['name'];
                $target[] = $v;

            }
        }

        $branch_m = new Branch();
        $where = [];
        if ($this->is_head_company != 1) {
            $where['id'] = $this->bc_id;
            $where_d['brand_id'] = $this->bc_id;
        }

        $where['status'] = 1;
        $where_d['status'] = 1;
        $list = $branch_m->getList($where, "id,name");

        $dept_info = $dept->getList(" status = 1", "id,name");
        $duty_info = $duty->getList($where_d, "id,name");

        $dept_arr = [];
        foreach ($dept_info as $k => $v) {
            $dept_arr[$v['id']] = $v['name'];
        }

        $duty_arr = [];
        foreach ($duty_info as $k => $v) {
            $duty_arr[$v['id']] = $v['name'];
        }

        $list_arr = [];
        foreach ($list as $k => $v) {
            $list_arr[0] = "请选择";
            $list_arr[$v['id']] = $v['name'];
        }
        if (empty($item['group_id'])) {
            $item['group_id'] = "0";
        }
        $data_info = ['data' => $item, 'bcid' => $item['bc_id'], 'groupid' => $item['group_id'], 'dept_arr' => $dept_arr, 'duty_arr' => $duty_arr, 'branch_arr' => $list_arr, 'target'=>$target];
        return $this->render('edit', $data_info);

    }

    /**
     * 业务员修改密码
     * @return json
     */
    public function actionPassword()
    {
        $business = new Business();
        $id = RequestHelper::get('id');
        $pwd = RequestHelper::get('pwd', '', 'trim');
        $updata = RequestHelper::get('updata');
        if (!empty($id) && !empty($pwd) && $updata == '' && empty($updata)) {
            $salt = $business->getOneRecord("id = {$id}", '', 'salt');
            $passwd = md5($salt['salt'].md5($pwd));
            $where = ['and',['=', 'id', $id],['=', 'pwd', $passwd]];
            $exist = $business->getOneRecord($where, '', 'id');
            if (!empty($exist)) {
                $res = array('msg'=>'','status'=>1);
                echo json_encode($res);
            } else {
                $res = array('msg'=>'','status'=>2);
                echo json_encode($res);
            }
        }
        if ($updata == 'updata') {
            if (!preg_match("/^(?![a-z]+$|[0-9]+$)^[a-zA-Z0-9]{6,12}$/", $pwd)) {
                $res = array('msg'=>'密码必须为6-12位的数字和字母的组合！！！','status'=>3);
                return json_encode($res);
            }
            $salt = $business->getOneRecord("id = {$id}", '', 'salt');
            $data['pwd'] = md5($salt['salt'] . md5($pwd));
            $result = $business->updateOneRecord("id = {$id}", "", $data);

            if ($result['result'] == '1') {
                $res = array('msg'=>'密码修改成功','status'=>1);
                echo json_encode($res);
            } else {
                $res = array('msg'=>'密码修改失败','status'=>2);
                echo json_encode($res);
            }
        }

    }

    /**
     * 业务员删除
     *
     * @return string
     */
    public function actionDelete()
    {

        $id = RequestHelper::get('id');
        $model = new Business();
        $exist = $model->getOneRecord("id = {$id}", "", "id,status");
        if (!empty($exist)) {
            if (0 == $exist['status']) {
                $res = array('msg' => '已经是删除状态不需要再操作！！！', 'status' => '0');
                return json_encode($res);
            }
            $field = array('status' => 0, 'imei'=>'');
            $info = $model->updateOneRecord("id = {$id}", "", $field);
            $info['result'] == '1' ?
                $res = array('msg' => '删除成功！！！', 'status' => '1') :
                $res = array('msg' => '删除失败！！！', 'status' => '0');

        } else {
            $res = array('msg' => '该id不存在！！！', 'status' => '2');
        }

        return json_encode($res);
    }

    /**
     * 业务员分组
     *
     * @return string
     */
    public function actionGroup()
    {
        $bus_group = new BusinessGroup();
        $branch_m = new Branch();
        $page = RequestHelper::get('page', 1, 'intval');
        $name = RequestHelper::get('g_name', '', 'trim');
        $id = RequestHelper::get('bc_id', '');
        $pagesize = $this->default_page_size;
        $admin_bc_id = $this->bc_id;

        $and_where = [];
        if ($this->is_head_company != 1) {
            $and_where = ['=', 'branch_id', $admin_bc_id];
        }

        $where = ['and'];
        if (!empty($id)) {
            $where[] = ['=','branch_id',$id];
        }

        if (!empty($name)) {
            $where[] = ['like','name',$name];
        }

        $group = $bus_group->getPageList($where, "id,branch_id,name,status", "id desc", $page, $pagesize, $and_where);
        $count       = $bus_group->getCount($where, $and_where);
        $page_count = $count;
        if ($count > 0 && $pagesize > 0) {
            $page_count = ceil($count / $pagesize);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $pagesize]);

        $list = array();
        if (!empty($group)) {
            foreach ($group as $v) {
                if ($v['branch_id'] != 0) {
                    $branch = $branch_m->getOneRecord("id = {$v['branch_id']}", "", "id,name");
                } else {
                    $branch['name'] = "没有分配公司";
                }
                $v['branch'] = $branch['name'];
                $list[] = $v;
            }
        }
        $branch = $branch_m->getList("status=1 or status=0", "", "id,name");
        $list_arr = [];
        foreach ($branch as $k => $v) {
            $list_arr[0] = "请选择";
            $list_arr[$v['id']] = $v['name'];
        }
        $param = ['name' => $name, 'data' => $list, 'branch_arr' => $list_arr,'count' => $count, 'pages'=> $pages, 'page_count' => $page_count, 'admin_bc_id' => $this->is_head_company];
        return $this->render('group', $param);
    }

    /**
     * 业务员添加分组
     *
     * @return string
     */
    public function actionGroupadd()
    {
        $bus_group = new BusinessGroup();
        $name = RequestHelper::get('name', '', 'trim');
        $stat = RequestHelper::get('stat', '');
        $bc_id = RequestHelper::get('bcid', '');
        $id = RequestHelper::get('id', '0', 'intval');
        $admin_bc_id = $this->bc_id;
        if (empty($bc_id)) {
            $bc_id = $admin_bc_id;
        }
        $res = array();
        if ($stat == "updata") {
            $data['name'] = $name;
            $data['branch_id'] = $bc_id;
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $info = $bus_group->updateOneRecord("id = {$id}", "", $data);
            $info['result'] == '1' ?
                $res = array('msg' => '修改成功！！！', 'status' => '1') :
                $res = array('msg' => '修改失败！！！', 'status' => '0');
        }

        if ($stat == "add") {

            $data['name'] = $name;
            $data['branch_id'] = $bc_id;
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $info = $bus_group->insertOneRecord($data);
            if ($info) {
                $res = array('msg' => '添加成功！！！', 'status' => '1');
            } else {
                $res = array('msg' => '添加失败！！！', 'status' => '0');
            }
        }
        return json_encode($res);
    }

    /**
     * 业务员修改分组
     *
     * @return string
     */
    public function actionGroupedit()
    {

        $bus_group = new BusinessGroup();
        $id = RequestHelper::get('id', '0', 'intval');
        $admin_bc_id = $this->bc_id;
        if ($id != '0') {
            $where = ['and',['=', 'id', $id], ['=', 'branch_id', $admin_bc_id]];
            $info = $bus_group->getOneRecord($where, "", "branch_id,name");

            if (!empty($info)) {
                $res = array('msg' => '', 'status' => '1', 'data' => $info);
                return json_encode($res);
            }
            $res = array('msg' => '您不可以修改分公司！！！', 'status' => '0', 'data' => '');
            return json_encode($res);
        }
        $res = array('msg' => 'id错误！！！', 'status' => '0', 'data' => '');
        return json_encode($res);
    }

    /**
     * 业务员删除分组
     *
     * @return string
     */
    public function actionGroupdel()
    {
        $bus_group = new BusinessGroup();
        $id = RequestHelper::get('id', '');
        $admin_bc_id = $this->bc_id;
        $where = ['and', ['=', 'id', $id],['=', 'branch_id', $admin_bc_id]];
        $exist = $bus_group->getOneRecord($where, "", "id");
        if (!empty($exist)) {
            $info = $bus_group->delOneRecord($where, "");
            $info['result'] == '1' ?
                $res = array('msg' => '删除成功！！！', 'status' => '1') :
                $res = array('msg' => '删除失败！！！', 'status' => '0');

        } else {
            $res = array('msg' => '不存在该分组！！！', 'status' => '2');
        }

        return json_encode($res);
    }

    /**
     * 业务员修改目标
     *
     * @return string
     */
    public function actionTarget()
    {
        $id = RequestHelper::get('id', '0', 'intval');
        $day_total = RequestHelper::get('day_total', '0', 'intval');
        $openshop_total = RequestHelper::get('openshop_total', '0', 'intval');
        $sales_total = RequestHelper::get('sales_total', '0', 'intval');
        $bus_info = new BusinessGoal();
        $date = date("Y-m");
        $date_one = date("Y-m", strtotime("+1 month"));

        $where = ['and', ['=', 'business_id', $id], ['>=', 'create_time', $date], ['<', 'create_time', $date_one]];
        $exist = $bus_info->getOneRecord($where, "", "business_id,day_total,openshop_total,sales_total");
        if (empty($exist)) {
            $data['business_id'] = $id;
            $data['day_total'] = $day_total;
            $data['openshop_total'] = $openshop_total;
            $data['sales_total'] = $sales_total;
            $data['start_time'] = date('Y-m-d H:i:s', time());
            $data['end_time'] = date('Y-m-d H:i:s', time());
            $data['create_time'] = date('Y-m-d H:i:s', time());

            $ress = $bus_info->insertOneRecord($data);
            if ($ress) {
                $res = array('msg' => '添加成功！！！', 'status' => '1');
            } else {
                $res = array('msg' => '添加失败！！！', 'status' => '0');
            }

        } else {
            $data['day_total'] = $day_total;
            $data['openshop_total'] = $openshop_total;
            $data['sales_total'] = $sales_total;
            $data['start_time'] = date('Y-m-d H:i:s', time());
            $data['end_time'] = date('Y-m-d H:i:s', time());
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $ress = $bus_info->updateOneRecord($where, "", $data);
            $ress['result'] == '1' ?
                $res = array('msg' => '修改成功！！！', 'status' => '5', 'info' => $exist) :
                $res = array('msg' => '修改失败！！！', 'status' => '0');
        }
        return json_encode($res);
    }

    /**
     * 读取目标信息
     *
     * @return string
     */
    public function actionGroupinfo()
    {
        $id = RequestHelper::get('id', '0', 'intval');
        $bus_info = new BusinessGoal();
        $where = ['and', ['=', 'business_id', $id]];
        $exist = $bus_info->getOneRecord($where, "", "business_id,day_total,openshop_total,sales_total");
        $exist['sales_total'] =  floor($exist['sales_total']);
        !empty($exist) ?
            $res = array('msg' => '', 'status' => '1', 'info' => $exist) :
            $res = array('msg' => '', 'status' => '0', 'info' => '');
        return json_encode($res);
    }

    /**
     * 二级联动
     *
     * @return string
     */
    public function actionLinkage()
    {
        $bus_group = new BusinessGroup();
        $id = RequestHelper::get('id', '0', 'intval');
        $branch = $bus_group->getList("branch_id = {$id}", "id,name", "id asc", "");
        $list = array();
        if (!empty($branch)) {
            foreach ($branch as $v) {
                $list[] = $v;
            }
        }
        return json_encode($list);
    }

    /**
     * 生成随机6位数
     *
     * @return string
     */
    public function rands()
    {
        $length = "6";
        $str = "";
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        return $str;
    }

    /**
     * 商家订单流水
     *
     * @return string
     */
    public function actionShopOrderflow()
    {

        $order = new ShopOrderflow();
        $businss = new Business();
        $shop_id = RequestHelper::get('shop_id', '', 'trim');
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 5;
        $date_m = date('Y-m');
        $date = date('Y-m-d H:i:s');
        $condition = ['and',["<=", 'data_time', $date], [">=", 'data_time', $date_m]];
        if (!empty($shop_id)) {
            $condition[] = ["=", 'shop_id', $shop_id];
        }

        $info = $order->groupList("id>0", "business_id", "business_id", "id desc");
        $res = array();
        if (!empty($info)) {
            foreach ($info as $v) {
                $res[] = $v['business_id'];
            }
        }
        $res_id = implode(',', array_unique($res));
        $and_where = array();
        if (!empty($res_id)) {
            $and_where = " id in ({$res_id})";
        }

        $b_where = ['=', 'bc_id', $this->bc_id];
        $b_info = $businss->getList($b_where, 'id', 'id desc', $and_where);

        $b_res = array();
        if (!empty($b_info)) {
            foreach ($b_info as $v) {
                $b_res[] = $v['id'];
            }
        }
        $bus_id = implode(',', array_unique($b_res));

        $and_cond = array();
        if (!empty($bus_id)) {
            $and_cond = " business_id in ($bus_id)";
        }
        $files = " id,business_id,shop_id,sum(order_total) as order_total,sum(money_total) as money_total,sum(zy_money_total) as zy_money_total,sum(tg_money_total) as tg_money_total,data_time";
        $list = $order->getPage($condition, $files, 'shop_id', 'id desc', $page, $page_size, $and_cond);


        if (empty($list)) {
            $list = array();
        }
        $count = $order->getviewCount($and_cond, ' shop_id');
        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);


        return $this->render('orderlist', ['data' => $list, 'count' => $count, 'pages' => $pages, 'page_count' => $page_count, 'shop_id' => $shop_id]);

    }

    /**
     * 商家订单流水详情
     *
     * @return string
     */
    public function actionShopOrderview()
    {

        $this->layout = 'dialog';
        $order = new ShopOrderflow();
        $businss = new Business();
        $shop_id = RequestHelper::get('id', '', 'trim');
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 10;
        $date_m = date('Y-m');
        $date = date('Y-m-d H:i:s');
        $condition = ['and',["<=", 'data_time', $date], [">=", 'data_time', $date_m]];

        if (!empty($shop_id)) {
            $condition[] = ["=", 'shop_id', $shop_id];
        }
        //获取商家id 然后去重
        $list = $order->getPageList($condition, '*', 'id desc', $page, $page_size);
        if (empty($list)) {
            $list = array();
        }
        $count = $order->getCount($condition);

        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);


        return $this->render('viewlist', ['data' => $list, 'count' => $count, 'pages' => $pages, 'page_count' => $page_count, 'shop_id' => $shop_id]);

    }
}

<?php
/**
 * 供应商出库
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   WarehouseController.php
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      2015/8/20 9:15
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      sunsongsong@iyangpin.com
 */
namespace backend\modules\storage\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Warehouse;
use backend\models\i500m\CrmBranch;
use backend\models\i500m\Province;
use backend\models\i500m\City;
use backend\models\i500m\Admin;
use backend\models\i500m\District;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * WarehouseController
 *
 * @category CRM
 * @package  WarehouseController
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class WarehouseController extends BaseController
{
    /**
     * 库房管理列表
     *
     * Author youyong@iyangpin.com
     * 多行函数说明（可选）
     *
     * $res array  所有库房信息
     * $count int  库房数量
     * $branch_arr array  分公司
     *
     * @return int 返回值说明
     */
    public function actionIndex()
    {
        $bc_id = RequestHelper::get('bc_id');
        $name = RequestHelper::get('name');

        $branch = ['status'=>1];
        /*if ($this->bc_id != 28) {
            $branch['id'] = $this->bc_id;
            $bc_id = $this->bc_id;
        }*/
        //获取分公司的ID和name
        $branch_m = new CrmBranch();
        $branch_arr = $branch_m->getList($branch, "id,name");

        $where = ['status' => [1,2]];

        $and_where = [];
        if (!empty($name)) {
            //$where .= " and name LIKE '%" . $name . "%' ";
            $and_where = ['like', 'name', $name];
        }
        //var_dump($name,$bc_id);exit;
        if (!empty($bc_id)) {
            $where['bc_id'] = " $bc_id " ;
        }
        //var_dump($where);exit;
        $p = RequestHelper::get('page', '1', 'intval');                //当前页
        $model = new Warehouse();
        $list = $model->getPageList($where, '*', 'id desc', $p, $this->size, $and_where);
        $count = $model->getCount($where, $and_where);
        //var_dump($list);exit;
        $res =array();
        $arr = array();
        foreach ($list as $v) {
            $arr['id']=$v['id'];
            $arr['sn']=$v['sn'];
            $arr['name']=$v['name'];
            $arr['name']=$v['name'];
            $arr['name']=$v['name'];
            $arr['username']=$v['username'];
            $arr['phone']=$v['phone'];
            $arr['bc_id']=$v['bc_id'];
            $arr['province_id']=$v['province_id'];
            $arr['city_id']=$v['city_id'];
            $arr['district_id']=$v['district_id'];
            $arr['address']=$v['address'];
            $arr['remarks']=$v['remarks'];
            $arr['status']=$v['status'];

            $branch_m = new CrmBranch();
            //$where = "id=".$v['bc_id'];
            $where = array('id'=>$v['bc_id']);
            $bc_name = $branch_m->getOneRecord($where, '', "name");
            if (empty($bc_name)) {
                $arr['bc_name']='';
            } else {
                $arr['bc_name'] = $bc_name['name'];
            }

            $branch_m = new Province();
            //$where = "id=".$v['province_id'];
            $where = array('id'=>$v['province_id']);
            $province = $branch_m->getOneRecord($where, '', "name");
            if (empty($province)) {
                $arr['province_name']='';
            } else {
                $arr['province_name'] = $province['name'];
            }

            $branch_m = new City();
            //$where = "id=".$v['city_id'];
            $where = array('id'=>$v['city_id']);
            $city = $branch_m->getInfo($where, "name");
            if (empty($city)) {
                $arr['city_name'] = '';
            } else {
                $arr['city_name'] = $city['name'];
            }
            $branch_m = new District();
            //$where = "id=".$v['district_id'];
            $where = array('id'=>$v['district_id']);
            $district = $branch_m->getInfo($where, "name");
            if (empty($district)) {
                $arr['district_name']='';
            } else {
                $arr['district_name'] = $district['name'];
            }

            $res[]=$arr;
        }

        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render('list', ['pages' => $pages, 'res' => $res, 'count' => $count, 'branch_arr'=>$branch_arr, 'name' => $name, 'bc_id' => $bc_id,]);
    }

    /**
     * 添加库房
     *
     * Author youyong@iyangpin.com
     * 多行函数说明（可选）
     *
     * $branch_arr array  分公司
     *
     * @return int 返回值说明
     **/
    public function actionAdd()
    {
        //获取分公司的ID和name
        $branch_m = new CrmBranch();
        $where['status'] = 1;
        $list = $branch_m->getList($where, "id,name");
        $admin = new Admin();
        $bc_id = $admin->getOneRecord(['id'=>$this->admin_id], '', 'bc_id');
        /*$branch_array = [];
        foreach ($list as $k =>$v) {
            $branch_array[$v['id']] = $v['name'];
        }
        if ('28' == $bc_id['bc_id']) {
            $branch_arr = $branch_array;
        } else {
            $branch_arr[$bc_id['bc_id']] = $branch_array[$bc_id['bc_id']];
        }*/
        //获取传参 array
        $model = new Warehouse();
        $Warehouse = RequestHelper::post('Warehouse');
        if (!empty($Warehouse)) {

            $cond = ['=', 'sn', $Warehouse['sn']];
            $info = $model->getOneRecord($cond, '', 'id');
            if (!empty($info)) {
                return $this->error('库房编号已经存在！！！', '/storage/warehouse/index');
            }
            $result = $model->insertInfo($Warehouse);
            if ($result == true) {
                return $this->success('添加成功', '/storage/warehouse/index');
            }
        }
        return $this->render('add', ['model' => $model,'branch_arr'=>$list]);
    }

    /**
     * 通过传过来的分公司$bc_id 获取省id name
     * 省id 查询省内所有的市id和市name
     * @return array
     */
    public function actionProvinceCity()
    {
        $bc_id = RequestHelper::get('bc_id', 1, 'intval');
        $branch_m = new CrmBranch();
        $where = "id=".$bc_id;
        $province = $branch_m->getList($where, "province_id");
        $pid = $province[0]['province_id'];
        //var_dump($pid);exit;

        //根据省id 查询省的name($pName)
        $model = new Province();
        $name = $model->province_one($pid);
        $pName = array('pid'=>$pid,'name'=>$name['name']);
        //print_r($pName);exit;

        //根据省id 查询省内所有的市id和市name
        $model = new City();
        $cwhere = ['and', ['=', 'id', $bc_id], ['=', 'province_id', $pid]];
        $info = $branch_m->getOneRecord($cwhere, '', 'city_id_arr');
        if (empty($info)) {
            $info = array();
        }
        $data = explode(',', $info['city_id_arr']);
        $cName = array();
        foreach ($data as $v) {

            $cond = ['=', 'id', $v];
            $names = $model->getOneRecord($cond, '', 'id,name');
            $cName[] = $names;
        }
        //print_r($cName);exit;
        //$cName = $model->city($pid);
        //print_r($cName);exit;

        $arr = array('pName'=>$pName,'cName'=>$cName);
        //print_r($arr);exit;
        echo json_encode($arr);
        return;
    }

    /**
     * 查询出对应市级下面的所有县或者区
     * @return string
     */
    public function actionDistrict()
    {
        $id = RequestHelper::get('city_id', 1, 'intval');
        $model = new District();
        $info = $model->district($id);
        echo json_encode($info);
        return;
    }

    /**
     * 库房删除
     *
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $model = new Warehouse();
            $result = $model->getDelete($id);
            if ($result == 200) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }

    /**
     * 库房修改
     *
     * @return string
     */
    public function actionEdit()
    {
        //获取分公司的ID和name
        $branch_m = new CrmBranch();
        $where['status'] = 1;
        $bList = $branch_m->getList($where, "id,name");

        $id = RequestHelper::get('id');
        $model = new Warehouse();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, true, '*');

        $province_model = new Province();
        $ProvinceWhere = $item['province_id'];

        $pProvince = $province_model ->province_one($ProvinceWhere);
        $pPro = array('id'=>$ProvinceWhere, 'name'=>$pProvince['name']);


        $city_model = new City();
        $CityWhere = $item['province_id'];

        $pCity = $city_model ->city($CityWhere, "id,name");


        $district_model = new District();
        $DistrictWhere = $item['city_id'];

        $pDistrict = $district_model ->district($DistrictWhere, "id,name");


        $model->attributes = $item;
        $Warehouse = RequestHelper::post('Warehouse');
        if (!empty($Warehouse)) {
            $model->attributes = $Warehouse;
            $model = new Warehouse();
            $cond1 = ['and', ['=', 'sn', $Warehouse['sn']], ['=', 'id', $id]];
            $info = $model->getOneRecord($cond1, '', 'id');
            if (!empty($info)) {
                $list = $model->getDetailsByName($Warehouse['name'], $id);
                if (empty($list)) {
                    $result = $model->updateInfo($Warehouse, $cond);
                    if ($result == true) {
                        return $this->success('修改成功', '/storage/warehouse/index');
                    }
                } else {
                    $model->addError('name', '库房名称 不能重复');
                }
            }
        }

        return $this->render('edit', ['model' => $model,'bList'=>$bList,'item'=>$item,'pPro'=>$pPro,'pCity'=>$pCity,'pDistrict'=>$pDistrict]);
    }

    /**
     * 核查库房编号
     * @return array
     */
    public function actionCheck()
    {
        $sn = RequestHelper::get('sn', '', 'trim');
        $where = [];
        if (!empty($sn)) {
            $where = ['=', 'sn', $sn];
        }
        $model = new Warehouse();
        $info = $model->getOneRecord($where, '', 'id');
        if (empty($info)) {
            $res = array('msg'=>'', 'status'=>0, 'data'=>'');
            return json_encode($res);
        }
        $res = array('msg'=>'库房编号已存在！！！', 'status'=>1, 'data'=>'');
        return json_encode($res);
    }

}


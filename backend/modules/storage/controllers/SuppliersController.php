<?php

/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   SuppliersController.php
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      2015/8/20 9:15
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      sunsongsong@iyangpin.com
 */

namespace backend\modules\storage\controllers;

use backend\models\i500m\City;
use backend\models\i500m\District;
use backend\models\i500m\Log;
use backend\models\i500m\Product;
use backend\models\i500m\Province;
use backend\models\i500m\StorageOutGood;
use backend\models\i500m\SupplierGood;
use backend\models\i500m\SupplierInfo;
use backend\models\i500m\Warehouse;
use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use yii\data\Pagination;

/**
 * SuppliersController
 *
 * 供应商信息
 *
 * @category CRM
 * @package  SuppliersController
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class SuppliersController extends BaseController
{

    /**
     * 供应商信息
     * @return array
     */
    public function actionIndex()
    {
        $supplier = new SupplierInfo();
        $supplier_good = new SupplierGood();
        $log_model = new Log();//日志
        $post = \Yii::$app->request->getIsPost();

        if ($post) {
            $code = RequestHelper::post('code', 0, 'intval');
            $phone = RequestHelper::post('phone', 0, 'intval');
            $data['supplier_name'] = RequestHelper::post('name_one', '', 'trim');
            $data['name'] = RequestHelper::post('name', '', 'trim');
            $data['mobile'] = RequestHelper::post('mobile', 0, 'intval');
            $data['phone'] = $code."-".$phone;
            if ($code == 0 && $phone == 0) {
                $data['phone'] == 0;
            }
            $data['email'] = RequestHelper::post('email', '', 'trim');
            $data['storage_sn'] = RequestHelper::post('depots', '', 'trim');
            $data['storage_name'] = RequestHelper::post('dep_name', '', 'trim');
            $data['code'] = $this->gainId();//获取订单编号
            $data['admin_id'] = $this->admin_id;
            $data['remark'] = RequestHelper::post('explain', '', 'trim');

            $info['info_id'] = RequestHelper::post('info_id', '', 'trim');
            $info['info_name'] = RequestHelper::post('info_name', '', 'trim');
            $info['info_attr_value'] = RequestHelper::post('info_attr_value', '', 'trim');
            $info['info_pric'] = RequestHelper::post('info_pric', '', 'trim');
            $info['info_num'] = RequestHelper::post('info_num', '', 'trim');
            $info['info_sum'] = RequestHelper::post('info_sum', '', 'trim');
            $info['info_bar_code'] = RequestHelper::post('info_bar_code', '', 'trim');
            $info['info_remark'] = RequestHelper::post('info_remark', '', 'trim');

            if (empty($data['supplier_name'])) {
                return $this->error('供应方名称不能为空！！！', '/storage/warehouse/index');
            }
            if (empty($data['name'])) {
                return $this->error('联系人名称不能为空！！！', '/storage/warehouse/index');
            }
            if (empty($data['mobile']) && $data['phone'] == 0) {
                return $this->error('联系手机号或电话不能为空！！！', '/storage/warehouse/index');
            }
            if (empty($data['email'])) {
                return $this->error('邮箱不能为空！！！', '/storage/warehouse/index');
            }
            if (empty($data['storage_sn'])) {
                return $this->error('仓库不能为空！！！', '/storage/warehouse/index');
            }
            if (empty($data['remark'])) {
                return $this->error('入库说明不能为空！！！', '/storage/warehouse/index');
            }
            if (empty($info)) {
                return $this->error('入库商品不能为空！！！', '/storage/warehouse/index');
            }

            if (!empty($info)) {
                $info_id = explode(",", $info['info_id']);
                $info_name = explode(",", $info['info_name']);
                $info_attr_value = explode(",", $info['info_attr_value']);
                $info_pric = explode(",", $info['info_pric']);
                $info_num = explode(",", $info['info_num']);
                $info_sum = explode(",", $info['info_sum']);
                $info_bar_code = explode(",", $info['info_bar_code']);
                $info_remark = explode(",", $info['info_remark']);

                $info_data = array();
                foreach ($info_id as $k => $v) {
                    $info_data[$k]['good_id'] = $v;
                    $info_data[$k]['good_name'] = $info_name[$k];
                    $info_data[$k]['attr_value'] = $info_attr_value[$k];
                    $info_data[$k]['price'] = $info_pric[$k];
                    $info_data[$k]['num'] = $info_num[$k];
                    $info_data[$k]['bar_code'] = $info_bar_code[$k];
                    $info_data[$k]['sum_price'] = $info_sum[$k];
                    $info_data[$k]['remark'] = $info_remark[$k];
                }
            }

            if (!empty($data)) {
                $info = $supplier->insertOneRecord($data);
                if ($info['result'] == 1) {
                    $supplier_id = $info['data']['new_id'];
                    if (!empty($info_data)) {
                        foreach ($info_data as $v) {
                            $v['supplier_id'] = $supplier_id;
                            $v['storage_sn'] = RequestHelper::post('depots', '', 'trim');
                            $supplier_good->insertInfo($v);

                            //日志
                            $goods = "供应商入库关联商品>商品ID:".$v['good_id'].",关联入库单ID:".$v['supplier_id'].",商品名称:".$v['good_name'].",商品单价:".$v['price'].",商品数量:".$v['num'].",库房编码:".$v['storage_sn'];
                            $log_model->recordLog($goods, 8);
                        }
                    }

                    //日志
                    $content = "供货方:".$data['supplier_name'].",联系人：".$data['name'].",联系电话:".$data['mobile'].",电子邮箱:".$data['email'].",仓库编号:".$data['storage_sn'].",仓库名称:".$data['storage_name'].",入库订单号:".$data['code'].",入库说明:".$data['remark'];
                    $log_model->recordLog($content, 8);
                    return $this->success('提交数据成功！！！', '/storage/suppliers/supplier-list');
                }
                return $this->error('提交数据失败！！！', '/storage/warehouse/index');
            }
        }

        return $this->render('index', ['data' => '', 'ware' => $this->warename()]);
    }

    /**
     * 获取id及拼凑订单编号
     * @return array
     */
    public function gainId()
    {
        $supplier = new SupplierInfo();
        $where = ['>', 'id', 0];
        $info = $supplier->getoneinfo($where, 'id', 'id desc');
        $num = $info['id']+1;
        if (strlen($info['id']) == "1") {
            $num = "00".($info['id']+1);
        }
        if (strlen($info['id']) == "2") {
            $num = "0".($info['id']+1);
        }
        if (empty($info)) {
            $num = "001";
        }
        //获取当前时间
        $date = date('Ymd');
        $code = "IN".$date."-".$num;
        $res = array('k'=>$code);
        return $res['k'];
    }
    /**
     * 仓库id和name
     * @return array
     */
    public function warename()
    {
        $model = new Warehouse();
        $where = ['>', 'id', 0];
        $list = $model->getList($where, 'name,sn', "id desc");
        if (empty($list)) {
            $list = array();
        }
        return $list;
    }

    /**
     * 仓库id和name
     * @return array
     */
    public function actionWarehouse()
    {
        $sn = RequestHelper::get('sn', '', 'trim');
        $model = new Warehouse();
        $province = new Province();
        $city = new City();
        $district = new District();
        $where = " sn = '{$sn}'";
        $list = $model->getOneRecord($where, '', "*");
        if (empty($list)) {
            $res = array('msg'=>'', 'status'=>0, 'info'=>'数据查询出错！！！');
            return json_encode($res);
        } else {
            $address = "";
            if (!empty($list['province_id'])) {
                $p_name = $province->getOneRecord(" id = '{$list['province_id']}'", '', 'name');
                $address .= $p_name['name'];
            }
            if (!empty($list['city_id'])) {
                $c_name = $city->getOneRecord(" id = '{$list['city_id']}'", '', 'name');
                $address .= $c_name['name'];
            }
            if (!empty($list['district_id'])) {
                $d_name = $district->getOneRecord(" id = '{$list['district_id']}'", '', 'name');
                $address .= $d_name['name'];
            }

            $list['dizhi'] = $address.$list['address'];
            $res = array('msg'=>'', 'status'=>1, 'info'=>$list);
            return json_encode($res);
        }
    }

    /**
     * 添加商品
     * @return array
     */
    public function actionGoodAdd()
    {
        $this->layout = 'dialog';
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 5;
        $name = RequestHelper::get('name', '', 'trim');
        $where = ['and', ['>', 'id', 0], ['!=', 'name', '']];
        if (!empty($name)) {
            $where[] = ['like', 'name', $name];
        }
        $goods = new Product();
        $files = "id,name,attr_value,bar_code";
        $list = $goods->getPageList($where, $files, 'id desc', $page, $page_size);
        if (empty($list)) {
            $list = array();
        }
        $count = $goods->getCount($where);

        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);//分页

        $data = array(
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count,
            'data' => $list,
            'name' => $name
        );
        return $this->render('goodlist', $data);

    }

    /**
     * 管理采购入库单
     * @return array
     */
    public function actionSupplierList()
    {

        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 10;
        $supplier = new SupplierInfo();

        $name = RequestHelper::get('supplier_name', '', 'trim');
        $time_one = RequestHelper::get('time_one', '', 'trim');
        $time_two = RequestHelper::get('time_two', '', 'trim');
        $where = ['and', ['>', 'id', 0], ['!=', 'supplier_name', '']];
        if (!empty($name)) {
            $where[] = ['like', 'supplier_name', $name];
        }
        if (!empty($time_one)) {
            $where[] = ['>=', 'create_time', $time_one];
        }
        if (!empty($time_two)) {
            $where[] = ['<=', 'create_time', $time_two];
        }
        $files = "id,supplier_name,code,create_time,remark";
        $list = $supplier->getPageList($where, $files, 'id desc', $page, $page_size);
        $count = $supplier->getCount($where);

        if (empty($list)) {
            $list = array();
        }
        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);//分页

        $data = array(
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count,
            'data'=>$list,
            'time_one'=>$time_one,
            'time_two'=>$time_two,
            'name'=>$name
        );

        return $this->render('list', $data);
    }

    /**
     * 管理入库单商品明细
     * @return array
     */
    public function actionView()
    {
        $page_size = 10;
        $page = RequestHelper::get('page', 1, 'intval');
        $id = RequestHelper::get('id', 0, 'intval');
        $su_good = new SupplierGood();
        $where = [];
        if ($id != 0) {
            $where = ['=', 'supplier_id', $id];
        }

        $files = "id,supplier_id,good_id,good_name,attr_value,price,num,sum_price,remark,create_time";
        $list = $su_good->getPageList($where, $files, 'id desc', $page, $page_size);
        $count = $su_good->getCount($where);

        if (empty($list)) {
            $list = array();
        }
        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);//分页

        $data = array(
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count,
            'data'=>$list
        );
        return $this->render('view', $data);
    }

    /**
     * 库存数据
     * @return array
     */
    public function actionStorage()
    {
        $page_size = 10;
        $page = RequestHelper::get('page', 1, 'intval');
        $sup_good = new SupplierGood();
        $stor_out_good = new StorageOutGood();
        $where = ['!=', 'good_id', 0];
        $sup_list = $sup_good->getPage($where, 'id,sum(num) as total,good_name,good_id,bar_code,attr_value', 'good_id', 'id desc', $page, $page_size);

        $stor_list = $stor_out_good->getAll($where, 'id,sum(num) as total,good_id', 'good_id', 'id desc');

        if (empty($sup_list)) {
            $sup_list = array();
        }
        $count = $sup_good->getNum($where, 'id,sum(num) as total,good_name,good_id,bar_code,attr_value', 'good_id');
        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);//分页
        $list = array();
        if (!empty($sup_list)) {
            foreach ($sup_list as $v) {
                foreach ($stor_list as $val) {
                    if ($v['good_id'] == $val['good_id']) {
                        $v['allnum'] = $v['total'] - $val['total'];
                    }
                }
                $list[] = $v;
            }
        }
        $data = array(
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count,
            'data'=>$list
        );
        return $this->render('storage', $data);
    }

}


<?php

/**
 * 商品出库单管理
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   StorageOutController.php
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      2015/8/25 9:15
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      sunsongsong@iyangpin.com
 */

namespace backend\modules\storage\controllers;

use backend\models\i500m\Log;
use backend\models\i500m\StorageOut;
use backend\models\i500m\StorageOutGood;
use backend\models\i500m\SupplierGood;
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
class StorageOutController extends BaseController
{

    /**
     * 供应商信息
     * @return array
     */
    public function actionIndex()
    {
        $storage = new StorageOut();
        $storage_good = new StorageOutGood();
        $log_model = new Log();
        $post = \Yii::$app->request->getIsPost();

        if ($post) {
            $data['status'] = RequestHelper::post('reason', 0, 'intval');
            $data['code'] = $this->gainId();//获取订单编号
            $data['admin_id'] = $this->admin_id;
            $data['storage_sn'] = RequestHelper::post('depots', '', 'trim');
            $data['remark'] = RequestHelper::post('explain', '', 'trim');

            $info['info_id'] = RequestHelper::post('info_id', '', 'trim');
            $info['info_name'] = RequestHelper::post('info_name', '', 'trim');
            $info['info_attr_value'] = RequestHelper::post('info_attr_value', '', 'trim');
            $info['info_pric'] = RequestHelper::post('info_pric', '', 'trim');
            $info['info_num'] = RequestHelper::post('info_num', '', 'trim');
            $info['info_sum'] = RequestHelper::post('info_sum', '', 'trim');
            $info['info_bar_code'] = RequestHelper::post('info_bar_code', '', 'trim');
            $info['info_storage_sn'] = RequestHelper::post('info_storage_sn', '', 'trim');
            $info['info_remark'] = RequestHelper::post('info_remark', '', 'trim');

            if ($data['status'] == 0) {
                return $this->error('出库原因选择错误！！！', '/storage/warehouse/index');
            }

            if (empty($data['remark'])) {
                return $this->error('出库说明不能为空！！！', '/storage/warehouse/index');
            }
            if (empty($info)) {
                return $this->error('出库商品不能为空！！！', '/storage/warehouse/index');
            }

            if (!empty($info)) {
                $info_id = explode(",", $info['info_id']);
                $info_name = explode(",", $info['info_name']);
                $info_attr_value = explode(",", $info['info_attr_value']);
                $info_pric = explode(",", $info['info_pric']);
                $info_num = explode(",", $info['info_num']);
                $info_sum = explode(",", $info['info_sum']);
                $info_bar_code = explode(",", $info['info_bar_code']);
                $info_storage_sn = explode(",", $info['info_storage_sn']);
                $info_remark = explode(",", $info['info_remark']);

                $info_data = array();
                foreach ($info_id as $k => $v) {
                    $info_data[$k]['good_id'] = $v;
                    $info_data[$k]['good_name'] = $info_name[$k];
                    $info_data[$k]['attr_value'] = $info_attr_value[$k];
                    $info_data[$k]['price'] = $info_pric[$k];
                    $info_data[$k]['num'] = $info_num[$k];
                    $info_data[$k]['bar_code'] = $info_bar_code[$k];
                    $info_data[$k]['storage_sn'] = $info_storage_sn[$k];
                    $info_data[$k]['sum_price'] = $info_sum[$k];
                    $info_data[$k]['remark'] = $info_remark[$k];
                }
            }

            if (!empty($data)) {
                $info = $storage->insertOneRecord($data);
                if ($info['result'] == 1) {
                    $storage_id = $info['data']['new_id'];
                    if (!empty($info_data)) {
                        foreach ($info_data as $v) {
                            $v['out_id'] = $storage_id;
                            $storage_good->insertInfo($v);
                            //日志
                            $goods = "商品出库关联商品>商品ID:".$v['good_id'].",关联出库单ID:".$v['out_id'].",商品名称:".$v['good_name'].",商品单价:".$v['price'].",商品数量:".$v['num'].",仓库编号:".$v['storage_sn'];
                            $log_model->recordLog($goods, 8);
                        }
                    }

                    //日志
                    $content = "出库订单表>出库原因:".$data['status'].",出库订单编号：".$data['code'].",出库说明:".$data['remark'].",仓库编号:".$data['storage_sn'];
                    $log_model->recordLog($content, 8);

                    return $this->success('提交数据成功！！！', '/storage/storage-out/list');
                }
                return $this->error('提交数据失败！！！', '/storage/storage-out/index');
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
        $storage = new StorageOut();
        $where = ['>', 'id', 0];
        $info = $storage->getoneinfo($where, 'id', 'id desc');
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
        $code = "OUT".$date."-".$num;
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
        $where = ['and', ['>', 'id', 0], ['=', 'status', 2]];
        $list = $model->getList($where, 'name,sn', "id desc");
        if (empty($list)) {
            $list = array();
        }
        return $list;
    }

    /**
     * 添加商品
     * @return array
     */
    public function actionGoodAdd()
    {
        $this->layout = 'dialog';
        $page = RequestHelper::get('page', 1, 'intval');
        $sn = RequestHelper::get('sn', '', 'trim');
        $page_size = 5;
        $name = RequestHelper::get('name', '', 'trim');
        $where = ['and', ['>', 'id', 0]];
        if (!empty($name)) {
            $where[] = ['like', 'good_name', $name];
        }
        if (!empty($sn)) {
            $where[] = ['=', 'storage_sn', $sn];
        }
        $goods = new SupplierGood();
        $files = "id,good_id,sum(num) as total,good_name,attr_value,bar_code,storage_sn";
        $list = $goods->getPage($where, $files, 'good_id', 'id desc', $page, $page_size);
        if (empty($list)) {
            $list = array();
        }
        $count = $goods->getNum($where, $files, 'good_id');

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
    public function actionList()
    {

        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 10;
        $storage = new StorageOut();
        $ware = new Warehouse();

        $time_one = RequestHelper::get('time_one', '', 'trim');
        $time_two = RequestHelper::get('time_two', '', 'trim');
        $reason = RequestHelper::get('reason', '', 'trim');
        $where = ['and', ['>', 'id', 0]];

        if (!empty($reason)) {
            $where[] = ['=', 'status', $reason];
        }
        if (!empty($time_one)) {
            $where[] = ['>=', 'create_time', $time_one];
        }
        if (!empty($time_two)) {
            $where[] = ['<=', 'create_time', $time_two];
        }
        $files = "id,code,create_time,remark,status,storage_sn";
        $list = $storage->getPageList($where, $files, 'id desc', $page, $page_size);
        $count = $storage->getCount($where);

        if (empty($list)) {
            $list = array();
            $res = array();
        }
        foreach ($list as $v) {
            $ware_where = ['=', 'sn', $v['storage_sn']];
            $info = $ware->getOneRecord($ware_where, '', 'name');
            if (empty($info)) {
                $info['name'] = "";
            }
            $v['name'] = $info['name'];
            $res[] = $v;
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
            'data'=>$res,
            'time_one'=>$time_one,
            'reason'=>$reason,
            'time_two'=>$time_two
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
        $storage_good = new StorageOutGood();
        $where = [];
        if ($id != 0) {
            $where = ['=', 'out_id', $id];
        }

        $files = "id,out_id,good_id,good_name,attr_value,price,num,sum_price,remark,bar_code,create_time";
        $list = $storage_good->getPageList($where, $files, 'id desc', $page, $page_size);
        $count = $storage_good->getCount($where);

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
     * 指定商品库存
     * @return string
     */
    public function actionStock()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $sn = RequestHelper::get('sn', '', 'trim');
        $sup_goods = new SupplierGood();
        $out_goods = new StorageOutGood();
        $where = [];
        if ($id != 0) {
            $where = ['=', 'good_id', $id];
        }
        if (!empty($sn) && $id != 0) {
            $where = ['and',['=', 'good_id', $id], ['=', 'storage_sn', $sn]];
        }
        $info = $sup_goods->getOneInfo($where, 'good_id', '', 'sum(num) as stock');
        $out_info = $out_goods->getOneInfo($where, 'good_id', '', 'sum(num) as stock');

        if (empty($out_info)) {
            $out_info['stock'] = "0";
        }
        $data = $info['stock']-$out_info['stock'];

        if (empty($info)) {
            $res = array('msg' => '没有查到库存', 'status' => 0, 'info' => '');
            return json_encode($res);
        }
        $res = array('msg' => '', 'status' => 1, 'info' => $data);
        return json_encode($res);
    }
}


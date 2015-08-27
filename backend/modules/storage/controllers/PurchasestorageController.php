<?php
/**
 * 采购入库管理
 *
 * PHP Version 5
 * 采购入库相关
 *
 * @category  Admin
 * @package   Storage
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/5/26 上午11:13 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\modules\storage\controllers;

use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use backend\models\i500m\CrmPurchaseStorage;
use backend\models\i500m\CrmPurchaseStorageItem;
use backend\models\i500m\Supplier;
use backend\models\i500m\CrmUniqueCode;
use backend\models\i500m\Product;
use backend\models\i500m\SupplierOrder;
use backend\models\i500m\SupplierOrderDetails;
use yii\data\Pagination;

/**
 * 采购入库管理
 *
 * @category ADMIN
 * @package  Storage
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class PurchasestorageController extends BaseController
{
    private $_default_page_size = 2;

    //private $_default_arr_where = array();
    private $_default_str_andwhere = '';
    private $_default_arr_order = array();
    private $_default_str_field = '*';
    private $_default_int_offset = -1;
    private $_default_int_limit = -1;

    /**
     * 采购入库单列表页面
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionIndex()
    {
        //echo "actionIndex 制作中";exit;

        //$sp_id = RequestHelper::get('sp_id', 0, 'intval');
        $sp_kw = RequestHelper::get('sp_kw', '', 'trim');
        //$like_goods_name = RequestHelper::get('like_goods_name', '', 'trim');
        $create_time_start = RequestHelper::get('create_time_start', '', 'trim');
        $create_time_end = RequestHelper::get('create_time_end', '', 'trim');

        $page_num = RequestHelper::get('page', 1, 'intval');
        $page_size = $this->_default_page_size;

        $arr_select_param = array();
        $arr_where = array();
        $arr_andwhere = array();
        $arr_andwhere_param = array();

        //if (ctype_digit(strval($sp_kw)) && intval($sp_kw) > 0) {
        //    $arr_where['sp_id'] = intval($sp_kw);
        //} elseif ($sp_kw != '') {
        //    //$arr_andwhere[] = " sp_name like '%" . $sp_kw . "%' ";
        //    $arr_andwhere[] = " sp_name like :sp_name ";
        //    $arr_andwhere_param[':sp_name'] = "%" . $sp_kw . "%";
        //}
        if ($sp_kw != '') {
            //$arr_andwhere[] = " sp_name like '%" . $sp_kw . "%' ";
            $arr_andwhere[] = " sp_name like :sp_name ";
            $arr_andwhere_param[':sp_name'] = "%" . $sp_kw . "%";
        }
        $arr_select_param['sp_kw'] = $sp_kw;
        //if ($sp_id > 0) {
        //    $arr_where['sp_id'] = $sp_id;
        //    $arr_select_param['sp_id'] = $sp_id;
        //} else {
        //    $arr_select_param['sp_id'] = '';
        //}
        if ('' != $create_time_start) {
            //$arr_andwhere[] = " create_time>='" . $create_time_start . "00:00:00' ";
            $arr_andwhere[] = " create_time >= :create_time ";
            $arr_andwhere_param[':create_time'] = $create_time_start . ' 00:00:00';
            $arr_select_param['create_time_start'] = $create_time_start;
        } else {
            $arr_select_param['create_time_start'] = '';
        }
        if ('' != $create_time_end) {
            //$arr_andwhere[] = " create_time<='" . $create_time_end . "23:59:59' ";
            $arr_andwhere[] = " create_time <= :create_time ";
            $arr_andwhere_param[':create_time'] = $create_time_end. ' 23:59:59';
            $arr_select_param['create_time_end'] = $create_time_end;
        } else {
            $arr_select_param['create_time_end'] = '';
        }
        $str_andwhere = implode(" and ", $arr_andwhere);
        //echo "<pre>";print_r($arr_where);echo "</pre>";exit;

        if ($page_num <= 0) {
            $page_num = 1;
        }
        $int_offset = ($page_num - 1) * $page_size;
        $int_limit = $page_size;

        $str_field = '*';
        $arr_order = array(
            'id' => SORT_DESC
        );

        $model_order = new CrmPurchaseStorage();
        $arr_ruku_list = $model_order->getRecordList(
            $arr_where,
            array(),
            $str_andwhere,
            $arr_andwhere_param,
            $arr_order,
            $str_field,
            $int_offset,
            $int_limit
        );
        //echo "<pre>arr_ruku_list=";print_r($arr_ruku_list);echo "</pre>";exit;

        if (!empty($arr_ruku_list)) {
            $str_field = 'count(*) as num';
            $arr_count = $model_order->getRecordListCount($arr_where, array(), $str_andwhere, $arr_andwhere_param, $str_field);
            //echo "<pre>";print_r($arr_count);echo "</pre>";exit;
            $record_count = $arr_count['num'];
        } else {
            $record_count = 0;
        }
        //echo "record_count=".$record_count;exit;

        $pages = new Pagination(['totalCount' => $record_count, 'pageSize' => $page_size]);

        foreach ($arr_ruku_list as $key => $a_ruku) {
            $arr_tmp = $this->_getRukuDetail($a_ruku);
            $arr_ruku_list[$key]['detail'] = $arr_tmp;
        }
        //echo "<pre>arr_ruku_list=";print_r($arr_ruku_list);echo "</pre>";exit;


        $arr_view_data = array(
            'arr_ruku_list' => $arr_ruku_list,
            'arr_select_param' => $arr_select_param,
            'record_count' => $record_count,
            'pages' => $pages,
        );
        echo $this->render('index', $arr_view_data);
        return;
    }

    /**
     *  
     * 添加采购入库单
     * @author linxinliang <linxinliang@iyangpin.com>
     * @time   2015-5-29
     * @return string
     */
    public function actionAdd()
    {
        //采购入库单号RK-Ymd7位随机数
        $sn = 'RK-'.date('Ymd') . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
        return $this->render('add', ['sn'=>$sn]);
    }

    /**
     * 获取采购单信息
     * @author linxinliang <linxinliang@iyangpin.com>
     * @time   2015-5-29
     * @return array
     */
    public function actionAjaxGetPurchaseInfo()
    {
        $sn = RequestHelper::get('sn', '');
        if (!empty($sn) && preg_match("/^CG-/", $sn)) {
            $m_purchase = new SupplierOrder();
            $purchase_cond['order_sn']       = $sn; //单号
            $purchase_cond['status']         = '1'; //已确认
            //采购的ID[id] 供应商id[supplier_id] 供应商名称[supplier_name] 库房ID[storage_id]
            $purchase_field = 'id,supplier_id,consignee,storage_id,supplier_name';
            $purchaseInfo = $m_purchase->getInfo($purchase_cond, true, $purchase_field);
            if (!empty($purchaseInfo)) {
                $rs = [];
                $rs['supplier_order']['supplier_id']   = $purchaseInfo['supplier_id'];
                $rs['supplier_order']['id']            = $purchaseInfo['id'];
                $rs['supplier_order']['storage_id']    = $purchaseInfo['storage_id'];
                $rs['supplier_order']['supplier_name'] = $purchaseInfo['supplier_name'];
                $rs['supplier_order']['storage_id']    = $purchaseInfo['storage_id'];
                //供应商id  查询 供应商信息
                $sp_id = $purchaseInfo['supplier_id'];
                if (!empty($sp_id)) {
                    $m_supplier = new Supplier();
                    $supplier_cond['id'] = $sp_id;
                    $supplier_field = 'company_name,contact,mobile,phone,email';
                    $supplierInfo = $m_supplier->getInfo($supplier_cond, true, $supplier_field);
                    if (!empty($supplierInfo)) {
                        $rs['supplier']['company_name'] = $supplierInfo['company_name'];
                        $rs['supplier']['contact']      = $supplierInfo['contact'];
                        $rs['supplier']['mobile']       = $supplierInfo['mobile'];
                        $rs['supplier']['phone']        = $supplierInfo['phone'];
                        $rs['supplier']['email']        = $supplierInfo['email'];
                    }
                }
                //库房信息
                if (!empty($purchaseInfo['consignee'])) {
                    $rs['storage']['name'] = $purchaseInfo['consignee'];
                }
                //订单号 查询 商品信息
                $m_item = new SupplierOrderDetails();
                $item_cond['order_sn'] = $sn;
                $itm_field = 'goods_id,name,attr_value,bar_code';
                $itemList = $m_item->getList($item_cond, $itm_field);
                if (!empty($itemList)) {
                    $rs['itemlist'] = $itemList;
                } else {
                    $rs['itemlist'] = [];
                }
                echo json_encode(['code'=>'ok','data'=>$rs,'message'=>'ok']);
            } else {
                echo json_encode(['code'=>'no','data'=>[],'message'=>'抱歉，未查询到关联采购单信息！']);
            }
        } else {
            echo json_encode(['code'=>'no','data'=>[],'message'=>'非法请求']);
        }
    }

    /**
     * 添加采购入库单
     * @author linxinliang <linxinliang@iyangpin.com>
     * @time   2015-5-29
     * @return array
     */
    public function actionAjaxAddPurchase()
    {
        $purchaseData = [];
        $itemData = [];
        $purchaseData['purchase_order_id'] = RequestHelper::post('purchase_order_id', 0, 'intval');  //采购的ID
        if (empty($purchaseData['purchase_order_id'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'采购的ID不能为空！']));
        }
        $purchaseData['storage_sn'] = RequestHelper::post('storage_sn', ''); //采购入库单号
        if (empty($purchaseData['storage_sn'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'采购入库单号不能为空！']));
        }
        $purchaseData['sp_id'] = RequestHelper::post('sp_id', 0, 'intval'); //供应商id
        if (empty($purchaseData['sp_id'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'供应商id不能为空！']));
        }
        $purchaseData['sp_name'] = RequestHelper::post('sp_name', ''); //供应商名称
        if (empty($purchaseData['sp_name'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'供应商名称不能为空！']));
        }
        $purchaseData['storage_id'] = RequestHelper::post('storage_id', 0, 'intval'); //库房ID
        if (empty($purchaseData['storage_id'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'库房ID不能为空！']));
        }
        $purchaseData['admin_id'] = $this->admin_id ; //操作人ID
        if (empty($purchaseData['admin_id'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'非法请求！']));
        }
        $purchaseData['create_time'] = RequestHelper::post('create_time', ''); //入库时间
        if (empty($purchaseData['create_time'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'入库时间不能为空！']));
        }
        $purchaseData['add_time'] = date('Y-m-d H:i:s', time()); //添加时间
        $purchaseData['remark'] = RequestHelper::post('remark', '');  //入库说明
        if (empty($purchaseData['remark'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'入库说明不能为空！']));
        }

        $itemData['goods_ids'] = RequestHelper::post('goods_ids', '');  //商品IDS
        $itemData['goods_names'] = RequestHelper::post('goods_names', '');  //商品名称
        $itemData['attr_values'] = RequestHelper::post('attr_values', '');  //商品规格
        $itemData['bar_codes'] = RequestHelper::post('bar_codes', '');  //商品条形码
        $itemData['good_numbers'] = RequestHelper::post('good_numbers', '');  //商品良品数
        $itemData['defective_numbers'] = RequestHelper::post('defective_numbers', '');  //商品次品数
        $itemData['goods_remarks'] = RequestHelper::post('goods_remarks', '');  //商品说明
        $itemData['purchase_storage_id'] = $purchaseData['purchase_order_id'];
        $itemData['create_time'] = RequestHelper::post('create_time', ''); //入库时间

        if (empty($itemData['goods_ids'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'商品IDS不能为空！']));
        } else {
            for ($i=0; $i<count($itemData['goods_ids']); $i++) {
                if ($itemData['good_numbers'][$i]==0 && $itemData['defective_numbers'][$i]==0) {
                    die (json_encode(['code'=>'no','data'=>[],'message'=>"商品ID[".$itemData['goods_ids'][$i]."]的商品，良品数和次品数，不能全部为0！"]));
                }
            }
        }
        if (empty($itemData['goods_names'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'商品名称不能为空！']));
        }
        if (empty($itemData['bar_codes'])) {
            die (json_encode(['code'=>'no','data'=>[],'message'=>'商品条形码不能为空！']));
        }
        $m_purchase = new CrmPurchaseStorage();
        $purchase_add_rs = $m_purchase->insertOneRecord($purchaseData);
        if ($purchase_add_rs) {
            //添加item 表
            if (!empty($itemData) && count($itemData['goods_ids']) > 1) {
                $m_item = new CrmPurchaseStorageItem();
                $item_data = [];
                $item_add_rs = false;
                for ($i=0; $i<count($itemData['goods_ids']); $i++) {
                    $item_data['purchase_storage_id'] = $purchase_add_rs['data']['new_id'];
                    $item_data['goods_id'] = $itemData['goods_ids'][$i];
                    $item_data['goods_name'] = $itemData['goods_names'][$i];
                    $item_data['attr_value'] = $itemData['attr_values'][$i];
                    $item_data['bar_code']   = $itemData['bar_codes'][$i];
                    $item_data['good_number'] = $itemData['good_numbers'][$i];
                    $item_data['defective_number'] = $itemData['defective_numbers'][$i];
                    $item_data['create_time'] = $itemData['create_time'];
                    $item_data['remark'] = $itemData['goods_remarks'][$i];
                    $item_add_rs = $m_item->insertInfo($item_data);
                }
                if ($item_add_rs) {
                    echo json_encode(['code'=>'ok','data'=>[],'message'=>'保存成功']);
                } else {
                    echo json_encode(['code'=>'no','data'=>[],'message'=>'保存失败']);
                }
            }
        } else {
            echo json_encode(['code'=>'no','data'=>[],'message'=>'保存失败']);
        }
    }

    /**
     *  
     * 获取采购单
     */
    public function actionAjaxGetPurchase()
    {
        $sn = RequestHelper::get('sn', '');
        $m_order = new SupplierOrder();
        $order_info = $m_order->getInfo(['order_sn'=>$sn, 'status'=>1, 'ship_status'=>1], true, 'supplier_id,consignee,address,province,city,district');
        if ($order_info) {
            $sp_id = $order_info['supplier_id'];
            $m_sp = new Supplier();

        }
    }

    /**
     *  
     * 删除采购单
     */
    public function actionDel()
    {

    }

    /**
     *  
     * 查看
     */
    public function actionView()
    {

    }


    /**
     * Ajax操作
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionAjax()
    {
        $act = RequestHelper::get('act', '', 'trim');
        if ($act == 'getsp') {
            $this->_getSpList();
            return;
        }
    }

    /**
     * 获取供应商列表
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    private function _getSpList()
    {
        $keyword = RequestHelper::post('kw', '', 'trim');

        $arr_where = array();
        $str_andwhere = '';
        if (ctype_digit(strval($keyword)) && intval($keyword) > 0) {
            $arr_where['id'] = $keyword;
        } elseif ($keyword != '') {
            $arr_andwhere = array();
            $arr_andwhere[] = " company_name like '%" . $keyword . "%' ";
            $str_andwhere = implode(" and ", $arr_andwhere);
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => ''));
            return;
        }

        $str_field = 'id,company_name';
        $arr_order = array(
            'id' => SORT_ASC
        );

        $model_sp = new Supplier();

        //列表
        $arr_list = $model_sp->getList2(
            $arr_where,
            $str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>arr_salesman_list=";print_r($arr_salesman_list);echo "</pre>";exit;

        $arr_data = array(
            'data' => $arr_list,
            'info' => array(),
        );
        echo json_encode(array('code' => '200', 'data' => $arr_data, 'msg' => ''));
        return;
    }


    /**
     * 获取指定入库单号的详细信息
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $ruku_info 入库单详情数组
     *
     * @return array
     */
    private function _getRukuDetail($ruku_info)
    {
        $ruku_id = $ruku_info['id'];
        $sp_order_id = $ruku_info['purchase_order_id'];

        $model_crm_purchase_storage_item = new CrmPurchaseStorageItem();
        //$model_sp_order = new SupplierOrder();
        $model_sp_order_detail = new SupplierOrderDetails();

        $arr_where = array('purchase_storage_id' => $ruku_id);
        $arr_detail = $model_crm_purchase_storage_item->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            $this->_default_str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>";print_r($arr_detail);echo "</pre>";exit;

        $arr_where = array('order_id' => $sp_order_id);
        $str_field = 'goods_id,num,price,total,freight,retread_num';
        $arr_order_detail = $model_sp_order_detail->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>";print_r($arr_order_detail);echo "</pre>";exit;
        $map_goods_2_order_detail = array();
        foreach ($arr_order_detail as $key => $value) {
            $map_goods_2_order_detail[$value['goods_id']] = $value;
        }
        unset($arr_order_detail);
        //echo "<pre>";print_r($map_goods_2_order_detail);echo "</pre>";exit;

        $arr_return = array();
        foreach ($arr_detail as $key => $value) {
            if (isset($map_goods_2_order_detail[$value['goods_id']])) {
                $arr_return[] = array_merge($value, $map_goods_2_order_detail[$value['goods_id']]);
            } else {
                $arr_tmp = array('zstatus' => 0, 'zgoodsid' => $value['goods_id'], 'zmsg' => '此sp订单详情中没有此商品id');
                $arr_return[] = array_merge($value, $arr_tmp);
            }
        }
        //echo "<pre>";print_r($arr_return);echo "</pre>";exit;

        return $arr_return;
    }

    /**
     *  
     * 生成唯一码
     */
    public function actionCreateCode()
    {
        $purchase_storage_id = RequestHelper::get('ps_id', 0, 'intval');

        $m_purchase_storage = new CrmPurchaseStorage();
        $storage_info = $m_purchase_storage->getInfo(['id'=>$purchase_storage_id], true, 'id, storage_sn, status, is_create_code');
        if ($storage_info && 1 == $storage_info['status'] && 0 == $storage_info['is_create_code']) {
            $m_purchase_storage_item = new CrmPurchaseStorageItem();
            $list = $m_purchase_storage_item->getList(['purchase_storage_id'=>$purchase_storage_id]);
            $code = [];
            $m_product = new Product();
            $m_unique = new CrmUniqueCode();

            foreach ($list as $k => $v) {
                $info = $m_product->getInfo(['bar_code'=>$v['bar_code']], true, 'id');
                $unique = $m_unique->getInfo(['bar_code'=>$v['bar_code']], true, 'unique_code', 'unique_code desc');
                $temp = 1;
                if ($v['good_number'] > 0) {
                    for ($i = 0; $i < $v['good_number']; $i++ ) {
                        $tmp_code = [];
                        $tmp_code['bar_code'] = $v['bar_code'];
                        $tmp_code['product_id'] = $info['id'];
                        $tmp_code['status']   = 1;
                        $tmp_code['purchase_storage_id'] = $purchase_storage_id;
                        $tmp_code['create_time'] = date('Y-m-d H:i:s');
                        $tmp_code['unique_code'] = $this->_create_code($info['id'], $unique['unique_code'], $temp);
                        $code[] = $tmp_code;
                        $temp++;
                    }
                    $list[$k]['code'][] = $tmp_code;
                }

                if ($v['defective_number'] > 0) {
                    for ($i = 0; $i < $v['defective_number']; $i++ ) {
                        $tmp_code = [];
                        $tmp_code['bar_code'] = $v['bar_code'];
                        $tmp_code['product_id'] = $info['id'];
                        $tmp_code['status']   = 0;
                        $tmp_code['purchase_storage_id'] = $purchase_storage_id;
                        $tmp_code['create_time'] = date('Y-m-d H:i:s');
                        $tmp_code['unique_code'] = $this->_create_code($info['id'], $unique['unique_code'], $temp);
                        $code[] = $tmp_code;
                        $list[$k]['code'][] = $tmp_code;
                        $temp++;
                    }
                }
            }
            if ($code) {
                $m_unique->insertMore($code);
                $m_purchase_storage->updateInfo(['is_create_code'=>1], ['id'=>$purchase_storage_id]);

                $this->_create_excel($list, $storage_info['storage_sn']);
            } else {
                echo '生成失败--请联系系统管理员';
            }
        } else {
            echo '生成失败--请检测采购单状态或已经生成过';
        }
    }

    /**
     *  
     * 生成唯一码
     */
    private function _create_code($product_id = 0, $unique_code = '', $temp = 1)
    {
        $start = 0;
        if ($unique_code) {
            $start += substr($unique_code, 0, 6);
        } else {
            $start += 100000;
        }
        $start += $temp;

        if ($product_id) {
            $code = $start.str_pad($product_id, 6, '0', STR_PAD_LEFT);
        } else {
            $code = $start. rand(100000, 999999);   //不存在商品id时后六位随机
        }
        return $code;
    }

    private function _create_excel($data = [], $storage_sn = ''){

        $obj = new \PHPExcel();

        $obj->getActiveSheet()->setCellValue('A1', '良品');
        $obj->getActiveSheet()->setCellValue('B1', '次品');

        $i = 2;
        foreach($data as $item){
            $obj->getActiveSheet()->setCellValue('A'.$i, $item['bar_code'].'111');
            $obj->getActiveSheet()->setCellValue('B'.$i, $item['goods_name'].'111');
            foreach ($item['code'] as $val) {
                $i++;
                if (1 == $val['status']) {
                    $obj->getActiveSheet()->setCellValue('A' . $i, $val['unique_code']);
                } else {
                    $obj->getActiveSheet()->setCellValue('B' . $i, $val['unique_code']);
                }
            }
        }

        //设置导出文件名
        $outputFileName = $storage_sn.'.xls';
        $xlsWriter = new \PHPExcel_Writer_Excel5($obj);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save( "php://output" );
    }

}
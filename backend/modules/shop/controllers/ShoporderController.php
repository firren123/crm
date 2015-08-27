<?php
/**
 * 简介1
 * 商家订单类
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  ShoporderController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/22 上午9:52
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\shop\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Admin;
use backend\models\i500m\Product;
use backend\models\i500m\Shop;
use backend\models\shop\OrderLog;
use backend\models\shop\ShopDetailOrder;
use backend\models\shop\ShopProduct;
use backend\models\i500m\OrderDetail;
use backend\models\shop\ShopPurchase;
use yii;
use backend\models\shop\ShopOrder;
use yii\data\Pagination;
use common\helpers\RequestHelper;
use yii\web\Controller;

class ShoporderController extends BaseController
{
    public $enableCsrfValidation = false;
    public static $pay_status_data = array(
        -1=>'全部',
        0 => '未支付',
        1 => '已支付'
    );
    public static $ship_status_data = array(
        -1=>'全部',
        0 => '待发货',
        1 => '待收货',
        2 => '已签收',
        3 => '有退货'

    );
    public static $status_data = array(
        -2=>'全部',
        0 => '未确认',
        1 => '已确认',
        2 => '取消',
        -1 => '删除'
    );
    public static $pay_type_data = array(
//        0 => '',
        -1=>'全部',
        1 => '支付宝',
        2 => '银联',
        3 => '货到付款'
    );

    /**
     * 简介：
     * 商家订单列表`
     * @author  lichenjun@iyangpin.com。
     * @return  string
     */
    public function actionIndex()
    {
        $shop_m = new Shop();
        $start_time = RequestHelper::get('start_time','');   //支付时间开始
        $end_time = RequestHelper::get('end_time','');        //支付时间结束
        $p = RequestHelper::get('page', '1', 'intval');                //当前页
        $order_sn = RequestHelper::get('order_sn','');        //订单号
        $order_sn = htmlspecialchars($order_sn,ENT_QUOTES,"UTF-8");
        $ship_status = RequestHelper::get('ship_status',-1);  //订单物流状态
        $pay_status = RequestHelper::get('pay_status',-1);
        $status = RequestHelper::get('status',-2);
        $shop_name = RequestHelper::get('shop_name','');
        $where =' 1 ';
        if(!in_array($this->quanguo_city_id,$this->city_id)){
            $city_id_str = implode(',',$this->city_id);
            $where .=" and city in(" . $city_id_str.")";
        }
        if($start_time && $end_time){
            if($start_time >$end_time){
                return $this->error('开始时间不能大于结束时间','/shop/shoporder/index');
            }
        }
        if ($start_time) {
            $where .= " and create_time>'" . $start_time . " 00:00:00'";
        }
        if ($end_time) {
            $where .= " and create_time<'" . $end_time . " 23:59:59'";
        }
        if($ship_status!=-1){
            $where .= " and ship_status =" . $ship_status;
        }
        if($status!=-2){
            $where .= " and status =" .$status;
        }
        if($pay_status !=-1){
            $where .=" and pay_status=".$pay_status;
        }
        if($shop_name){

            $shop_id = $shop_m->getList(array('shop_name' => $shop_name), "id");
            if($shop_id){
                $arr = [];
                foreach($shop_id as $k =>$v){
                    $arr[] = $v['id'];
                }
                $ids = implode(',',$arr);
                $where .= " and shop_id in($ids)";
            }else{
                return $this->error('商家名不存在','/shop/shoporder/index');
            }

        }


        if ($order_sn && $order_sn != '订单编号') {
            $where .= " and order_sn='" . $order_sn . "'";
        }
        $model = new ShopOrder();
        $result_arr = array();
        $list = $model->getPageList($where, '*', 'id desc', $p, $this->size);
        foreach($list as $k => $v) {
            if ($this->_check($v['id'])) {
                array_push($result_arr, $v['id']);
            }
            $shop_info = $shop_m->getInfo(array('id' => $v['shop_id']), true, 'shop_name');
            $list[$k]['shop_name'] = $shop_info['shop_name'];
        }
        $count = $model->getCount($where);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render('index', [
            'pages' => $pages,
            'list' => $list,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
            'order_sn'=>$order_sn,
            'status'=>$status,
            'ship_status' =>$ship_status,
            'pay_status'=>$pay_status,
            'shop_name'=>$shop_name,
            'status_data' => ShoporderController::$status_data,
            'ship_status_data' => ShoporderController::$ship_status_data,
            'pay_status_data' => ShoporderController::$pay_status_data,
            'result_arr' => $result_arr,
        ]);
    }

    /**
     * 简介：检查订单号存不存在珍品
     *
     * @author  songjiankang@iyangpin.com。
     */
    private function _check($o_id = ''){
        $model_detail = new ShopDetailOrder();
        $p_id = $model_detail->getList(array('o_id'=>$o_id),'p_id');
        $p_id_arr = array();
            foreach($p_id as $v){
                $p_id_arr[] = $v['p_id'];
            }
        $model_product = new Product();
        $cate_id_arr = array();
        if(!empty($p_id_arr)){
            foreach($p_id_arr as $value){
                $cate_id = $model_product->getList(array('id'=>$value),'cate_first_id');
                $cate_id_arr[]=$cate_id[0]['cate_first_id'];
            }
        }
        return in_array('3043',$cate_id_arr);
    }

    /**
     * 简介：详情页面提交处理页面
     * @author  lichenjun@iyangpin.com。
     */
    public function actionEdits()
    {
        $order_sn = RequestHelper::post('order_sn');
        $ship_status = RequestHelper::post('ship_status', '', 'intval');
        $pay_status = RequestHelper::post('pay_status', '', 'intval');
        $status = RequestHelper::post('status', '', 'intval');
        $remark = RequestHelper::post('remark');
        $map = array();
        $log_info = \Yii::$app->user->identity->username;
        if ('' != $pay_status) {
            $map['pay_status'] = $pay_status;
            if($pay_status == 1) {
                $log_info .= ' 确定用户收款了 订单号为'.$order_sn;
            } elseif ($pay_status == 2) {
                $log_info .= ' 取消用户付款 订单号为 '.$order_sn;
            }
            $log['pay_status'] = $pay_status;

        }
        if ('' != $status) {
            $map['status'] = $status;
            $log['status'] = $status;
            if($status == 1) {
                $log_info .= ' 确认了订单'.$order_sn;
            } elseif ($status == 2) {
                $log_info .= ' 取消了订单 '.$order_sn;
            }
        }
        if ('' != $ship_status) {
            $map['ship_status'] = $ship_status;
            $map['delivery_time'] = date("Y-m-d H:i:s", time());
            $log['ship_status'] = $ship_status;
            if($ship_status == 1) {
                $log_info .= ' 发货了订单'.$order_sn;
            } elseif ($ship_status == 2) {
                $log_info .= ' 确定收货了订单 '.$order_sn;
            } elseif ($ship_status == 3) {
                $log_info .= ' 操作了退货订单 '.$order_sn;
            }
        }

        if (!$order_sn) {
            return $this->error('订单号不能为空');
        }
        if (empty($remark) || empty($map)) {
            return $this->error('状态或备注不能为空');
        }
        $shop_model = new ShopOrder();
        $where = [];
        $where['order_sn'] = $order_sn;
        $shop_id = $shop_model->getFieldName($where, 'shop_id');
        if (empty($shop_id)) {
            return $this->error('此订单无效，订单未指定商家');
        }
        $shop = new Shop();
        $shop_name = $shop->getFieldName(['id'=>$shop_id], 'shop_name');
        if (empty($shop_name)) {
            return $this->error('无效的商家');
        }
        $time = date("Y-m-d H:i:s", time());
        $ret = $shop_model->updateAll($map, $where);
        if ($ret) {
            $k = array_keys($map);
            $v = array_values($map);

            $modelLog = new OrderLog();
//            $user_id = Yii::$app->user->identity->id;
//            $modelLog->admin_id = $user_id;
//            $modelLog->order_sn = $order_sn;
//            $modelLog->$k[0] = $v[0];
//            $modelLog->add_time = date("Y-m-d H:i:s", time());
//            $modelLog->oper = $remark;
//            $modelLog->type = 3;
//            $modelLog->insert();

            $log['oper'] = $remark;
            $log['order_sn'] = $order_sn;
            $log['log_info'] = $log_info . ' 备注:'.$remark;;
//        $map['add_time'] = date('Y-m-d H:i:s');
//        $map['create_time'] = time();
//        $map['type'] = 3;
//        $order_log->insertInfo($map);//操作备注

            $modelLog->recordLog($log);//操作备注
            //发货修改库存
            if ($ship_status==1) {
                $p_model = new Product();
                $ret = $p_model->reduceInventory($order_sn);
            }
            //收货修改商家库存
            if($ship_status==2){
                $shop_order_detail_m = new ShopDetailOrder();
                $ret = $shop_order_detail_m->getList(['order_sn'=>$order_sn],"*");

                $_model = new ShopProduct(); //商家商品表
                $_modelProduct = new Product();  //商品表
                foreach($ret as $k=>$v){
                    $details = $_modelProduct->getInfo(['id'=>$v['p_id']]);
                    $arr = [];
                    $arr['cat_id'] = $details['cate_first_id'];
                    $arr['brand_id'] = $details['brand_id'];
                    $arr['type'] = 2;
                    $arr['status'] = 2;
                    $arr['product_id'] = $v['p_id'];
                    $arr['product_sn'] = $details['bar_code'];
                    $arr['product_number'] = $v['num'];
                    $arr['shop_id'] = $v['shop_id'];
                    $arr['price'] = $v['price'];
                    $re = $_model->addProduct($arr);
                    if ($re) {
                        $buy_record = [
                            'product_id'=>$v['p_id'],
                            'shop_id'=>$v['shop_id'],
                            'shop_name'=>$shop_name,
                            'cat_id'=>$details['cate_first_id'],
                            'buy_number'=>$v['num'],
                            'surplus'=>$v['num'],
                            'goods_type'=>2,
                            'buy_price'=>$v['price'],
                            'buy_date'=>$time,
                            'order_sn'=>$v['order_sn'],
                            'status'=>0,


                        ];
                        $purchase = new ShopPurchase();
                        $purchase->insertInfo($buy_record);
                    }

                }
            }
        }
        if ($ret) {
            return $this->success('修改成功', '/shop/shoporder/detail?order_sn=' . $order_sn);
        }
        return $this->success('修改失败', '/shop/shoporder/detail?order_sn=' . $order_sn);


    }

    /**
     * 简介：批量处理订单操作
     * @author  lichenjun@iyangpin.com。
     */
    public function actionAlledit()
    {
        $user_id = yii::$app->user->id;
        $order_sn_arr = RequestHelper::post("order_sn", '');
        $type = RequestHelper::post("type", 0, 'intval');

        $map = array();
        if (0 == $type || '' == $order_sn_arr) {
            return $this->error('没有选择订单或操作类型');
        }
        switch ($type) {
            case 1:  //确认
                $map['status'] = 1;
                break;
            case 2:  //发货
                $map['ship_status'] = 1;
                $map['delivery_time'] = date("Y-m-d H:i:s", time());
                break;
            case 3:   //收款
                $map['pay_status'] = 1;
                break;
            default:
                break;
        }
        $shop_model = new ShopOrder();
        $ret = $shop_model->updateAll($map, array('order_sn' => $order_sn_arr));

        if ($ret) {
            $kk = array_keys($map);
            $vv = array_values($map);

            foreach ($order_sn_arr as $k => $v) {
                $modelLog = new OrderLog();
                $modelLog->admin_id = $user_id;
                $modelLog->$kk[0] = $vv[0];
                $modelLog->add_time = date("Y-m-d H:i:s", time());
                $modelLog->oper = '管理员批量修改订单状态';
                $modelLog->type = 3;
                $modelLog->order_sn = $v;
                $modelLog->insert();

                if ($type == 2) {
                    //修改标准库
                    $p_model = new Product();
                    $p_model->reduceInventory($v);
                }
            }

        }
        if ($ret) {
            return $this->success('修改成功', '/shop/shoporder/index');
        }
        return $this->success('修改失败', '/shop/shoporder/index');

    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionDetail()
    {
        $order_sn = RequestHelper::get("order_sn");
        $model = new ShopOrder();
        $map = ['order_sn'=>$order_sn];
        $data = $model->getDetail($map);
//        echo "<pre>";
//        print_r($data);
//        exit;
        //读取管理员操作日志
        $order_log_m = new OrderLog();
        $log_list = $order_log_m->getList(['order_sn'=>$order_sn,'type'=>3],"*",'id desc');
        $admin_n = new Admin();
        foreach($log_list as $k=>$v){
            $admin_id = $admin_n->getInfo(['id'=>$v['admin_id']],true,'name');
            $log_list[$k]['name'] = $admin_id['name'];
            $status_log = [];
            $status_log[] = $v['status']?ShoporderController::$status_data[$v['status']]:'';
            $status_log[] = $v['ship_status']?ShoporderController::$ship_status_data[$v['ship_status']]:'';
            $status_log[] = $v['pay_status']?ShoporderController::$pay_status_data[$v['pay_status']]:"";
            $log_list[$k]['status_log'] = implode('|',array_filter($status_log));

        }
        $shop = Shop::findOne($data['shop_id']);
        return $this->render('detail', [
            'model' => $model,
            'order_info' => $data,
            'log_list'=>$log_list,
            'status_type' => ShoporderController::$status_data,
            'ship_status_type' => ShoporderController::$ship_status_data,
            'pay_status_type' => ShoporderController::$pay_status_data,
            'pay_type_data' => ShoporderController::$pay_type_data,
            'shop_name' => $shop['shop_name'],
        ]);
    }


}
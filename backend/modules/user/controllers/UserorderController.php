<?php
/**
 * 简介
 * 用户订单类
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  UserorderController.php
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/26 下午7:52
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\user\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Admin;
use backend\models\i500m\City;
use backend\models\i500m\Coupons;
use backend\models\i500m\CrmConfig;
use backend\models\i500m\District;
use backend\models\i500m\OrderAllocation;
use backend\models\i500m\OrderDetail;
use backend\models\i500m\OrderLog;
use backend\models\i500m\PaySite;
use backend\models\i500m\Product;
use backend\models\i500m\Province;
use backend\models\i500m\QueueSms;
use backend\models\i500m\Shop;
use backend\models\i500m\ShopOrderBlack;
use backend\models\i500m\User;
use backend\models\i500m\UserOrder;
use backend\models\shop\ShopProduct;
use common\helpers\CurlHelper;
use common\helpers\FilePutContentHelps;
use yii;
use yii\data\Pagination;
use common\helpers\RequestHelper;
use yii\helpers\ArrayHelper;

/**
 * Class UserorderController
 * @category  PHP
 * @package   UserorderController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class UserorderController extends BaseController
{
    public $enableCsrfValidation = false;
    public $pay_status_data = array(
        0 => '未支付',
        1 => '支付中',
        2 => '已支付',
        3 => '退款中',
        4 => '退款成功',
        5 => '退款失败'
    );
    public $ship_status_data = array(
        -1 => '全部',
        0 => '待发货',
        1 => '配货',
        2 => '发货中',
        3 => '已发货(部分)',
        4 => '已发货',
        5 => '已收货',
    );
    public $status_data = array(
        0 => '未确认',
        1 => '已确认',
        2 => '已取消',
        4 => '商家接单',
        6 => '取消中',
        -1 => '删除',
    );
    public $pay_site_id_data = array();

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function init()
    {
        parent::init();
        //获取支付类型
        $pay_site = new PaySite();
        $pay_site_arr = $pay_site->getList(1, 'id,name');
        $this->pay_site_id_data[0] = '--';
        foreach ($pay_site_arr as $k => $v) {
            $this->pay_site_id_data[$v['id']] = $v['name'];
        }
    }


    /**
     * 简介：
     * 用户订单列表`
     * @author  lichenjun@iyangpin.com。
     * @return  string
     */
    public function actionIndex()
    {
        $order_sn = RequestHelper::get('order_sn', '');
        $username = RequestHelper::get('username', '');
        $order_sn = $order_sn == '订单编号' ? '' : $order_sn;
        $username = $username == '用户名' ? '' : $username;
        $start_time = RequestHelper::get('start_time');
        $end_time = RequestHelper::get('end_time');

        $status = RequestHelper::get('status', 999, 'intval');
        $pay_status = RequestHelper::get('pay_status', 999, 'intval');
        $pay_site_id = RequestHelper::get('pay_site_id', 0, 'intval');
        $ship_status = RequestHelper::get('ship_status', -1, 'intval');
        $shop_name = RequestHelper::get('shop_name', '');
        $page = RequestHelper::get('page', 1, 'intval');
        $down = RequestHelper::get('down', 0, 'intval');
        $where = array();
        $user_model = new User();
        $andWhere = [];
        if (strlen($order_sn) < 6 && strlen($order_sn) > 0) {
            return $this->error('订单号搜索至少6位末尾号码', '/user/userorder/index');
        }
        if ($order_sn) {
            $order_sn = htmlspecialchars($order_sn, ENT_QUOTES, "UTF-8");
            $andWhere[] = " order_sn like '%$order_sn' ";
        }

        if ($start_time && $end_time) {
            if ($start_time > $end_time) {
                return $this->error('开始时间不能大于结束时间', '/user/userorder/index');
            }
        }

        if ($start_time) {
            $andWhere[] = " create_time>'" . $start_time . " 00:00:00'";
        }
        if ($end_time) {
            $andWhere[] = " create_time<'" . $end_time . " 23:59:59'";
        }

        if ($ship_status != -1) {
            $where['ship_status'] = $ship_status;
        }
        if ($status != 999) {
            $where['status'] = $status;
        }

        if ($pay_status != 999) {
            $where['pay_status'] = $pay_status;
        }

        if ($pay_site_id > 0) {
            $where['pay_site_id'] = $pay_site_id;
        }
        if ($username) {
            $user_id = $user_model->getInfo(array('username' => $username), true, 'id');
            if ($user_id['id']) {
                $where['user_id'] = $user_id['id'];
            } else {
                return $this->error('用户名不存在', '/user/userorder/index');
            }

        }
        $shop_m = new Shop();
        if ($shop_name) {

            $shop_id = $shop_m->getList(array('shop_name' => $shop_name), "id");
            if ($shop_id) {
                $arr = [];
                foreach ($shop_id as $k => $v) {
                    $arr[] = $v['id'];
                }
                $where['shop_id'] = $arr;
            } else {

                return $this->error('商家名不存在', '/user/userorder/index');
            }

        }

        if (!in_array($this->quanguo_city_id, $this->city_id)) {

            $where['city'] = $this->city_id;
        }
        $andWhere = empty($andWhere) ? '' : implode(' and ', $andWhere);
        $model = new UserOrder();
        if ($down == 1) {
            error_reporting(E_ALL);
            $objPHPExcel = new \PHPExcel();
            $title = ['店铺名称','用户名','订单号','下单时间','支付状态','支付方式','发货状态','订单金额','优惠金额','商品名称','商品金额','条形码','收货人','手机号','地址'];
            $i = 1;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A".$i, $title[0])
                ->setCellValue("B".$i, $title[1])
                ->setCellValue("C".$i, $title[2])
                ->setCellValue("D".$i, $title[3])
                ->setCellValue("E".$i, $title[4])
                ->setCellValue("F".$i, $title[5])
                ->setCellValue("G".$i, $title[6])
                ->setCellValue("H".$i, $title[7])
                ->setCellValue("I".$i, $title[8])
                ->setCellValue("J".$i, $title[9])
                ->setCellValue("K".$i, $title[10])
                ->setCellValue("L".$i, $title[11])
                ->setCellValue("M".$i, $title[12])
                ->setCellValue("N".$i, $title[13])
                ->setCellValue("O".$i, $title[14]);
            $list = $model->getList2($where, $andWhere, ['create_time' => SORT_DESC], "*");
            $productModel = new Product();
            foreach ($list as $k => $v) {
                $username_info = $user_model->getInfo(array('id' => $v['user_id']), true, 'username');
                $username = $username_info['username'];
                $shop = $shop_m->getInfo(array('id' => $v['shop_id']), true, "shop_name");
                $shop_name = $shop['shop_name'];
                $order_info_model = new OrderDetail();
                $info = $order_info_model->getList(array('order_sn' => $v['order_sn']));
                foreach ($info as $kk => $vv) {
                    $code_bar = $productModel->getInfo(array('id' => $vv['p_id']), true, 'bar_code');
                    $pay_status_data ='';
                    $pay_site_id_data = '';
                    $ship_status_data = '';
                    if (isset($this->pay_status_data[$v['pay_status']])) {
                        $pay_status_data = $this->pay_status_data[$v['pay_status']];
                    }
                    if (isset($this->pay_site_id_data[$v['pay_site_id']])) {
                        $pay_site_id_data = $this->pay_site_id_data[$v['pay_site_id']];
                    }
                    if (isset($this->ship_status_data[$v['ship_status']])) {
                        $ship_status_data = $this->ship_status_data[$v['ship_status']];
                    }
                    $i++;
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue("A" . $i, $shop_name)//店铺名称
                        ->setCellValueExplicit("B" . $i, $username, \PHPExcel_Cell_DataType::TYPE_STRING)//用户名
                        ->setCellValueExplicit("C" . $i, $v['order_sn'], \PHPExcel_Cell_DataType::TYPE_STRING)//订单号
                        ->setCellValue("D" . $i, $v['create_time'])//下单时间
                        ->setCellValue("E" . $i, $pay_status_data)//支付状态
                        ->setCellValue("F" . $i, $pay_site_id_data)//支付方式
                        ->setCellValue("G" . $i, $ship_status_data)//发货状态
                        ->setCellValue("H" . $i, $v['total'])//订单金额
                        ->setCellValue("I" . $i, $v['dis_amount'])//优惠金额
                        ->setCellValue("J" . $i, $vv['name'])//商品名称
                        ->setCellValue("K" . $i, $vv['price'])//商品金额
                        ->setCellValueExplicit("L" . $i, $code_bar['bar_code'], \PHPExcel_Cell_DataType::TYPE_STRING)//条形码
                        ->setCellValue("M" . $i, $v['consignee'])//收货人
                        ->setCellValueExplicit("N" . $i, $v['mobile'], \PHPExcel_Cell_DataType::TYPE_STRING)//手机号
                        ->setCellValue("O" . $i, $v['address']);   //地址
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(21);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(16);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);

                }
            }
            $name = '用户订单'.time();
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$name.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            ob_clean();
            $objWriter->save('php://output');
            exit;
        }
        $count = $model->getListCount($where, $andWhere);
        $list = $model->getList2($where, $andWhere, ['create_time' => SORT_DESC], "*", ($page - 1) * $this->size, $this->size);
        $result_arr = [];
        foreach ($list as $k => $v) {
            if ($this->_check($v['id'])) {
                array_push($result_arr, $v['id']);
            }
            $username_info = $user_model->getInfo(array('id' => $v['user_id']), true, 'username');
            $list[$k]['username'] = $username_info['username'];
            $shop = $shop_m->getInfo(array('id' => $v['shop_id']), true, "shop_name");
            $list[$k]['shop_name'] = $shop['shop_name'];
        }
        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        $pay_site_list = $this->pay_site_id_data;
        unset($pay_site_list[0]);
        unset($pay_site_list[6]);

        $ship_status_data = $this->ship_status_data;
        unset($ship_status_data[1]);
        unset($ship_status_data[2]);
        unset($ship_status_data[3]);
        // var_dump($list);exit;
        $data_info = [
            'pages' => $pages,
            'data' => $list,
            'order_sn' => $order_sn,
            'username' => $username,
            'ship_status' => $ship_status,
            'pay_status' => $pay_status,
            'pay_site_id' => $pay_site_id,
            'status' => $status,
            'shop_name' => $shop_name,
            'pay_size_id' => $this->pay_site_id_data,
            'status_data' => $this->status_data,
            'ship_status_data' => $ship_status_data,
            'pay_status_data' => $this->pay_status_data,
            'pay_site_list' => $pay_site_list,
            'result_arr' => $result_arr,
            'start_time' => $start_time,
            'end_time' => $end_time,

        ];
        return $this->render('index', $data_info);
    }

    /**
     * 简介：检验订单存不存在珍品
     * @author  lichenjun@iyangpin.com。
     * @param string $o_id 订单号
     * @return bool
     */
    private function _check($o_id = '')
    {
        $model_detail = new OrderDetail();
        $p_id = $model_detail->getList(array('o_id' => $o_id), 'p_id');
        $p_id_arr = array();
        foreach ($p_id as $v) {
            $p_id_arr[] = $v['p_id'];
        }
        $model_product = new Product();
        $cate_id_arr = array();
        if (!empty($p_id_arr)) {
            foreach ($p_id_arr as $value) {
                $cate_id = $model_product->getList(array('id' => $value), 'cate_first_id');
                if ($cate_id) {
                    $cate_id_arr[] = $cate_id[0]['cate_first_id'];
                }

            }
        }
        return in_array('3043', $cate_id_arr);
    }

    /**
     * 简介：订单详情
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionDetail()
    {
        $order_sn = RequestHelper::get('order_sn');
        if (empty($order_sn)) {
            return $this->error('无效的订单号！');
        }
        $model = new UserOrder();
        $detail = $model->getInfo(['order_sn' => $order_sn]);
        if (!$detail) {
            return $this->error('无效的订单号！');
        }
        $shop_m = new Shop();
        $shop_info = $shop_m->getInfo(['id' => $detail['shop_id']], true, '*');
        $detail['shop_info'] = $shop_info;

        if ($detail['shop_info']) {
            $m_city = new City();
            $city = $m_city->getInfo(['id' => $detail['shop_info']['city']], true, 'name');
            if (!strstr($detail['shop_info']['address'], $city['name'])) {
                $m_province = new Province();
                $province = $m_province->getInfo(['id' => $detail['shop_info']['province']], true, 'name');
                $district_name = '';
                if ($detail['shop_info']['district']) {
                    $m_district = new District();
                    $district = $m_district->getInfo(['id' => $detail['shop_info']['district']], true, 'name');
                    $district_name = $district['name'];
                }

                $detail['shop_info']['address'] = $province['name'] . $city['name'] . $district_name . $detail['shop_info']['address'];
            }
            $detail['shop_info']['address'] = trim($detail['shop_info']['address']);
        }

        $od_model = new OrderDetail();
        $detail['goods_list'] = $od_model->getOrderDetailBySn($order_sn);
        $productModel = new Product();
        foreach ($detail['goods_list'] as $k => $v) {
            $image = $productModel->getInfo(array('id' => $v['p_id']), true, 'image');
            $detail['goods_list'][$k]['image'] = $image['image'];
        }

        //优惠信息
        $model = new Coupons();
        //var_dump($model);
        $coupon_id = ArrayHelper::getValue($detail, 'coupon_id', '');
        if (!empty($coupon_id)) {
            $detail['coupon'] = $model->getInfo(array('id' => $coupon_id));
        }

        //读取管理员操作日志
        $order_log_m = new OrderLog();
        $log_list = $order_log_m->getList(['order_sn' => $order_sn, 'type' => 3], "*", 'id desc');
        $admin_n = new Admin();
        foreach ($log_list as $k => $v) {
            $admin_id = $admin_n->getInfo(['id' => $v['admin_id']], true, 'name');
            $log_list[$k]['name'] = $admin_id['name'];
            $status_log = [];
            $status_log[] = $v['status'] ? $this->status_data[$v['status']] : '';
            $status_log[] = $v['ship_status'] ? $this->ship_status_data[$v['ship_status']] : '';
            $status_log[] = $v['pay_status'] ? $this->pay_status_data[$v['pay_status']] : "";
            $log_list[$k]['status_log'] = implode('|', array_filter($status_log));

        }
        $data_info = ['order_info' => $detail,
            'log_list' => $log_list,
            'pay_type_data' => $this->pay_site_id_data,
            'status_data' => $this->status_data,
            'ship_status_data' => $this->ship_status_data,
            'pay_status_type' => $this->pay_status_data,
        ];
        return $this->render('detail', $data_info);

    }


    /**
     * 简介：详情页面提交订单处理页面
     * type    自定义  1：订单状态  2：配送状态
     * @author  zhaochengqiang@iyangpin.com。
     * @return null
     */
    public function actionEdits()
    {
        $type = 1;
        $order_sn = RequestHelper::post('order_sn');
        $o_id = RequestHelper::post('o_id');
        $ship_status = RequestHelper::post('ship_status', '', 'intval');
        $status = RequestHelper::post('status', '', 'intval');
        $remark = RequestHelper::post('remark');
        $sts = '';
        $log_info = \Yii::$app->user->identity->username;
        if (!$order_sn || !$o_id) {
            return $this->error('订单不能为空');
        }
        if ('' == $ship_status && '' == $status) {
            return $this->error('状态不能为空');
        }
        if ('' != $status) {
            $sts = $status;  //1确认 2取消
            $map['status'] = $status;
            if ($status == 1) {
                $log_info .= ' 确认了订单' . $order_sn;
            } elseif ($status == 2) {
                $log_info .= ' 取消了订单 ' . $order_sn;
            }

        }
        if ('' != $ship_status) {
            $type = 2;
            $sts = $ship_status;  //4发货 5收货
            $map['ship_status'] = $ship_status;
            if ($ship_status == 4) {
                $log_info .= ' 发货了订单' . $order_sn;
            } elseif ($ship_status == 5) {
                $log_info .= ' 确定收货了订单 ' . $order_sn;
            }
        }

        if (empty($remark)) {
            return $this->error('订单操作备注不能为空');
        }
        $model = new UserOrder();
        $w = [];
        $w['order_sn'] = $order_sn;
        if (!in_array($this->quanguo_city_id, $this->city_id)) {
            $w['city'] = $this->city_id;
        }
        $order_status = $model->getInfo($w);
        if (!$order_status) {
            return $this->error('订单不存在');
        }
        if ($order_status['status'] == 2) {
            return $this->error('订单已取消,不能操作', '/user/userorder/detail?order_sn=' . $order_sn);
        }
        if ($order_status['status'] == 0 && $type == 2) {
            return $this->error('订单必须先确认', '/user/userorder/detail?order_sn=' . $order_sn);
        }

        $order_log = new OrderLog();
        $map['o_id'] = $o_id;
        $map['oper'] = $remark;
        $map['order_sn'] = $order_sn;
        $map['log_info'] = $log_info . ' 备注:' . $remark;;

        $ret = $this->editOrderStatus($order_sn, $sts, $type);
        if ($ret == 200) {
            $order_log->recordLog($map);//操作备注
            //取消之后要判断是否用过优惠券  并将其券恢复使用
            $info = $model->getInfo(['order_sn' => $order_sn]);
            $coupons_model = new Coupons();
            if ('' != $status) {
                if ($status == 2) {//  //1确认 2取消
                    if ($info['coupon_id']) {
                        $coupons_model->getCoupons($info['coupon_id'], $order_sn, 0);
                    }
                }
            }
            //发货减库存
            //pay_site_id=1为货到付款 ，线上支付的是支付减库存 2015-04-29
            if ($info['pay_site_id'] == 1 && $ship_status == 4) {
                $order_detail_model = new OrderDetail();
                //查询出购买的商品
                $order_arr = $order_detail_model->getList(['order_sn' => $order_sn]);
                $shop_product_model = new ShopProduct();
                foreach ($order_arr as $k => $v) {
                    $shop_product_model->reduceProductStock($v['shop_id'], $v['p_id'], $v['num']);

                }
            }
            //ship_status = 5添加销售量
            if ($ship_status == 5) {
                $order_detail_model = new OrderDetail();
                $order_arr = $order_detail_model->find()->where(['order_sn' => $order_sn])->asArray()->all();
                $shop_product_model = new ShopProduct();
                foreach ($order_arr as $k => $v) {
                    $shop_product_model->Up_sales_volume($v['shop_id'], $v['p_id'], $v['num']);
                }

            }
            //给商家发送短信
            if ($status == 1) {
                $queueSms_m = new QueueSms();
                $shop_m = new Shop();
                $shopinfo = $shop_m->getInfo(['id' => $info['shop_id']]);
                $data = array(
                    'mobile' => $shopinfo['mobile'],
                    'content' => '【i500】亲，您的店铺有新的订单，请登录i500商家后台查看',
                    'create_time' => date('Y-m-d H:i:s')
                );
                $queueSms_m->insertInfo($data);
            }

            return $this->success('订单操作成功', '/user/userorder/detail?order_sn=' . $order_sn);
        }
        return $this->error('订单操作失败', '/user/userorder/detail?order_sn=' . $order_sn);
    }

    /**
     * 简介：更改订单状态
     * @param int $order_sn 订单状态 1 确认 2取消 4发货 5收货
     * @param int $status   类型 1 订单确认状态 2发货状态
     * @param int $type     类型
     * @return int
     */
    public function editOrderStatus($order_sn, $status, $type)
    {
        $time = date('Y-m-d H:i:s');
        $code = 0;
        if ($order_sn && isset($status)) {
            $m_order = new UserOrder();
            if ($type != 2) {
                $order = $m_order->getInfo(['order_sn' => $order_sn], false, 'id,status,pay_status,pay_site_id,ship_status');

                if (empty($order['id'])) {
                    return $code = 104;
                }
                //订单已取消
                if (2 == $status && $order['status'] == 0) {
                    $order->status = $status;
                    $order->save();
                    $code = 200;
                } elseif (1 == $status && $order['status'] == 0) {
                    $order->status = $status;
                    $order->allocate_time = $time;
                    $order->save();
                    $code = 200;
                } else {
                    $code = 104;
                }
            } else {
                $order = $m_order->getInfo(['order_sn' => $order_sn], false, 'id,status,ship_status');
                if (empty($order['id'])) {
                    return $code = 104;
                }

                if (4 == $status) {
                    if ($order['ship_status'] >= $status) {
                        return $code = 103;
                    }
                } elseif (5 == $status) {
                    if ($order['ship_status'] == $status) {
                        return $code = 103;
                    } elseif ($order['ship_status'] < 4) {
                        return $code = 103;
                    }
                    $order->pay_status = 2;
                }
                $order->ship_status_time = $time;
                $order->ship_status = $status;
                $order->save();
                $code = 200;

            }
        }
        return $code;
    }

    /**
     * 简介：修改收货地址
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionEditOrderInfo()
    {
        $order_sn = RequestHelper::post('order_sn');
        $consignee = RequestHelper::post('consignee');
        $mobile = RequestHelper::post('mobile');
        $address = RequestHelper::post('address');
        $order_model = new UserOrder();
        if (!$order_sn) {
            echo $ret = $this->ajaxReturn(100, '订单为空');
            exit;
        }
        $data = [];
        if ($consignee) {
            $data['consignee'] = $consignee;
        }
        if ($mobile) {
            $data['mobile'] = $mobile;
        }
        if ($address) {
            $data['address'] = $address;
        }
        $old_order_info = $order_model->getInfo(['order_sn' => $order_sn]);
        $ret = $order_model->updateInfo($data, ['order_sn' => $order_sn]);
        if ($ret) {
            //写入日志
            $old_address = $old_order_info['consignee'] . ',' . $old_order_info['mobile'] . ',' . $old_order_info['address'];
            $new_address = $consignee . ',' . $mobile . ',' . $address;
            $order_log = new OrderLog();

            $map['o_id'] = $old_order_info['id'];
            $map['oper'] = '修改用户收货信息';
            $map['order_sn'] = $order_sn;
            $map['log_info'] = '把收货信息：' . $old_address . ',修改成：' . $new_address;
            $order_log->recordLog($map);//操作备注
            echo $ret = $this->ajaxReturn('200');
        } else {
            echo $ret = $this->ajaxReturn('100', '网络超时');
        }

    }

    /**
     * 简介：订单商家转移
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEditShop()
    {
        $this->layout = '/dialog';
        $order_sn = RequestHelper::get('order_sn');
        $orderModel = new UserOrder();
        $orderInfoModel = new OrderDetail();
        $configModel = new CrmConfig();
        $ProvinceModel = new Province();
        $cityModel = new City();
        $district = new District();
        $shopProduct = new ShopProduct();
        $ShopOrderBlack = new ShopOrderBlack();
        $shopModel = new Shop();
        $order_info = $orderModel->getInfo(['order_sn' => $order_sn]);
        $new_time_5 = date('Y-m-d H:i:s', strtotime('-5min'));
        if (!$order_info) {
            echo "订单不存在，请使用正确的订单号";
            exit;
        }
        if ($order_info['status'] != 1) {
            echo "订单没有确认，不能再次分配订单";
            exit;
        }
        if ($order_info['status'] == 4) {
            echo "商家已经接单，不能再次分配订单";
            exit;
        }
        //判断订单是否确认超过5分钟
        if ($new_time_5 < $order_info['allocate_time']) {
            echo "订单没有确认没有超过5分钟，不能再次分配订单";
            exit;
        }
        FilePutContentHelps::writeFile('getShop.log', 'start' . $order_sn);
        //获取订单中的商品
        $goods_list = $orderInfoModel->getList(['order_sn' => $order_sn], "GROUP_CONCAT(p_id) as list");
        $goods_arr = explode(',', $goods_list[0]['list']);
        $num = count($goods_arr);

        //根据用户收货地址获取坐标
        $curl_info = $configModel->getInfo(['key' => 'channel']);
        $pro = $ProvinceModel->getInfo(['id' => $order_info['province']], 'name');
        $city = $cityModel->getInfo(['id' => $order_info['city']], 'name');
        $dist = $district->getInfo(['id' => $order_info['district']], 'name');
        $curl = $curl_info['value'] . 'lbs/get-point?address=' . $pro['name'] . $city['name'] . $dist['name'] . $order_info['address'];
        $ret = CurlHelper::get($curl);
        $shop_list_n = [];
        //去查询周围商家
        if ($ret['code'] == 200) {
            $value = $configModel->getInfo(['key' => 'orderNearShop'], 'value');
            $content = $value['value'];
            $curl = $curl_info['value'] . 'lbs/near-shop?lng=' . $ret['data']['lng'] . '&lat=' . $ret['data']['lat'] . '&dis=' . $content;
            $ret = CurlHelper::get($curl);
            if ($ret['code'] == 200) {
                $shop_list_n = $ret['data'];
            }
        }
        $ner_shop = [];
        foreach ($shop_list_n as $k => $v) {
            $ner_shop[] = $v['shop_id'];
        }
        $where = ['product_id' => $goods_arr, 'status' => 1, 'shop_id' => $ner_shop];
        FilePutContentHelps::writeFile('getShop.log', '获取坐标商家' . var_export($ner_shop, true));

        //查询商家中有订单中的商品的商家
        $shop_list1 = $shopProduct->find()->select('shop_id,count(product_id) as num ')->where($where)->groupBy('shop_id')->having('num = ' . $num)->asArray()->all();
        FilePutContentHelps::writeFile('getShop.log', '查询商家中有订单中的商品的商家' . var_export($shop_list1, true));

        $shop_list_s = [];
        foreach ($shop_list1 as $k => $v) {
            foreach ($shop_list_n as $kk => $vv) {
                if ($vv['shop_id'] == $v['shop_id']) {
                    $shop_list_s[] = $v['shop_id'];
                }
            }
        }
        FilePutContentHelps::writeFile('getShop.log', '满足条件的商家' . var_export($shop_list_s, true));

        //黑名单商家
        $shop_list2 = $ShopOrderBlack->getList(['order_sn' => $order_sn], "shop_id");
        FilePutContentHelps::writeFile('getShop.log', '黑名单的商家' . var_export($shop_list2, true));
        if ($shop_list2) {
            $shop_list3 = [];
            foreach ($shop_list2 as $k => $v) {
                $shop_list3[] = $v['shop_id'];
            }
            foreach ($shop_list_s as $kk => &$vv) {
                if (in_array($vv, $shop_list3)) {
                    unset($shop_list_s[$kk]);
                }
            }
        }
        FilePutContentHelps::writeFile('getShop.log', '过滤黑名单黑名单的商家' . var_export($shop_list_s, true));
        //删除本单商家
        foreach ($shop_list_s as $kk => &$vv) {
            if ($order_info['shop_id'] == $vv) {
                unset($shop_list_s[$kk]);
            }
            //删除ID为1的商家
            if ($vv == 1) {
                unset($shop_list_s[$kk]);
            }
        }
        FilePutContentHelps::writeFile('getShop.log', '本单的商家' . $order_info['shop_id']);
        FilePutContentHelps::writeFile('getShop.log', '去掉本单的商家和1号商家' . var_export($shop_list_s, true));
        FilePutContentHelps::writeFile('getShop.log', 'end ');
        $hop_list = $shopModel->getList(['id' => $shop_list_s, 'business_status' => 1]);
        $pages = new Pagination();
        $data_info = [
            'order_sn' => $order_sn,
            'pages' => $pages,
            'shop_list' => $hop_list
        ];
        return $this->render('edit_shop', $data_info);
    }

    /**
     * 简介：订单商家转移提交
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionEditShopUp()
    {
        $configModel = new CrmConfig();
        $QueueSmsModel = new QueueSms();
        $order_sn = RequestHelper::post('order_sn');
        $shop_id = RequestHelper::post('shop_id');
        if ($order_sn == '' || $shop_id == "") {
            return $this->ajaxReturn(100, '参数错误');
        }
        $orderModel = new UserOrder();
        $shopModel = new Shop();
        //把订单中商家添加到黑名单中。
        $order_info = $orderModel->getInfo(['order_sn' => $order_sn]);
        if ($order_info['status'] ==4) {
            return $this->ajaxReturn(102, '商家已经确认订单，不能转移');
        }
        $ShopOrderBlack = new ShopOrderBlack();
        $data = [
            'shop_id' => $order_info['shop_id'],
            'order_sn' => $order_sn
        ];
        $ShopOrderBlack->insertInfo($data);
        //给黑名单商家发送短信
        $shopInfo = $shopModel->getInfo(['id' => $order_info['shop_id']]);
        $mobile = $shopInfo['mobile'];
        $value = $configModel->getInfo(['key' => 'orderOldShop'], 'value');
        $content = $value['value'];//'尊敬的i500商家，由于您有未及时处理的订单，您的订单已被转移至其他商家，如有问题请与客服联系400－661－1690。';
        $QueueSmsModel->addMsg($mobile, $content);
        //计算订单总金额
        $orderDetailModel = new OrderDetail();
        $order_detail = $orderDetailModel->getList(['order_sn' => $order_sn], "p_id,num");
        $total = 0;
        $shopProductModel = new ShopProduct();
        $productModel = new Product();
        $goods_info = [];
        foreach ($order_detail as $k => $v) {
            $price_new = $shopProductModel->getInfo(['product_id' => $v['p_id'], 'shop_id' => $shop_id], "price");
            if (!$price_new && $shop_id == 1) {
                $price_new = $productModel->getInfo(['id' => $v['p_id']], true, "origin_price as price");
            }
            $goods_info[$v['p_id']]['product_id'] = $v['p_id'];
            $goods_info[$v['p_id']]['price'] = $price_new['price'];
            $goods_info[$v['p_id']]['total'] = $v['num'] * $price_new['price'];
            $total += $v['num'] * $price_new['price'];
        }
        $product_info = json_encode($goods_info);
        $total_z = $total - $order_info['dis_amount'];
        $orderAllocationModel = new OrderAllocation();
        //判断是否被转移过
        $count = $orderAllocationModel->getCount(['order_sn' => $order_sn]);
        if ($count) {
            $orderAllocationModel->updateInfo(['shop_id' => $shop_id, 'total' => $total_z, 'product_info' => $product_info], ['order_sn' => $order_sn]);
        } else {
            $data2 = [
                'shop_id' => $shop_id,
                'order_sn' => $order_sn,
                'total' => $total_z,
                'product_info' => $product_info
            ];
            $orderAllocationModel->insertInfo($data2);
        }
        //修改订单所属商家
        $time = date('Y-m-d H:i:s');
        $ret = $orderModel->updateInfo(['shop_id' => $shop_id, 'allocate_time' => $time], ['order_sn' => $order_sn]);
        if ($ret) {
            //给商家发送短信
            $shopInfo2 = $shopModel->getInfo(['id' => $shop_id]);
            $mobile = $shopInfo2['mobile'];
            $value1 = $configModel->getInfo(['key' => 'orderNewShop'], 'value');
            $content = $value1['value'];//'亲爱的i500商家，您有一个新的订单，请及时登录i500商家后台或通过i500商家APP查看订单详情。';
            $QueueSmsModel->addMsg($mobile, $content);
            //记录日志
            $order_log = new OrderLog();
            $map['o_id'] = $order_info['id'];
            $map['oper'] = '修改订单商家信息';
            $map['order_sn'] = $order_sn;
            $map['log_info'] = '把用户订单所属商家ID：' . $order_info['shop_id'] . ',修改成：' . $shop_id;
            $order_log->recordLog($map);//操作备注
            return $this->ajaxReturn(200, '订单转移成功');
        } else {
            return $this->ajaxReturn(100, '订单转移失败');
        }
    }
}

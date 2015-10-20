<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  UserorderController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/7 下午4:02
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Admin;
use backend\models\i500m\City;
use backend\models\i500m\Coupons;
use backend\models\i500m\District;
use backend\models\i500m\PaySite;
use backend\models\i500m\Province;
use backend\models\i500m\Shop;
use backend\models\shop\ShopProduct;
use backend\models\social\Order;
use backend\models\social\OrderDetail;
use backend\models\social\OrderLog;
use backend\models\social\ShopGrade;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;


/**
 * Class UserorderController
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class UserorderController extends BaseController
{
    public $pay_status_data = array(
        0 => '未支付',
        1 => '已支付',
        2 => '已退款'
    );
    public $ship_status_data = array(
        999 => '全部',
        0 => '未发货',
        1 => '发货',
        2 => '确定收货',
    );
    public $status_data = array(
        0 => '未确认',
        1 => '已确认',
        2 => '已取消',
        3 => '已分单',
        4 => '商家接单',
        5 => '已完成'
    );
    public $source_type_data = array(
        1 =>'pc',
        2 =>'wap',
        3 =>'ios',
        4 =>'android'
    );
    public $error_code = array(
        101 => "订单不存在",
        102 => "订单无需修改",
        103 => "订单已发货",
        104 => "请先发货"
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
        $pay_site_arr = $pay_site->getList('pid != 0', 'id,name');
        foreach ($pay_site_arr as $k => $v) {
            $this->pay_site_id_data[$v['id']] = $v['name'];
        }
    }
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionIndex()
    {
        $model = new Order();

        $order_sn = RequestHelper::get('order_sn', '');
        $mobile = RequestHelper::get('mobile', '');
        $order_sn = $order_sn == '订单编号' ? '' : $order_sn;
        $mobile = $mobile == '用户名' ? '' : $mobile;
        $start_time = RequestHelper::get('start_time');
        $end_time = RequestHelper::get('end_time');

        $status = RequestHelper::get('status', 999, 'intval');
        $pay_status = RequestHelper::get('pay_status', 999, 'intval');
        $pay_site_id = RequestHelper::get('pay_site_id', 0, 'intval');
        $ship_status = RequestHelper::get('ship_status', 999, 'intval');
        $shop_name = RequestHelper::get('shop_name', '');
        $page = RequestHelper::get('page', 1, 'intval');
        $where = array();
        $andWhere = [];
        if (strlen($order_sn) < 6 && strlen($order_sn) > 0) {
            return $this->error('订单号搜索至少6位末尾号码', '/social/userorder/index');
        }
        if ($order_sn) {
            $order_sn = htmlspecialchars($order_sn, ENT_QUOTES, "UTF-8");
            $andWhere[] = " order_sn like '%$order_sn' ";
        }

        if ($start_time && $end_time) {
            if ($start_time > $end_time) {
                return $this->error('开始时间不能大于结束时间', '/social/userorder/index');
            }
        }

        if ($start_time) {
            $andWhere[] = " create_time>'" . $start_time . " 00:00:00'";
        }
        if ($end_time) {
            $andWhere[] = " create_time<'" . $end_time . " 23:59:59'";
        }

        if ($ship_status != 999) {
            $where['ship_status'] = $ship_status;
        }
        if ($status != 999) {
            $where['status'] = $status;
        }

        if ($pay_status != 999) {
            $where['pay_status'] = $pay_status;
        }

        if ($pay_site_id > 0) {
            $where['pay_method_id'] = $pay_site_id;
        }
        if ($mobile) {
            $where['mobile'] = $mobile;
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
                return $this->error('商家名不存在', '/social/userorder/index');
            }
        }
        if (!in_array($this->quanguo_city_id, $this->city_id)) {
            $where['city'] = $this->city_id;
        }
        $andWhere = empty($andWhere) ? '' : implode(' and ', $andWhere);
        $count = $model->getListCount($where, $andWhere);
        $list = $model->getList2($where, $andWhere, ['create_time' => SORT_DESC], "*", ($page - 1) * $this->size, $this->size);
        $result_arr = [];
        foreach ($list as $k => $v) {
            $shop = $shop_m->getInfo(array('id' => $v['shop_id']), true, "shop_name");
            $list[$k]['shop_name'] = $shop['shop_name'];
        }
        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        $pay_site_list = $this->pay_site_id_data;

        $ship_status_data = $this->ship_status_data;
        // var_dump($list);exit;
        $data_info = [
            'pages' => $pages,
            'data' => $list,
            'order_sn' => $order_sn,
            'mobile' => $mobile,
            'ship_status' => $ship_status,
            'pay_status' => $pay_status,
            'pay_site_id' => $pay_site_id,
            'status' => $status,
            'shop_name' => $shop_name,
            'pay_size_id' => $this->pay_site_id_data,
            'status_data' => $this->status_data,
            'ship_status_data' => $ship_status_data,
            'pay_status_data' => $this->pay_status_data,
            'source_type_data'=>$this->source_type_data,
            'pay_site_list' => $pay_site_list,
            'result_arr' => $result_arr,
            'start_time' => $start_time,
            'end_time' => $end_time,

        ];
        return $this->render('index', $data_info);
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
        $model = new Order();
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
        $detail['goods_list'] = $od_model->getList(['order_sn'=>$order_sn]);
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
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionEdits()
    {
        $order_sn = RequestHelper::post('order_sn');
        $ship_status = RequestHelper::post('ship_status', 999, 'intval');
        $status = RequestHelper::post('status', 999, 'intval');
        $remark = RequestHelper::post('remark');
        $type = RequestHelper::post('type', 0, 'intval');
        $log_info = \Yii::$app->user->identity->username;
        if (!$order_sn) {
            return $this->error('订单不能为空');
        }
        if (999 == $ship_status && 999 == $status) {
            return $this->error('状态不能为空');
        }
        $model = new Order();
        $w = [];
        $w['order_sn'] = $order_sn;
        if (!in_array($this->quanguo_city_id, $this->city_id)) {
            if ($this->city_id) {
                $w['city'] = $this->city_id;
            }
        }
        $order_status = $model->getInfo($w);
        if (!$order_status) {
            return $this->error('订单不存在');
        }
        if ($order_status['status'] == 2) {
            return $this->error('订单已取消,不能操作', '/social/userorder/detail?order_sn=' . $order_sn);
        }
        if ($order_status['status'] == 0 && $type == 1) {
            return $this->error('订单必须先确认', '/social/userorder/detail?order_sn=' . $order_sn);
        }
        if (empty($remark)) {
            return $this->error('订单操作备注不能为空');
        }

        if (999 != $status && $type == 0) {
            $sts = $status;  //1确认 2取消
            $map['status'] = $status;
            if ($status == 1) {
                $log_info = ' 确认了订单';
            } elseif ($status == 2) {
                $log_info = ' 取消了订单 ';
            }

        } elseif (999 != $ship_status && $type == 1) {
            $sts = $ship_status;  //1发货 2收货
            $map['ship_status'] = $ship_status;
            if ($ship_status == 1) {
                $log_info = ' 发货了订单';
            } elseif ($ship_status == 2) {
                $log_info = ' 确定收货了订单 ';
            }
        } else {
            return $this->error('提交数据错误！');
        }
        $order_log = new OrderLog();
        $map['oper'] = $log_info;
        $map['order_sn'] = $order_sn;
        $map['log_info'] = ' 备注:' . $remark;;

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
                    //加回库存
                    if ($info['pay_method_id'] != 1) {
                        $order_detail_model = new OrderDetail();
                        //查询出购买的商品
                        $order_arr = $order_detail_model->getList(['order_sn' => $order_sn]);
                        $shop_product_model = new ShopProduct();
                        foreach ($order_arr as $k => $v) {
                            $shop_product_model->addProductStock($v['shop_id'], $v['product_id'], $v['num']);
                        }
                    }
                }
                //已支付的走退款流程
                //code....
            }
            //下单减库存 取消加回库存
            //pay_site_id=1为货到付款 ，线上支付的是支付减库存 2015-04-29

            //ship_status = 2添加销售量
            if ($ship_status == 2) {
                $order_detail_model = new OrderDetail();
                $order_arr = $order_detail_model->find()->where(['order_sn' => $order_sn])->asArray()->all();
                $shop_product_model = new ShopProduct();
                foreach ($order_arr as $k => $v) {
                    $shop_product_model->Up_sales_volume($v['shop_id'], $v['product_id'], $v['num']);
                }

            }
            //给商家发送短信
            //if ($status == 1) {
            //    $queueSms_m = new QueueSms();
            //    $shop_m = new Shop();
            //    $shopinfo = $shop_m->getInfo(['id' => $info['shop_id']]);
            //    $data = array(
            //        'mobile' => $shopinfo['mobile'],
            //        'content' => '【i500】亲，您的店铺有新的订单，请登录i500商家后台查看',
            //        'create_time' => date('Y-m-d H:i:s')
            //    );
            //    $queueSms_m->insertInfo($data);
            //}

            return $this->success('订单操作成功', '/social/userorder/detail?order_sn=' . $order_sn);
        }
        return $this->error('订单操作失败,'.$this->error_code[$ret], '/social/userorder/detail?order_sn=' . $order_sn);
    }

    /**
     * 更改订单状态
     * @param string $order_sn 订单号
     * @param int    $status   订单状态 1 确认 2取消 4发货 5收货
     * @param int    $type     类型 1 订单确认状态 2发货状态
     * @return int
     */
    public function editOrderStatus($order_sn, $status, $type)
    {
        $time = date('Y-m-d H:i:s');
        $code = 0;
        if ($order_sn && isset($status)) {
            $m_order = new Order();
            if ($type != 1) {
                $order = $m_order->getInfo(['order_sn' => $order_sn], false, 'id,status,pay_status,pay_method_id,ship_status');
                if (empty($order['id'])) {
                    return $code = 101;   //订单不存在
                }
                //订单已取消
                if (2 == $status && $order['status'] == 0) {
                    $order->status = $status;
                    $order->save();
                    $code = 200;
                } elseif (1 == $status && $order['status'] == 0) {
                    $order->status = $status;
                    $order->save();
                    $code = 200;
                } else {
                    $code = 102;    //订单无需修改
                }
            } else {
                $order = $m_order->getInfo(['order_sn' => $order_sn], false, 'id,status,ship_status');
                if (empty($order['id'])) {
                    return $code = 101;     //订单不存在
                }
                if (1 == $status) {
                    if ($order['ship_status'] >= $status) {
                        return $code = 103;   //订单已发货
                    }
                } elseif (2 == $status) {
                    if ($order['ship_status'] == $status) {
                        return $code = 102;   //订单无需修改
                    } elseif ($order['ship_status'] == 0) {
                        return $code = 104;   //请先发货
                    }
                    $order->pay_status = 1;
                }
                $order->delivery_time = $time;
                $order->ship_status = $status;
                $order->save();
                $code = 200;

            }
        }
        return $code;
    }
    /**
     * 简介：ShopGrade店铺评论列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionGradeList()
    {
        $shopGradeModel = new ShopGrade();
        $mobile = RequestHelper::get('mobile');

    }

    /**
     * 简介：ShopGrade店铺评论删除
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionGradeDel()
    {

    }

    /**
     * 简介：ShopGrade店铺评论详情
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionGradeDetail()
    {

    }
}

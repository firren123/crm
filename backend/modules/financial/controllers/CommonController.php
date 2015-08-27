<?php
/**
 * 退款流程
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  CommonController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/21 下午4:44
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\financial\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Coupons;
use backend\models\i500m\OrderLog;
use backend\models\i500m\RefundGoods;
use backend\models\i500m\RefundOperationLog;
use backend\models\i500m\RefundOrder;
use backend\models\i500m\UserOrder;

/**
 * CommonController
 *
 * @category CRM
 * @package  CommonController
 * @author   lichenjun <lichenjun@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     lichenjun@iyangpin.com
 */
class CommonController extends BaseController
{
    /**
     * 退款规则退货规则
     * 1.生鲜易腐不支持退货
     * 2.其他常规产品，7天支付退货
     * @author  lichenjun@iyangpin.com。
     * @param string $order_sn 订单号
     * @return int
     */
    public function refundRole($order_sn)
    {
        $orderModel = new UserOrder();
        $order_info = $orderModel->getInfo(['order_sn' => $order_sn]);
        $time_7 = date('Y-m-d H:i:s', strtotime('-7 day'));
        if ($order_info['ship_status_time'] < $time_7) {
            return false;
        }
        return 1;
    }

    /**
     * 优惠券退款
     * @author  lichenjun@iyangpin.com。
     * @param string $coupon_id 优惠券ID
     * @return int
     */
    public function getCouponMoney($coupon_id)
    {
        $CouponModel = new Coupons();
        $coupon_info = $CouponModel->getInfo(['id' => $coupon_id], true, 'par_value');
        $coupon_money = $coupon_info['par_value'] ? $coupon_info['par_value'] : 0;
        return $coupon_money;

    }

    /**
     * 已经退的产品数量
     * @author lichenjun@iyangpin.com。
     * @param  string $order_sn 订单号
     * @param  int    $p_id     产品ID
     * @return int
     */
    public function oldRefundOrderNum($order_sn, $p_id)
    {
        $refundGoodsModel = new RefundGoods();
        $count = $refundGoodsModel->getInfo(['order_sn' => $order_sn, 'product_id' => $p_id], true, 'sum(num) as total');
        return $count['total'];
    }

    /**
     * 简介：已经退的优惠券金额 code_money
     * @author  lichenjun@iyangpin.com。
     * @param string $order_sn 订单号
     * @return int
     */
    public function oldRefundCoupon($order_sn)
    {
        $RefundOrderModel = new RefundOrder();
        $count = $RefundOrderModel->getInfo(['order_sn' => $order_sn, 'status' => 2], true, 'sum(code_money) as total');
        return $count['total'];
    }

    /**
     * 退款订单日志表
     * @author  lichenjun@iyangpin.com。
     * @param int    $rid      数据表ID
     * @param int    $order_sn 订单号
     * @param string $type     1用户,2客服,3部门,4财务,10系统
     * @param string $info     取消,审核 等操作'
     * @param string $remark   备注
     * @return mixed
     */
    public function refundOrderLog($rid, $order_sn, $type, $info = '', $remark = '')
    {
        $refundOperationLogModel = new RefundOperationLog();
        $data = [
            'rid' => $rid,
            'order_sn' => $order_sn,
            'admin_id' => $this->admin_id,
            'add_time' => date('Y-m-d H:i:s'),
            'type' => $type,
            'info' => $info,
            'remark' => $remark
        ];
        $retOperationLog = $refundOperationLogModel->insertInfo($data);
        return $retOperationLog;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $order_sn 订单号
     * @param int    $status   状态
     * @return int
     */
    public function editOrderStatus($order_sn, $status)
    {
        $orderModel = new UserOrder();
        $ret = $orderModel->updateInfo(['status' => $status], ['order_sn' => $order_sn]);
        if ($ret) {
            $orderLogModel = new OrderLog();
            $data = [
                'order_sn' => $order_sn,
                'oper' => '退款',
                'log_info' => '提交退款申请、审核',
                'status' => $status,
            ];
            $ret2 = $orderLogModel->recordLog($data);
            return 1;

        }
        return 0;
    }


}
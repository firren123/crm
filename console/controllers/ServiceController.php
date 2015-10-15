<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  ServiceController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/14 上午11:42
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace console\controllers;

use backend\models\i500m\RefundOrder;
use backend\models\social\ServiceOrder;
use yii\console\Controller;


/**
 * Class ServiceController
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ServiceController extends Controller
{

    /**
     * 简介：服务订单未支付的回滚
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionNotPayRollBack()
    {
        $service_order = new ServiceOrder();
        $old_12_hour = date('Y-m-d H:i:s', strtotime("-12 hour"));
        $and_where = "pay_status = 0 and create_time <'$old_12_hour'";
        $ret = $service_order->updateAll(['status' => 2], $and_where);
        if ($ret) {
            echo "success";
        } else {
            echo "error";
        }
    }

    /**
     * 简介：已经支付，但是超过预约时间一个小时没确认的订单，自动退款
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionPayRollBack()
    {
        $service_order = new ServiceOrder();
        $refund_order_model = new RefundOrder();
        $new_hour = date('Y-m-d H:i:s', strtotime("1 hour"));
        $and_where = "pay_status = 1 and appointment_service_time <'$new_hour'";
        $order_arr = $service_order->getList($and_where);
        foreach ($order_arr as $k => $v) {
            $data = [
                'order_sn' => $v['order_sn'],
                'type' => 1,
                'add_time' => date('Y-m-d H:i:s'),
                'money' => $v['total'],
                'unionpay_tn' => $v['unionpay_tn'],
                'refund_time' => date('Y-m-d H:i:s'),
                'refund_type' => $v['pay_site_id'],
                'from_data' => 2,
            ];
            $ret = $refund_order_model->insertInfo($data);
            if ($ret) {
                $service_order->updateInfo(['status' => 2, 'pay_status' => 3], ['order_sn' => $v['order_sn']]);
                echo $v['order_sn']."  success\n";
            } else {
                echo $v['order_sn']."  error\n";
            }
        }
    }
}

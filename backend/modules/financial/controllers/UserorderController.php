<?php
/**
 * 用户退款
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  UserorderController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/20 下午5:47
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\financial\controllers;


use backend\models\i500m\OrderDetail;
use backend\models\i500m\RefundGoods;
use backend\models\i500m\RefundOrder;
use backend\models\i500m\UserOrder;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * UserOrderController
 *
 * @category CRM
 * @package  UserorderController
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class UserorderController extends CommonController
{
    public $refund_status = [
        0 => '退款中',
        1 => '取消',
        2 => '退款成功',
    ];
    public $audit_status = [
        0 => '待审核',
        1 => '等待财务审核',
        2 => '审核通过',
        3 => '审核驳回'
    ];

    /**
     * 退换货首页
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $order_sn = RequestHelper::get('order_sn');
        //判断订单是否符合退款规则
        if (!$this->refundRole($order_sn)) {
            return $this->error('订单已经支付完成超过7天，不符合7天退规则');
        }
        //判断订单是否完成
        $orderModel = new UserOrder();
        //订单信息
        $order_info = $orderModel->getInfo(['order_sn' => $order_sn]);
        $list_detail = array();
        $cart = 0;  //是否可修改购物车
        //订单完成
        if ($order_info['ship_status'] == 5) {
            $cart = 1;
        }

        //订单商品详情
        $order_detail = new OrderDetail();
        if (!empty($order_sn)) {
            $where = ['order_sn' => $order_sn];//"order_sn1 = " . $order_sn;
            $list_detail = $order_detail->getList($where, '*', 'id desc');
        }
        //coupon_id 是否使用优惠券
        if ($order_info['coupon_id'] > 0) {
            $order_info['coupon_money'] = $this->getCouponMoney($order_info['coupon_id']);
        }
        return $this->render('index', ['data' => $list_detail, 'order_info' => $order_info, 'cart' => $cart,]);
    }

    /**
     * 简介：提交退款
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAdd()
    {
        $refund_money = 0;  //  退款总金额
        $refund_goods = array();    //退款商品
        $refund_code = 0; //退款优惠券金额
        $oldRefundCoupon = 0;// 已经退的优惠券
        $order_sn = RequestHelper::post('order_sn');
        $goods_arr = RequestHelper::post('goods', array());
        $remark = RequestHelper::post('remark');  //备注说明
        $type = RequestHelper::post('type', 1, 'intval'); //1整单退，2部分退
        $orderModel = new UserOrder();
        $refundGoodsModel = new RefundGoods();
        $refundOrderModel = new RefundOrder();

        //判断退款订单中是否存在正在退款的订单
        $isYou = $refundOrderModel->getCount(['order_sn' => $order_sn, 'status' => 0]);
        if ($isYou) {
            return $this->error('订单正在退款中,请不要重复提交');
        }

        $order_info = $orderModel->getInfo(['order_sn' => $order_sn]);
        //修改订单状态和添加日志
        $this->editOrderStatus($order_sn, 6);
        //判断是否支付完成
        if ($order_info['ship_status'] == 5) {
            //支付完成，支持部分退款
            //判断订单数量是否正确
            $orderDetailModel = new OrderDetail();
            $orderDetailInfo = $orderDetailModel->getList(['order_sn' => $order_sn]);
            if ($type == 2) { //部分退执行
                foreach ($orderDetailInfo as $k => $v) {
                    foreach ($goods_arr as $kk => $vv) {
                        //退款商品
                        if (isset($vv['pid']) && $v['p_id'] == $vv['pid']) {
                            //判断已经退的数量+现在退的是否超过
                            $oldNum = $this->oldRefundOrderNum($order_sn, $v['p_id']);
                            $r_num = $v['num'] - $oldNum;
                            //判断是否还有商品可退
                            if ($r_num > 0) {
                                //商品不能大于应该退的数量
                                if ($vv['num'] > $r_num) {
                                    //如果退货数量大于
                                    $refund_goods[] = [
                                        'pid' => $v['p_id'],
                                        'num' => $r_num
                                    ];
                                    $refund_money += $r_num * $v['price'];

                                    $data = [
                                        'order_sn' => $order_sn,
                                        'product_id' => $v['p_id'],
                                        'add_time' => date('Y-m-d H:i:s'),
                                        'status' => 0,
                                        'user_id' => 0,
                                        'num' => $r_num,
                                        'price' => $v['price'],
                                        'remark' => $remark
                                    ];
                                } else {
                                    $refund_goods[] = [
                                        'pid' => $v['p_id'],
                                        'num' => $vv['num']
                                    ];
                                    $refund_money += $vv['num'] * $v['price'];
                                    $data = [
                                        'order_sn' => $order_sn,
                                        'product_id' => $v['p_id'],
                                        'add_time' => date('Y-m-d H:i:s'),
                                        'status' => 0,
                                        'user_id' => 0,
                                        'num' => $vv['num'],
                                        'price' => $v['price'],
                                        'remark' => $remark
                                    ];
                                }
                                $refundGoodsModel->insertInfo($data);
                            }

                        }
                    }
                }
            } else {
                $refund_money = $order_info['total'] + $order_info['dis_amount'];
            }
            //判断是否使用优惠券
            if ($order_info['dis_amount'] > 0) {
                $refund_code = $order_info['dis_amount'];
                //查询优惠券已经退
                $oldRefundCoupon = $this->oldRefundCoupon($order_sn);
            }

        } elseif ($order_info['pay_status'] == 2 && $order_info['ship_status'] != 5) {
            //支付完成，没有收货的,全部退
            $refund_money = $order_info['total'] + $order_info['dis_amount'];
            //判断是否使用优惠券
            if ($order_info['dis_amount'] > 0) {
                $refund_code = $order_info['dis_amount'];
                //查询优惠券已经退
                $oldRefundCoupon = $this->oldRefundCoupon($order_sn);
            }

        } else {
            //货到付款，没有支付的，无需退款，只需记录

        }
        $money = 0;
        //$code_money = 0;
        //需要退款金额
        $_money = $refund_money - $refund_code + $oldRefundCoupon;
        //判断优惠券是否足够退
        if ($refund_money > 0) {
            if ($_money < 0) {
                $code_money = $refund_money;
            } else {
                $code_money = $refund_code;
                $money = $_money;
            }
        } else {
            return $this->error('订单无需操作退款流程');
        }

        //把数据放入数据库

        $data = [
            'order_sn' => $order_sn,
            'type' => $type,
            'add_time' => date('Y-m-d H:i:s'),
            'status' => 0,
            'audit_status' => 0,
            'money' => $money,
            'code_money' => $code_money,
            'unionpay_tn' => $order_info['unionpay_tn']
        ];

        $retOrder = $refundOrderModel->insertOneRecord($data);
        if ($retOrder['result'] != 1) {
            return $this->error('提交失败');
        }
        //记录日志
        $this->refundOrderLog($retOrder['data']['new_id'], $order_sn, 2, '提交退款', $remark);

        if ($retOrder) {
            return $this->success('提交成功~', '/financial/userorder/list');
        } else {
            return $this->success('提交失败~');
        }

    }

    /**
     * 简介：退款列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionList()
    {
        $page = RequestHelper::get('page', 1, 'intval');
        $order_sn = RequestHelper::get('order_sn');
        $start_time = RequestHelper::get('start_time');
        $end_time = RequestHelper::get('end_time');


        $refundOrderModel = new RefundOrder();
        $where = array();
        if (!empty($order_sn)) {
            $where['order_sn'] = $order_sn;
        }
        $andWhere = '';
        if ($start_time) {
            $andWhere[] = " add_time>'" . $start_time . " 00:00:00'";
        }
        if ($end_time) {
            $andWhere[] = " add_time<'" . $end_time . " 23:59:59'";
        }
        $order = ['id' => SORT_DESC];
        $count = $refundOrderModel->find()->where($where)->andWhere($andWhere)->count('id');
        $list = $refundOrderModel->getList2($where, $andWhere, $order, "*", ($page - 1) * $this->size, $this->size);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);

        //获取角色
        $role_id = \Yii::$app->user->identity->role_id;
        $role = array('fin' => 0, 'dep' => 0);
        if ($role_id == 14) {//财务
            $role['fin'] = 1;
        }
        if ($role_id == 20) {//客服主管
            $role['dep'] = 1;
        }
        return $this->render('list', ['data' => $list, 'pages' => $pages, 'refund_status' => $this->refund_status, 'audit_status' => $this->audit_status, 'role' => $role]);
    }

    /**
     * 简介：财务审核提交
     * @author  lichenjun@iyangpin.com。
     * @return  string
     */
    public function actionAuditUpFin()
    {
        $id = RequestHelper::get('id');
        $status = RequestHelper::get('status');
        $remark = RequestHelper::get('remark');
        $refundOrderModel = new RefundOrder();
        $time = date('Y-m-d H:i:s');
        $order_info = $refundOrderModel->getInfo(['id' => $id]);
        if ($order_info && $order_info['audit_status'] == 1) {
            if ($status == 3) {
                //退款单驳回
                $ret = $refundOrderModel->updateInfo(['audit_status' => 3], ['id' => $id]);
                //记录日志
                $this->refundOrderLog($id, $order_info['order_sn'], 4, '退款取消', $remark);

                if ($ret) {
                    //修改订单状态和添加日志
                    $this->editOrderStatus($order_info['order_sn'], 1);
                    echo json_encode(array('code' => 200, 'date' => '退款取消成功'));
                    exit(0);
                } else {
                    echo json_encode(array('code' => 100, 'date' => '退款取消失败'));
                    exit(0);
                }

            }
            if ($status == 2) {
                //退款单确认查询是否需要退现金

                //退款单审核确认
                $ret = $refundOrderModel->updateInfo(['audit_status' => 2], ['id' => $id]);
                //记录日志
                $this->refundOrderLog($id, $order_info['order_sn'], 4, '退款审核通过', $remark);
                if ($order_info['money'] > 0) {
                    //去调取第三方退款接口
                    file_get_contents(\Yii::$app->params['serverUrl'] . '/user/alipaypc-refund?order_sn=' . $order_info['order_sn']);
                    if ($ret) {
                        echo json_encode(array('code' => 200, 'date' => '审核成功,提交第三方处理退款中。。。.'));
                        exit(0);
                    } else {
                        echo json_encode(array('code' => 100, 'date' => '审核失败'));
                        exit(0);
                    }
                } else {
                    //修改 status = 2 , refund_time 添加时间
                    $ret = $refundOrderModel->updateInfo(['status' => 2, 'refund_time' => $time], ['id' => $id]);
                    if ($ret) {
                        //修改订单状态和添加日志
                        $this->editOrderStatus($order_info['order_sn'], 2);
                        echo json_encode(array('code' => 200, 'date' => '审核成功,退款成功'));
                        exit(0);
                    } else {
                        echo json_encode(array('code' => 100, 'date' => '审核失败'));
                        exit(0);
                    }
                }
            }
        }
        echo json_encode(array('code' => 100, 'date' => '审核失败'));
        exit;
    }

    /**
     * 简介：部门主管审核提交
     * @author  lichenjun@iyangpin.com。
     * @return  string
     */
    public function actionAuditUpDep()
    {
        $id = RequestHelper::get('id');
        $status = RequestHelper::get('status');
        $remark = RequestHelper::get('remark');
        $refundOrderModel = new RefundOrder();
        $order_info = $refundOrderModel->getInfo(['id' => $id]);
        if ($order_info && $order_info['audit_status'] == 0) {
            if ($status == 3) {
                //退款单驳回
                $ret = $refundOrderModel->updateInfo(['audit_status' => 3], ['id' => $id]);
                //记录日志
                $this->refundOrderLog($id, $order_info['order_sn'], 3, '退款审核驳回', $remark);


                if ($ret) {
                    echo json_encode(array('code' => 200, 'date' => '退款审核驳回'));
                    exit;
                } else {
                    echo json_encode(array('code' => 100, 'date' => '退款审核驳回失败'));
                    exit;
                }

            }
            if ($status == 1) {
                //退款单确认查询是否需要退现金
                //退款单审核确认
                $ret = $refundOrderModel->updateInfo(['audit_status' => 1], ['id' => $id]);
                //记录日志
                $this->refundOrderLog($id, $order_info['order_sn'], 3, '退款审核通过', $remark);
                if ($ret) {
                    echo json_encode(array('code' => 200, 'date' => '退款审核成功'));
                    exit;
                } else {
                    echo json_encode(array('code' => 100, 'date' => '退款审核失败'));
                    exit;
                }

            }
        }
        echo json_encode(array('code' => 100, 'date' => '审核失败'));
        exit;
    }
}

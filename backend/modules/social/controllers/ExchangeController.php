<?php
/**
 * 退换货审核
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  ExchangeController.php
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/7 下午4:02
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\RefundGoods;
use backend\models\i500m\RefundOperationLog;
use backend\models\i500m\RefundOrder;
use backend\models\i500m\Shop;
use backend\models\shop\ShopProduct;
use backend\models\social\ExChange;
use backend\models\social\ExChangeChecked;
use backend\models\social\Order;
use common\helpers\RequestHelper;
use yii\data\Pagination;


/**
 * Class ExchangeController
 * @category  PHP
 * @package   Crm
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ExchangeController extends BaseController
{
    /**
     * 简介：
     * @author  sunsong@iyangpin.com。
     * @return null
     */
    public function actionIndex()
    {
        $page_size = 10;
        $page = RequestHelper::get('page', 1, 'intval');
        $mobile = RequestHelper::get('mobile', '', 'trim');
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $order_sn = RequestHelper::get('order_sn', '', 'trim');
        $status = RequestHelper::get('status', '', 'trim');
        $type = RequestHelper::get('type', '', 'trim');

        $model = new ExChange();
        $where = ['and', ['>', 'id', 0]];

        if (!empty($mobile)) {
            $where[] = ['=', 'mobile', $mobile];
        }
        if ($shop_id != 0) {
            $where[] = ['=', 'shop_id', $shop_id];
        }
        if (!empty($order_sn)) {
            $where[] = ['=', 'order_sn', $order_sn];
        }
        if (!empty($status)) {
            $where[] = ['=', 'status', $status];
        }
        if (!empty($type)) {
            $where[] = ['=', 'type', $type];
        }

        $list = $model->getPageList($where, '*', 'id desc', $page, $page_size);
        $info = array();
        if (!empty($list)) {
            foreach ($list as $v) {
                $v['shop_name'] = $this->shopName($v['shop_id']);
                $info[] = $v;
            }
        }
        $count       = $model->getCount($where);
        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);//分页

        $cond = ['>', 'id', 0];
        $res = $model->getList($cond, 'shop_id', 'id desc', '');

        $result = array();
        $dev = array();
        if (!empty($res)) {

            foreach ($res as $k => $v) {
                $result[] = $v['shop_id'];
            }
            $map = array_unique($result);
            foreach ($map as $key => $val) {

                $dev[$key]['shop_name'] = $this->shopName($val);
                $dev[$key]['shop_id'] = $val;
            }
        }

        $data = array(
            'data' => $info,
            'dev' => $dev,
            'mobile' => $mobile,
            'shop_id' => $shop_id,
            'order_sn' => $order_sn,
            'type' => $type,
            'status' => $status,
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count,
        );
        return $this->render('index', $data);
    }

    /**
     * 获取商家名称
     * @param int $shop_id 商家商铺id
     * @return string
     */
    public function shopName($shop_id = 0)
    {
        $shop = new Shop();
        $where = ['=', 'id', $shop_id];
        $res = "";
        if ($shop_id != 0) {
            $info = $shop->getOneRecord($where, '', 'shop_name');
            if (empty($info)) {
                $res = "店铺名称不存在";
            } else {
                $res = $info['shop_name'];
            }
        }
        return $res;
    }

    /**
     * 获取订单所有商家名称
     * @return array
     */
    public function actionView()
    {
        //$this->layout='dialog';
        $id = RequestHelper::get('id', 0, 'intval');
        $order_sn = RequestHelper::get('order_sn', '', 'trim');
        if ($id == 0) {
            return $this->error("访问错误！！！", '/social/exchange/index');
        }
        if (empty($order_sn)) {
            return $this->error("访问错误！！！", '/social/exchange/index');
        }
        $model = new ExChange();
        $where = ['and', ['=', 'id', $id], ['=', 'order_sn', $order_sn]];

        $list = $model->getOneRecord($where, '', '*');
        if (!empty($list)) {
            $list['shop_name'] = $this->shopName($list['shop_id']);
            $list['image'] = explode(',', rtrim($list['product_img'], ','));
        } else {
            $list = array();
        }
        return $this->render('view', ['data'=>$list, 'img_url' =>\yii::$app->params['imgHost']]);
    }

    /**
     * 审核订单
     * @return array
     */
    public function actionCheck()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $num = RequestHelper::get('num', 0, 'intval');
        $product_id = RequestHelper::get('product_id', '', 'trim');
        $status = RequestHelper::post('status', 0, 'intval');
        $contact = RequestHelper::post('contact', '', 'trim');
        $remark = RequestHelper::post('remark', '', 'trim');
        $post = \Yii::$app->request->getIsPost();
        if ($id == 0) {
            return $this->error("访问错误！！！", '/social/exchange/index');
        }
        if ($post) {
            if (empty($contact) || count($contact) < 2) {
                return $this->error("提交数据错误！！！", '/social/exchange/index');
            }
            $model = new ExChange();
            $checked = new ExChangeChecked();
            $Stock = new ShopProduct();
            $data['ex_id'] = RequestHelper::post('ex_id', 0, 'intval');
            $data['status'] = $status;
            $data['contact_shop'] = $contact[0];
            $data['contact_user'] = $contact[1];
            $data['operator_id'] = $this->admin_id;
            $data['operator_name'] = \Yii::$app->user->identity->username;

            if ($status == 0) {
                $ex_data['status'] = 4;
                $data['remark'] = $remark;
            }
            if ($status == 1) {
                $ex_data['status'] = 1;
                $data['remark'] = "";
                $shop['shop_id'] = RequestHelper::post('shop_id', 0, 'intval');
                $shop['num'] = RequestHelper::post('num', 0, 'intval');
                $shop['product_id'] = RequestHelper::post('product_id', '', 'trim');
            }

            $ex_data['operator_id'] = $this->admin_id;
            $ex_data['operator_name'] = \Yii::$app->user->identity->username;
            $where = ['=', 'id', $id];
            $ex_info = $model->updateOneRecord($where, '', $ex_data);
            if ($ex_info['result'] == 1) {
                $checked->insertOneRecord($data);
                if (!empty($shop)) {
                    $shop_where = ['and', ['=', 'product_id', $shop['product_id']], ['=', 'shop_id', $shop['shop_id']]];
                    $shop_info = $Stock->getOneRecord($shop_where, '', '*');
                    $shop_data['product_number'] = $shop_info['product_number']-$shop['num'];
                    $Stock->updateOneRecord($shop_where, '', $shop_data);
                }
                return $this->success('审核成功！！！', '/social/exchange/index');
            } else {
                return $this->error('审核失败！！！', '/social/exchange/index');
            }
        }

        $data = array(
            'ex_id'=> $id,
            'shop_id'=> $shop_id,
            'num'=> $num,
            'product_id'=> $product_id
        );
        return $this->render('check', $data);
    }

    /**
     * 退货申请审核
     * @return array
     */
    public function actionReturnGoods()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $status = RequestHelper::post('status', 0, 'intval');
        $contact = RequestHelper::post('contact', '', 'trim');
        $order_sn = RequestHelper::get('order_sn', '', 'trim');
        $apply_time = RequestHelper::get('apply_time', '', 'trim');
        $remark = RequestHelper::post('remark', '', 'trim');
        $post = \Yii::$app->request->getIsPost();
        if ($id == 0) {
            return $this->error("访问错误！！！", '/social/exchange/index');
        }
        if ($post) {
            if (empty($contact) || count($contact) < 2) {
                return $this->error("提交数据错误！！！", '/social/exchange/index');
            }
            $model = new ExChange();
            $checked = new ExChangeChecked();
            $ref_order = new RefundOrder();
            $ref_good = new RefundGoods();
            $ref_order_log = new RefundOperationLog();
            $data['ex_id'] = RequestHelper::post('ex_id', 0, 'intval');
            $data['status'] = $status;
            $data['contact_shop'] = $contact[0];
            $data['contact_user'] = $contact[1];
            $data['operator_id'] = $this->admin_id;
            $data['operator_name'] = \Yii::$app->user->identity->username;

            if ($status == 0) {
                $ex_data['status'] = 4;
                $data['remark'] = $remark;
            }
            if ($status == 1) {
                $ex_data['status'] = 1;
                $data['remark'] = "";
            }

            $ex_data['operator_id'] = $this->admin_id;
            $ex_data['operator_name'] = \Yii::$app->user->identity->username;

            $info = $this->payWay($order_sn);
            $map['order_sn'] = RequestHelper::post('order_sn', '', 'trim');
            $map['type'] = 2;
            $map['add_time'] = date('Y-m-d H:i:s', time());
            $map['status'] = 0;
            $map['audit_status'] = 0;
            $map['from_data'] = 1;
            $map['refund_time'] = RequestHelper::post('apply_time', '', 'trim');
            if (!empty($info)) {
                $map['money'] = $info['total'];
                $map['code_money'] = $info['dis_amount'];
                $map['unionpay_tn'] = $info['unionpay_tn'];
                $map['refund_type'] = $info['pay_method_id'];
            } else {
                $map['money'] = "";
                $map['code_money'] = "";
                $map['unionpay_tn'] = "";
                $map['refund_type'] = "";
            }

            $ex_where = ['=', 'order_sn', $order_sn];
            $ex_list = $model->getOneRecord($ex_where, '', '*');
            $make['order_sn'] = $order_sn;
            $make['product_id'] = $ex_list['product_id'];
            $make['price'] = $ex_list['price'];
            $make['num'] = $ex_list['number'];
            $make['add_time'] = $ex_list['apply_time'];
            $make['status'] = $ex_list['status'];
            $make['remark'] = $ex_list['remark'];

            if (empty($ex_list)) {
                $make['order_sn'] = "";
                $make['product_id'] = "";
                $make['price'] = "";
                $make['num'] = "";
                $make['add_time'] = "";
                $make['status'] = "";
                $make['remark'] = "";
            }

            $where = ['=', 'id', $id];
            $ex_info = $model->updateOneRecord($where, '', $ex_data);
            if ($ex_info['result'] == 1) {
                $checked->insertOneRecord($data);
                if ($status == 1) {
                    $ref_info = $ref_order->insertOneRecord($map);
                    if ($ref_info['result'] == 1) {
                        $log_data['rid'] = $ref_info['data']['new_id'];
                        $log_data['order_sn'] = RequestHelper::post('order_sn', '', 'trim');
                        $log_data['admin_id'] = $this->admin_id;
                        $log_data['add_time'] = date('Y-m-d H:i:s', time());
                        $log_data['type'] = 2;
                        $log_data['info'] = "客服审核通过";
                        $ref_order_log->insertOneRecord($log_data);
                    }
                    $ref_good->insertOneRecord($make);
                }
                return $this->success('审核成功！！！', '/social/exchange/index');
            } else {
                return $this->error('审核失败！！！', '/social/exchange/index');
            }
        }

        $data = array(
            'ex_id'=> $id,
            'order_sn'=> $order_sn,
            'apply_time'=> $apply_time
        );
        return $this->render('returngoods', $data);
    }

    /**
     * 获取订单支付方式
     * @param string $order_sn 订单编号
     * @return string
     */
    public function payWay($order_sn = '')
    {
        $model = new Order();
        $where = ['=', 'order_sn', $order_sn];
        $info = $model->getOneRecord($where, '', 'pay_method_id,unionpay_tn,dis_amount,total');
        if (empty($info)) {
            $info = array();
        }
        return $info;
    }
}


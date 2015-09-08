<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   crm
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
use backend\models\social\Order;


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
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionIndex()
    {
        $Order = new Order();

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
}
<?php
/**
 * 报表
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ReportController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/9/21 0021 上午 10:38
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\modules\report\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Category;
use backend\models\i500m\Product;
use backend\models\i500m\Shop;
use backend\models\social\Order;
use backend\models\social\OrderDetail;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * ReportController
 *
 * @category CRM
 * @package  ReportController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ReportController extends BaseController
{
    /**
     * 报表列表
     *
     * @return array
     */
    public function actionIndex()
    {
        $model = new Order();
        $shop_model = new Shop();
        $cond['pay_status'] = 1;
        $cond['status'] = 1;
        $where = [];
        $and_where = [];
        $start_time = RequestHelper::get('start_time', '');
        $end_time = RequestHelper::get('end_time', '');
        $shop_id= RequestHelper::get('shop_id', '');
        if (!empty($start_time)) {
            $where = ['>=', 'create_time', $start_time];
        }
        if (!empty($end_time)) {
            $and_where = ['<=', 'create_time', $end_time];
        }
        if (!empty($shop_id)) {
            $cond['shop_id'] = $shop_id;
        }
        $page = RequestHelper::get('page', 1);
        $size = $this->size;
        $data = $model->getPageLists($cond, '*', 'id desc', $page, $size, $where, $and_where);
        $shop_cond = [];
        //商家列表
        if ($data) {
            $shop_cond['id'] = ArrayHelper::getColumn($data, 'shop_id');
        }
        $shop_list = $shop_model->getList($shop_cond, 'id,shop_name', 'id desc');
        $shop_data = [];
        if ($shop_list) {
            foreach ($shop_list as $k => $v) {
                $shop_data[$v['id']] = $v['shop_name'];
            }
        }
        $list = [];
        if ($data) {
            foreach ($data as $key => $value) {
                $list[$key] = $value;
                $list[$key]['shop_name'] = !empty($shop_data[$value['shop_id']]) ? $shop_data[$value['shop_id']] : '';
            }
        }
        //没有分页的列表
        $data_all = $model->getList($cond, 'order_sn,total', 'id desc', $where, $and_where);
        //订单总金额
        $order_detail_cond =[];
        $number = 0;
        if ($data_all) {
            $order_detail_cond['order_sn'] = ArrayHelper::getColumn($data_all, 'order_sn');
            foreach ($data_all as $v) {
                $number += $v['total'];
            }
        }
        //是水果的分类
        $category_model = new Category();
        $product_model = new Product();
        $order_detail_model = new OrderDetail();
        $category_cond['status'] = 2;
        $category_cond['level'] = 1;
        $category_where = ['like', 'name', '水果'];
        $category_list = $category_model->getList($category_cond, 'id', 'id desc', $category_where);
        $product_cond['single'] = 1;
        if ($category_list) {
            $product_cond['cate_first_id'] = ArrayHelper::getColumn($category_list, 'id');
        }
        $product_list = $product_model->getList($product_cond, 'id', 'id desc');
        if ($product_list) {
            $order_detail_cond['product_id'] = ArrayHelper::getColumn($product_list, 'id');
        }
        $order_detail_list = $order_detail_model->getList($order_detail_cond, 'total', 'id desc');
        $fruits_total = 0;
        if ($order_detail_list) {
            foreach ($order_detail_list as $value) {
                $fruits_total += $value['total'];
            }
        }
        //百分比
        $fruits = $number>0 ? round(($fruits_total/$number)*100, 1).'%' : '0%';
        //商品数量及分页
        $total = $model->getCounts($cond, $where, $and_where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $size]);
        //print_r($list);exit;
        $param = [
            'total' => $total,
            'pages' => $pages,
            'data' => $list,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'number' => $number,
            'fruits_total' => $fruits_total,
            'fruits' => $fruits,
            'shop_id' => $shop_id
        ];
        echo $this->render('index', $param);
    }

    /**
     * 详情
     *
     * @return array
     */
    public function actionView()
    {
        $fruits_total = RequestHelper::get('fruits_total');
        $total = RequestHelper::get('total');
        $surplus_total = $total-$fruits_total;
        echo $this->renderPartial('view', ['fruits_total'=>$fruits_total,'total'=>$surplus_total]);
    }
}

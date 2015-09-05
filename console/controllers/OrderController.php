<?php
/**
 * 订单脚本
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   OrderController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/9/5 0005 上午 10:46
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace console\controllers;

use backend\models\i500m\CrmConfig;
use backend\models\shop\ShopActivityGift;
use backend\models\shop\ShopActivityProduct;
use backend\models\shop\ShopProduct;
use backend\models\social\Order;
use backend\models\social\OrderDetail;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

/**
 * OrderController
 *
 * @category CRM
 * @package  OrderController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class OrderController extends Controller
{
    /**
     * 订单回滚功能（自动取消订单）及减库存机制修改
     *
     * @return array
     */
    public function actionRollback()
    {
        $order_model = new Order();
        $config_model = new CrmConfig();
        $detail_model = new OrderDetail();
        $product_model = new ShopProduct();
        $shop_activity_model = new ShopActivityProduct();
        $shop_activity_gift_model = new ShopActivityGift();
        $config_item = $config_model->getInfo(['key'=>'orderRollTime']);
        $config_time = 60;
        if ($config_item) {
            $config_time = $config_item['value'];
        }
        $time = date("Y-m-d H:i:s", time()-60*$config_time);
        $order_config['pay_status'] = 0;
        $order_config['status'] = 0;
        $where = ['<','create_time',$time];
        $order_item = $order_model->getList($order_config, 'order_sn', 'id desc', $where);
        $result = true;
        if (!empty($order_item)) {
            $detail_cond['order_sn'] = ArrayHelper::getColumn($order_item, 'order_sn');
            $detail_list = $detail_model->getList($detail_cond, 'shop_id,product_id,activity_id,is_gift,num');
            foreach ($detail_list as $list) {
                //普通商品
                if ($list['activity_id']==0 and $list['is_gift']==0) {
                    $product_item = $product_model->getInfo(['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    if ($product_item) {
                        $total = $product_item['product_number'] + $list['num'];
                        $product_model->updateInfo(['product_number' => $total], ['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    }
                }
                //活动商品
                if ($list['activity_id']>0 and $list['is_gift']==0) {
                    $product_item = $shop_activity_model->getInfo(['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    if ($product_item) {
                        $total = $product_item['day_confine_num'] + $list['num'];
                        $shop_activity_model->updateInfo(['day_confine_num' => $total], ['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    }
                }
                //赠品
                if ($list['activity_id']==0 and $list['is_gift']==1) {
                    $product_item = $shop_activity_gift_model->getInfo(['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    if ($product_item) {
                        $total = $product_item['number'] + $list['num'];
                        $shop_activity_gift_model->updateInfo(['number' => $total], ['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    }
                }
                //赠品和活动商品两种情况
                if ($list['activity_id']>0 and $list['is_gift']==1) {
                    $activity_product_item = $shop_activity_model->getInfo(['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    if ($activity_product_item) {
                        $activity_total = $activity_product_item['day_confine_num'] + $list['num'];
                        $shop_activity_model->updateInfo(['day_confine_num' => $activity_total], ['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    }
                    $product_item = $shop_activity_gift_model->getInfo(['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    if ($product_item) {
                        $total = $product_item['number'] + $list['num'];
                        $shop_activity_gift_model->updateInfo(['number' => $total], ['shop_id' => $list['shop_id'], 'product_id' => $list['product_id']]);
                    }
                }
            }
            $result = $order_model->updateInfo(['status'=>2], $order_config);
        }
        if ($result==true) {
            $msg = '成功';
        } else {
            $msg = '失败';
        }
        echo $msg;
    }
}

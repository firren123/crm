<?php
/**
 * 商家返利
 *
 * PHP Version 5
 *
 * @category  Admin
 * @package   RebateController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/6/10 0010 下午 2:32
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */
namespace backend\modules\shop\controllers;
use backend\controllers\BaseController;
use backend\models\i500m\Branch;
use backend\models\i500m\City;
use backend\models\i500m\OrderDetail;
use backend\models\i500m\Product;
use backend\models\i500m\UserOrder;
use backend\models\shop\ShopRebate;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * RebateController
 *
 * @category Admin
 * @package  RebateController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class RebateController extends BaseController
{
    public $config = [
        '500m'=>['amount'=>30, 'reduce'=>5, 'cat_id'=>3043],
        'other'=>['amount'=>30, 'reduce'=>1],
    ];
    /**
     * 商家返利列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new  ShopRebate;
        //分公司列表
        $branch_model = new Branch();
        $branch_cond['status'] = 1;
        $branch_list = $branch_model->getList($branch_cond);
        $branch_data = [];
        if ($branch_list) {
            foreach ($branch_list as $k=>$v) {
                $branch_data[$v['id']] = $v;
            }
        }
        //城市列表
        $city_item = [];
        $city_model = new City();
        $city_cond = ['>', 'id', 0];
        $city_list = $city_model->getList($city_cond, 'id,name');
        $city_data = [];
        if ($city_list) {
            foreach ($city_list as $k=>$v) {
                $city_data[$v['id']] = $v;
            }
        }
        $cond['status'] = [0,1];
        //搜索
        $search = RequestHelper::get('Search');
        if (!empty($search['branch_id'])) {
            $cond['branch_id'] = $search['branch_id'];
            $data['id'] = $search['branch_id'];
            $city_id = $branch_model->getInfo($data, true, 'city_id_arr');
            $ids = explode(',', $city_id['city_id_arr']);
            $city_item = $city_model->getList(array('id'=>$ids));

        }
        if (!empty($search['city_id'])) {
            $cond['city_id'] = $search['city_id'];
        }
        if (!empty($search['create_time'])) {
            $cond['create_time'] = $search['create_time'];
        }
        if (!empty($search['shop_id'])) {
            if (is_numeric($search['shop_id'])) {
                $cond['shop_id'] = $search['shop_id'];
            } else {
                $cond['shop_id'] = 0;
            }
        }
        $page = RequestHelper::get('page', 1);
        $size = $this->size;
        $list = $model->getPageList($cond, '*', 'create_time desc', $page, $size);
        $settled_total = 0;
        $unsettled_total = 0;
        //返现列表结合 分公司和城市
        $data = [];
        if ($list) {
            foreach ($list as $key=>$value) {
                $data[] = $value;
                $data[$key]['branch_name'] = empty($branch_data[$value['branch_id']]) ? '--' : $branch_data[$value['branch_id']]['name'];
                $data[$key]['city_name'] = empty($city_data[$value['city_id']]) ? '--' : $city_data[$value['city_id']]['name'];
                if ($value['status']==1) {
                    $settled_total += $value['money'];
                }
                if ($value['status']==2) {
                    $unsettled_total += $value['money'];
                }
            }
        }
        $total = $model->getCount($cond);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $size]);
        $param = [
            'list' => $data,
            'total' => $total,
            'pages' => $pages,
            'branch_list' => $branch_list,
            'search' => $search,
            'city_list' => $city_item,
            'settled_total' => $settled_total,
            'unsettled_total' => $unsettled_total
        ];
        return $this->render('index', $param);
    }

    /**
     * 商家返利明细
     *
     * @return string
     */
    public function actionDetail()
    {
        $total = 0;
        $treasure_total = 0;
        $other_total = 0;
        $total_treasure =0;
        $total_other =0;
        $id = RequestHelper::get('id');
        $model = new ShopRebate();
        $order_model = new UserOrder();
        $detail_model = new OrderDetail();
        $cond['id'] = $id;
        $item = $model->getInfo($cond, true, 'shop_id,create_time');
        $data = [];
        $config = $this->config;
        if ($item) {
            $order_cond['shop_id'] = $item['shop_id'];
            $start_time = $item['create_time'];
            $end_time = date('Y-m-d', strtotime('+1 day', strtotime($item['create_time'])));
            $map = ['status'=>1, 'ship_status'=>5,'shop_id'=>$item['shop_id']];
            $order_data = $order_model->find()->select('shop_id,order_sn')->where($map)
                ->andWhere(['!=', 'pay_site_id', 1])
                ->andWhere(['>=', 'ship_status_time', $start_time])
                ->andWhere(['<=', 'ship_status_time', $end_time])
                ->asArray()->all();
            $ids = [];
            if ($order_data) {
                foreach ($order_data as $v) {
                    $ids[] = $v['order_sn'];
                }
            }
            $detail_conf['shop_id'] = $item['shop_id'];
            $detail_conf['order_sn'] = $ids;
            $list = $detail_model->getList($detail_conf, '*', 'id desc');
            if ($list) {

                $product_ids = [];
                foreach ($list as $v) {
                    $product_ids[] = $v['p_id'];//获取商品id数组
                }
                $product = new Product();
                $p_list = $product->getList(['id'=>$product_ids], 'id,cate_first_id');
                $p_list = ArrayHelper::index($p_list, 'id');
                foreach ($list as $key=>$value) {
                    $data[] = $value;
                    $data[$key]['attribute_str'] =  empty($value['attribute_str']) ? '--' : implode(' ', explode('_', $value['attribute_str']));;
                    $cat_id = $p_list[$value['p_id']]['cate_first_id'];
                    if ($cat_id == $config['500m']['cat_id'] ) { //是珍品
                        $data[$key]['cate_name'] = '珍品';
                        $treasure_total += $value['total'];
                    } else {
                        $data[$key]['cate_name'] = '其他';
                        $other_total += $value['total'];
                    }
                }
                if ($treasure_total>0) {
                    $total +=floor($treasure_total/$config['500m']['amount']) * $config['500m']['reduce'];
                    $total_treasure +=floor($treasure_total/$config['500m']['amount']) * $config['500m']['reduce'];
                }
                if ($other_total>0) {
                    $total +=floor($other_total/$config['other']['amount']) * $config['other']['reduce'];
                    $total_other +=floor($other_total/$config['other']['amount']) * $config['other']['reduce'];
                }
            }
        }
        return $this->render('detail', ['list'=>$data, 'treasure_total'=>$total_treasure,'other_total'=>$total_other,'total'=>$total]);
    }

    /**
     * 根据分公司id获取开通的城市
     *
     * @return array
     */
    public function actionCity()
    {
        $branch_id = RequestHelper::get('branch_id');//省id
        $branch_model = new Branch();
        $data['id'] = $branch_id;

        $city_id = $branch_model->getInfo($data, true, 'city_id_arr');
        $ids = explode(',', $city_id['city_id_arr']);
        $city = new City();
        $data = $city->getList(array('id'=>$ids));
        echo json_encode($data);
    }

}
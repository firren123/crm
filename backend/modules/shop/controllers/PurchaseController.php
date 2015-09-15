<?php
/**
 * 商家进货记录
 *
 * PHP Version 5
 *
 * @category  Admin
 * @package   PurchaseController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/6/16 0016 上午 10:58
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\modules\shop\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Category;
use backend\models\i500m\Product;
use backend\models\shop\ShopPurchase;
use common\helpers\CurlHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * PurchaseController
 *
 * @category Admin
 * @package  PurchaseController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class PurchaseController extends BaseController
{
    /**
     * 商家进货记录列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new ShopPurchase();
        $page = RequestHelper::get('page', 1);
        $size = $this->size;
        $cond['status'] = [0, 1];
        $where = [];
        $and_where = [];
        //搜索
        $search = RequestHelper::get('Search');
        //搜索商家名称
        if (!empty($search['shop_name'])) {
            $and_where = ['like', 'shop_name', $search['shop_name']];
        }
        //搜索时间
        if (!empty($search['begin_time'])) {
            $where = ['>=', 'buy_date', $search['begin_time']];
        }
        if (!empty($search['end_time'])) {
            $where = ['<=', 'buy_date', $search['end_time']];
        }
        //计算状态
        if (!empty($search['status'])) {
            $cond['status'] = $search['status'] == 2 ? 0 : $search['status'];
        }
        $list = $model->getPageLists($cond, '*', 'buy_date desc', $page, $size, $where, $and_where);
        $cate_id = ArrayHelper::getColumn($list, 'cat_id');//检索出分类id集合
        $product_id = ArrayHelper::getColumn($list, 'product_id');//检索出商品id集合
        //商品分类列表
        $cate_model = new Category();
        $cate_cond['id'] = $cate_id;
        $cate_list = $cate_model->getList($cate_cond, 'id,name', 'id desc');
        $cate_data = ArrayHelper::index($cate_list, 'id');
        //商品列表
        $product_model = new Product();
        $product_cond['id'] = $product_id;
        $product_list = $product_model->getList($product_cond, 'id,name', 'id desc');
        $product_data = ArrayHelper::index($product_list, 'id');
        $data = [];
        if ($list) {
            foreach ($list as $key => $value) {
                $data[] = $value;
                $data[$key]['cate_name'] = empty($cate_data[$value['cat_id']]) ? "--" : $cate_data[$value['cat_id']]['name'];
                $data[$key]['product_name'] = empty($product_data[$value['product_id']]) ? "--" : $product_data[$value['product_id']]['name'];
            }
        }
        $total = $model->getCounts($cond, $where, $and_where);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $size]);
        $param = [
            'list' => $data,
            'pages' => $pages,
            'total' => $total,
            'search' => $search
        ];
        return $this->render('index', $param);
    }
}

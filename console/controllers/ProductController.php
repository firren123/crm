<?php
/**
 * 标准库-脚本
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ProductController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/10/8 0008 下午 1:27
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace console\controllers;

use backend\models\i500m\Product;
use yii\console\Controller;

/**
 * ProductController
 *
 * @category Admin
 * @package  ProductController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ProductController extends Controller
{
    /**
     * 计算毛利率
     *
     * @return array
     */
    public function actionProfit()
    {
        $model = new Product();
        $cond['single'] = 1;
        $where = ['>', 'status', '0'];
        $data = $model->getList($cond, '*', 'id asc', $where);
        $result = true;
        if (!empty($data)) {
            foreach ($data as $value) {
                if ($value['sale_profit_margin']=="") {
                    //毛利率
                    if ($value['origin_price']>0) {
                        $values['sale_profit_margin'] = round(($value['origin_price'] - $value['sale_price']) / $value['origin_price'] * 100, 2) . '%';
                        $values['shop_profit_margin'] = round(($value['origin_price'] - $value['shop_price']) / $value['origin_price'] * 100, 2) . '%';
                    } else {
                        $values['sale_profit_margin'] = '0%';
                        $values['shop_profit_margin'] = '0%';
                    }
                    $result = $model->updateInfo($values, ['id'=>$value['id']]);
                }
            }
        }
        if ($result==true) {
            $array = ['code'=>200, 'msg'=>'成功'];
        } else {
            $array = ['code'=>101, 'msg'=>'失败'];
        }
        echo json_encode($array);
    }
}

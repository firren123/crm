<?php
/**
 * 商家登录日志
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ShoploginlogController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/7/28 0028 下午 3:32
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */
namespace backend\modules\shop\controllers;

use backend\controllers\BaseController;
use backend\models\shop\ShopLoginLog;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * ShoploginlogController
 *
 * @category CRM
 * @package  ShoploginlogController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ShoploginlogController extends BaseController
{
    public $size = 20;

    /**
     * 列表页
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new ShopLoginLog();
        $shop_name = RequestHelper::get('shop_name', '');
        $and_where = [];
        if (!empty($shop_name)) {
            $and_where = ['like', 'shop_name', $shop_name];
        }
        $page = RequestHelper::get('page', 0);
        $size = $this->size;
        $cond = ['!=', 'shop_id', 0];
        $data = $model->getPageList($cond, '*', 'id desc', $page, $size, $and_where);
        $total = $model->getCount($cond, $and_where);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $size]);
        $param = [
            'data' => $data,
            'total' => $total,
            'pages' => $pages,
            'shop_name' => $shop_name
        ];
        return $this->render('index', $param);
    }
}
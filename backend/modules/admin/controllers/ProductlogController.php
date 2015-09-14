<?php
/**
 * Xxx
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   Admin
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @time      2015/3/31 12:27
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhoujun@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\OrderLog;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * Class ProductlogController
 * @category  PHP
 * @package   ProductlogController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ProductlogController extends BaseController
{
    public $size = 10;

    /**
     * 简介：
     * @return string
     */
    public function actionIndex()
    {
        $model = new OrderLog();
        $data = array();
        $data['page'] = RequestHelper::get('page', 1);
        $data['size'] = RequestHelper::get('per-page', $this->size);
        if ($data['page'] == 1) {
            $offset = 0;
        } else {
            $offset = ($data['page'] - 1) * $data['size'];
        }

        $log_type = RequestHelper::get('log_type');
        $order_sn = RequestHelper::get('order_sn');
        if ($order_sn == '0') {
            $order_sn = '1';
        }

        $where = array();
        if ($log_type) {
            $where[] = 'type =' . $log_type;
        }
        if ($order_sn) {
            $where[] = 'order_sn =' . $order_sn;
        }
        $where = empty($where) ? '' : implode(' and ', $where);
        $shop_info = $model->show($data, $offset, $where);
        $total = $model->total($where);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        return $this->render('index', ['shop_info' => $shop_info, 'pages' => $pages, 'log_type' => $log_type]);
    }
}

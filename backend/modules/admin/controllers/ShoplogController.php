<?php
/**
 * 简介:敏感词管理
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   I500
 * @filename  SensitiveKeywordsController.php
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/23 下午8:21
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Log;
use backend\models\shop\OrderLog;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * Class ShoplogController
 * @category  PHP
 * @package   ShoplogController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ShoplogController extends BaseController
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

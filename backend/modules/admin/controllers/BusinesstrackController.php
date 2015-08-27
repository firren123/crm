<?php
/**
 * 业务员轨迹
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  BusinessController.php
 * @author    sunsongsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/27 下午14:04
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\BusinessPosition;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use Yii;

/**
 * BusinessTrackController
 *
 * @category CRM
 * @package  BusinesstrackController
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class BusinesstrackController extends BaseController
{

    /**
     * 业务员轨迹
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'dialog';
        $business_track = new BusinessPosition();
        $business_id = RequestHelper::get('id');
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 5;
        $date = date('Y-m-d');
        $date_start = $date." 09:00:00";
        $date_end = $date." 18:00:00";

        $condition = ['and', ['>', 'create_time', $date_start],['<=', 'create_time', $date_end]];

        if ($business_id != '') {
            $condition = ['and', ['>', 'create_time', $date_start],['<=', 'create_time', $date_end], ['=', 'business_id', $business_id]];
        }

        $list = $business_track->getPageList($condition, '*', 'id desc', $page, $page_size);
        if (empty($list)) {
            $list = array();
        }

        $count = $business_track->getCount($condition);

        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);

        return $this->render('index', ['data' => $list, 'bus_id' => $business_id, 'count' => $count, 'pages' => $pages, 'page_count' => $page_count]);
    }

}
<?php
/**
 * 退款流程
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  User_orderController.php
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/7 下午4:02
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\social\ExChange;
use common\helpers\RequestHelper;
use yii\data\Pagination;


/**
 * Class UserorderController
 * @category  PHP
 * @package   Crm
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ExchangeController extends BaseController
{
    /**
     * 简介：
     * @author  sunsong@iyangpin.com。
     * @return null
     */
    public function actionIndex()
    {
        $page_size = 5;
        $page = RequestHelper::get('page', 1, 'intval');

        $model = new ExChange();
        $where = ['>', 'id', 0];
        $list = $model->getPageList($where, '*', 'id desc', $page, $page_size);

        $count       = $model->getCount($where);
        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);//分页

       /* echo "<pre>";
        print_r($list);
        echo "</pre>";
        die;*/
        $data = array(
            'data' => $list,
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count,
        );
        return $this->render('index', $data);
    }
}


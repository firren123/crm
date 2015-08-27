<?php
/**
 * 业务员提交信息
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
 * @datetime  15/6/10 下午11:04
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\BusinessReport;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * BusinessSubInfoController
 *
 * @category CRM
 * @package  BusinesssubinfoController
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class BusinesssubinfoController extends BaseController
{
    /**
     * 业务员提交信息展示
     *
     * @return array
     */
    public function actionIndex()
    {

        //$this->layout = 'dialog';
        $business_report = new BusinessReport();
        $business_id = RequestHelper::get('id');
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 10;
        $date_m = date('Y-m');
        $date = date('Y-m-d H:i:s');
        $admin_bc_id = $this->bc_id;

        $and_where = [];
        if ((!empty($admin_bc_id) || $admin_bc_id != '0') && $admin_bc_id != '28') {
            $and_where = ['=', 'bc_id', $admin_bc_id['bc_id']];
        }

        $condition = [];
        if ($business_id != '') {
            $condition = ['and', ["<=", 'create_time', $date], [">=", 'create_time', $date_m], ["like", 'business_id', $business_id]];
        }

        $list = $business_report->getPageList($condition, '*', 'id desc', $page, $page_size, $and_where);
        $count = $business_report->getCount($condition, $and_where);

        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);


        return $this->render('index', ['data' => $list, 'count' => $count, 'pages' => $pages, 'page_count' => $page_count, 'business_id' => $business_id]);

    }

    /**
     * 查看图片信息
     *
     * @return array
     */
    public function actionLook()
    {
        $business_report = new BusinessReport();
        $id = RequestHelper::get('id');
        $business_id = RequestHelper::get('bid');
        $where = ['and', ['=', 'id', $id], ['=', 'business_id', $business_id]];
        $info = $business_report->getOneRecord($where, '', '*');
        $img = array();
        if (!empty($info)) {
            $imgs = explode(',', rtrim($info['image'], ','));
        }
        foreach ($imgs as $v) {
            $img[] = \Yii::$app->params['imgHost'].$v;
        }

        $res = array('msg' => $img, 'status' => '1');
        echo json_encode($res);
        exit(0);

    }

    /**
     * 业务员修改
     *
     * @return string
     */
    public function actionEdit()
    {

        return $this->render('edit', []);

    }

    /**
     * 业务员删除
     *
     * @return string
     */
    public function actionDelete()
    {

        return $this->render('del', []);

    }

}
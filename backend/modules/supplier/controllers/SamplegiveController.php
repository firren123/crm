<?php
/**
 * 样品控制器
 *
 * PHP Version 5
 * 样品管理
 *
 * @category  Admin
 * @package   Supplier
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      15/9/22 上午11:52
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      sunsong@iyangpin.com
 */

namespace backend\modules\supplier\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\SampleApply;
use backend\models\i500m\SupplierSample;
use backend\models\i500m\SupplierSampleGive;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * Class SampleController
 * @category  PHP
 * @package   SampleController
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SamplegiveController extends BaseController
{
    /**
     * 简介：
     * @return string
     */
    public function actionIndex()
    {
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 10;
        $cond = [];
        $cond['status'] = 1;

        $model = new SupplierSampleGive();
        $list = $model->getPageList($cond, '*', 'id desc', $page, $page_size);

        $count = $model->getCount($cond);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);

        return $this->render('index', ['list' => $list, 'pages' => $pages]);
    }

    /**
     * 简介：
     * @return string
     */
    public function actionDetail()
    {
        $id = RequestHelper::get('id', 0, 'intval');

        $info = [];
        if ($id) {
            $m_supplier_sample = new SupplierSample();
            $info = $m_supplier_sample->getInfo(['id' => $id]);
        }

        return $this->render('detail', ['info' => $info]);
    }

    /**
     * 简介：接收人列表
     * @return string
     */
    public function actionList()
    {
        $sam_apply = new SampleApply();
        $where = ['>', 'id', 0];
        $list = $sam_apply->getList($where, '*', 'id desc', '');
        if (empty($list)) {
            $list = array();
        }
        $data = array(
            'data' => $list
        );
        return $this->render('list', $data);
    }

    /**
     * 简介：
     * @return string
     */
    public function actionExamine()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $status = RequestHelper::get('status', 0, 'intval');

        if ($id) {
            $m_supplier_sample = new SupplierSampleGive();
            $supplier_sample = $m_supplier_sample->getInfo(['id' => $id], false);

            if (!$supplier_sample['id']) {
                echo json_encode(['code' => 101, 'msg' => '无数据']);
                exit;
            }

            if ($supplier_sample['status'] != 1) {
                echo json_encode(['code' => 102, 'msg' => '不是待审核状态，不能操作']);
                exit;
            }

            $supplier_sample->status = $status;
            $supplier_sample->save();

            echo json_encode(['code' => 200, 'msg' => '操作成功']);
            exit;
        }
        echo json_encode(['code' => 103, 'msg' => '缺少id']);
        exit;
    }
}


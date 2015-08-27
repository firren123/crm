<?php
/**
 * 样品控制器
 *
 * PHP Version 5
 * 样品管理
 *
 * @category  Admin
 * @package   Supplier
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/7/24 上午11:52 
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\modules\supplier\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\SupplierSample;
use common\helpers\RequestHelper;
use yii\data\Pagination;

class SampleController extends BaseController{

    public function actionIndex()
    {
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 10;
        $cond = [];
        $cond['status'] = 1;
        $m_supplier_sample = new SupplierSample();

        $list = $m_supplier_sample->getPageList($cond, '*', 'id desc', $page, $page_size);

        $count = $m_supplier_sample->getCount($cond);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);

        return $this->render('index', ['list'=>$list, 'pages'=>$pages]);
    }

    public function actionDetail()
    {
        $id = RequestHelper::get('id', 0, 'intval');

        $info = [];
        if ($id) {
            $m_supplier_sample = new SupplierSample();
            $info = $m_supplier_sample->getInfo(['id'=>$id]);
        }

        return $this->render('detail', ['info'=>$info]);
    }

    public function actionExamine()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $status = RequestHelper::get('status', 0, 'intval');

        if ($id) {
            $m_supplier_sample = new SupplierSample();
            $supplier_sample = $m_supplier_sample->getInfo(['id'=>$id], false);

            if (!$supplier_sample['id']) {
                echo json_encode(['code'=>101, 'msg'=>'无数据']);
                exit;
            }

            if ($supplier_sample['status'] != 1) {
                echo json_encode(['code'=>102, 'msg'=>'不是待审核状态，不能操作']);
                exit;
            }

            $supplier_sample->status = $status;
            $supplier_sample->save();

            echo json_encode(['code'=>200, 'msg'=>'操作成功']);
            exit;
        }
        echo json_encode(['code'=>103, 'msg'=>'缺少id']);
        exit;
    }
}
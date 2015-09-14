<?php
/**
 * 商家展位管理
 *
 * PHP Version 5
 * 商家展位相关操作
 *
 * @category  Admin
 * @package   Shop
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/7/23 下午5:08
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\modules\shop\controllers;


use backend\controllers\BaseController;
use backend\models\shop\ShopCupboard;
use common\helpers\RequestHelper;
use yii\data\Pagination;

class CupboardController extends BaseController
{

    public function actionIndex()
    {
        $page = RequestHelper::get('page', 1, 'intval');

        $page_size = 10; //每页显示条数
        $cond = [];
        $cond['status'] = [0, 1];   //默认显示待确认的
        $m_cupboard = new ShopCupboard();

        $list = $m_cupboard->getPageList($cond, '*', 'id desc', $page, $page_size);

        $count = $m_cupboard->getCount($cond);

        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);

        return $this->render('index', ['list' => $list, 'pages' => $pages]);
    }

    public function actionDetail()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $info = [];
        if ($id) {
            $m_cupboard = new ShopCupboard();
            $info = $m_cupboard->getInfo(['id' => $id]);
        }

        return $this->render('detail', ['info' => $info]);
    }

    public function actionExamine()
    {
        $id = RequestHelper::get('id', 0, 'intval');

        if ($id) {
            $m_cupboard = new ShopCupboard();
            $cupboard = $m_cupboard->getInfo(['id' => $id], false);
            if (!$cupboard) {
                echo json_encode(['code' => 101, 'msg' => '无数据']);
                exit;
            }

            if ($cupboard['status'] != 0) {
                echo json_encode(['code' => 102, 'msg' => '不是待审核状态，不能操作']);
                exit;
            }

            $cupboard->status = 1;
            $cupboard->save();

            echo json_encode(['code' => 200, 'msg' => '操作成功']);
            exit;
        }

        echo json_encode(['code' => 103, 'msg' => '缺少id']);
        exit;
    }
}
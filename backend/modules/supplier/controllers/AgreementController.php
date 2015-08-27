<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Wap   
 * @package   Member     (这里写模块名)
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/7/27 上午9:48 
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\modules\supplier\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\SupplierAgreement;
use backend\models\shop\ShopCupboard;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class AgreementController extends BaseController
{
    public function actionIndex()
    {
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = 10;
        $cond = [];
        $cond['status'] = [0,1,2,3];
        $m_agreement = new SupplierAgreement();

        $list = $m_agreement->getPageList($cond, '*', 'id desc', $page, $page_size);
        $count = $m_agreement->getCount($cond);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);

        return $this->render('index', ['list'=>$list, 'pages'=>$pages]);
    }
    public function actionUpStatus()
    {
        $id = RequestHelper::get('id',0,'intval');
        $status = RequestHelper::get('status',0,'intval');
        if (in_array($status,[0,1,2,3])) {
            $model = new SupplierAgreement();
            $re = $model->updateInfo(['status'=>$status], ['id'=>$id]);
            if ($re) {
                $info = $model->getInfo(['id'=>$id],true,['sample_id','sample_name','cupboard_id']);
                $m_cupboard = new ShopCupboard();
                $res = $m_cupboard->updateInfo(['sample_id'=>$info['sample_id'],'sample_name'=>$info['sample_name'],'is_occupy'=>1],['id'=>$info['cupboard_id']]);
                if ($res) {
                    return $this->success('操作成功！','index');
                } else {
                    return $this->error('更新橱位失败！','index');
                }

            } else {
                return $this->error('操作失败！','index');
            }
        } else {
            return $this->error('无效的状态值！','index');
        }
        //sample_id  sample_name  cupboard_id


    }
}
<?php
/**
 * 简介
 * @category WAP
 * @package 模块名称
 * @author zhoujun<dj652262790@126.com>
 * @time 2015/6/3 13:44
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license http://www.i500m.com
 * @link zhoujun@iyangpin.com
 */

namespace backend\modules\admin\controllers;
use backend\controllers\BaseController;
use backend\models\i500m\Log;
use backend\models\shop\OrderLog;
use common\helpers\RequestHelper;
use yii\data\Pagination;

class ShoplogController extends BaseController{
    public $size = 10;

    public function actionIndex()
    {
        $model = new OrderLog();
        $data = array();
        $data['page'] = RequestHelper::get('page',1);
        $data['size'] = RequestHelper::get('per-page',$this->size);
        if($data['page'] == 1){
            $offset = 0;
        }else{
            $offset = ($data['page']-1) * $data['size'];
        }

        $log_type = RequestHelper::get('log_type');
        $order_sn = RequestHelper::get('order_sn');
        if($order_sn == '0'){
            $order_sn = '1';
        }

        $where = array();
        if($log_type){
            $where[] = 'type ='.$log_type;
        }
        if($order_sn){
            $where[] = 'order_sn ='.$order_sn;
        }
        $where = empty($where) ? '' : implode(' and ', $where);
        $shop_info = $model->show($data, $offset, $where);
        $total = $model->total($where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index',['shop_info'=>$shop_info,'pages'=>$pages,'log_type'=>$log_type]);
    }
} 
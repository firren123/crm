<?php
/**
 * 简介
 * @category WAP
 * @package 模块名称
 * @author zhoujun<dj652262790@126.com>
 * @time 2015/6/3 11:20
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license http://www.i500m.com
 * @link zhoujun@iyangpin.com
 */

namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Log;
use common\helpers\RequestHelper;
use yii\data\Pagination;


class LogController extends BaseController{

    public $size = 10;

    /**
     * 简介：
     * @return string
     */
    public function actionIndex()
    {
        $model = new Log();
        $data = array();
        $data['page'] = RequestHelper::get('page',1);
        $data['size'] = RequestHelper::get('per-page',$this->size);
        if($data['page'] == 1){
            $offset = 0;
        }else{
            $offset = ($data['page']-1) * $data['size'];
        }

        $log_type = RequestHelper::get('log_type');

        $where = array();
        if($log_type){
            $where[] = 'log_type ='.$log_type;
        }
        $where = empty($where) ? '' : implode(' and ', $where);
        $shop_info = $model->show($data, $offset, $where);
        $total = $model->total($where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index',['shop_info'=>$shop_info,'pages'=>$pages,'log_type'=>$log_type]);
    }
} 
<?php
/**
 * 简介
 * 用户订单类
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  UserorderController.php
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright  www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/26 下午7:52
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\goods\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Barcode;
use backend\models\i500m\Shop;
use yii;
use yii\data\Pagination;
use common\helpers\RequestHelper;
use yii\helpers\ArrayHelper;

class BarcodeController extends BaseController
{
    public $enableCsrfValidation = false;

    public $pay_site_id_data = array();
    public function init(){
        parent::init();
    }

    /**
     * 简介：
     * 扫码列表`
     * @author  lichenjun@iyangpin.com。
     * @return  string
     */
    public function actionIndex()
    {
        $bar_code = RequestHelper::post('bar_code', '');
        $start_time = RequestHelper::post('start_time',0);
        $end_time = RequestHelper::post('end_time',0);

        $page = RequestHelper::get('page', 1, 'intval');
        $where = array();
        $andWhere = [];

        if ($bar_code) {
            if(strlen($bar_code)!=13){
                return $this->error('请输入正确的13位码','/goods/barcode/index');
            }

            $where['bar_code'] = $bar_code;
        }

        if($start_time && $end_time){
            if($start_time >$end_time){
                return $this->error('开始时间不能大于结束时间','/goods/barcode/index');
            }
        }


        if ($start_time) {
            $andWhere[] = " add_time>'" . $start_time . " 00:00:00'";
        }
        if ($end_time) {
            $andWhere[] = " add_time<'" . $end_time . " 23:59:59'";
        }

        $andWhere = empty($andWhere) ? '' : implode(' and ', $andWhere);

        $model = new Barcode();

        $count = $model->getListCount($where,$andWhere);

        $list = $model->getList2($where,$andWhere,['add_time'=> SORT_DESC ],"*",($page-1)*$this->size,$this->size);

        //商家名称
        if(is_array($list) && !empty($list)){
            foreach($list as $key =>$val){
                $shop_id = $val['shop_id'];
                $shop_model = new Shop();
                $shop_info = $shop_model->shop_info($shop_id);
                $list[$key]['shop_name'] = $shop_info['shop_name'];
            }
        }

        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        return $this->render('index', [
            'pages' => $pages,
            'data' => $list,
            'bar_code'=>$bar_code,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
        ]);
    }




}
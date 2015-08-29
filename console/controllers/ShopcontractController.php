<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  ShopcontractController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/11 下午2:50
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace console\controllers;
use backend\models\shop\ShopContract;
use yii\console\Controller;


/**
 * Class ShopcontractController
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ShopcontractController extends Controller{
    /**
     * 简介：添加商家合同到OA系统中
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAddOa()
    {
        $connection = \Yii::$app->db_oa;
//        $ret = $connection->createCommand('select max(RUN_ID) as run_id from flow_run')->queryAll();
        $time = date("Y-m-d H:i:s",strtotime("-1 hour"));

        $sql ='select GROUP_CONCAT(run_id) as run_ids from flow_run_prcs where DELIVER_TIME>"$time" and FLOW_PRCS =4 and PRCS_FLAG = 3';
        $ret = $connection->createCommand($sql)->queryAll();
        if($ret){
            $run_ids = $ret[0]['run_ids'];
            echo $sql ='select data_93 as crm_id from flow_data_160 where run_id in ('.$run_ids.')';
            $crm_ids = $connection->createCommand($sql)->queryAll();
            foreach($crm_ids as $k => $v){
                $shopContractModel = new ShopContract();
                $t = $shopContractModel->updateInfo(['status'=>1],['id'=>$v['crm_id']]);
                if($t){
                    echo $v['crm_id']."success\n";
                }
            }
        }
    }
}
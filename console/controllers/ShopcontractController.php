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
        $connection = @\Yii::$app->db_oa;
        $ret = $connection->createCommand('select max(RUN_ID) as run_id from flow_run')->queryAll();
        var_dump($ret);
        $run_id = $ret[0]['run_id']+1;
        $new_time = date('Y-m-d H:i:s');
        $data_info = date('Y-m-d H:i:s') .'--'. rand(00000, 99999);
        $info = [];
        $info['run_id'] = $run_id ; //'107' 自增
        $info['run_name'] = "商家合同审批(".$new_time.")" ;  //商家合同审批(2015-08-10 09:11:57) 按照格式填写
        $info['begin_user'] = 'BJ1013' ; //BJ1013 固定一人
        $info['begin_time'] = $new_time ;  //2015-08-10 09:11:57 当前时间
        $info['flow_auto_num'] = $data_info ; //0
        $info['data_76'] = $data_info ;  //测试注册名称
        $info['data_75'] = $data_info ;  //测试注册地址
        $info['data_74'] = $data_info ;  //测试注册登记号
        $info['data_73'] = $data_info ;  //测试注册资本
        $info['data_90'] = $data_info ;  //测试合同号
        $info['data_91'] = $data_info ;  //测试公司性质
        $info['data_72'] = $data_info ;  //测试法定代表人
        $info['data_71'] = $data_info ;  //测试邮箱
        $info['data_70'] = $data_info ;  //测试证件类型
        $info['data_69'] = $data_info ;  //测试证件号
        $info['data_68'] = $data_info ;  //测试联系人
        $info['data_67'] = $data_info ;  //测试联系电话
        $info['data_66'] = $data_info ;  //测试经营名称
        $info['data_65'] = $data_info ;  //测试经营范围
        $info['data_64'] = $data_info ;  //测试实际经营范围
        $info['data_63'] = $data_info ;  //测试经营地址
        $info['data_59'] = $data_info ;  //测试联系人
        $info['data_60'] = $data_info ;  //测试职务
        $info['data_58'] = $data_info ;  //测试联系电话
        $info['data_54'] = $data_info ;  //测试月均营业额
        $info['data_57'] = $data_info ;  //测试营业时间
        $info['data_56'] = $data_info ;  //测试面积
        $info['data_55'] = $data_info ;  //测试所在社区名称
        $info['data_53'] = $data_info ;  //测试账户类型
        $info['data_52'] = $data_info ;  //测试开户银行
        $info['data_51'] = $data_info ;  //测试所在城市
        $info['data_50'] = $data_info ;  //测试开户支行
        $info['data_49'] = $data_info ;  //测试银行卡号
        $info['data_48'] = $data_info ;  //测试开户名称
        $info['data_47'] = $data_info ;  //测试结算周期
        $info['data_42'] = $data_info ;  //测试服务费用
        $info['data_44'] = $data_info ;  //2015-08-10开始时间
        $info['data_45'] = $data_info ;  //2015-08-14结束时间
        $info['data_46'] = $data_info ;  //测试业务员

        //flow_run
        $data =[];
        $data['run_id'] = $run_id ; //'107' 自增
        $data['RUN_NAME'] = '商家合同审批('.$new_time.')' ; //商家合同审批1(2015-08-10 09:11:57)
        $data['FLOW_ID'] = '160' ;              //合同表ID
        $data['BEGIN_USER'] = 'BJ1013' ;        //BJ1013  流程发起人ID
        $data['BEGIN_DEPT'] = 43 ;              //流程发起人部门ID
        $data['BEGIN_TIME'] = $new_time;   //流程实例创建时间
        $data['DEL_FLAG'] = 0; //删除标记(0-未删除,1-已删除)删除后流程实例可在工作销毁中确实删除或还原
        $data['ARCHIVE'] = 0;       //是否归档(0-否,1-是)
        $data['work_level'] = 0;    //工作等级 0-普通 1-重要 2-紧急

        $connection = @\Yii::$app->db_oa;
        $transaction = $connection->beginTransaction();
        try {
            $connection->createCommand()->insert('flow_data_160', $info)->execute();
            $connection->createCommand()->insert('flow_run', $data)->execute();
            // ... executing other SQL statements ...
            $transaction->commit();
            echo 'success';
        } catch (Exception $e) {
            echo "error";
            $transaction->rollBack();

        }
    }

    /**
     * 简介：读取OA获取ID
     * @author  lichenjun@iyangpin.com。
     * @return mixed
     */
    protected function getOaRunIdValue()
    {

        return $r;
    }


}
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

use backend\models\i500m\Business;
use backend\models\i500m\City;
use backend\models\shop\ShopContract;
use backend\models\shop\ShopManage;
use common\helpers\CommonHelper;
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
class ShopcontractController extends Controller
{
    public $document_type_data = [
        0 => '二代身份证',
        1 => '港澳通行证',
        2 => '台湾通行证',
        3 => '护照'
    ];
    public $company_nature_data = [
        0 => '个体商户',
        1 => '民办非企业',
        2 => '股份制',
        3 => '有限责任制',
        4 => '其他'
    ];
    public $account_type_data = [
        0 => '银行账号',
        1 => '支付宝账号',
    ];
    public $settlement_cycle_data = [
        0 => '1天',
        1 => '5天',
        2 => '7天',
        3 => '14天',
        4 => '30天',
        5 => '60天',
        6 => '每月1次',
        7 => '每月2次'
    ];
    public $business_scope_data = [
        1 => '日用百货',
        2 => '工艺美术品',
        3 => '文教用品',
        4 => '副食品',
    ];
    /**
     * 简介：添加商家合同到OA系统中
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAddOa()
    {
        $connection = \Yii::$app->db_oa;
        $time = date("Y-m-d H:i:s", strtotime("-1 hour"));
        $sql = 'select run_id from flow_run_prcs where DELIVER_TIME>"' . $time . '" and FLOW_PRCS =6 and PRCS_FLAG = 4';
        $ret = $connection->createCommand($sql)->queryAll();
        if ($ret) {
            $shopContractModel = new ShopContract();
            foreach ($ret as $k => $v) {
                $sql = 'select data_93 as crm_id from flow_data_160 where run_id = ' . $v['run_id'];
                $crm_ids = $connection->createCommand($sql)->queryAll();
                $t = $shopContractModel->updateInfo(['status' => 1], ['id' => $crm_ids[0]['crm_id']]);
                if ($t) {
                    echo $v['crm_id'] . "success\n";
                } else {
                    echo $v['crm_id'] . "error\n";
                }
            }
        } else {
            echo "sql error";
        }
    }

    /**
     * 简介：导入之前遗留合同信息
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionAddOldContract()
    {
        $connection = \Yii::$app->db_oa;

        $shopContractModel = new ShopContract();
        $where = ['<', 'id', 43];
        $data_info = $shopContractModel->getList($where);
        $shop_manage_model = new ShopManage();
        foreach ($data_info as $k => $v) {
            $ShopManageMsg = $shop_manage_model->getList(['contract_id' => $v['id']]);
            $ret = $connection->createCommand('select max(RUN_ID) as run_id from flow_run')->queryAll();
            echo $run_id = $ret[0]['run_id'] + 1;
            $new_time = date('Y-m-d H:i:s');
            $info = [];
            var_dump($v['shop_contract_name']);
            $info['run_id'] = $run_id; //'107' 自增
            $info['run_name'] = CommonHelper::utf8ToGbk("商家合同审批(" . $new_time . ")");  //商家合同审批(2015-08-10 09:11:57) 按照格式填写
            $info['begin_user'] = 'BJ1013'; //BJ1013 固定一人
            $info['begin_time'] = $new_time;  //2015-08-10 09:11:57 当前时间
            $info['flow_auto_num'] = 0; //0
            $info['data_76'] = CommonHelper::utf8ToGbk($v['shop_contract_name']);  //测试注册名称
            $info['data_75'] = CommonHelper::utf8ToGbk($v['registered_address']);  //测试注册地址
            $info['data_74'] = CommonHelper::utf8ToGbk($v['registered_id']); //测试注册登记号
            $info['data_73'] = CommonHelper::utf8ToGbk($v['registered_capital']); //测试注册资本
            $info['data_90'] = CommonHelper::utf8ToGbk($v['htnumber']);  //测试合同号
            $info['data_91'] = CommonHelper::utf8ToGbk($this->company_nature_data[$v['company_nature']]);  //测试公司性质
            $info['data_72'] = CommonHelper::utf8ToGbk($v['legal_representative']);   //测试法定代表人
            $info['data_71'] = CommonHelper::utf8ToGbk($v['email']);   //测试邮箱
            $info['data_70'] = CommonHelper::utf8ToGbk($this->document_type_data[$v['document_type']]);   //测试证件类型
            $info['data_69'] = CommonHelper::utf8ToGbk($v['document_number']); //测试证件号
            $info['data_68'] = CommonHelper::utf8ToGbk($v['contacts']);  //测试联系人
            $info['data_67'] = CommonHelper::utf8ToGbk($v['contacts_umber']);  //测试联系电话
            $info['data_66'] = CommonHelper::utf8ToGbk($ShopManageMsg['business_name']);  //测试经营名称
            $info['data_65'] = CommonHelper::utf8ToGbk(implode(',', $this->business_scope_data));  //测试经营范围
            $scope = '';
            foreach ($this->business_scope_data as $kk => $vv) {
                $pos = strpos($ShopManageMsg['business_scope'], "$kk");
                if ($pos === false) {
                } else {
                    $scope .= $vv . ',';
                }
            }
            $info['data_64'] = CommonHelper::utf8ToGbk($scope);  //测试实际经营范围  待定字段
            $info['data_63'] = CommonHelper::utf8ToGbk($ShopManageMsg['business_address']); //测试经营地址
            $info['data_59'] = CommonHelper::utf8ToGbk($v['common_contacts_name']);  //测试联系人
            $info['data_60'] = CommonHelper::utf8ToGbk($v['common_contacts_job']);  //测试职务
            $info['data_58'] = CommonHelper::utf8ToGbk($v['common_contacts_phone']);  //测试联系电话
            $info['data_54'] = CommonHelper::utf8ToGbk($v['monthly_turnover']);  //测试月均营业额
            $info['data_57'] = CommonHelper::utf8ToGbk($v['business_hours']); //测试营业时间
            $info['data_56'] = CommonHelper::utf8ToGbk($v['area']);  //测试面积
            $info['data_55'] = CommonHelper::utf8ToGbk($v['community_name']); //测试所在社区名称
            $info['data_53'] = CommonHelper::utf8ToGbk($this->account_type_data[$v['account_type']]);   //测试账户类型
            $info['data_52'] = CommonHelper::utf8ToGbk($v['bank_name']);  //测试开户银行
            $cityModel = new City();
            $city = $cityModel->getInfo(['id' => $v['bank_city']]);
            $info['data_51'] = CommonHelper::utf8ToGbk($city['name']);  //测试所在城市
            $info['data_50'] = CommonHelper::utf8ToGbk($v['bank_branch']);  //测试开户支行
            $info['data_49'] = CommonHelper::utf8ToGbk($v['bank_number']);  //测试银行卡号
            $info['data_48'] = CommonHelper::utf8ToGbk($v['bankcard_username']);  //测试开户名称
            $info['data_47'] = CommonHelper::utf8ToGbk($this->settlement_cycle_data[$v['settlement_cycle']]);    //测试结算周期
            $info['data_42'] = CommonHelper::utf8ToGbk($v['service_charge'] == 0 ? '固定服务费用' : '服务佣金');  //测试服务费用
            $info['data_44'] = CommonHelper::utf8ToGbk($v['start_time']);  //2015-08-10开始时间
            $info['data_45'] = CommonHelper::utf8ToGbk($v['end_time']); //2015-08-14结束时间
            $info['data_93'] = CommonHelper::utf8ToGbk($ShopManageMsg['contract_id']);  // //2015-08-14结束时间
            $businessModel = new Business();
            $business = $businessModel->getInfo(['id' => $v['counterman']]);
            $info['data_46'] = CommonHelper::utf8ToGbk($business['name']); //测试业务员
            //flow_run
            $data = [];
            $data['run_id'] = $run_id; //'107' 自增
            $data['RUN_NAME'] = CommonHelper::utf8ToGbk('商家合同审批(' . $new_time . ')'); //商家合同审批1(2015-08-10 09:11:57)
            $data['FLOW_ID'] = '160';              //合同表ID
            $data['BEGIN_USER'] = 'BJ1013';        //BJ1013  流程发起人ID
            $data['BEGIN_DEPT'] = 43;              //流程发起人部门ID
            $data['BEGIN_TIME'] = $new_time;   //流程实例创建时间
            $data['DEL_FLAG'] = 0; //删除标记(0-未删除,1-已删除)删除后流程实例可在工作销毁中确实删除或还原
            $data['ARCHIVE'] = 0;       //是否归档(0-否,1-是)
            $data['work_level'] = 0;    //工作等级 0-普通 1-重要 2-紧急

            //flow_run_prcs
            $data2 = [];
            $data2['RUN_ID'] = $run_id;
            $data2['PRCS_ID'] = '1';  //流程实例步骤ID
            $data2['USER_ID'] = 'BJ1013';  //用户ID
            $data2['PRCS_TIME'] = $new_time; //工作接收时间
            $data2['DELIVER_TIME'] = '0000-00-00 00:00:00'; //工作转交/办结时间
            $data2['PRCS_FLAG'] = '2';   //步骤状态(1-未接收,2-办理中,3-转交下一步，下一步经办人无人接收,4-已办结,5-自由流程预设步骤,6-已挂起,)
            $data2['FLOW_PRCS'] = '1';   //步骤ID[设计流程中的步骤号]
            $data2['OP_FLAG'] = '1';     //是否主办(0-经办,1-主办)
            $data2['TOP_FLAG'] = '0';   //主办人选项(0-指定主办人,1-先接收者主办,2-无主办人会签,)
            $data2['PARENT'] = '0';    //上一步流程FLOW_PRCS
            $data2['CHILD_RUN'] = '0';  //子流程的流程实例ID
            $data2['TIME_OUT'] = '';  //待定
            $data2['FREE_ITEM'] = '';  //步骤可写字段[仅自由流程且只有主办人生效]
            $data2['TIME_OUT_TEMP'] = '';  //待定
            $data2['OTHER_USER'] = '';   //工作委托用户ID串
            $data2['TIME_OUT_FLAG'] = '0'; //是否超时(1-超时,其他-不超时)
            $data2['CREATE_TIME'] = $new_time;   //工作创建时间
            $data2['FROM_USER'] = '';    //工作移交用户ID
            $data2['ACTIVE_TIME'] = '0000-00-00 00:00:00';   //取消挂起的时间
            $data2['COMMENT'] = '';      //批注
            $data2['PRCS_DEPT'] = '43';     //超时统计查询增加部门
            $data2['PARENT_PRCS_ID'] = 0;  //上一步流程PRCS_ID
            $data2['BACK_PRCS_ID'] = 0;    //返回步骤PRCS_ID标志
            $data2['BACK_FLOW_PRCS'] = 0;  //返回步骤FLOW_PRCS标志
            $data2['TIME_OUT_ATTEND'] = 0;  //是否排除工作时段按排班类型(0-否,1-是)
            $data2['TIME_OUT_TYPE'] = '1';    //超时计算方法(0-本步骤接收后开始计时,1-上一步骤转交后开始计时 )
            $data2['RUN_PRCS_NAME'] = '';
            $data2['RUN_PRCS_ID'] = '';
            $transaction = $connection->beginTransaction();
            try {
                $connection->createCommand()->insert('flow_data_160', $info)->execute();
                $connection->createCommand()->insert('flow_run', $data)->execute();
                $connection->createCommand()->insert('flow_run_prcs', $data2)->execute();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();

            }
        }
    }
}

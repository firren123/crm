<?php
/**
 * 商家合同
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ShopcontractController.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @time      2015/8/13  下午 5:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      weitonghe@iyangpin.com
 */
namespace backend\modules\shop\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\City;
use backend\models\i500m\Province;
use backend\models\i500m\Business;
use backend\models\shop\ShopContract;
use backend\models\shop\ShopManage;
use backend\models\shop\ShopBank;
use backend\models\shop\ShopBcBank;
use common\helpers\CommonHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use common\helpers\FastDFSHelper;

/**
 * ShopcontractController
 *
 * @category CRM
 * @package  ShopcontractController
 * @author   weitonghe <weitonghe@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ShopcontractController extends BaseController
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
        //1 => '支付宝账号',
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
     * 简介：商家合同列表
     * @author  weitonghe@iyangpin.com
     * @return Array
     */
    public function actionIndex()
    {
        $ShopContract_model = new ShopContract();
        $page = RequestHelper::get('page', 1, 'intval');//获得当前的页数
        $pageSize = 10;                                 //设置每页显示的记录条数
        // 查询条件
        $htNumber = RequestHelper::get('htnumber');                     //合同号
        $shop_contract_name = RequestHelper::get('shop_contract_name'); //注册名称
        $contacts = RequestHelper::get('contacts');                     //联系人
        $contacts_umber = RequestHelper::get('contacts_umber');         //电话
        $counterman = RequestHelper::get('counterman');                 //业务员

        !empty($htNumber) ? $and_Cond1 = ['like', 'htnumber', $htNumber] : $and_Cond1 = [];
        !empty($shop_contract_name) ? $and_Cond2 = ['like', 'shop_contract_name', $shop_contract_name] : $and_Cond2 = [];
        !empty($contacts) ? $and_Cond3 = ['like', 'contacts', $contacts] : $and_Cond3 = [];
        !empty($contacts_umber) ? $and_Cond4 = ['like', 'contacts_umber', $contacts_umber] : $and_Cond4 = [];
        !empty($counterman) ? $and_Cond5 = ['like', 'counterman', $counterman] : $and_Cond5 = [];
        $cond = [];
        $total = $ShopContract_model->countId($cond, $and_Cond1, $and_Cond2, $and_Cond3, $and_Cond4, $and_Cond5);//获得总记录条数
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        $fields = 'id,htnumber,shop_contract_name,contacts,contacts_umber,counterman,status';
        $order = 'id DESC';
        $allInfo = $ShopContract_model->selInfo($cond, $and_Cond1, $and_Cond2, $and_Cond3, $and_Cond4, $and_Cond5, $fields, $order, $page, $pageSize);
        return $this->render('index', array('list' => $allInfo, 'pages' => $pages, 'model' => $ShopContract_model));
    }

    /**
     * 简介：显示添加商家合同页面
     * @author  weitonghe@iyangpin.com
     * @return  Array
     */
    public function actionAdd()
    {
        $ShopContract_model = new ShopContract();
        //得到省份列表
        $Province_model = new Province();
        $Province_result = $Province_model->province();
        //得到银行列表
        $Bank_model = new ShopBank();
        $Bank_result = $Bank_model->bank();
        return $this->render('add', array('ShopContract_model' => $ShopContract_model, 'Province_result' => $Province_result, 'Bank_result' => $Bank_result));
    }

    /**
     * 简介：执行添加商家合同操作
     * @author  weitonghe@iyangpin.com
     * @return  Boolean
     */
    public function actionShowmsg()
    {
        $ShopContract_model = new ShopContract();
        $ShopManage_model = new ShopManage();
        $ShopContractMsg = [
            //基本信息
            'htnumber' => RequestHelper::post('htnumber'),                          //合同号
            'shop_contract_name' => RequestHelper::post('shop_contract_name'),      //注册名称
            'registered_address' => RequestHelper::post('registered_address'),      //注册地址
            'registered_id' => RequestHelper::post('registered_id'),                //注册登记号
            'registered_capital' => RequestHelper::post('registered_capital'),      //注册资本
            'legal_representative' => RequestHelper::post('legal_representative'),  //法定代表人
            'email' => RequestHelper::post('email'),                                //电子邮箱
            'document_type' => RequestHelper::post('document_type'),                //证件类型
            'document_number' => RequestHelper::post('document_number'),            //证件号
            'contacts' => RequestHelper::post('contacts'),                          //联系人
            'contacts_umber' => RequestHelper::post('contacts_umber'),              //联系电话
            'company_nature' => implode(',', RequestHelper::post('company_nature')),//公司性质
            'company_nature_other' => RequestHelper::post('company_nature_other'),  //公司性质其他信息
            //服务信息
            'common_contacts' => RequestHelper::post('common_contacts'),            //同店面联系人    0、是   1、否
            'common_contacts_job' => RequestHelper::post('common_contacts_job'),    //职务
            'common_contacts_name' => RequestHelper::post('common_contacts_name'),  //联系人
            'common_contacts_phone' => RequestHelper::post('common_contacts_phone'),//电话
            'business_hours' => RequestHelper::post('business_hours_start') . ',' . RequestHelper::post('business_hours_end'),//营业时间   上午,下午
            'area' => RequestHelper::post('area'),                                  //面积
            'community_name' => RequestHelper::post('community_name'),              //所在社区名称
            'monthly_turnover' => RequestHelper::post('monthly_turnover'),          //月均营业额
            //清算信息
            'account_type' => RequestHelper::post('account_type'),                  //账户类型  0、银行账号    1、支付宝账号
            'alipay_name' => RequestHelper::post('alipay_name'),                    //支付宝账号
            'bank_id' => RequestHelper::post('bank_id'),                            //开户银行
            'bank_province' => RequestHelper::post('bank_province'),                //所在省份
            'bank_city' => RequestHelper::post('bank_city'),                        //所在城市
            //'bank_branch_id'     => RequestHelper::post('bank_branch'),           //所在支行的ID保存到表里的bank_branch_id(支行ID)里
            'bank_branch' => RequestHelper::post('bank_branch'),                    //银行支行
            'bank_number' => RequestHelper::post('bank_number'),                    //银行卡号
            'bankcard_username' => RequestHelper::post('bankcard_username'),        //开户名称
            //结算信息
            'settlement_cycle' => RequestHelper::post('settlement_cycle'),          //结算周期
            'service_charge' => RequestHelper::post('service_charge'),              //服务费用方式   0、固定服务费    1、服务佣金
            'fixed_service_charge' => RequestHelper::post('fixed_service_charge'),  //固定服务费
            'service_commission' => RequestHelper::post('service_commission'),      //服务佣金
            //其他信息
            'start_time' => RequestHelper::post('start_time'),                      //起止时间
            'end_time' => RequestHelper::post('end_time'),
            'counterman' => RequestHelper::post('counterman'),                      //业务员
            //备注信息
            'remark' => RequestHelper::post('remark'),                              //备注
            'create_time' => date('Y-m-d H:i:s', time())
        ];
        //判断月均营业额是否为空值
        if (empty($ShopContractMsg['monthly_turnover'])) {
            $ShopContractMsg['monthly_turnover'] = 0;
        }
        //判断职务是否为空值
        if (empty($ShopContractMsg['common_contacts_job'])) {
            $ShopContractMsg['common_contacts_job'] = ' ';
        }
        //判断面积是否为空值
        if (empty($ShopContractMsg['area'])) {
            $ShopContractMsg['area'] = ' ';
        }
        //判断邮箱是否为空值
        if (empty($ShopContractMsg['email'])) {
            $ShopContractMsg['email'] = ' ';
        }
        //判断注册资本是否为空值
        if (empty($ShopContractMsg['registered_capital'])) {
            $ShopContractMsg['registered_capital'] = ' ';
        }
        //合同上传图片
        $fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('file_name');
        if ($rs_data) {
            $ShopContractMsg['image'] = '/' . $rs_data['group_name'] . '/' . $rs_data['filename'];
        }
        //营业执照上传图片
        //$fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('business_licence_image');
        if ($rs_data) {
            $ShopContractMsg['business_licence_image'] = '/' . $rs_data['group_name'] . '/' . $rs_data['filename'];
        }
        //银行卡上传图片
        //$fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('bank_number_image');
        if ($rs_data) {
            $ShopContractMsg['bank_number_image'] = '/' . $rs_data['group_name'] . '/' . $rs_data['filename'];
        }
        //身份证上传图片
        //$fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('IDcard_image');
        if ($rs_data) {
            $ShopContractMsg['IDcard_image'] = '/' . $rs_data['group_name'] . '/' . $rs_data['filename'];
        }
        $ShopContractMsg['status'] = 3;                       //合同状态
        //营业时间
        if (!empty($ShopContractMsg['business_hours'])) {

        }
        //帐户类型的选择
        if ($ShopContractMsg['account_type'] == '0') {
            $ShopContractMsg['alipay_name'] = '';
            //根据bank_id在shop_bank表里找到bank_name加入bank_name(开户银行名称)字段
            $ShopBank_model = new ShopBank();
            $where = "id='" . $ShopContractMsg['bank_id'] . "'";
            $result = $ShopBank_model->getOneBank($where);
            $ShopContractMsg['bank_name'] = $result['name'];
            //根据bank_id在shop_bank表里 找到bank_name加入bank_name(开户银行名称)字段
            //$ShopBcBank_model = new ShopBcBank();
            //$where = "id='".$ShopContractMsg['bank_branch_id']."'";
            //$result=$ShopBcBank_model->getOneBcBank($where);
            //$ShopContractMsg['bank_branch']=$result['name'];
        } else {
            $ShopContractMsg['bank_id'] = '0';
            $ShopContractMsg['bank_province'] = '0';
            $ShopContractMsg['bank_city'] = '0';
            $ShopContractMsg['bank_branch'] = '0';
            $ShopContractMsg['bank_number'] = '0';
            $ShopContractMsg['bankcard_username'] = '0';
        }
        //服务费用方式的选择
        if ($ShopContractMsg['service_charge'] == '1') {
            $ShopContractMsg['service_commission'] = $ShopContractMsg['service_commission']*0.01;//服务佣金
        }
        $result = $ShopContract_model->insertOneData($ShopContractMsg); //$result为shop_contract(合同基本信息表的主键ID)
        //经营信息表
        $ShopManageMsg = [
            'contract_id' => $result,
            'business_name' => RequestHelper::post('store_registration_name'), //店面注册名称
            'business_address' => RequestHelper::post('business_address'),     //经营地址
            'create_time' => date('Y-m-d H:i:s', time()),                      //备注信息
            'htnumber' => $ShopContractMsg['htnumber']                         //合同号
        ];
        //经营范围;
        $business_scope = RequestHelper::post('business_scope');
        if (!empty($business_scope)) {
            $ShopManageMsg['business_scope'] = implode(',', $business_scope);
        }
        $ShopManage_model_result = $ShopManage_model->insertOneData($ShopManageMsg);  //将经营信息插入到经营信息表里
        if ($ShopManage_model_result) {
            //$this->_addOa($ShopContractMsg, $ShopManageMsg);
            return $this->success('商家合同添加操作成功！', 'index');
        } else {
            return $this->error('商家合同添加操作失败！', 'add');
        }
    }

    /**
     * 简介：商家合同修改页面
     * @author  weitonghe@iyangpin.com
     * @return  array
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');//合同ID
        if (empty($id)) {
            return $this->error('未知错误 ，请重试!', 'index');
        }
        $ShopContract_model = new ShopContract();       //合同表
        $cond['id'] = $id;
        $ShopContract_model_result = $ShopContract_model->selOneInfo($cond);
        if (empty($ShopContract_model_result)) {
            return $this->error('未知错误 ，请重试!', 'index');
        }
        if (isset($ShopContract_model_result['status']) && $ShopContract_model_result['status']!=3) {
            return $this->error('该合同不可以修改!', 'index');
        }
        $init_array = [
            'document_type_data' => $this->document_type_data,       //证件类型
            'company_nature_data' => $this->company_nature_data,     //公司性质
            'account_type_data' => $this->account_type_data,         //账户类型
            'settlement_cycle_data' => $this->settlement_cycle_data, //结算周期
            'business_scope_data' => $this->business_scope_data,     //经营范围
        ];
        if (!empty($ShopContract_model_result)) {
            $ShopContract_model_result['business_hours'] = explode(',', $ShopContract_model_result['business_hours']);   //营业时间
            $ShopContract_model_result['start_time']     = substr($ShopContract_model_result['start_time'], 0, 10);//合同开始时间
            $ShopContract_model_result['end_time']       = substr($ShopContract_model_result['end_time'], 0, 10);  //合同结束时间
        }
        unset($cond['id']);
        $ShopManage_model = new ShopManage();         //经营信息表
        $ShopManage_model_result = [];
        if (!empty($ShopContract_model_result) && !empty($ShopContract_model_result['id'])) {
            $cond['contract_id'] = $ShopContract_model_result['id'];
            $field = 'business_name,business_scope,business_address';   //经营名称 经营范围(1,2,3,4) 经营地址
            $ShopManage_model_result = $ShopManage_model->getInfo($cond, true, $field);
            $ShopManage_model_result['business_scope'] = explode(',', $ShopManage_model_result['business_scope']);     //经营范围
            unset($cond['contract_id']);
        }

        //省份列表
        $Province_model = new Province();
        $Province_result = $Province_model->province();
        //省份对应的城市列表
        $City_model = new City();
        $Province_City_result = $City_model->city($ShopContract_model_result['bank_province']);

        //银行列表
        $Bank_model = new ShopBank();
        $Bank_result = $Bank_model->bank();

        //业务员
        $Business_model = new Business();

        $Business_model_result = [];
        if (!empty($ShopContract_model_result)) {
            $cond['id'] = $ShopContract_model_result['counterman'];
            $field = 'id,name';
            $Business_model_result = $Business_model->getInfo($cond, true, $field);
        }
        $data_info = [
            'list' => $ShopContract_model_result,
            'shop' => $ShopManage_model_result,
            'province_list' => $Province_result,
            'Province_City_list' => $Province_City_result,
            'bank_list' => $Bank_result,
            'business' => $Business_model_result,
            'init_array' => $init_array
        ];
        //var_dump($ShopContract_model_result);exit;
        return $this->render('edit', $data_info);
    }

    /**
     * 简介：商家合同修改操作
     * @author  weitonghe@iyangpin.com
     * @return  array
     */
    public function actionDoedit()
    {
        $id = RequestHelper::post('id');//合同ID
        if (empty($id)) {
            return $this->error('未知错误 ，请重试!', 'index');
        }
        $ShopContract_model = new ShopContract();
        $ShopManage_model = new ShopManage();
        $ShopContractMsg = [
            //基本信息
            'htnumber' => RequestHelper::post('htnumber'),                          //合同号
            'shop_contract_name' => RequestHelper::post('shop_contract_name'),      //注册名称
            'registered_address' => RequestHelper::post('registered_address'),      //注册地址
            'registered_id' => RequestHelper::post('registered_id'),                //注册登记号
            'registered_capital' => RequestHelper::post('registered_capital'),      //注册资本
            'legal_representative' => RequestHelper::post('legal_representative'),  //法定代表人
            'email' => RequestHelper::post('email'),                                //电子邮箱
            'document_type' => RequestHelper::post('document_type'),                //证件类型
            'document_number' => RequestHelper::post('document_number'),            //证件号
            'contacts' => RequestHelper::post('contacts'),                          //联系人
            'contacts_umber' => RequestHelper::post('contacts_umber'),              //联系电话
            //'company_nature' => implode(',', RequestHelper::post('company_nature')),//公司性质
            'company_nature' => RequestHelper::post('company_nature'),//公司性质
            'company_nature_other' => RequestHelper::post('company_nature_other'),  //公司性质其他信息
            //服务信息
            'common_contacts' => RequestHelper::post('common_contacts'),            //同店面联系人    0、是   1、否
            'common_contacts_job' => RequestHelper::post('common_contacts_job'),    //职务
            'common_contacts_name' => RequestHelper::post('common_contacts_name'),  //联系人
            'common_contacts_phone' => RequestHelper::post('common_contacts_phone'),//电话
            'business_hours' => RequestHelper::post('business_hours_start') . ',' . RequestHelper::post('business_hours_end'),//营业时间   上午,下午
            'area' => RequestHelper::post('area'),                                  //面积
            'community_name' => RequestHelper::post('community_name'),              //所在社区名称
            'monthly_turnover' => RequestHelper::post('monthly_turnover'),          //月均营业额
            //清算信息
            'account_type' => RequestHelper::post('account_type'),                  //账户类型  0、银行账号    1、支付宝账号
            'alipay_name' => RequestHelper::post('alipay_name'),                    //支付宝账号
            'bank_id' => RequestHelper::post('bank'),                               //开户银行
            'bank_province' => RequestHelper::post('bank_province'),                //所在省份
            'bank_city' => RequestHelper::post('bank_city'),                        //所在城市
            //'bank_branch_id'     => RequestHelper::post('bank_branch'),           //所在支行的ID保存到表里的bank_branch_id(支行ID)里
            'bank_branch' => RequestHelper::post('bank_branch'),                    //银行支行
            'bank_number' => RequestHelper::post('bank_number'),                    //银行卡号
            'bankcard_username' => RequestHelper::post('bankcard_username'),        //开户名称
            //结算信息
            'settlement_cycle' => RequestHelper::post('settlement_cycle'),          //结算周期
            'service_charge' => RequestHelper::post('service_charge'),              //服务费用方式   0、固定服务费    1、服务佣金
            //'fixed_service_charge' => RequestHelper::post('fixed_service_charge'),  //固定服务费
            //'service_commission' => RequestHelper::post('service_commission'),      //服务佣金
            //其他信息
            'start_time' => RequestHelper::post('start_time'),                      //起止时间
            'end_time' => RequestHelper::post('end_time'),
            'counterman' => RequestHelper::post('business_id'),                      //业务员
            //备注信息
            'remark' => RequestHelper::post('remark'),                              //备注
            'update_time' => date('Y-m-d H:i:s', time())
        ];
        //合同上传图片
        $fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('HeTong');
        if ($rs_data) {
            $ShopContractMsg['image'] = '/' . $rs_data['group_name'] . '/' . $rs_data['filename'];
        }
        //营业执照上传图片
        //$fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('YingYeZhiZhao');
        if ($rs_data) {
            $ShopContractMsg['business_licence_image'] = '/' . $rs_data['group_name'] . '/' . $rs_data['filename'];
        }
        //银行卡上传图片
        //$fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('YinHangKa');
        if ($rs_data) {
            $ShopContractMsg['bank_number_image'] = '/' . $rs_data['group_name'] . '/' . $rs_data['filename'];
        }
        //身份证上传图片
        //$fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('ShenFenZheng');
        if ($rs_data) {
            $ShopContractMsg['IDcard_image'] = '/' . $rs_data['group_name'] . '/' . $rs_data['filename'];
        }
        $ShopContractMsg['status'] = 3;                       //合同状态
        //帐户类型的选择
        if ($ShopContractMsg['account_type'] == '0') {
            $ShopContractMsg['alipay_name'] = '';
            //根据bank_id在shop_bank表里找到bank_name加入bank_name(开户银行名称)字段
            $ShopBank_model = new ShopBank();
            $where = "id='" . $ShopContractMsg['bank_id'] . "'";
            $result = $ShopBank_model->getOneBank($where);
            $ShopContractMsg['bank_name'] = $result['name'];
            //根据bank_id在shop_bank表里 找到bank_name加入bank_name(开户银行名称)字段
            //$ShopBcBank_model = new ShopBcBank();
            //$where = "id='".$ShopContractMsg['bank_branch_id']."'";
            //$result=$ShopBcBank_model->getOneBcBank($where);
            //$ShopContractMsg['bank_branch']=$result['name'];
        } else {
            $ShopContractMsg['bank_id'] = '0';
            $ShopContractMsg['bank_province'] = '0';
            $ShopContractMsg['bank_city'] = '0';
            $ShopContractMsg['bank_branch'] = '0';
            $ShopContractMsg['bank_number'] = '0';
            $ShopContractMsg['bankcard_username'] = '0';
        }
        //服务费用方式的选择
        if ($ShopContractMsg['service_charge'] == '0') {
            $ShopContractMsg['fixed_service_charge'] = RequestHelper::post('FuWuFeiYong');
        } else {
            $ShopContractMsg['service_commission'] = RequestHelper::post('FuWuFeiYong')*0.01;
        }

        $ShopContractWhere['id'] = $id;
        $ShopContract_model->updateInfo($ShopContractMsg, $ShopContractWhere); //update合同基本信息表
        //经营信息表
        $ShopManageMsg = [
            //'contract_id' => $result,
            'business_name' => RequestHelper::post('business_name'),           //店面注册名称
            'business_address' => RequestHelper::post('business_address'),     //经营地址
            'htnumber' => $ShopContractMsg['htnumber'],                        //合同号
            'update_time' => date('Y-m-d H:i:s', time())
        ];
        //经营范围;
        $business_scope = RequestHelper::post('business_scope');
        if (!empty($business_scope)) {
            $ShopManageMsg['business_scope'] = implode(',', $business_scope);
        }
        $ShopManageWhere['contract_id'] = $id;
        $ShopManage_model_result = $ShopManage_model->updateInfo($ShopManageMsg, $ShopManageWhere);  //update经营信息表
        if ($ShopManage_model_result) {
            //$this->_addOa($ShopContractMsg, $ShopManageMsg);
            return $this->success('操作成功！', 'index');
        } else {
            return $this->error('操作失败,请重试！', 'edit?id='.$id);
        }
    }

    /**
     * 简介：商家合同详情
     * @author  weitonghe@iyangpin.com
     * @return  array
     */
    public function actionDetail()
    {
        $id = RequestHelper::get('id');//注册名称
        if (empty($id)) {
            return $this->error('未知错误 ，请重试!', 'index');
        }
        $ShopContract_model = new ShopContract();       //合同表
        $cond['id'] = $id;
        $ShopContract_model_result = $ShopContract_model->selOneInfo($cond);
        if (empty($ShopContract_model_result)) {
            return $this->error('未知错误 ，请重试!', 'index');
        }
        if (!empty($ShopContract_model_result)) {
            $ShopContract_model_result['company_nature'] = explode(',', $ShopContract_model_result['company_nature']);//公司性质
            $ShopContract_model_result['business_hours'] = explode(',', $ShopContract_model_result['business_hours']);//营业时间
        }
        unset($cond['id']);
        $ShopManage_model = new ShopManage();         //经营信息表
        $ShopManage_model_result = [];
        if (!empty($ShopContract_model_result) && !empty($ShopContract_model_result['id'])) {
            $cond['contract_id'] = $ShopContract_model_result['id'];
            $field = 'business_name,business_scope,business_address';   //经营名称 经营范围(1,2,3,4) 经营地址
            $ShopManage_model_result = $ShopManage_model->getInfo($cond, true, $field);
            $ShopManage_model_result['business_scope'] = explode(',', $ShopManage_model_result['business_scope']);     //经营范围
            unset($cond['contract_id']);
        }
        $Province_model = new Province();           //省
        $Province_model_result = [];
        if (!empty($ShopContract_model_result)) {
            $cond['id'] = $ShopContract_model_result['bank_province'];
            $field = 'name';
            $Province_model_result = $Province_model->getInfo($cond, true, $field);
        }
        $City_model = new City();               //市
        $City_model_result = [];
        if (!empty($ShopContract_model_result)) {
            $cond['id'] = $ShopContract_model_result['bank_city'];
            $field = 'name';
            $City_model_result = $City_model->getInfo($cond, true, $field);
        }
        $Business_model = new Business();           //业务员
        $Business_model_result = [];
        if (!empty($ShopContract_model_result)) {
            $cond['id'] = $ShopContract_model_result['counterman'];
            $field = 'id,name';
            $Business_model_result = $Business_model->getInfo($cond, true, $field);
        }
        $data_info = [
            'list' => $ShopContract_model_result,
            'shop' => $ShopManage_model_result,
            'province' => $Province_model_result,
            'city' => $City_model_result,
            'business' => $Business_model_result
        ];
        return $this->render('detail', $data_info);
    }

    /**
     * 简介：商家合同图片下载
     * @author  weitonghe@iyangpin.com
     * @return  null
     */
    public function actionDownloadimg()
    {
        $img_src = RequestHelper::get('img_src');
        if ($img_src == '/images/05_mid.jpg') {
            return $this->error('图片下载失败 ，请重试!', 'index');
        } else {
            //下载
            ob_start();
            $filename = $img_src;
            $date = date("Ymd-H:i:m");
            header("Content-type:  application/octet-stream");
            header("Accept-Ranges:  bytes ");
            header("Content-Disposition:  attachment;  filename= {$date}.jpg");
            $size = readfile($filename);
            header("Accept-Length: " . $size);
            return null;
        }
    }

    /**
     * 简介：通过 ajax 获得 省份 对应的 city
     * @author  weitonghe@iyangpin.com
     * @return  void
     */
    public function actionGetcityajax()
    {
        $City_model = new City();
        $Province_id = RequestHelper::post('province_id');
        $City_result = $City_model->city($Province_id);
        if (!empty($City_result)) {
            echo json_encode($City_result);
            return;
        } else {
            return;
        }
    }

    /**
     * 简介：通过 ajax 获得 银行id和城市ID  对应的 支行信息
     * @author  weitonghe@iyangpin.com
     * @return  void
     */
    public function actionGetbcbankajax()
    {
        $ShopBcBank_model = new ShopBcBank();
        $msg = RequestHelper::post('msg');//0 银行代码 1
        $where = '';
        if (!empty($msg)) {
            //$where="bank_id='".$msg[0]."'";
            $where = "bank_id=" . $msg[0] . " and city_id=" . $msg[1];
        }
        $BcBank_result = $ShopBcBank_model->getBcBank($where);
        if (!empty($BcBank_result)) {
            echo json_encode($BcBank_result);
            return;
        } else {
            return;
        }
    }

    /**
     * 简介：通过 ajax 获得 业务员ID 对应的 业务员姓名
     * @author  weitonghe@iyangpin.com
     * @return  void
     */
    public function actionGetcountermanidajax()
    {
        //得到业务员列表
        $Business_model = new Business();
        $msg = RequestHelper::post('msg');//业务员Id
        $cond['id'] = $msg;
        $field = 'name';
        $Business_result = $Business_model->getInfo($cond, true, $field);
        //$Business_result=$Business_model->find()->select('name')->where(['id' => $msg])->asArray()->one();
        if (!empty($Business_result)) {
            echo json_encode($Business_result);
            return;
        } else {
            return 0;
        }
    }

    /**
     * 简介：通过 ajax 获得 合同号
     * @author  weitonghe@iyangpin.com
     * @return  int
     */
    public function actionGethtnumberajax()
    {
        $ShopContract_model = new ShopContract();
        $htNumber = RequestHelper::get('htnumber');
        $id  = RequestHelper::get('id');
        if (!empty($htNumber)) {
            $and_where = [];
            if (!empty($id)) {
                $and_where = ['<>', 'id', $id];
            }
            $where['htnumber'] = $htNumber;
            $result = $ShopContract_model->selOneInfo($where, $and_where, 'htnumber');
            if ($result) {
                return 0;
            } else {
                return 1;
            }
        }
        return 1;
    }

    /**
     * 简介：通过 ajax 获得 注册名称
     * @author  weitonghe@iyangpin.com
     * @return  int
     */
    public function actionGetzcmcajax()
    {
        $ShopContract_model = new ShopContract();
        $shop_contract_name = RequestHelper::get('shop_contract_name');
        $id  = RequestHelper::get('id');
        if (!empty($shop_contract_name)) {
            $and_where = [];
            if (!empty($id)) {
                $and_where = ['<>', 'id', $id];
                //$and_where = "id != '".$id."'";
            }
            $where['shop_contract_name'] = $shop_contract_name;
            $result = $ShopContract_model->selOneInfo($where, $and_where, 'shop_contract_name');
            if ($result) {
                return 0;
            } else {
                return 1;
            }
        }
        return 1;
    }

    /**
     * 简介：OA添加
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionAddoa()
    {
        $connection = \Yii::$app->db_oa;

        $shopContractModel = new ShopContract();
        $where['id'] = RequestHelper::get('id');
        if (empty($where['id'])) {
            return $this->error('操作错误！', 'index');
        }
        //$where = ['<', 'id', 43];
        $data_info = $shopContractModel->getList($where);
        if (empty($data_info) || !isset($data_info[0]) || empty($data_info[0]['htnumber'])) {
            return $this->error('请先完善合同信息！', 'index');
        }
        $shopContractModel->updateInfo(['status' => 4], $where);//更新合同的状态
        $shop_manage_model = new ShopManage();
        $approve_code = 'BJ1055';//合同审核人
        foreach ($data_info as $k => $v) {
            $ShopManageMsg = $shop_manage_model->getList(['contract_id' => $v['id']]);
            $ShopManageMsg = $ShopManageMsg[0];
            $ret = $connection->createCommand('select max(RUN_ID) as run_id from flow_run')->queryAll();
            $run_id = $ret[0]['run_id'] + 1;
            $new_time = date('Y-m-d H:i:s');
            $info = [];
            //var_dump($v['shop_contract_name']);
            $info['run_id'] = $run_id; //'107' 自增
            $info['run_name'] = CommonHelper::utf8ToGbk("商家合同审批(" . $new_time . ")");  //商家合同审批(2015-08-10 09:11:57) 按照格式填写
            $info['begin_user'] = $approve_code;
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
            $data['BEGIN_USER'] = $approve_code;
            $data['BEGIN_DEPT'] = 43;              //流程发起人部门ID
            $data['BEGIN_TIME'] = $new_time;   //流程实例创建时间
            $data['DEL_FLAG'] = 0; //删除标记(0-未删除,1-已删除)删除后流程实例可在工作销毁中确实删除或还原
            $data['ARCHIVE'] = 0;       //是否归档(0-否,1-是)
            $data['work_level'] = 0;    //工作等级 0-普通 1-重要 2-紧急

            //flow_run_prcs
            $data2 = [];
            $data2['RUN_ID'] = $run_id;
            $data2['PRCS_ID'] = '1';  //流程实例步骤ID
            $data2['USER_ID'] = $approve_code;
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
                return $this->success('操作成功！', 'index');
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error('操作失败！', 'index');
            }
        }
    }
}

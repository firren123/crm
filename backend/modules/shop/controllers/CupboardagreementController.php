<?php
/**
 * 简介1
 * 商家订单类
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/24 上午9:52
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\shop\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Admin;
use backend\models\i500m\OrderLog;
use backend\models\i500m\Shop;
use backend\models\shop\CupboardAgreement;
use backend\models\shop\CupboardAgreementLog;
use backend\models\shop\ShopCupboard;
use yii;
use yii\data\Pagination;
use common\helpers\RequestHelper;

class CupboardagreementController extends BaseController
{
    public $enableCsrfValidation = false;
    public static $status_data = array(
        -2=>'全部',
        0 => '待审核',
        1 => '审核通过',
    );
    public static $jiesuan_status = array(
        1 => '按天',
        2 => '按卖出数量',
    );
    public static $status_data_log = array(
        0 => '未操作',
        1 => '审核通过',
        2 => '审核不通过',
        3 => '取消',
        4 => '删除',
    );


    /**
     * 简介：
     * 商家协议列表`
     * @author  zhaochengqiang@iyangpin.com。
     * @return  string
     */
    public function actionIndex()
    {
        $shop_cup = new ShopCupboard();
        $shop_m = new Shop();
        $p = RequestHelper::get('page', '1', 'intval');                //当前页
        $sn = RequestHelper::get('sn','');        //订单号
        $sn = htmlspecialchars($sn,ENT_QUOTES,"UTF-8");
        $status = RequestHelper::get('status',-2);
        $shop_name = RequestHelper::get('shop_name','');
        $where =' 1 ';

        if($status!=-2){
            $where .= " and status =" .$status;
        }

        if($shop_name){
            $shop_id = $shop_m->getList(array('shop_name' => $shop_name), "id");
            if($shop_id){
                $arr = [];
                foreach($shop_id as $k =>$v){
                    $arr[] = $v['id'];
                }
                $ids = implode(',',$arr);
                $where .= " and shop_id in($ids)";
            }else{
                return $this->error('商家名不存在','/shop/cupboardagreement/index');
            }

        }

        if ($sn && $sn != '协议编号') {
            $where .= " and sn='" . $sn . "'";
        }
        $model = new CupboardAgreement();
        $result_arr = array();
        $list = $model->getPageList($where, '*', 'id desc', $p, $this->size);
        foreach($list as $k => $v) {
            $cupboard_info = $shop_cup->getInfo(array('id' => $v['cupboard_id']), true, 'title,number');
            $list[$k]['title'] = $cupboard_info['title']."(".$cupboard_info['number'].")";
            $shop_info = $shop_m->getInfo(array('id' => $v['shop_id']), true, 'shop_name');
            $list[$k]['shop_name'] = $shop_info['shop_name'];
        }

        $count = $model->getCount($where);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render('index', [
            'pages' => $pages,
            'list' => $list,
            'sn'=>$sn,
            'status'=>$status,
            'shop_name'=>$shop_name,
            'status_data' => CupboardagreementController::$status_data,
            'jiesuan_data' => CupboardagreementController::$jiesuan_status,
            'result_arr' => $result_arr,
        ]);
    }

    /**
     * 简介：详情页面
     * @author  zhaochengqiang@iyangpin.com。
     * @return string
     */
    public function actionDetail()
    {
        $id = RequestHelper::get("id");
        $list=$log_list=[];
        if($id){
            $shop_cup = new ShopCupboard();
            $shop_m = new Shop();
            $cup_m = new CupboardAgreement();
            $list = $cup_m->getInfo(array('id' => $id),true);
            if(is_array($list) && !empty($list)){
                $cupboard_info = $shop_cup->getInfo(array('id' => $list['cupboard_id']), true, 'title,number');
                $list['title'] = $cupboard_info['title']."(".$cupboard_info['number'].")";
                $shop_info = $shop_m->getInfo(array('id' => $list['shop_id']), true, 'shop_name,contact_name,mobile,phone,address');
                $list['shop_name'] = $shop_info['shop_name'];
                $list['contact_name'] = $shop_info['contact_name'];
                $list['mobile'] = $shop_info['mobile'];
                $list['phone'] = $shop_info['phone'];
                $list['address'] = $shop_info['address'];
            }

            //读取管理员操作日志
            $order_log_m = new CupboardAgreementLog();
            $log_list = $order_log_m->getList(['sn'=>$list['sn']],"*",'id desc');
            $admin_n = new Admin();
            foreach($log_list as $k=>$v){
                $admin_id = $admin_n->getInfo(['id'=>$v['admin_id']],true,'name');
                $log_list[$k]['name'] = $admin_id['name'];
            }

        }
        return $this->render('detail', [
            'list' => $list,
            'log_list'=>$log_list,
            'status_data' => CupboardagreementController::$status_data,
            'jiesuan_data' => CupboardagreementController::$jiesuan_status,
            'status_data_log' => CupboardagreementController::$status_data_log,
        ]);
    }

    /**
     * 详情页面提交处理页面
     * @author  zhaochengqiang@iyangpin.com。
     */
    public function actionEdits()
    {
        $id = RequestHelper::post('id');
        $sn = RequestHelper::post('sn');
        $status = RequestHelper::post('status', '', 'intval');
        $remark = RequestHelper::post('info');
        $log_info = \Yii::$app->user->identity->username;
        if (!$id || !$sn ) {
            return $this->error('非法操作');
        }
        if ('' != $status) {
            $log['status'] = $status;
            if($status == 1) {
                $log_info .= ':操作—通过了审核';
            } elseif ($status == 2) {
                $log_info .= ':操作—未通过审核';
                $status = 0;
            }
        }
        if (empty($remark)) {
            return $this->error('状态或备注不能为空');
        }
        $cup_model = new CupboardAgreement();
        $ret = $cup_model->updateInfo($status, $id);
        if($ret){
            $log['sn'] = $sn;
            $log['info'] = $log_info."(".$remark.")";
            $cup_log = new CupboardAgreementLog();
            $cup_log->recordLog($log);
            return $this->success('操作成功', '/shop/cupboardagreement/detail?id=' . $id);
        }
        return $this->success('操作失败', '/shop/cupboardagreement/detail?id=' . $id);
    }

    /**
     * 添加操作
     * @author  zhaochengqiang@iyangpin.com。
     */
    public function actionAdd()
    {
        $model = new CupboardAgreement();
        if ($_POST) {
            $sn = RequestHelper::post('sn');
            //$shop_name = RequestHelper::post('shop_name');
            $shop_id = RequestHelper::post('shop_id');
            //$title = RequestHelper::post('title');
            $cupboard_id = RequestHelper::post('cupboardid');
            $type = RequestHelper::post('type');

            $cupboard_amount = RequestHelper::post('cupboard_amount');
            $cupboard_period = RequestHelper::post('cupboard_period');
            $description = RequestHelper::post('description');

            $data = array();
            $data['sn']       = $sn;
            $data['shop_id']  = $shop_id;
            $data['cupboard_id'] = $cupboard_id;
            $data['type']        = $type;
            $data['cupboard_amount'] = $cupboard_amount;
            $data['cupboard_period'] = $cupboard_period;
            $data['description']= $description;
            $data['create_time']= date("Y-m-d H:i:s");
            if (!empty($data)) {
                $result = $model->insertInfo($data);
                if ($result == true) {
                    return $this->success('添加成功', '/shop/cupboardagreement/index');
                }
            }
        }

        return $this->render('add', [
            'sn' => time()
        ]);
    }


    /**
     * 验证商家名称并获取展位信息
     * @author  zhaochengqinag@iyangpin.com。
     */
    public function actionGetshopinfoByid()
    {
        $m_shop = new Shop();
        $shop_name = RequestHelper::get('shop_name');
        $list=[];
        if($shop_name) {
            $arr['shop_name'] = $shop_name;
            $shopinfo = $m_shop->getListinfo($arr);
            $list = array();
            if (!empty($shopinfo)) {
                $shop_cup = new ShopCupboard();
                $map['shop_id']=$shopinfo['id'];
                $map['status']=1;
                $cup_info = $shop_cup->getlist($map);
                foreach ($cup_info as $v) {
                    $list[] = $v;
                }
            }
        }

        return json_encode($list);
    }


    /**
     * 协议编辑页面
     * @author  zhaochengqiang@iyangpin.com。
     */
    public function actionEditone()
    {
        $sn = RequestHelper::post('sn');
        if($sn){
            //post update
            $id = RequestHelper::post('id');
            $shop_id = RequestHelper::post('shop_id');
            $cupboard_id = RequestHelper::post('cupboardid');
            $type = RequestHelper::post('type');
            $cupboard_amount = RequestHelper::post('cupboard_amount');
            $cupboard_period = RequestHelper::post('cupboard_period');
            $description = RequestHelper::post('description');

            $data = array();
            $data['sn']       = $sn;
            $data['shop_id']  = $shop_id;
            $data['cupboard_id'] = $cupboard_id;
            $data['type']        = $type;
            $data['cupboard_amount'] = $cupboard_amount;
            $data['cupboard_period'] = $cupboard_period;
            $data['description']= $description;
            $data['update_time']= date("Y-m-d H:i:s");
            if (!empty($data)) {
                $model = new CupboardAgreement();
                $where['id']=$id;
                $result = $model->updateAll($data,$where);
                if ($result == true) {
                    return $this->success('更新成功', '/shop/cupboardagreement/detail?id='.$id);
                }
            }

        }else{
            //get datainfo
            $id = RequestHelper::get('id', '0', 'intval');
            if($id){
                $shop_cup = new ShopCupboard();
                $shop_m = new Shop();
                $cup_m = new CupboardAgreement();
                $list = $cup_m->getInfo(array('id' => $id),true);
                if(is_array($list) && !empty($list)){
                    $cupboard_info = $shop_cup->getlist(array('shop_id' => $list['shop_id']));
                    $list['cupboard_info'] = $cupboard_info;
                    $shop_info = $shop_m->getInfo(array('id' => $list['shop_id']), true, 'shop_name');
                    $list['shop_name'] = $shop_info['shop_name'];
                }
            }
            return $this->render('edit', [
                'data' => $list
            ]);
        }
    }


}
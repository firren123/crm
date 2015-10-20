<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  ServiceController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 上午9:48
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\social\OpLog;
use backend\models\social\Service;
use backend\models\social\ServiceOrder;
use backend\models\social\ServiceOrderEvaluation;
use backend\models\social\ServiceSetting;
use backend\models\social\ServiceUnit;
use backend\models\social\UserBasicInfo;
use backend\models\social\UserPushId;
use common\helpers\CurlHelper;
use common\helpers\RequestHelper;
use backend\models\SSDB;
use yii\data\Pagination;

/**
 * Class ServiceController
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ServiceController extends BaseController
{
    public $size = 10;
    public $audit_status_data = [
        0 => '未审核',
        1 => '审核中',
        2 => '审核成功',
        3 => '审核失败'
    ];
    public $sex = [
        0 => '未知',
        1 => '男',
        2 => '女'
    ];
    public $unit_data = [
        1=>'元',
        2=>'元/次',
        3=>'元/小时'
    ];
    public $service_way_data = [
        1=>'上门服务',
        2=>'到店体验'
    ];
    public $user_auth_status_data = [
        1=>'认证成功',
        2=>'认证失败'
    ];
    public $order_status_data = [
        0=>'未确认',
        1=>'已经确认',
        2=>'已经取消',
        3=>'进行中',
        4=>'等待体验方确认',
        5=>'待评价'
    ];
    public $order_pay_status_data = [
        0=>'未支付',
        1=>'已支付',
        2=>'已退款',
        3=>'退款中',
    ];
    public $ssdb = null;
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function init()
    {
        parent::init();
        $obj_ssdb = new SSDB();
        $this->ssdb = $obj_ssdb->instance();
        if (isset($this->ssdb->result) && $this->ssdb->result == 0) {
            echo "ssdb对象初始化失败:" . $this->ssdb->msg;
            exit;
        }
    }
    /**
     * 简介：服务列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $model = new Service();
        $title = RequestHelper::get('title');
        $status = RequestHelper::get('status', 999, 'intval');
        $audit_status = RequestHelper::get('audit_status', 999, 'intval');
        $page = RequestHelper::get('page', 1, 'intval');
        $where = [];
        $where['is_deleted'] = 2;
        if ($title != '') {
            $where['title'] = $title;
        }
        if ($status != 999) {
            $where['status'] = $status;
        }
        if ($audit_status != 999) {
            $where['audit_status'] = $audit_status;
        }
        $count = $model->getCount($where);
        $list = $model->getPageList($where, "*", "audit_status asc,id desc", $page, $this->size);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render(
            'index',
            [
                'list' => $list,
                'pages' => $pages,
                'title' => $title,
                'unit_data' => $this->unit_data,
                'service_way_data' => $this->service_way_data,
                'audit_status' => $audit_status,
                'audit_status_data' => $this->audit_status_data
            ]
        );
    }

    /**
     * 简介：服务详情
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionDetail()
    {
        $model = new Service();
        $id = RequestHelper::get('id', 0, 'intval');
        $list = $model->getInfo(['id'=>$id]);
        if (!$list) {
            return $this->error('信息不存在');
        }
        return $this->render(
            'detail',
            [
                'unit_data' => $this->unit_data,
                'service_way_data' => $this->service_way_data,
                'list' => $list,
                'audit_status_data' => $this->audit_status_data
            ]
        );
    }
    /**
     * 简介：服务修改状态service-up-field
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionServiceUpField()
    {
        $id = RequestHelper::post('id', 0, 'intval');
        $audit_status = RequestHelper::post('audit_status', 999, 'intval');
        $status = RequestHelper::post('status', 999, 'intval');
        $del = RequestHelper::post('del');
        $remark = RequestHelper::post('remark');
        if ($remark == '') {
            return $this->error('备注不能为空');
        }
        if ($id == 0) {
            return $this->error('参数错误');
        }
        $model = new Service();

        $where = [];
        if ($audit_status != 999) {
            $where['audit_status'] = $audit_status;
            $log_info = '审核状态'.$this->audit_status_data[$audit_status];
        } elseif ($status != 999) {
            $where['status'] = $status;
            $log_info = '上下架状态'.$status==1?'上架':'下架';
        } elseif ($del != '') {
            $where['is_deleted'] = 1;
            $log_info = '删除状态';
        } else {
            return $this->error('参数错误');
        }
        $ret = $model->updateInfo($where, ['id' => $id]);
        if ($ret) {
            $log = new OpLog();
            $log->writeLog('服务修改id='.$id.'状态,'.$log_info.'|备注：'.$remark);
            return $this->success('操作成功', 'index');
        } else {
            return $this->error('操作失败');
        }

    }

    /**
     * 简介：服务设置列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionSetting()
    {
        $model = new ServiceSetting();
        $name = RequestHelper::get('name');
        $status = RequestHelper::get('status', 999, 'intval');
        $page = RequestHelper::get('page', 1, 'intval');
        $where = [];
        $where['is_deleted'] = 2;
        if ($name != '') {
            $where['name'] = $name;
        }
        if ($status != 999) {
            $where['status'] = $status;
        }

        $count = $model->getCount($where);
        $list = $model->getPageList($where, "*", "id desc", $page, $this->size);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render(
            'setting',
            [
                'list' => $list,
                'pages' => $pages,
                'name' => $name,
                'audit_status_data' => $this->audit_status_data
            ]
        );

    }

    /**
     * 简介：服务设置详情
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionSettingDetail()
    {
        $model = new ServiceSetting();
        $id = RequestHelper::get('id', 0, 'intval');
        $list = $model->getInfo(['id'=>$id]);
        if (!$list) {
            return $this->error('信息不存在');
        }
        return $this->render(
            'setting_detail',
            [
                'list' => $list,
                'sex' => $this->sex,
                'user_auth_status_data'=>$this->user_auth_status_data,
                'audit_status_data' => $this->audit_status_data
            ]
        );
    }

    /**
     * 简介：服务修改状态
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionServiceSettingUpField()
    {
        $id = RequestHelper::post('id', 0, 'intval');
        $audit_status = RequestHelper::post('audit_status', 999, 'intval');
        $status = RequestHelper::post('status', 999, 'intval');
        $del = RequestHelper::post('del');
        $remark = RequestHelper::post('remark');
        if ($remark == '') {
            return $this->error('备注不能为空');
        }
        if ($id == 0) {
            return $this->error('参数错误');
        }
        $model = new ServiceSetting();

        $where = [];
        $service_model = new Service();
        $where2 = [];
        if ($audit_status != 999) {
            $where['audit_status'] = $audit_status;
            $where2['user_auth_status'] = $audit_status==2?1:2;
            $log_info = $this->audit_status_data[$audit_status];
        } elseif ($status != 999) {
            $where['status'] = $status;
            $where2['servicer_info_status'] = $status==1?2:1;
            $log_info = $status==1?'禁用':'启用';
        } elseif ($del != '') {
            $where['is_deleted'] = 1;
            $where2['is_deleted'] = 1;
            $log_info = '删除';
        } else {
            return $this->error('参数错误');
        }
        $setting = $model->findOne($id);
        if ($setting) {
            $ret = $model->updateInfo($where, ['id' => $id]);
            if ($ret) {
                //修改服务
                $service_model->updateInfo($where2, ['uid'=>$setting->uid]);
                //添加日志
                $log = new OpLog();
                $log->writeLog('服务设置修改id='.$id.'状态,'.$log_info.'|备注：'.$remark);
                //百度推送
                $pushModel = new UserPushId();
                $pushInfo = $pushModel->getInfo(['uid'=>$setting->uid]);
                $custom_content = ['title'=>'服务已经审核','id'=>$id];
                CurlHelper::pushPost($pushInfo['push_id'], $log_info, $remark, $custom_content, 30);
                return $this->success('操作成功', 'setting');
            }
        }
        return $this->error('数据错误');
    }

    /**
     * 简介：服务单位表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionUnit()
    {
        $model = new ServiceUnit();
        $page = RequestHelper::get('page', 1, 'intval');
        $where = 1;

        $count = $model->getCount($where);
        $list = $model->getPageList($where, "*", "id desc", $page);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render(
            'unit',
            [
                'list' => $list,
                'pages' => $pages,
                'audit_status_data' => $this->audit_status_data
            ]
        );
    }
    /**
     * 简介：服务单位表添加
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionUnitAdd()
    {
        $model = new ServiceUnit();
        $post = RequestHelper::post('ServiceUnit');
        if ($post) {
            $model->attributes = $post;
            if ($model->save()) {
                $log = new OpLog();
                $log->writeLog('添加成功服务单位:id='.$model->id);
                return $this->success('添加成功', 'unit');
            } else {
                return $this->error('添加失败');
            }
        }
        return $this->render('unit_add', ['model' => $model]);
    }

    /**
     * 简介：服务单位表修改
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionUnitEdit()
    {
        $model = new ServiceUnit();
        $id = RequestHelper::get('id', 0, 'intval');
        $model = $model->findOne(['id' => $id]);
        $post = RequestHelper::post('ServiceUnit');
        if ($post) {
            $model->attributes = $post;
            if ($model->save()) {
                $log = new OpLog();
                $log->writeLog('修改服务单位,'.$model->id);
                return $this->success('修改成功', 'unit');
            } else {
                return $this->error('修改失败');
            }

        }
        return $this->render('unit_add', ['model' => $model]);
    }

    /**
     * 简介：服务单位表删除
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionUnitDel()
    {
        $model = new ServiceUnit();
        $id = RequestHelper::get('id', 0, 'intval');
        $info = $model->findOne(['id' => $id]);
        if ($info) {
            if ($model->deleteAll(['id'=>$id])) {
                $log = new OpLog();
                $log->writeLog('修改服务单位,'.$id);
                echo 1;
            } else {
                echo '修改删除失败';
            }

        }
    }

    /**
     * 简介：服务设置列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionOrder()
    {
        $model = new ServiceOrder();
        $mobile = RequestHelper::get('mobile');
        $pay_status = RequestHelper::get('pay_status', 999, 'intval');
        $status = RequestHelper::get('status', 999, 'intval');
        $service_mobile = RequestHelper::get('service_mobile');
        $page = RequestHelper::get('page', 1, 'intval');
        $where = [];
        if ($mobile != '') {
            $where['mobile'] = $mobile;
        }
        if ($service_mobile != '') {
            $where['service_mobile'] = $service_mobile;
        }

        if ($pay_status != 999) {
            $where['pay_status'] = $pay_status;
        }
        if ($status != 999) {
            $where['status'] = $status;
        }
        $and_where = '';
        if (!empty($start_time)) {
            $and_where .='create_time >'. $start_time;
        }
        if (!empty($end_time)) {
            $and_where .= 'create_time <'. $end_time;
        }
        if (empty($where)) {
            $where = 1;
        }
        $count = $model->getCount($where, $and_where);
        $list = $model->getPageList($where, "*", "id desc", $page, $this->size, $and_where);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render(
            'order',
            [
                'list' => $list,
                'pages' => $pages,
                'mobile' => $mobile,
                'status' => $status,
                'pay_status' => $pay_status,
                'service_mobile' => $service_mobile,
                'order_pay_status_data'=>$this->order_pay_status_data,
                'order_status_data'=>$this->order_status_data,
            ]
        );

    }

    /**
     * 简介：服务设置详情
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionOrderDetail()
    {
        $model = new ServiceOrder();
        $order_sn = RequestHelper::get('order_sn');
        $list = $model->getInfo(['order_sn'=>$order_sn]);
        if (!$list) {
            return $this->error('信息不存在');
        }
        $userInfoMpdel = new UserBasicInfo();
        $uidName = $userInfoMpdel->getInfo(['uid'=>$list['uid']]);
        $list['uid_name'] = $uidName['nickname'];
        $ServiceName = $userInfoMpdel->getInfo(['uid'=>$list['service_uid']]);
        $list['service_name'] = $ServiceName['nickname'];
        //订单评论
        $service_order_evaluation_model = new ServiceOrderEvaluation();
        $eva_list = $service_order_evaluation_model->getList(['order_sn'=>$order_sn]);
        return $this->render(
            'order_detail',
            [
                'list' => $list,
                'sex' => $this->sex,
                'eva_list'=>$eva_list,
                'order_pay_status_data'=>$this->order_pay_status_data,
                'order_status_data'=>$this->order_status_data
            ]
        );
    }

    /**
     * 简介：评论列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEvaList()
    {
        $page = RequestHelper::get('page', 1, 'intval');
        $mobile = RequestHelper::get('mobile');
        $where = [];
        if ($mobile) {
            $where['mobile'] = $mobile;
        }
        if (empty($where)) {
            $where = 1;
        }
        $evaModel = new ServiceOrderEvaluation();
        $count = $evaModel->getCount($where);
        $list = $evaModel->getPageList($where, "*", "id desc", $page, $this->size);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render(
            'eva_list',
            [
                'mobile'=>$mobile,
                'list'=>$list,
                'pages'=>$pages,
            ]
        );
    }

    /**
     * 简介：评论删除
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionEvaDel()
    {
        $model = new ServiceOrderEvaluation();
        $id = RequestHelper::get('id', 0, 'intval');
        $info = $model->findOne(['id' => $id]);
        if ($info) {
            if ($model->deleteAll(['id'=>$id])) {
                echo 1;
            } else {
                echo '删除失败';
            }

        }
    }

    /**
     * 简介：评论详情
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEvaDetail()
    {
        $id = RequestHelper::get('id');
        $where = [];
        if ($id) {
            $where['id'] = $id;
        }

        $evaModel = new ServiceOrderEvaluation();
        $info = $evaModel->getInfo($where);
        if ($info == false) {
            return $this->error('评论不存在');
        }
        return $this->render(
            'eva_detail',
            [
                'list'=>$info,
            ]
        );
    }
    /**
     * 简介：测试ssdb是否通
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionTest()
    {
        $obj_ssdb = new SSDB();
        $ssdb = $obj_ssdb->instance();
        if (isset($ssdb->result) && $ssdb->result == 0) {
            echo "ssdb对象初始化失败:" . $ssdb->msg;
            return;
        }

        $result = $ssdb->setx('key', 'value', 30);
        if ($result === false) {
            echo "ssdb set数据失败";
            return;
        }

        $value= $ssdb->get('key');
        //object(SSDB\Response)#22 (4)
        //{ ["cmd"]=> string(3) "get" ["code"]=> string(2) "ok" ["data"]=> string(5) "value" ["message"]=> NULL }
        if ($value->code == 'ok') {
            echo $value->data;
        }
        return;
    }

    /**
     * 简介：百度推送信息
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionTestPush()
    {
        $channel_id =RequestHelper::get('channel_id')?RequestHelper::get('channel_id'):'3862737169522734802';
        $log_info =RequestHelper::get('title')?RequestHelper::get('title'):'我是消息标题';
        $remark = RequestHelper::get('content')?RequestHelper::get('content'):'我是消息测试';
        $channel_type = RequestHelper::get('channel_type')?RequestHelper::get('channel_type'):'4';
        $custom_content = RequestHelper::get('custom_content');
        $type = RequestHelper::get('type');
        $ret = CurlHelper::pushPost($channel_id, $log_info, $remark, $custom_content, $channel_type, $type);
        var_dump($ret);
    }
}

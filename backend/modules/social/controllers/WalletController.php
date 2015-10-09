<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  WalletController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/9 上午9:46
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\social\controllers;
use backend\controllers\BaseController;
use backend\models\social\Wallet;
use backend\models\social\Withdrawal;
use common\helpers\RequestHelper;
use yii\data\Pagination;


/**
 * Class WalletController
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class WalletController extends BaseController
{
    /**
     * 简介：服务列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $model = new Wallet();
        $mobile = RequestHelper::get('mobile');
        $page = RequestHelper::get('page', 1, 'intval');
        $start_time = RequestHelper::get('start_time');
        $end_time = RequestHelper::get('end_time');
        $where = [];
        $andwhere = ' 1 ';
        if ($mobile != '') {
            $where['mobile'] = $mobile;
        }
        if ($start_time != '') {
            $andwhere .=" and create_time >='$start_time'";
        }
        if ($end_time != '') {
            $andwhere.=" and create_time <='$end_time 23:59:59'";
        }
        $count = $model->getCount($where, $andwhere);
        $list = $model->getPageList($where, "*", "id desc", $page, $this->size, $andwhere);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render(
            'index',
            [
                'list' => $list,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'pages' => $pages,
                'mobile' => $mobile,
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
        $model = new Wallet();
        $Withdrawal = new Withdrawal();
        $id = RequestHelper::get('id', 0, 'intval');
        $list = $model->getInfo(['id'=>$id]);
        if (!$list) {
            return $this->error('信息不存在');
        }
        $list['child'] = $Withdrawal->getList(1);
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
        $audit_status = RequestHelper::get('audit_status', 999, 'intval');
        $page = RequestHelper::get('page', 1, 'intval');
        $where = [];
        $where['is_deleted'] = 2;
        if ($name != '') {
            $where['name'] = $name;
        }
        if ($status != 999) {
            $where['status'] = $status;
        }
        if ($audit_status != 999) {
            $where['audit_status'] = $audit_status;
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
                'audit_status'=>$audit_status,
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
                $custom_content = ['title'=>234,'id'=>2];
                CurlHelper::pushPost('3524545843427557178', $log_info, $remark, $custom_content, 30);
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
     * 简介：测试ssdb是否通
     * @author  lichenjun@iyangpin.com。
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
}
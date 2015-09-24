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
use backend\models\social\ServiceSetting;
use backend\models\social\ServiceUnit;
use common\helpers\RequestHelper;
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
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function init()
    {
        parent::init();
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
        if ($status != 999) {
            $where['audit_status'] = $audit_status;
        }
        $count = $model->getCount($where);
        $list = $model->getPageList($where, "*", "id desc", $page);
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
            return $this->success('操作成功', 'unit');
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
        $list = $model->getPageList($where, "*", "id desc", $page);
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
        if ($audit_status != 999) {
            $where['audit_status'] = $audit_status;
            $log_info = '审核状态'.$this->audit_status_data[$audit_status];
        } elseif ($status != 999) {
            $where['status'] = $status;
            $log_info = '审核状态'.$status==1?'禁用':'启用';
        } elseif ($del != '') {
            $where['is_deleted'] = 1;
            $log_info = '删除状态';
        } else {
            return $this->error('参数错误');
        }
        $ret = $model->updateInfo($where, ['id' => $id]);
        if ($ret) {
            $log = new OpLog();
            $log->writeLog('服务设置修改id='.$id.'状态,'.$log_info.'|备注：'.$remark);
            return $this->success('操作成功', 'unit');
        } else {
            return $this->error('插入失败');
        }

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
}

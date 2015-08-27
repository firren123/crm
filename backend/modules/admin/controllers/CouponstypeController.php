<?php
/**
 * 优惠券
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  CRM
 * @package   I500
 * @filename  Coupons-type
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/23 下午8:42
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\City;
use backend\models\i500m\Coupons;
use backend\models\i500m\CouponType;
use backend\models\i500m\CrmBranch;
use backend\models\i500m\Log;
use backend\models\i500m\OpenCity;
use backend\models\i500m\Province;
use backend\models\social\UserCoupons;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\web\User;

/**
 * Coupons-type
 *
 * @category CRM
 * @package  CouponstypeController
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class CouponstypeController extends BaseController
{
    /**
     * 优惠券分类列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $page = RequestHelper::get('page', 1, 'intval');
        $size = $this->size;
        $model = new CouponType();
        $coupon = new Coupons();
        $coupon_model = new UserCoupons();
        $cond = "status!=0";
        if (!in_array($this->quanguo_city_id, $this->city_id)) {
            $city_arr = $this->city_id;
            $city_str = implode(',', $city_arr);
            $cond .= ' and city_id in (999,' . $city_str . ')';
        }
        $order = "type_id desc";
        $data = $model->getPageList($cond, '*', $order, $page, $size);
        $item = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $item[] = $v;
                $coupon_cond = "coupon_type_id=" . $v['type_id'];
                if ($v['use_system']==1) {
                    $count = $coupon->getCount($coupon_cond);
                } else {
                    $count = $coupon_model->getCount($coupon_cond);
                }
                $item[$k]['coupon_number'] = $count;
                if ($v['coupon_type'] == 2) {
                    $item[$k]['coupon_name'] = '系统劵';
                }
                if ($v['coupon_type'] == 1) {
                    $item[$k]['coupon_name'] = '注册劵';
                }
                if ($v['coupon_type'] == 0) {
                    $item[$k]['coupon_name'] = '普通劵';
                }
                //开通城市
                $open_model = new OpenCity();
                if ($v['city_id'] == 999) {
                    $area_name = '全国';
                } else {
                    $open_cond = "city =" . $v['city_id'];
                    $open_list = $open_model->getInfo($open_cond, 'name');
                    $area_name = empty($open_list) ? '' : $open_list['city_name'];
                }
                $item[$k]['area_name'] = $area_name;
            }
        }
        $total = $model->getCount($cond);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        return $this->render('index', ['data' => $item, 'pages' => $pages]);
    }

    /**
     * 优惠券详情
     *
     * @return string
     */
    public function actionView()
    {
        $id = RequestHelper::get('id', 0);
        $model = new CouponType();
        $cond = 'type_id=' . $id;
        $item = $model->getInfo($cond, true, '*');
        $coupon = new Coupons();
        $coupon_model = new UserCoupons();
        $coupon_cond = "coupon_type_id=" . $id;
        if ($item['use_system']==1) {
            $item['count'] = $coupon->getCount($coupon_cond);
        } else {
            $item['count'] = $coupon_model->getCount($coupon_cond);
        }
        return $this->render('view', ['item' => $item]);

    }

    /**
     * 优惠券删除
     *
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $id = RequestHelper::get('id');
        $model = new CouponType();
        $coupon = new Coupons();
        $cond = "type_id=" . $id;
        $data = $model->getInfo($cond);
        $coupon_cond = "coupon_type_id=" . $id;
        $count = $coupon->getCount($coupon_cond);
        if (empty($data)) {
            return $this->error('不存在', '/admin/couponstype');
        } else {
            if ($data['coupon_type'] != 2 and $count == 0) {
                $list = $model->delOneRecord($cond);
                if ($list['result'] == 1) {
                    //记录日志  刘伟
                    $content = "管理员：" . \Yii::$app->user->identity->username . ',删除了一个:id为' . $data['type_id'] . ',列表名称为:' . $data['type_name'] . ' 的优惠券类别,删除时间是:' . date('Y-m-d H:i:s');
                    $log_model = new Log();
                    $log_model->recordLog($content, 6);
                    return $this->success('删除成功', '/admin/couponstype');
                } else {
                    return $this->error('删除失败', '/admin/couponstype');
                }
            } else {
                return $this->error('不能删除', '/admin/couponstype');
            }
        }
    }

    /**
     * 添加优惠券
     *
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $bc_id = \Yii::$app->user->identity->bc_id;//分公司id
        $branch_model = new CrmBranch();
        $data_cond['name'] = '总公司';
        $branch_item = $branch_model->getInfo($data_cond);
        $branch_id = empty($branch_item['id']) ? 0 : $branch_item['id'];//总公司id
        //分公司对应的省id集合
        $crm_branch_conf['status'] = 1;
        if ($bc_id != $branch_id) {
            $crm_branch_conf['id'] = array($bc_id);
        }
        $branch_data = $branch_model->getList($crm_branch_conf, '*');
        $model = new CouponType();
        $model->send_type = 2;
        $model->used_status = 0;
        $model->is_all = 0;
        $model->coupon_type = 0;
        $model->status = 1;
        $model->use_system = 2;
        $CouponType = RequestHelper::post('CouponType');
        //开通城市
        $open_model = new OpenCity();
        $open_cond = 'status=1';
        $city_list = $open_model->getList($open_cond, 'city,city_name');
        $city_data = array();
        if (!empty($city_list)) {
            foreach ($city_list as $k => $v) {
                $city_data[0]['id'] = 0;
                $city_data[0]['city_name'] = '全国';
                $city_data[$v['city']] = $v;

            }
        }
        if (!empty($CouponType)) {
            $model->attributes = $CouponType;
            $type_name_number = mb_strlen($CouponType['type_name'], 'utf8');
            $start_time = $CouponType['start_time'];//开始时间
            $expired_time = $CouponType['expired_time'];//结束时间
            $par_value = $CouponType['par_value'];//现金金额
            $min_amount = $CouponType['min_amount'];//最小消费金额
            $consumer_points = $CouponType['consumer_points'];//消费积分
            $number = $CouponType['number'];//数量
            $limit_num = $CouponType['limit_num'];//最多换几张
            if ($type_name_number > 30) {
                $model->addError('type_name', '类别名称 最长30位');
            } elseif (!is_numeric($par_value)) {
                $model->addError('par_value', '现金劵面额 必须是数字');
            } elseif ($par_value <= 0) {
                $model->addError('par_value', '现金劵面额 必须是大于0的数字');
            } elseif (!is_numeric($min_amount)) {
                $model->addError('min_amount', '最小订单金额 必须是数字');
            } elseif ($min_amount <= 0) {
                $model->addError('min_amount', '最小订单金额 必须是大于0的数字');
            } elseif (!is_numeric($consumer_points)) {
                $model->addError('consumer_points', '消费积分 必须是数字');
            } elseif ($consumer_points < 0) {
                $model->addError('consumer_points', '消费积分 必须是大于0的数字');
            } elseif (!is_numeric($number)) {
                $model->addError('number', '数量 必须是数字');
            } elseif ($number <= 0) {
                $model->addError('number', '数量 必须是大于0的数字');
            } elseif (!is_numeric($limit_num)) {
                $model->addError('limit_num', '用户最多兑换张数 必须是数字');
            } elseif ($limit_num <= 0) {
                $model->addError('limit_num', '用户最多兑换张数 必须是大于0的数字');
            } elseif (empty($start_time)) {
                \Yii::$app->getSession()->setFlash('start_time', '开始时间 不能为空');
            } elseif (empty($start_time)) {
                \Yii::$app->getSession()->setFlash('start_time', '开始时间 不能为空');
            } elseif (empty($expired_time)) {
                \Yii::$app->getSession()->setFlash('expired_time', '结束时间 不能为空');
            } elseif (strtotime($expired_time) <= strtotime($start_time)) {
                \Yii::$app->getSession()->setFlash('expired_time', '结束时间 不能小于等于开始时间');
            } elseif (empty($CouponType['city_id'])) {
                \Yii::$app->getSession()->setFlash('error', '限定区域 不能为空');
            } else {
                $CouponType['add_time'] = date('Y-m-d H:i:s');
                $list = $model->getInsert($CouponType);
                if ($list > 0) {
                    //记录日志  刘伟
                    $content = "管理员：" . \Yii::$app->user->identity->username . ',添加了一个:id为' . $list . ',列表名称为:' . $CouponType['type_name'] . ' 的优惠券类别,添加时间是:' . date('Y-m-d H:i:s');
                    $log_model = new Log();
                    $log_model->recordLog($content, 6);
                    return $this->redirect('/admin/couponstype');
                } else {
                    echo "<script>alert('失败');</script>";
                }
            }
        }
        return $this->render('add', ['model' => $model, 'city_data' => $city_data, 'bc_id' => $bc_id, 'branch_data' => $branch_data]);
    }

    /**
     * 简介：城市
     * @return null
     */
    public function actionCity()
    {
        $bc_id = RequestHelper::get('bc_id');//省id
        $branch_model = new CrmBranch();
        $data['province_id'] = $bc_id;

        $city_id = $branch_model->getInfo($data, true, 'city_id_arr');
        $ids = explode(',', $city_id['city_id_arr']);
        $city = new City();
        $data = $city->getList(array('id' => $ids));
        echo json_encode($data);
    }
}

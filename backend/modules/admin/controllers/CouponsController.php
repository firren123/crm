<?php
/**
 * 优惠券列表
 *
 * PHP Version 5
 * 优惠券信息
 *
 * @category  PHP
 * @package   I500
 * @filename  CouponsController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/23 下午8:21
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Coupons;
use backend\models\i500m\CouponType;
use backend\models\i500m\Log;
use backend\models\i500m\OpenCity;
use backend\models\i500m\User;
use backend\models\social\UserCoupons;
use common\helpers\CommonHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * 优惠券列表
 *
 * PHP Version 5
 * 优惠券信息
 *
 * @category  PHP
 * @package   I500
 * @filename  CouponsController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/23 下午8:21
 * @link      http://www.i500m.com/
 */
class CouponsController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 简介：获取优惠券类型的名称和ID
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $type_id = RequestHelper::get('type_id');
        $name = RequestHelper::get('name');
        $page = RequestHelper::get('page', 1);
        $use_system = RequestHelper::get('use_system', 1);
        $model = new Coupons();
        $type_model = new CouponType();
        $user_model = new UserCoupons();
        //优惠券类型列表
        $where = "status!=0";
        $city_id = $this->city_id;
        if (!in_array($this->quanguo_city_id, $city_id)) {
            $city_str = implode(',', $city_id);
            $cond['city_id'] = ['999', $city_str];
            $where .= ' and city_id in (999,' . $city_str . ')';
        }
        $type_list = $type_model->getList($where, 'type_id,type_name');
        if (!empty($type_id)) {
            $cond['coupon_type_id'] = $type_id;
        } else {
            if ($type_list) {
                $cond['coupon_type_id'] = $type_list[0]['type_id'];
            } else {
                $cond['coupon_type_id'] = '';
            }
        }
        $and_where = [];
        if (!empty($name)) {
            $and_where = ['like', 'mobile', $name];
        }
        if ($use_system==1) {
            $data = $model->getPageList($cond, '*', 'id desc', $page, $this->size, $and_where);
        } else {
            $data = $user_model->getPageList($cond, '*', 'id desc', $page, $this->size, $and_where);
        }
        $item = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $item[] = $v;
                $type_cond = "type_id=" . $v['coupon_type_id'];
                $type_data = $type_model->getInfo($type_cond, 'type_name');
                $item[$k]['coupon_type_name'] = empty($type_data) ? '--' : $type_data['type_name'];
                if ($v['status'] == 0) {
                    $item[$k]['status_name'] = '未使用';
                }
                if ($v['status'] == 1) {
                    $item[$k]['status_name'] = '已使用';
                }
                if ($v['status'] == 2) {
                    $item[$k]['status_name'] = '已过期';
                }
                //开通城市
                $open_model = new OpenCity();
                if ($v['city_id'] == 0) {
                    $area_name = '全国';
                } else {
                    $open_cond = "city =" . $v['city_id'];
                    $open_list = $open_model->getInfo($open_cond, 'name');
                    $area_name = empty($open_list) ? '' : $open_list['city_name'];
                }
                $item[$k]['area_name'] = $area_name;
            }
        }
        $total = $model->getCount($cond, $and_where);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        $param = [
            'data' => $item,
            'pages' => $pages,
            'type_list' => $type_list,
            'type_id' => $type_id,
            'name' => $name,
            'use_system' => $use_system
        ];
        return $this->render('index', $param);
    }

    /**
     * 详情
     * @return string
     */
    public function actionView()
    {
        $id = RequestHelper::get('id');
        $type = RequestHelper::get('type');
        $model = new Coupons();
        $user_coupons_model = new UserCoupons();
        $cond['id'] = $id;
        if ($type==1) {
            $item = $model->getInfo($cond);
        } else {
            $item = $user_coupons_model->getInfo($cond);
        }
        if ($item['status'] == 0) {
            $item['status_name'] = '未使用';
        }
        if ($item['status'] == 1) {
            $item['status_name'] = '已使用';
        }
        if ($item['status'] == 2) {
            $item['status_name'] = '已过期';
        }
        return $this->render('view', ['item' => $item]);
    }

    /**
     * 批量上传
     *
     * @return string
     */
    public function actionAdd()
    {
        $id = RequestHelper::get('type_id');
        $type_list = [];
        if (empty($id)) {
            $this->redirect('/admin/couponstype');
        } else {
            $user_coupons_model = new UserCoupons();
            $type_model = new CouponType();
            $model = new Coupons();
            $type_cond = "type_id=" . $id;
            $type_list = $type_model->getInfo($type_cond);
            $cond = "coupon_type_id=" . $id;
            if ($type_list['use_system']==1) {
                $coupon_number = $model->getCount($cond);
            } else {
                $coupon_number = $user_coupons_model->getCount($cond);
            }

            if ($type_list['send_type'] == 1) {
                if ($type_list['used_status'] == 0) {
                    $number = RequestHelper::post('number');
                    $new_number = $type_list['number'] - $coupon_number;
                    if (!empty($number)) {
                        if ($number > $new_number) {
                            $numbers = $new_number;
                        } else {
                            $numbers = $number;
                        }
                        $data = array(
                            'coupon_type_id' => $type_list['type_id'],
                            'par_value' => $type_list['par_value'],
                            'consumer_points' => $type_list['consumer_points'],
                            'source' => $type_list['source'],
                            'get_time' => date('Y-m-d H:i:s'),
                            'expired_time' => $type_list['expired_time'],
                            'used_time' => '0000-00-00 00:00:00',
                            'status' => 0,
                            'remark' => $type_list['remark'],
                            'type_name' => $type_list['type_name'],
                            'min_amount' => $type_list['min_amount'],
                            'phone' => '',
                            'is_geted' => 0,
                            'city_id' => $type_list['city_id']
                        );
                        $result = false;
                        if ($numbers == 0) {
                            return $this->error('已经到达您允许生成的数量了', '/admin/couponstype');
                        } else {
                            for ($n = 0; $n < $numbers; $n++) {
                                $data['serial_number'] = $this->_getSerialNumber($n);
                                if ($type_list['use_system']==1) {
                                    $result = $model->insertInfo($data);
                                } else {
                                    $result = $user_coupons_model->insertInfo($data);
                                }
                            }
                            if ($result == true) {
                                //记录日志  刘伟
                                $content = "管理员：" . \Yii::$app->user->identity->username . '生成了' . $number . '个优惠券类别id为:' . $type_list['type_id'] . ',类别名称为:' . $type_list['type_name'] . ' 的优惠券';
                                $log_model = new Log();
                                $log_model->recordLog($content, 6);
                                return $this->success('提交成功，券号生成完成', '/admin/couponstype');
                            }
                        }

                    }
                } else {
                    return $this->error('优惠券类型不可用', '/admin/couponstype');
                }
            } else {
                return $this->error('优惠券类型不能批量生成', '/admin/couponstype');
            }
        }
        return $this->render('add', ['type_list' => $type_list]);
    }

    /**
     * 给用户发放
     *
     * @return string
     */
    public function actionAddbyuser()
    {
        $id = RequestHelper::get('id');
        $model_type = new CouponType();
        $type_cond = "type_id=" . $id;
        $type_list = $model_type->getInfo($type_cond, '*');
        if ($type_list['send_type'] == 2) {
            if ($type_list['used_status'] == 0) {
                $model = new Coupons();
                $user_coupons_model = new UserCoupons();
                $type_model = new CouponType();
                $cond = "coupon_type_id=" . $id;
                $coupon_number = $model->getCount($cond);
                $type_cond = "type_id=" . $id;
                $type_list = $type_model->getInfo($type_cond);
                $new_number = $type_list['number'] - $coupon_number;
                $user_model = new User();
                $social_user_model = new \backend\models\social\User();
                $list = array();
                $username = RequestHelper::post('username');
                if (!empty($username)) {
                    $data0 = CommonHelper::replace_space($username);
                    $data = explode(",", $data0);
                    $data2 = (array_filter($data));
                    $cond = array('mobile' => $data2);
                    if ($type_list['use_system']==1) {
                        $list = $user_model->getList($cond, '*');
                    } else {
                        $list = $social_user_model->getList($cond, '*');
                    }
                }
                $ids = RequestHelper::post('ids');

                if (!empty($ids)) {

                    $data = array(
                        'coupon_type_id' => $type_list['type_id'],
                        'par_value' => $type_list['par_value'],
                        'consumer_points' => $type_list['consumer_points'],
                        'source' => $type_list['source'],
                        'get_time' => date('Y-m-d H:i:s'),
                        'expired_time' => $type_list['expired_time'],
                        'used_time' => '0000-00-00 00:00:00',
                        'status' => 0,
                        'remark' => $type_list['remark'],
                        'type_name' => $type_list['type_name'],
                        'min_amount' => $type_list['min_amount'],
                        'phone' => '',
                        'is_geted' => 1,
                        'city_id' => $type_list['city_id']
                    );

                    $ids = implode(',', $ids);
                    $user_cond = "id in (" . $ids . ")";
                    if ($type_list['use_system']==1) {
                        $user_data = $user_model->getList($user_cond, 'id,username');
                    } else {
                        $user_data = $social_user_model->getList($user_cond, 'id,username,mobile');
                    }
                    $number = count($user_data);
                    if ($number > $new_number) {
                        $numbers = $new_number;
                    } else {
                        $numbers = $number;
                    }
                    $result = false;
                    if ($numbers <= 0) {
                        return $this->error('已经到达您允许生成的数量了', '/admin/couponstype');
                    } else {
                        for ($n = 0; $n < $number; $n++) {
                            if ($type_list['use_system']==1) {
                                $list_coupons = $model->getUserIdCoupons($user_data[$n]['id'], $id);
                            } else {
                                $list_coupons = $user_coupons_model->getCount(['coupon_type_id'=>$id, 'mobile'=>$user_data[$n]['mobile']]);
                            }

                            if ($list_coupons < $type_list['limit_num']) {

                                $data['mobile'] = $user_data[$n]['username'];
                                $data['serial_number'] = $this->_getSerialNumber($n);
                                if ($type_list['use_system']==1) {
                                    $data['user_id'] = $user_data[$n]['id'];
                                    $result = $model->insertInfo($data);
                                } else {
                                    $data['mobile'] = $user_data[$n]['mobile'];
                                    $result = $user_coupons_model->insertInfo($data);
                                }
                                //记录日志  刘伟
                                $content = "管理员：" . \Yii::$app->user->identity->username . '把优惠券类别id为:' . $type_list['type_id'];
                                $content .= ',类别名称为:' . $type_list['type_name'] . ' 的优惠券分发给了用户:' . $data['mobile'];
                                $log_model = new Log();
                                $log_model->recordLog($content, 6);
                            } else {
                                $result = true;
                            }

                        }
                        if ($result == true) {
                            return $this->success('提交成功，券号生成完成', '/admin/couponstype');
                        } else {
                            return $this->error('系统繁忙', '/admin/couponstype');
                        }
                    }
                }
            } else {
                return $this->error('优惠券类型不可用', '/admin/couponstype');
            }
        } else {
            return $this->error('优惠券类型不能分发给用户', '/admin/couponstype');
        }
        return $this->render('list', ['list' => $list, 'username' => $username, 'id' => $id]);

    }

    /**
     * 简介：券号生成规则
     * @author  lichenjun@iyangpin.com。
     * @param int $num id
     * @return string
     */
    private function _getSerialNumber($num)
    {
        return time() . $num . rand(10000, 99999);
    }
}

<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  Coupons.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/20 下午3:28
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;


/**
 * Class Coupons
 * @category  PHP
 * @package   Coupons
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Coupons extends I500Base
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return '{{%coupons}}';
    }

    /**
     * 查询用户已经存在优惠券的数量
     * @param int $user_id        x
     * @param int $coupon_type_id x
     * @return array
     */
    public function getUserIdCoupons($user_id, $coupon_type_id)
    {
        $number = 0;
        if (!empty($user_id) and !empty($coupon_type_id)) {
            $number = $this->find()->where("user_id=$user_id and coupon_type_id=$coupon_type_id")->count();
        }
        return $number;
    }

    /**
     * 简介：
     * @param int $type_id type_id
     * @return array|mixed
     */
    public function getListCoupons($type_id)
    {

    }

    /**
     * 简介：优惠券列表
     * @param int $id       x
     * @param int $order_sn x
     * @param int $status   x
     * @return int
     */
    public function getCoupons($id, $order_sn, $status)
    {
        $code = 0;
        if (empty($id) || empty($order_sn)) {
            $code = 0;
        }
        $model_cond = "order_sn='" . $order_sn . "' and id=" . $id;
        $data = array();
        $data['order_sn'] = $status==0?'':$order_sn;
        $data['used_time'] = $status == 0 ? '0000-00-00 00:00:00' : date('Y-m-d H:i:s', time());
        $data['status'] = $status;
        $list = $this->updateInfo($data, $model_cond);
        if ($list) {
            $code = 200;
        }
        return $code;
    }

}

<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  CheckInputHelps.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/6/2 下午3:54
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace common\helpers;


use backend\models\i500m\Product;
use backend\models\i500m\Shop;

/**
 * Class CheckInputHelps
 * @category  PHP
 * @package   CheckInputHelps
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CheckInputHelps
{
    /**
     * 简介：添加、更新活动表
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public static function activityAdd()
    {
        $error = array();
        $data = [];
        $data['name'] = RequestHelper::post('name');
        if (!$data['name']) {
            $error[] = '名称不能为空';
        }
        $data['type'] = RequestHelper::post('type', 0, 'intval');
        if (!$data['type']) {
            $error[] = '类型不能为空';
        }
        $data['register_start_time'] = RequestHelper::post('register_start_time');
        if (!$data['register_start_time']) {
            $error[] = '报名结束时间不能为空';
        }
        $data['register_end_time'] = RequestHelper::post('register_end_time');
        if (!$data['register_end_time']) {
            $error[] = '报名结束时间不能为空';
        }
        $data['start_time'] = RequestHelper::post('start_time');
        if (!$data['start_time']) {
            $error[] = '开始时间不能为空';
        }
        $data['end_time'] = RequestHelper::post('end_time');
        if (!$data['end_time']) {
            $error[] = '结束不能为空';
        }
        $data['pay_type'] = RequestHelper::post('pay_type');
        if (empty($data['pay_type'])) {
            $error[] = '支付类型不能为空';
        }
        $data['platform'] = RequestHelper::post('platform');
        if (empty($data['platform'])) {
            $error[] = '平台不能为空';
        }
        $data['new_user_site'] = RequestHelper::post('new_user_site');
        if (!$data['new_user_site']) {
            $error[] = '新用户限制不能为空';
        }
        $data['display'] = RequestHelper::post('display', 0, 'intval');
        $data['display_url'] = RequestHelper::post('display_url');
        if ($data['display_url'] == '' && $data['display'] == 1) {
            $error[] = '活动页面链接地址不能为空';
        }

        $data['image1'] = RequestHelper::post('image1');
        if (empty($data['image1'][0])) {
            $error[] = '主图不能为空';
        }
        $data['area_status'] = RequestHelper::post('area_status');
        if (!$data['area_status']) {
            $error[] = '限制地区不能为空';
        }
        $data['province'] = RequestHelper::post('province', 0, 'intval');
        if ($data['province'] == 0) {
            $error[] = '省份不能为空';
        }
        $data['city'] = RequestHelper::post('city', 0, 'intval');
        $data['district'] = RequestHelper::post('district', 0, 'intval');
        $data['sort'] = RequestHelper::post('sort', 999, 'intval');
        $data['is_all_area'] = RequestHelper::post('is_all_area', 0, 'intval');
        $data['describe'] = RequestHelper::post('describe', '', '');
        $data['status'] = RequestHelper::post('status', 1, 'intval');
        $data['gift_type'] = RequestHelper::post('gift_type', 0, 'intval');
        $data['status'] = RequestHelper::post('status', 1, 'intval');
        $data['gift_coupons_id'] = RequestHelper::post('gift_coupons_id', 0, 'intval');
        $data['confine_num'] = RequestHelper::post('confine_num', 0, 'intval');
        $data['confine_num2'] = RequestHelper::post('confine_num2', 0, 'intval');
        $data['meet_amount'] = RequestHelper::post('meet_amount', 0, 'intval');
        $data['meet_amount2'] = RequestHelper::post('meet_amount2', 0, 'intval');
        $data['goods_type'] = RequestHelper::post('goods_type', 0, 'intval');
        $data['goods_cate'] = RequestHelper::post('goods_cate', 0, 'intval');
        if ($data['gift_type'] == 1 && !$data['gift_coupons_id']) {
            $error[] = '赠品优惠券不能为空';
        }
        if ($data['goods_type'] == 1 && !$data['goods_cate']) {
            $error[] = '商品分类不能为空';
        }
        if (!empty($error)) {
            $e = implode(',', $error);
            return array(100, $e);
        }
        //拼接字符串
        $arr = array();

        $pay_type = implode(',', RequestHelper::post('pay_type'));
        $platform = implode(',', RequestHelper::post('platform'));
        $image = implode('###', RequestHelper::post('image1'));

        $arr['name'] = $data['name'];
        $arr['type'] = $data['type'];
        $arr['register_start_time'] = $data['register_start_time'];
        $arr['register_end_time'] = $data['register_end_time'];
        $arr['start_time'] = $data['start_time'];
        $arr['end_time'] = $data['end_time'];
        $arr['new_user_site'] = $data['new_user_site'];
        $arr['display'] = $data['display'];
        $arr['display_url'] = $data['display_url'];
        $arr['area_status'] = $data['area_status'];
        if ($arr['area_status'] > 2) {
            $arr['district'] = $data['district'];
        }
        if ($arr['area_status'] > 1) {
            $arr['city'] = $data['city'];
        }
        $arr['province'] = $data['province'];
        $arr['is_all_area'] = $data['is_all_area'];
        $arr['describe'] = $data['describe'];
        $arr['sort'] = $data['sort'];
        $arr['pay_type'] = $pay_type;
        $arr['platform'] = $platform;
        $arr['images'] = $image;
        $arr['create_time'] = date('Y-m-d H:i:s');
        $arr['admin_id'] = \Yii::$app->user->identity->id;
        $arr['confine_num'] = $data['confine_num'] == 0 ? 0 : $data['confine_num2'];
        $arr['meet_amount'] = $data['meet_amount'] == 0 ? 0 : $data['meet_amount2'];
        $arr['status'] = $data['status'];
        $arr['gift_type'] = $data['gift_type'];
        $arr['gift_coupons_id'] = $data['gift_coupons_id'];
        $arr['goods_type'] = $data['goods_type'];
        $arr['goods_cate'] = $data['goods_cate'];
        return array(200, $arr);
    }

    /**
     * 简介：添加、更新活动商家表
     * @author  lichenjun@iyangpin.com。
     * @param  array $data      数组
     * @param  int   $insert_id 最后ID
     * @return array
     */
    public static function activityAddShop($data, $insert_id)
    {
        $where = [];
        $shop_list = [];
        $data['is_all_area'] = RequestHelper::post('is_all_area', 0, 'intval');
        $data['area_status'] = RequestHelper::post('area_status', 0, 'intval');
        $data['province'] = RequestHelper::post('province', 0, 'intval');
        $data['city'] = RequestHelper::post('city', 0, 'intval');
        $data['district'] = RequestHelper::post('district', 0, 'intval');
        $data['shop_id'] = RequestHelper::post('shop_id', 0, 'intval');
        if ($data['is_all_area'] == 1) {
            if ($data['area_status'] != 4) {
                if ($data['area_status'] == 1) {
                    //获取省份
                    $where['province'] = $data['province'];
                } elseif ($data['area_status'] == 2) {
                    //获取市
                    $where['city'] = $data['city'];
                } elseif ($data['area_status'] == 3) {
                    //获取区
                    $where['district'] = $data['district'];
                }
                if (!empty($where)) {
                    $shop_model = new Shop();
                    $list = $shop_model->getList($where, 'id');

                    foreach ($list as $k => $v) {
                        $shop_list[$k]['shop_id'] = $v['id'];
                        $shop_list[$k]['activity_id'] = $insert_id;
                    }
                }
            } else {
                if (isset($data['shop_id'])) {
                    foreach ($data['shop_id'] as $k => $v) {
                        $shop_list[$k]['shop_id'] = $v;
                        $shop_list[$k]['activity_id'] = $insert_id;
                    }
                }
            }
        }
        return array(200, $shop_list);
    }

    /**
     * 简介：更新商家商品表活动字段
     * @author  lichenjun@iyangpin.com。
     * @param array $data      数组
     * @param int   $insert_id 最后ID
     * @param array $shop      商家
     * @return array
     */
    public static function activityEditShopProduct($data, $insert_id, $shop)
    {
        $arr = [];
        $key = 0;
        $goods_type = RequestHelper::post('goods_type', 0, 'intval');
        $data['goods1_json'] = RequestHelper::post('goods1_json', array(), '');
        $data['goods_cate'] = RequestHelper::post('goods_cate', 0, 'intval');
        $data['platform'] = RequestHelper::post('platform');
        if ($goods_type == 0) {
            if ($data['goods1_json'] ) {
                foreach ($data['goods1_json'] as $k => $v) {
                    $info = json_decode($v, true);
                    $arr2 = [];
                    foreach ($shop as $kk => $vv) {
                        $arr[$key]['shop_id'] = $vv['shop_id'];
                        $arr[$key]['activity_id'] = $insert_id;
                        $arr[$key]['product_id'] = $info['id'];
                        $arr2['platform'] = implode(',', $data['platform']);
                        $arr2['start_time'] = $info['start_time'];
                        $arr2['end_time'] = $info['end_time'];
                        $arr2['price'] = $info['price'];
                        $arr2['day_confine_num'] = $info['day_num'];
                        $arr[$key]['activity_temp'] = json_encode($arr2);
                        $key++;
                    }
                }
            }
            return array(200, $arr);
        } elseif ($goods_type == 1) {
            foreach ($shop as $kk => $vv) {
                $arr2 = [];
                $arr[$key]['shop_id'] = $vv['shop_id'];
                $arr[$key]['activity_id'] = $insert_id;
                $arr[$key]['cat_id'] = $data['goods_cate'];
                $arr2['platform'] = implode(',', $data['platform']);
                $arr[$key]['activity_temp'] = json_encode($arr2);
                $key++;
            }
            return array(201, $arr);

        }
        return array(100, '添加商家商品库信息不正确');




    }

    /**
     * 简介：更新活动商品表
     * @author  lichenjun@iyangpin.com。
     * @param array $data      数组
     * @param int   $insert_id 最后ID
     * @return array
     */
    public static function activityAddGoods($data, $insert_id)
    {
        $arr = [];
        $goods_type = RequestHelper::post('goods_type', 0, 'intval');
        $data['goods1_json'] = RequestHelper::post('goods1_json', array(), '');
        $data['goods_cate']  = RequestHelper::post('goods_cate', 0, 'intval');
        $data['start_time']  = RequestHelper::post('start_time');
        $data['end_time']    = RequestHelper::post('end_time');
        if ($goods_type == 0) {
            foreach ($data['goods1_json'] as $k => $v) {
                $info = json_decode($v, true);
                $arr[$k]['activity_id']     = $insert_id;
                $arr[$k]['product_id']      = $info['id'];
                $arr[$k]['product_name']    = $info['goods_name'];
                $arr[$k]['start_time']      = $info['start_time'];
                $arr[$k]['end_time']        = $info['end_time'];
                $arr[$k]['price']           = $info['price'];
                $arr[$k]['day_confine_num'] = $info['day_num'];
            }
        } elseif ($goods_type == 1) {
            $productModel = new Product();
            $product_list = $productModel->getList(['cate_first_id'=>$data['goods_cate']]);
            foreach ($product_list as $k => $v) {
                $arr[$k]['activity_id']     = $insert_id;
                $arr[$k]['product_id']      = $v['id'];
                $arr[$k]['product_name']    = $v['name'];
                $arr[$k]['start_time']      = $data['start_time'];
                $arr[$k]['end_time']        = $data['end_time'];
                $arr[$k]['price']           = $v['origin_price'];
                $arr[$k]['day_confine_num'] = 0;
            }
        } else {
            return array(100, '添加商家商品库信息不正确');
        }
        return array(200, $arr);
    }

    /**
     * 简介：添加、更新活动赠品表
     * @author  lichenjun@iyangpin.com。
     * @param array $data      数组
     * @param int   $insert_id 最后ID
     * @return array
     */
    public static function activityAddGift($data, $insert_id)
    {
        $arr = [];
        $data['gift_type'] = RequestHelper::post('gift_type', 0, 'intval');
        if ($data['gift_type'] == 0) {
            if (isset($data['goods2_json'])) {
                foreach ($data['goods2_json'] as $k => $v) {
                    $info = json_decode($v, true);
                    $arr[$k]['activity_id'] = $insert_id;
                    $arr[$k]['product_id'] = $info['id'];
                    $arr[$k]['product_name'] = $info['goods_name'];
                    $arr[$k]['start_time'] = $info['start_time'];
                    $arr[$k]['end_time'] = $info['end_time'];
                    $arr[$k]['day_confine_num'] = $info['day_num'];
                }
            }
        }
        return array(200, $arr);
    }
}

<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  ActivityController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/6/1 下午12:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\goods\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Activity;
use backend\models\i500m\ActivityGift;
use backend\models\i500m\ActivityProduct;
use backend\models\i500m\ActivityShop;
use backend\models\i500m\City;
use backend\models\i500m\CouponType;
use backend\models\i500m\District;
use backend\models\i500m\Province;
use backend\models\i500m\Category;
use backend\models\i500m\ManageType;
use backend\models\i500m\PaySite;
use backend\models\i500m\Product;
use backend\models\i500m\Shop;
use backend\models\shop\ShopActivity;
use backend\models\shop\ShopActivityGift;
use backend\models\shop\ShopActivityProduct;
use backend\models\shop\ShopProduct;
use common\helpers\CheckInputHelps;
use common\helpers\CommonHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

/**
 * Class ActivityController
 * @category  PHP
 * @package   ActivityController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ActivityController extends BaseController
{
    public $size = 10;
    public $activity_type = [
        //'1'=>'限购',
        '2' => '买赠',
        //'3'=>'加价购'
    ];
    public $activity_scope = [
        '1' => '指定省份',
        '2' => '指定城市',
        '3' => '指定区县',
        '4' => '指定店铺',
    ];
    public $pay_site_id_data = array();

    /**
     * 简介：构造函数
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function init()
    {
        parent::init();
        //获取支付类型
        $pay_site = new PaySite();
        $pay_site_arr = $pay_site->getList('id !=1', 'id,name');
        foreach ($pay_site_arr as $k => $v) {
            $this->pay_site_id_data[$v['id']] = $v['name'];
        }

    }

    /**
     * 简介：活动列表
     * @author  lichenjun@iyangpin.com
     * @return null
     */
    public function actionList()
    {

        $id = RequestHelper::get('id');
        $name = RequestHelper::get('name');
        $type_id = RequestHelper::get('type_id');
        $province_id = RequestHelper::get('province_id');

        $where['status'] = [1, 2, 3];
        $and_where = [];
        if (!empty($name)) {
            //$where .= " and name LIKE '%" . $name . "%' ";
            $and_where = ['like', 'name', $name];
        }

        if (!empty($id)) {
            $where['id'] = " $id ";
        }

        if (!empty($type_id)) {
            $where['type'] = " $type_id ";
        }

        if (!empty($province_id)) {
            $where['province'] = " $province_id ";
        }

        $p = RequestHelper::get('page', '1', 'intval');                //当前页
        $model = new Activity();
        $list = $model->getPageList($where, '*', 'id desc', $p, $this->size, $and_where);
        $count = $model->getCount($where, $and_where);
        //var_dump($list);exit;
        $res = array();
        $arr = array();
        foreach ($list as $v) {
            $arr['id'] = $v['id'];
            $arr['name'] = $v['name'];
            $arr['type'] = $v['type'];
            $arr['sort'] = $v['sort'];
            $arr['start_time'] = date('Y-m-d', strtotime($v['start_time']));
            $arr['end_time'] = date('Y-m-d', strtotime($v['end_time']));
            $arr['status'] = $v['status'];
            $arr['province'] = $v['province'];

            //报名时间活动时间转换为时间戳
            $register_start_time = strtotime($v['register_start_time']);
            $register_end_time = strtotime($v['register_end_time']);
            $start_time = strtotime($v['start_time']);
            $end_time = strtotime($v['end_time']);
            $time = time();
            //var_dump($time);exit;
            if ($v['status'] == 2) {
                $arr['status'] = '禁用';
            } else {
                if ($start_time < $time && $time < $end_time) {
                    $arr['status'] = '已开始';
                } elseif ($register_start_time > $time) {
                    $arr['status'] = '未开始';
                } elseif ($register_start_time < $time && $time < $register_end_time) {
                    $arr['status'] = '报名中';
                } elseif ($register_end_time < $time && $time < $start_time) {
                    $arr['status'] = '报名结束';
                } elseif ($time > $end_time) {
                    $arr['status'] = '已结束';
                } else {
                    $arr['status'] = '';
                }
            }

            //获取省名
            $branch_m = new Province();
            $where = array('id' => $v['province']);
            $province = $branch_m->getOneRecord($where, '', "name");
            if (empty($province)) {
                $arr['province_name'] = '';
            } else {
                $arr['province_name'] = $province['name'];
            }


            $res[] = $arr;
        }
        //获取省份
        $province_list = CommonHelper::province();
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        $data_info = [
            'pages' => $pages,
            'id' => $id,
            'name' => $name,
            'type_id' => $type_id,
            'province_id' => $province_id,
            'res' => $res,
            'count' => $count,
            'province_list' => $province_list,
            'activity_type' => $this->activity_type,
        ];
        return $this->render('list', $data_info);
    }

    /**
     * 简介：活动状态修改
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionEditStatus()
    {
        if ($_GET) {
            $id = RequestHelper::get('id');
            $cond = ['id' => $id];
            $model = new Activity();
            $where = array();
            $where['stop_remark'] = RequestHelper::get('stop_remark', '');
            $where['status'] = 2;
            if (empty($where['stop_remark'])) {
                echo 2;
                exit;
            }
            $ret = $model->updateInfo($where, $cond);

            //清除activity_id
            $con = ['activity_id' => $id];
            $arr['activity_id'] = 0;
            $arr['activity_temp'] = '';
            $shop_model = new ShopProduct();
            $shop_model->updateInfo($arr, $con);
            //var_dump($result);exit;
            if ($ret == true) {
                echo 1;
                exit;
            } else {
                echo 3;
                exit;
            }
        }
    }

    /**
     * 简介：添加活动
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAdd()
    {
        $Activity_model = new Activity();
        // POS提交数据
        if (\Yii::$app->request->getIsPost()) {
            $data = CheckInputHelps::activityAdd();
            if ($data[0] == 100) {
                return $this->error($data[1]);
            }

            $ret = $Activity_model->insertOneRecord($data[1]);

            if ($ret['result'] == 1) {
                $insert_id = $ret['data']['new_id'];
            } else {
                return $this->error("添加失败！" . $ret['msg']);
            }
            //添加商家
            $data2 = CheckInputHelps::activityAddShop($_POST, $insert_id);

            if ($data2[0] == 100) {
                return $this->error($data2[1]);
            }
            $ActivityShopModel = new ActivityShop();
            foreach ($data2[1] as $k) {
                $ActivityShopModel->insertInfo($k);
            }
            //添加商家库
            $shopProduct = CheckInputHelps::activityEditShopProduct($_POST, $insert_id, $data2[1]);

            if ($shopProduct[0] == 100) {
                return $this->error($shopProduct[1]);
            }
            $ShopProductModel = new ShopProduct();
            if ($shopProduct[0] == 200) {
                foreach ($shopProduct[1] as $k) {
                    $ShopProductModel->updateInfo($k, array('shop_id' => $k['shop_id'], 'product_id' => $k['product_id']));
                }
            }

            //添加商品
            $data3 = CheckInputHelps::activityAddGoods($_POST, $insert_id);
            if ($data3[0] == 100) {
                return $this->error($data3[1]);
            }
            $ActivityProductModel = new ActivityProduct();
            foreach ($data3[1] as $k) {
                $ActivityProductModel->insertInfo($k);
            }
            //添加赠品
            $data4 = CheckInputHelps::activityAddGift($_POST, $insert_id);
            if ($data4[0] == 100) {
                return $this->error($data4[1]);
            }
            $ActivityGiftModel = new ActivityGift();
            foreach ($data4[1] as $k) {
                $ActivityGiftModel->insertInfo($k);
            }
            return $this->success('添加成功', '/goods/activity/list');
        }
        $title = '添加活动';
        //获取省份
        if ($this->is_head_company) {
            $province_list = CommonHelper::province(true);
        } else {
            $provinceModel = new Province();
            $p_list = $provinceModel->getList(['id' => $this->province_id], 'id,name');
            $province_list = ArrayHelper::map($p_list, 'id', 'name');
        }
        //获取优惠券
        $coupon_list = $this->getCouponType();
        //获取商品分类
        $cateModel = new Category();
        $goods_cate = $cateModel->getList(['level'=>1, 'status'=>2,'type'=>0], "id,name");
        $data_info = [
            'model' => $Activity_model,
            'title' => $title,
            'coupon_list' => $coupon_list,
            'province_list' => $province_list,
            'activity_type' => $this->activity_type,
            'activity_scope' => $this->activity_scope,
            'pay_site_id_data' => $this->pay_site_id_data,
            'goods_cate'       => $goods_cate
        ];
        return $this->render('add', $data_info);
    }

    /**
     * 简介：修改活动
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEdit()
    {
        $Activity_model = new Activity();
        $id = RequestHelper::get('id', 0, 'intval');

        //判断报名时间
        $title = '修改活动';
        $activity = $Activity_model->getInfo(['id' => $id]);
        if (!$activity) {
            return $this->error("活动不存在,请正确选择活动");
        }
        $time = date('Y-m-d H:i:s');
        if ($time > $activity['register_start_time']) {
            //return $this->error("活动已经开始，不能编辑，只能查看", "/goods/activity/view?id=" . $id);
        }
        // POS提交数据
        if (\Yii::$app->request->getIsPost()) {
            $data = CheckInputHelps::activityAdd($_POST);
            if ($data[0] == 100) {
                return $this->error($data[1]);
            }
            $ret = $Activity_model->updateInfo($data[1], ['id' => $id]);
            if ($ret) {

                //添加商家
                $data2 = CheckInputHelps::activityAddShop($_POST, $id);
                if ($data2[0] == 100) {
                    return $this->error($data2[1]);
                }
                $ActivityShopModel = new ActivityShop();
                //删除商家
                $ActivityShopModel->deleteAll(['activity_id' => $id]);
                foreach ($data2[1] as $k) {
                    $ActivityShopModel->insertInfo($k);
                }


                $shopProduct = CheckInputHelps::activityEditShopProduct($_POST, $id, $data2[1]);

                if ($shopProduct[0] == 100) {
                    return $this->error($shopProduct[1]);
                }
                $ShopProductModel = new ShopProduct();
                //删除商家库
                $ShopProductModel->updateInfo(['activity_id' => 0, 'activity_temp' => ''], ['activity_id' => $id]);
                //添加商家库
                if ($shopProduct[0] == 200) {
                    foreach ($shopProduct[1] as $k) {
                        $ShopProductModel->updateInfo($k, array('shop_id' => $k['shop_id'], 'product_id' => $k['product_id']));
                    }
                }

                //添加商品
                $data3 = CheckInputHelps::activityAddGoods($_POST, $id);
                if ($data3[0] == 100) {
                    return $this->error($data3[1]);
                }
                $ActivityProductModel = new ActivityProduct();
                //删除产品
                $ActivityProductModel->deleteAll(['activity_id' => $id]);
                foreach ($data3[1] as $k) {
                    $ActivityProductModel->insertInfo($k);
                }

                //添加赠品
                $data4 = CheckInputHelps::activityAddGift($_POST, $id);
                if ($data4[0] == 100) {
                    return $this->error($data4[1]);
                }
                $ActivityGiftModel = new ActivityGift();
                //删除赠品
                $ActivityGiftModel->deleteAll(['activity_id' => $id]);
                foreach ($data4[1] as $k) {
                    $ActivityGiftModel->insertInfo($k);
                }

                return $this->success('修改成功', '/goods/activity/list');
            }

        }

        //获取省份
        $province_list = CommonHelper::province(true);
        //获取已经选中的商家
        $shop_list = $this->getSelectShop($id);
        //获取已经选中的商品
        $goods_list = $this->getSelectGoods($id);  //买赠商品
        //获取已经选中的赠品
        $gift_list = $this->getSelectGift($id);     //买赠赠品
        //获取优惠券
        $coupon_list = $this->getCouponType();
        //获取商品分类
        $cateModel = new Category();
        $goods_cate = $cateModel->getList(['level'=>1, 'status'=>2,'type'=>0], "id,name");
        $data_info = [
            'model' => $Activity_model,
            'title' => $title,
            'shop_list' => $shop_list,
            'goods_list' => $goods_list,
            'gift_list' => $gift_list,
            'activity' => $activity,
            'coupon_list' => $coupon_list,
            'province_list' => $province_list,
            'activity_type' => $this->activity_type,
            'activity_scope' => $this->activity_scope,
            'pay_site_id_data' => $this->pay_site_id_data,
            'goods_cate'       => $goods_cate
        ];
        return $this->render('add', $data_info);
    }

    /**
     * 简介：查看活动
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionView()
    {
        $Activity_model = new Activity();
        $id = RequestHelper::get('id', 0, 'intval');
        $title = '查看活动';
        $activity = $Activity_model->getInfo(['id' => $id]);
        //获取省份
        $province_list = CommonHelper::province(true);
        //获取已经选中的商家
        $shop_list = $this->getSelectShop($id);
        //获取已经选中的商品
        $goods_list = $this->getSelectGoods($id);  //买赠商品
        //获取已经选中的赠品
        $gift_list = $this->getSelectGift($id);     //买赠赠品
        //获取优惠券
        $coupon_list = $this->getCouponType();
        $cateModel = new Category();
        $goods_cate = $cateModel->getList(['level'=>1, 'status'=>2,'type'=>0], "id,name");
        $data_info = [
            'model' => $Activity_model,
            'title' => $title,
            'shop_list' => $shop_list,
            'goods_list' => $goods_list,
            'gift_list' => $gift_list,
            'activity' => $activity,
            'coupon_list' => $coupon_list,
            'province_list' => $province_list,
            'activity_type' => $this->activity_type,
            'activity_scope' => $this->activity_scope,
            'pay_site_id_data' => $this->pay_site_id_data,
            'goods_cate'  => $goods_cate
        ];
        return $this->render('view', $data_info);
    }

    /**
     * 简介：获取产品信息1111111111
     * @author  zhoujun@iyangpin.com。
     * @return string
     */
    public function actionShopList()
    {
        $page_size = 10;
        $this->layout = 'dialog';
        $shop_model = new Shop();
        $type_model = new ManageType();
        $type_info = $type_model->type_all();
        $data = array();
        $data['district'] = RequestHelper::get('district', 0);
        if ($data['district']) {
            $data['page'] = RequestHelper::get('page', 1);
            $data['size'] = 10;
            if ($data['page'] == 1) {
                $offset = 0;
            } else {
                $offset = ($data['page'] - 1) * $data['size'];
            }
            $district = RequestHelper::get('district', 0, 'intval');
            $shop_id = RequestHelper::get('shop_id', 0, 'intval');
            $type_id = RequestHelper::get('type_id', 0, 'intval');
            $where = array();
            if ($shop_id) {
                $where[] = 'id =' . $shop_id;
            }

            if ($type_id) {
                $where[] = 'manage_type =' . $type_id;
            }
            if ($data['district']) {
                $where[] = ' district=' . $district;
            }
            $where = empty($where) ? '' : implode(' and ', $where);
            $shop_info = $shop_model->show($data, $offset, $where);
            foreach ($shop_info as $k => $v) {
                $type_name = $type_model->type_name($v['manage_type']);
                $shop_info[$k]['manage_name'] = $type_name['name'];
            }
            $total = $shop_model->total($where);
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $page_size]);

            return $this->render('shoplist', ['shop_info' => $shop_info, 'type_info' => $type_info, 'type_id' => $type_id, 'pages' => $pages]);
        } else {
            echo "请先选择区县";
            exit;
        }
    }

    /**
     * 简介：商品列表1111111
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionGoodsList()
    {
        $this->layout = 'dialog';
        $cate_model = new Category();
        $pro_model = new Product();

        $type = RequestHelper::get('type');
        $product_id = RequestHelper::get('product_id', 0, 'intval');
        $bar_code = RequestHelper::get('bar_code', 0);
        if ($this->is_head_company) {
            if ($product_id) {
                $list = $pro_model->getInfo(['id' => $product_id, 'status' => 1]);
            } elseif ($bar_code) {
                $list = $pro_model->getInfo(['bar_code' => $bar_code, 'status' => 1]);
            } else {
                $list = '';
            }

        } else {
            if ($product_id) {
                $where = [
                    'id' => $product_id,
                    'status' => 1,
                    'area_id' => array($this->bc_id, 0),
                ];
            } elseif ($bar_code) {
                $where = [
                    'bar_code' => $bar_code,
                    'status' => 1,
                    'area_id' => array($this->bc_id, 0),
                ];
            } else {
                $where = [
                    'id' => 0,
                    'bar_code' => 0,
                    'status' => 1,
                    'area_id' => array($this->bc_id, 0),
                ];
            }
            $list = $pro_model->getInfo($where);
        }
        if ($list) {
            $cate_info = $cate_model->cate_info($list['cate_first_id']);
            $list['pro_type'] = $cate_info['name'];
        }
        return $this->render('goodslist', ['type' => $type, 'pro_info' => $list]);
    }

    /**
     * 简介：获取商家信息
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionGetShopInfo()
    {
        $shop_m = new Shop();
        $id = RequestHelper::get('id', 0, 'intval');
        $where = ['id' => $id];
        $shop_info = $shop_m->getInfo($where, true, "id,shop_name,manage_type");
        $manage_type_model = new ManageType();
        $manage_type = $manage_type_model->getInfo(['id' => $shop_info['manage_type']], true, "name");
        $shop_info['manage_type'] = $manage_type['name'];
        echo json_encode($shop_info);
        exit;

    }

    /**
     * 简介：城市下拉选择器
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionSelectList()
    {
        $type = RequestHelper::get('type', 1);
        $id = RequestHelper::get('id', 1, 'intval');
        if ($type == 1) {
            $province = CommonHelper::province();
            echo $ret = $this->ajaxReturn(200, [], $province);
        }
        if ($type == 2 && $id > 0) {
            $city = CommonHelper::city($id, true);
            echo $ret = $this->ajaxReturn(200, [], $city);
        }
        if ($type == 3 && $id > 0) {
            echo $ret = $this->ajaxReturn(200, [], CommonHelper::district($id));
        }
    }


    /**
     * ===============================================================================
     *  私有方法
     * ===============================================================================
     */
    /**
     * 简介：获取选中的商家
     * @author  lichenjun@iyangpin.com。
     * @param int $id ID
     * @return array
     */
    public function getSelectShop($id)
    {
        $ActivityShopModel = new ActivityShop();
        $list = $ActivityShopModel->getList(['activity_id' => $id], "shop_id", "id");

        $ShopModel = new Shop();
        $ManageTypeModel = new ManageType();
        foreach ($list as $k => $v) {
            $shop_list = $ShopModel->getInfo(['id' => $v['shop_id']], true, "shop_name,manage_type");
            $list[$k]['shop_name'] = $shop_list['shop_name'];
            $manage_type = $ManageTypeModel->getInfo(['id' => $shop_list['manage_type']], true, "name");
            $list[$k]['manage_name'] = $manage_type['name'];
        }
        return $list;
    }

    /**
     * 简介：获取选中的商品
     * @author  lichenjun@iyangpin.com。
     * @param int $id ID
     * @return array
     */
    public function getSelectGoods($id)
    {
        $ActivityProductModel = new ActivityProduct();
        $list = $ActivityProductModel->getList(['activity_id' => $id], "*", "id");
        foreach ($list as $k => $v) {
            $list[$k]['goods_name'] = $v['product_name'];
            $list[$k]['day_num'] = $v['day_confine_num'];
            $list[$k]['id'] = $v['product_id'];
            $list[$k]['goods1_json'] = json_encode($list[$k]);

        }
        return $list;
    }

    /**
     * 简介：获取选中的赠品
     * @author  lichenjun@iyangpin.com。
     * @param int $id ID
     * @return array
     */
    public function getSelectGift($id)
    {
        $ActivityGiftModel = new ActivityGift();
        $list = $ActivityGiftModel->getList(['activity_id' => $id], "*", "id");
        foreach ($list as $k => $v) {
            $list[$k]['goods_name'] = $v['product_name'];
            $list[$k]['day_num'] = $v['day_confine_num'];
            $list[$k]['id'] = $v['product_id'];
            $list[$k]['goods2_json'] = json_encode($list[$k]);
        }
        return $list;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCouponType()
    {
        $CouponTypeModel = new CouponType();
        $where = [
            'status' => 1,
            'coupon_type' => array(0, 1),
        ];
        $andWhere = "expired_time > '" . date('Y-m-d H:i:s') . "'";
        $list = $CouponTypeModel->getList($where, "type_id,type_name", "type_id", $andWhere);
        return $list;
    }

    /**
     * 查看参与的所有商家
     * @author sunsong<sunsongsong@iyangpin.com>
     * @return array
     */
    public function actionActivityView()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $page = RequestHelper::get('page', 1, 'intval');
        $page_size = $this->size;
        if (0 == $id) {
            return $this->error("访问错误！！！", "/goods/activity/list");
        }
        $act_shop = new ActivityShop();
        $province = new Province();
        $city = new City();
        $district = new District();
        $shop = new Shop();
        $type = new ManageType();

        $where = ['=', 'activity_id', $id];
        $list = $act_shop->getPageList($where, 'id,activity_id,shop_id', 'id desc', $page, $page_size);

        if (empty($list)) {
            $list = array();
        }
        $count = $act_shop->getCount($where);
        $page_count = $count;
        if ($count > 0 && $page_size > 0) {
            $page_count = ceil($count / $page_size);
        }
        $info = array();
        foreach ($list as $v) {
            $shop_where = ['=', 'id', $v['shop_id']];
            $shop_name = $shop->getOneRecord($shop_where, '', 'shop_name,manage_type,province,city,district');

            if (!empty($shop_name)) {

                $type_where = ['=', 'id', $shop_name['manage_type']];
                $type_name = $type->getOneRecord($type_where, '', 'name');
                if (!empty($type_name)) {
                    $v['m_name'] = $type_name['name'];
                }
                $p_where = ['=', 'id', $shop_name['province']];
                $p_name = $province->getOneRecord($p_where, '', 'name');
                if (!empty($p_name)) {
                    $v['p_name'] = $p_name['name'];
                }

                $c_where = ['=', 'id', $shop_name['city']];
                $c_name = $city->getOneRecord($c_where, '', 'name');
                if (!empty($c_name)) {
                    $v['c_name'] = $c_name['name'];
                }

                $d_where = ['=', 'id', $shop_name['district']];
                $d_name = $district->getOneRecord($d_where, '', 'name');
                if (!empty($d_name)) {
                    $v['d_name'] = $d_name['name'];
                }
                $v['shop_name'] = $shop_name['shop_name'];
            }
            $info[] = $v;
        }

        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $page_size]);//分页

        $data = array(
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count,
            'data' => $info,
        );
        return $this->render('activity-view', $data);
    }

    /**
     * 商家活动列表
     * @author sunsong<sunsongsong@iyangpin.com>
     * @return array
     */
    public function actionActivityShop()
    {
        $page       = RequestHelper::get('page', 1, 'intval');
        $id       = RequestHelper::get('id', 0, 'intval');
        $name       = RequestHelper::get('name', '', 'trim');
        $pageSize   = 10;
        $date = date("Y-m-d H:i:s", time());
        $activity = new ShopActivity();
        $shop = new Shop();
        $condition = ['>', 'id', 0];
        $filed = "id,name,subtitle,type,shop_id,start_time,
                  end_time,stop_remark,describe,images,status,meet_amount,sort,is_verify";
        $desc = "id desc";
        $list = $activity->getPageList($condition, $filed, $desc, $page, $pageSize);
        if (empty($list)) {
            return $this->error('暂无活动！！！', '/activity/index');
        }
        $info = array();
        foreach ($list as $v) {
            $s_where = ['=', 'id', $v['shop_id']];
            $shop_name = $shop->getOneRecord($s_where, '', 'shop_name');
            if (empty($shop_name)) {
                $shop_name['shop_name'] = "";
            }
            $v['shop_name'] = $shop_name['shop_name'];
            if ($v['start_time'] > $date) {
                $v['status_name'] = "未开始";
                $v['key'] = 1;
            }
            if ($v['start_time'] < $date && $v['end_time'] > $date) {
                $v['status_name'] = "进行中";
            }
            if ($v['end_time'] < $date) {
                $v['status_name'] = "已结束";
            }
            $info[] = $v;
        }

        $count       = $activity->getCount($condition);

        $page_count = $count;
        if ($count > 0 && $pageSize > 0) {
            $page_count = ceil($count / $pageSize);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        $data = array(
            'id' => $id,
            'name' => $name,
            'data' => $info,
            'count' => $count,
            'pages'=> $pages,
            'page_count' => $page_count
        );
        return $this->render('shopactivity', $data);
    }

    /**
     * 商家活动详情
     * @author sunsong<sunsongsong@iyangpin.com>
     * @return array
     */
    public function actionLookShop()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $shop_id = RequestHelper::get('shopid', 0, 'intval');
        $pageSize   = 10;
        $page       = RequestHelper::get('page', 1, 'intval');
        $where = [];
        if ($id != 0 && $shop_id != 0) {
            $where = ['and', ['=', 'activity_id', $id], ['=', 'shop_id', $shop_id]];
        }

        $act_shop = new ShopActivityProduct();
        $gift = new ShopActivityGift();
        $activit = new ShopActivity();
        $act_where = ['=', 'id', $id];
        $act_type = $activit->getOneRecord($act_where, '', 'type');
        $z_list = $gift->getList($where, '*', 'id desc');
        //活动商品查询
        $list = $act_shop->getPageList($where, '*', 'id desc', $page, $pageSize);

        $count = $act_shop->getCount($where);
        $page_count = $count;
        if ($count > 0 && $pageSize > 0) {
            $page_count = ceil($count / $pageSize);
        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);

        if (empty($list)) {
            $list = array();
        }
        if (empty($z_list)) {
            $z_list = array();
        }
        $data = array(
            'data' => $list,
            'data_z' => $z_list,
            'id' => $id,
            'shop_id' => $shop_id,
            'pages' => $pages,
            'count' => $count,
            'page_count' => $page_count,
            'type' => $act_type['type']
        );

        return $this->render('shopview', $data);
    }

    /**
     * 审核商家活动
     * @author sunsong<sunsongsong@iyangpin.com>
     * @return array
     */
    public function actionCheck()
    {
        $is_verify = RequestHelper::post('check', 0, 'intval');
        $id = RequestHelper::post('act_id', 0, 'intval');
        $shop_id = RequestHelper::post('shop_id', 0, 'intval');
        $center = RequestHelper::post('center', '', 'trim');
        $act_shop = new ShopActivity();

        if ($is_verify == 1 && !empty($center)) {
            $data['is_verify'] = $is_verify;
            $data['reason'] = $center;
            $where = ['and', ['=', 'id', $id], ['=', 'shop_id', $shop_id]];
            $info = $act_shop->updateOneRecord($where, '', $data);
            if ($info['result'] == 0) {
                return $this->error("审核提交不正确！！！", "/goods/activity/activity-shop");
            }
            return $this->success("审核提交成功！！！", "/goods/activity/activity-shop");
        }
        if ($is_verify == 2) {
            $data['is_verify'] = $is_verify;
            $data['reason'] = "";
            $where = ['and', ['=', 'id', $id], ['=', 'shop_id', $shop_id]];
            $info = $act_shop->updateOneRecord($where, '', $data);
            if ($info['result'] == 0) {
                return $this->error("审核提交不正确！！！", "/goods/activity/activity-shop");
            }
            return $this->success("审核提交成功！！！", "/goods/activity/activity-shop");
        }
        return $this->error("提交数据不正确！！！", "/goods/activity/activity-shop");
    }
}


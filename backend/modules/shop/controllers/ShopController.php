<?php
/**
 * 商家相关功能控制器
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/20 16:41
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


namespace backend\modules\shop\controllers;


use yii;
use common\helpers\RequestHelper;
use common\helpers\FastDFSHelper;
use backend\controllers\BaseController;
use backend\models\i500m\Shop;
use backend\models\i500m\Province;
use backend\models\i500m\City;
use backend\models\i500m\Log;
use backend\models\i500m\District;
use backend\models\i500m\ManageType;
use backend\models\i500m\CrmBranch;
use backend\models\i500m\Business;
use backend\models\shop\ShopContract;
use yii\data\Pagination;

//use alexgx\phpexcel\PhpExcel;


/**
 * 商家相关功能控制器
 *
 * @category ADMIN
 * @package  CONTROLLER
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class ShopController extends BaseController
{
    private $_default_page_size = 10;

    //private $_default_arr_where = array();
    private $_default_arr_where_param = array();
    private $_default_str_andwhere = '';
    //private $_default_arr_andwhere_param = array();
    private $_default_arr_order = array();
    private $_default_str_field = '*';
    private $_default_int_offset = -1;
    private $_default_int_limit = -1;

    private $_global_province_id = 35;
    private $_global_city_id = 999;


    /**
     * Action之前的处理
     *
     * //z20150422 关闭csrf
     *
     * Author zhengyu@iyangpin.com
     *
     * @param \yii\base\Action $action action
     *
     * @return bool
     *
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * 预处理
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        if (!$this->admin_id) {
            $this->redirect('/site/login');
            \Yii::$app->end();
            return;
        }

        $this->_global_province_id = intval($this->quanguo_province_id);
        $this->_global_city_id = intval($this->quanguo_city_id);

        $this->province_id = intval($this->province_id);
        //var_dump($this->province_id);
        //echo "<pre>";print_r($this->city_id);echo "</pre>";exit;
        $this->is_head_company = intval($this->is_head_company);
        $this->bc_id = intval($this->bc_id);
    }


    /**
     * 列表页面
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionIndex()
    {
        //echo "actionIndex";exit;
        //echo $this->render('index', array());return;
        //var_dump($this->city_id);exit;

        $id = RequestHelper::get('id', 0, 'intval');
        $province = RequestHelper::get('province', 0, 'intval');
        $city = RequestHelper::get('city', 0, 'intval');
        $district = RequestHelper::get('district', 0, 'intval');
        $manage_type = RequestHelper::get('manage_type', 0, 'intval');
        $status = RequestHelper::get('status', -1, 'intval');//状态(0、删除 1、禁用 2、正常)
        $business_status = RequestHelper::get('business_status', -1, 'intval');//营业状态，默认1营业，2打烊
        $workflow = RequestHelper::get('workflow', -1, 'intval');//激活步骤 1 2 3 4
        $business_id = RequestHelper::get('business_id', 0, 'intval');

        $like_shop_name = RequestHelper::get('like_shop_name', '', 'trim');
        $like_mobile = RequestHelper::get('like_mobile', '', 'trim');
        $like_phone = RequestHelper::get('like_phone', '', 'trim');
        $like_contact_name = RequestHelper::get('like_contact_name', '', 'trim');

        $page_num = RequestHelper::get('page', 1, 'intval');
        $page_size = RequestHelper::get('page_size', $this->_default_page_size, 'intval');

        $branch_province_id = $this->province_id;
        $arr_branch_city_list = $this->city_id;
        if ($branch_province_id != $this->_global_province_id) {
            $province = $branch_province_id;
        }
        if (!in_array($this->_global_city_id, $arr_branch_city_list)) {
            if (!in_array($city, $arr_branch_city_list)) {
                $city = 0;
            }
        }

        //z20150513 补充逻辑
        if ($province == 0) {
            $city = 0;
            $district = 0;
        }
        if ($city == 0) {
            $district = 0;
        }


        $arr_select_param = array();
        $arr_where = array();
        if ($id > 0) {
            $arr_where['id'] = $id;
            $arr_select_param['id'] = $id;
        } else {
            $arr_select_param['id'] = '';
        }
        if ($province > 0) {
            $arr_where['province'] = $province;
            $arr_select_param['province'] = $province;
        } else {
            $arr_select_param['province'] = '';
        }
        if ($city > 0) {
            $arr_where['city'] = $city;
            $arr_select_param['city'] = $city;
        } else {
            if (!in_array($this->_global_city_id, $arr_branch_city_list)) {
                $arr_where['city'] = $arr_branch_city_list;
            }
            $arr_select_param['city'] = '';
        }
        if ($district > 0) {
            $arr_where['district'] = $district;
            $arr_select_param['district'] = $district;
        } else {
            $arr_select_param['district'] = '';
        }
        if ($manage_type > 0) {
            $arr_where['manage_type'] = $manage_type;
            $arr_select_param['manage_type'] = $manage_type;
        } else {
            $arr_select_param['manage_type'] = '';
        }
        if ($business_id > 0) {
            $arr_where['business_id'] = $business_id;
            $arr_select_param['business_name'] = '';
        } else {
            $arr_select_param['business_name'] = '';
        }
        if (in_array($status, array(0, 1, 2))) {
            $arr_where['status'] = $status;
            $arr_select_param['status'] = $status;
        } else {
            $arr_select_param['status'] = -1;
        }
        if (in_array($business_status, array(1, 2))) {
            $arr_where['business_status'] = $business_status;
            $arr_select_param['business_status'] = $business_status;
        } else {
            $arr_select_param['business_status'] = -1;
        }
        if (in_array($workflow, array(1, 2, 3, 4))) {
            $arr_where['workflow'] = $workflow;
            $arr_select_param['workflow'] = $workflow;
        } else {
            $arr_select_param['workflow'] = -1;
        }
        //$arr_where['status'] = 2;
        //echo "<pre>";print_r($arr_where);echo "</pre>";exit;

        $arr_andwhere = array();
        $arr_andwhere_param = array();
        if ('' != $like_shop_name) {
            //$arr_andwhere[] = " shop_name like '%" . $like_shop_name . "%' ";

            $arr_andwhere[] = " shop_name like :shop_name ";
            $arr_andwhere_param[':shop_name'] = "%" . $like_shop_name . "%";

            $arr_select_param['like_shop_name'] = $like_shop_name;
        } else {
            $arr_select_param['like_shop_name'] = '';
        }
        if ('' != $like_contact_name) {
            $arr_andwhere[] = " contact_name like :contact_name ";
            $arr_andwhere_param[':contact_name'] = "%" . $like_contact_name . "%";

            $arr_select_param['like_contact_name'] = $like_contact_name;
        } else {
            $arr_select_param['like_contact_name'] = '';
        }
        if ('' != $like_mobile) {
            //$arr_andwhere[] = " mobile like '%" . $like_mobile . "%' ";
            $arr_andwhere[] = " mobile like :mobile ";
            $arr_andwhere_param[':mobile'] = "%" . $like_mobile . "%";
            $arr_select_param['like_mobile'] = $like_mobile;
        } else {
            $arr_select_param['like_mobile'] = '';
        }
        if ('' != $like_phone) {
            //$arr_andwhere[] = " phone like '%" . $like_phone . "%' ";
            $arr_andwhere[] = " phone like :phone ";
            $arr_andwhere_param[':phone'] = "%" . $like_phone . "%";
            $arr_select_param['like_phone'] = $like_phone;
        } else {
            $arr_select_param['like_phone'] = '';
        }
        $str_andwhere = implode(" and ", $arr_andwhere);


        if ($page_num <= 0) {
            $page_num = 1;
        }
        if ($page_size <= 0) {
            $page_size = $this->_default_page_size;
        }
        $int_offset = ($page_num - 1) * $page_size;
        $int_limit = $page_size;

        $str_field = '*';
        $arr_order = array(
            'id' => SORT_DESC
        );
        //echo "<pre>arr_where=";print_r($arr_where);echo "</pre>";exit;

        $model_shop = new Shop();
        $model_province = new Province();
        $model_city = new City();
        $model_district = new District();
        $model_managetype = new ManageType();
        $model_business = new Business();

        //列表
        $arr_shop_list = $model_shop->getRecordList(
            $arr_where,
            $this->_default_arr_where_param,
            $str_andwhere,
            $arr_andwhere_param,
            $arr_order,
            $str_field,
            $int_offset,
            $int_limit
        );
        //echo "<pre>arr_shop_list=";print_r($arr_shop_list);echo "</pre>";exit;

        //$arr_shop_list = $model_shop->getList2(
        //    $arr_where,
        //    $str_andwhere,
        //    $arr_order,
        //    $str_field,
        //    $int_offset,
        //    $int_limit
        //);
        //echo "<pre>arr_shop_list=";print_r($arr_shop_list);echo "</pre>";exit;


        if (!empty($arr_shop_list)) {
            $str_field = 'count(*) as num';
            $arr_count = $model_shop->getRecordListCount(
                $arr_where,
                array(),
                $str_andwhere,
                $arr_andwhere_param,
                $str_field
            );
            //echo "<pre>";print_r($arr_count);echo "</pre>";exit;
            $record_count = $arr_count['num'];
        } else {
            $record_count = 0;
        }
        //echo "record_count=".$record_count;exit;

        $pages = new Pagination(['totalCount' => $record_count, 'pageSize' => $page_size]);


        //当前页面记录中涉及到的省市区 start
        $arr_related_province = array();
        $arr_related_city = array();
        $arr_related_district = array();
        $arr_related_business = array();
        foreach ($arr_shop_list as $a_shop) {
            $arr_related_province[] = $a_shop['province'];
            $arr_related_city[] = $a_shop['city'];
            $arr_related_district[] = $a_shop['district'];
            $arr_related_business[] = $a_shop['business_id'];
        }

        $arr_where = array('id' => $arr_related_province);
        $str_field = 'id,name';
        $arr_tmp = $model_province->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $map_related_province = array();
        foreach ($arr_tmp as $a_row) {
            $map_related_province[$a_row['id']] = $a_row['name'];
        }
        //echo "<pre>";print_r($map_related_province);echo "</pre>";exit;

        $arr_where = array('id' => $arr_related_city);
        $str_field = 'id,name';
        $arr_tmp = $model_city->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $map_related_city = array();
        foreach ($arr_tmp as $a_row) {
            $map_related_city[$a_row['id']] = $a_row['name'];
        }
        //echo "<pre>";print_r($map_related_city);echo "</pre>";exit;

        $arr_where = array('id' => $arr_related_district);
        $str_field = 'id,name';
        $arr_tmp = $model_district->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $map_related_district = array();
        foreach ($arr_tmp as $a_row) {
            $map_related_district[$a_row['id']] = $a_row['name'];
        }
        //echo "<pre>";print_r($map_related_district);echo "</pre>";exit;
        //当前页面记录中涉及到的省市区 end
        $arr_where = array('id' => $arr_related_business);
        $str_field = 'id,name';
        $arr_tmp = $model_business->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $map_related_business = array();
        foreach ($arr_tmp as $a_row) {
            $map_related_business[$a_row['id']] = $a_row['name'];
        }




        //筛选条件界面用省市区 start
        //  获取全部省  //z20150525m 改为获取已开通的省
        //$arr_where = array('status' => 1);
        //$str_field = 'id,name';
        //$arr_order = array('sort' => SORT_ASC);
        //$arr_select_province = $model_province->getList2(
        //    $arr_where,
        //    $this->_default_str_andwhere,
        //    $arr_order,
        //    $str_field,
        //    $this->_default_int_offset,
        //    $this->_default_int_limit
        //);
        $arr_select_province = $model_shop->getOpenProvince();
        //echo "<pre>";print_r($arr_select_province);echo "</pre>";exit;
        //  获取当前筛选条件的市 //z20150525m 改为获取已开通的城市
        $arr_cur_select_city_list = array();
        if ($province > 0) {
            //$arr_where = array('province_id' => $province);
            //$str_field = 'id,name';
            //$arr_order = array('sort' => SORT_ASC);
            //$arr_cur_select_city_list = $model_city->getList2(
            //    $arr_where,
            //    $this->_default_str_andwhere,
            //    $arr_order,
            //    $str_field,
            //    $this->_default_int_offset,
            //    $this->_default_int_limit
            //);

            $arr_cur_select_city_list = $model_shop->getOpenP2C($province);
        }
        //echo "<pre>";print_r($arr_cur_select_city_list);echo "</pre>";exit;
        //  获取当前筛选条件的区县
        $arr_cur_select_district_list = array();
        if ($city > 0) {
            $arr_where = array('city_id' => $city);
            $str_field = 'id,name';
            $arr_order = array('sort' => SORT_ASC);
            $arr_cur_select_district_list = $model_district->getList2(
                $arr_where,
                $this->_default_str_andwhere,
                $arr_order,
                $str_field,
                $this->_default_int_offset,
                $this->_default_int_limit
            );
        }
        //echo "<pre>";print_r($arr_cur_select_district_list);echo "</pre>";exit;
        //筛选条件界面用省市区 end
        //获取业务员
        $arr_select_business = array();
        if ($business_id > 0) {
            $arr_select_business = $model_business->getOneRecord(
                array('id' => $business_id),
                $this->_default_str_andwhere,
                'id,name'
            );
            $arr_select_param['business_name'] = $arr_select_business['name'];
        }


        //获取店铺类型（经营种类） start
        $arr_where = array();
        $str_field = 'id,name';
        $arr_order = array('sort' => SORT_ASC);
        $arr_tmp = $model_managetype->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $map_all_manage_type = array();
        foreach ($arr_tmp as $value) {
            $map_all_manage_type[$value['id']] = $value['name'];
        }
        //echo "<pre>";print_r($map_all_manage_type);echo "</pre>";exit;

        $arr_where = array('status' => 2);
        $arr_select_manage_type = $model_managetype->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>";print_r($arr_select_manage_type);echo "</pre>";exit;
        //获取店铺类型（经营种类） end




        //状态(0、删除 1、禁用 2、正常)
        $map_status = array(
            '0' => '删除',
            '1' => '禁用',
            '2' => '正常',
        );
        //营业状态，默认1营业，2打烊
        $map_business_status = array(
            '1' => '营业',
            '2' => '打烊'
        );


        //分公司数据过滤
        if ($branch_province_id != $this->_global_province_id) {
            foreach ($arr_select_province as $tmp_key => $tmp_value) {
                if (isset($tmp_value['id']) && (intval($tmp_value['id']) != $branch_province_id)) {
                    unset($arr_select_province[$tmp_key]);
                }
            }
        }
        if (!in_array($this->_global_city_id, $arr_branch_city_list)) {
            foreach ($arr_cur_select_city_list as $tmp_key => $tmp_value) {
                if (isset($tmp_value['id']) && !in_array(intval($tmp_value['id']), $arr_branch_city_list)) {
                    unset($arr_cur_select_city_list[$tmp_key]);
                }
            }
        }


        $arr_view_data = array(
            'arr_select_param' => $arr_select_param,
            'arr_shop_list' => $arr_shop_list,
            'record_count' => $record_count,
            'pages' => $pages,
            'map_related_province' => $map_related_province,
            'map_related_city' => $map_related_city,
            'map_related_district' => $map_related_district,
            'arr_select_province' => $arr_select_province,
            'arr_cur_select_city_list' => $arr_cur_select_city_list,
            'arr_cur_select_district_list' => $arr_cur_select_district_list,
            'map_all_manage_type' => $map_all_manage_type,
            'arr_select_manage_type' => $arr_select_manage_type,
            'map_status' => $map_status,
            'map_business_statusist' => $map_business_status,
            'branch_province_id' => $branch_province_id,
            'arr_branch_city_list' => $arr_branch_city_list,
            'map_related_business' => $map_related_business,
            'arr_select_business' => $arr_select_business,
        );
        echo $this->render('index', $arr_view_data);
        return;
    }

    /**
     * 通过省获取市
     *
     * Author zhengyu@iyangpin.com
     * get方式
     *   open=1=通过已开通的数据获取
     *
     * @return void
     */
    public function actionP2c()
    {
        $province_id = RequestHelper::get('pid', 0, 'intval');
        $open = RequestHelper::get('open', 0, 'intval');
        if ($province_id <= 0) {
            echo json_encode(array());
            return;
        }

        if ($open == 0) {
            $model_city = new City();
            $arr_where = array('province_id' => $province_id);
            $str_field = 'id,name';
            $arr_order = array('sort' => SORT_ASC);
            $arr_city_list = $model_city->getList2(
                $arr_where,
                $this->_default_str_andwhere,
                $arr_order,
                $str_field,
                $this->_default_int_offset,
                $this->_default_int_limit
            );
        } else {
            $model_shop = new Shop();
            $arr_city_list = $model_shop->getOpenP2C($province_id);
        }
        //echo "<pre>";print_r($arr_city_list);echo "</pre>";exit;


        //分公司数据过滤
        $arr_branch_city_list = $this->city_id;
        if (!in_array($this->_global_city_id, $arr_branch_city_list)) {
            foreach ($arr_city_list as $tmp_key => $tmp_value) {
                if (isset($tmp_value['id']) && !in_array(intval($tmp_value['id']), $arr_branch_city_list)) {
                    unset($arr_city_list[$tmp_key]);
                }
            }
        }
        $arr_city_list = array_values($arr_city_list);

        echo json_encode($arr_city_list);
        return;
    }

    /**
     * 通过市获取区县
     *
     * Author zhengyu@iyangpin.com
     * get方式
     *
     * @return void
     */
    public function actionC2d()
    {
        $city_id = RequestHelper::get('cid', 0, 'intval');
        if ($city_id <= 0) {
            echo json_encode(array());
            return;
        }

        $model_district = new District();
        $arr_where = array('city_id' => $city_id);
        $str_field = 'id,name';
        $arr_order = array('sort' => SORT_ASC);
        $arr_district_list = $model_district->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );

        echo json_encode($arr_district_list);
        return;
    }

    /**
     * 删除商家
     *
     * Author zhengyu@iyangpin.com
     * get方式
     * //z20150520m 效果改为禁用
     *
     * @return void
     */
    public function actionDel()
    {
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        if ($shop_id <= 0) {
            echo "01";
            return;
        }

        $branch_province_id = $this->province_id;
        $arr_branch_city_list = $this->city_id;

        $model_shop = new Shop();
        $arr_where = array('id' => $shop_id);
        if ($branch_province_id != $this->_global_province_id) {
            $arr_where['province'] = $branch_province_id;
        }
        $str_andwhere = '';
        if (!in_array($this->_global_city_id, $arr_branch_city_list)) {
            $tmp_str = implode(",", $arr_branch_city_list);
            $str_andwhere = " city in (" . $tmp_str . ")";
        }
        //$arr_set = array('status' => 0);
        $arr_set = array('status' => 1);//z20150520m 效果改为禁用
        $arr_result = $model_shop->updateOneRecord($arr_where, $str_andwhere, $arr_set);
        if ($arr_result['result'] == 1) {
            echo "1";
            return;
        } else {
            echo "02";
            return;
        }
    }

    /**
     * 修改页面
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionEdit()
    {
        $shop_id = RequestHelper::get('id', 0, 'intval');
        if ($shop_id <= 0) {
            echo "参数错误";
            return;
        }

        $model_shop = new Shop();
        $model_province = new Province();
        //$model_city = new City();
        $model_district = new District();
        $model_managetype = new ManageType();
        $model_crmbranch = new CrmBranch();
        $model_business = new Business();

        //商家详情
        $arr_where = array('id' => $shop_id);
        $arr_shop_info = $model_shop->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_str_field
        );
        //echo "<pre>arr_shop_info=";print_r($arr_shop_info);echo "</pre>";exit;


        $branch_province_id = $this->province_id;//当前登录帐号分公司对应的省
        $arr_branch_city_list = $this->city_id;//当前登录帐号 分公司关联的全部市
        //分公司权限判断
        if (!in_array($this->_global_city_id, $arr_branch_city_list) && !in_array($arr_shop_info['city'], $arr_branch_city_list)) {
            $this->redirect("/shop/shop/index");
            return;
        }


        $is_admin_bc = 0;//当前登录帐号是否是分公司
        //全部开通的省
        if ($branch_province_id != $this->_global_province_id) {
            //分公司帐号登录
            $is_admin_bc = 1;
            //$arr_all_province_list = $model_province->getList2(
            //    array('id' => $arr_shop_info['province']),
            //    $this->_default_str_andwhere,
            //    $this->_default_arr_order,
            //    'id,name',
            //    $this->_default_int_offset,
            //    $this->_default_int_limit
            //);
        } else {
            //$arr_all_province_list = $model_shop->getOpenProvince();
        }
        $arr_all_province_list = $model_province->getList2(
            array('id' => $arr_shop_info['province']),
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            'id,name',
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>";print_r($arr_all_province_list);echo "</pre>";exit;

        //此省全部开通的市
        $arr_cur_city_list = array();
        $province_id = isset($arr_shop_info['province']) ? intval($arr_shop_info['province']) : 0;
        if ($province_id > 0) {
            $arr_cur_city_list = $model_shop->getOpenP2C($province_id);
        }
        //echo "<pre>";print_r($arr_cur_city_list);echo "</pre>";exit;

        //此市全部开通的区
        $arr_cur_district_list = array();
        $city_id = isset($arr_shop_info['city']) ? intval($arr_shop_info['city']) : 0;
        if ($city_id > 0) {
            $arr_where = array('city_id'=> $city_id);
            $str_field = 'id,name';
            $arr_order = array('sort' => SORT_ASC);
            $arr_cur_district_list = $model_district->getList2(
                $arr_where,
                $this->_default_str_andwhere,
                $arr_order,
                $str_field,
                $this->_default_int_offset,
                $this->_default_int_limit
            );
        }
        //echo "<pre>";print_r($arr_cur_district_list);echo "</pre>";exit;

        //全部店铺类型列表
        $arr_where = array('status'=> 2);
        $str_field = 'id,name';
        $arr_order = array('sort' => SORT_ASC);
        $arr_all_manage_type_list = $model_managetype->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>";print_r($arr_all_manage_type_list);echo "</pre>";exit;

        //全部分公司列表
        if ($branch_province_id != $this->_global_province_id) {
            //分公司帐号登录
            $arr_where = array('id' => $this->bc_id);
            $str_field = 'id,name';
            $arr_all_branch_list = $model_crmbranch->getList2(
                $arr_where,
                $this->_default_str_andwhere,
                $this->_default_arr_order,
                $str_field,
                $this->_default_int_offset,
                $this->_default_int_limit
            );
        } else {
            $arr_where = array('status' => 1);
            $str_field = 'id,name';
            $arr_order = array('sort' => SORT_ASC);
            $arr_all_branch_list = $model_crmbranch->getList2(
                $arr_where,
                $this->_default_str_andwhere,
                $arr_order,
                $str_field,
                $this->_default_int_offset,
                $this->_default_int_limit
            );
        }
        //echo "<pre>";print_r($arr_all_branch_list);echo "</pre>";exit;

        //业务员信息
        $arr_where = array('id' => $arr_shop_info['business_id']);
        $str_field = 'id,name';
        $arr_cur_business = $model_business->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        //echo "<pre>";print_r($arr_cur_business);echo "</pre>";exit;


        //状态(0、删除 1、禁用 2、正常) key有序
        $map_status = array(
            '1' => '禁用',
            //'0' => '删除',
            '2' => '正常',
        );
        //营业状态，默认1营业，2打烊
        $map_business_status = array(
            '1' => '营业',
            '2' => '打烊'
        );
        //z20150806 如果合同状态是未生效，营业状态只有打烊
        $model_shop_contract = new ShopContract();
        $arr_contract_info = $model_shop_contract->getOneRecord(
            array('htnumber' => $arr_shop_info['htnumber']),
            $this->_default_str_andwhere,
            'status'
        );
        if (!$arr_contract_info
            || (isset($arr_contract_info['status']) && $arr_contract_info['status'] == '0')
        ) {
            $map_business_status = array('2' => '打烊');
        }

        //是否是自提点 0否 1是  key有序
        $map_take_point = array(
            '1' => '是',
            '0' => '否'
        );

        //介绍字符串处理
        if (isset($arr_shop_info['introduction'])) {
            //过滤html、js、css   数据库中存的是已转实体的html标签
            //echo $arr_shop_info['introduction'];exit;
            $arr_shop_info['introduction'] = htmlspecialchars_decode($arr_shop_info['introduction']);
            //echo $arr_shop_info['introduction'];exit;
            $arr_shop_info['introduction'] = stripslashes($arr_shop_info['introduction']);
            //echo $arr_shop_info['introduction'];exit;
            $arr_shop_info['introduction'] = trim($arr_shop_info['introduction']);
            $arr_shop_info['introduction'] = strip_tags($arr_shop_info['introduction']);
            //echo $arr_shop_info['introduction'];exit;
            $arr_shop_info['introduction'] = preg_replace(
                '/<script[^>]*?>(.*?)<\/script>/si',
                '',
                $arr_shop_info['introduction']
            );
            $arr_shop_info['introduction'] = preg_replace(
                '/<style[^>]*?>(.*?)<\/style>/si',
                '',
                $arr_shop_info['introduction']
            );
            $arr_replace = array(
                "\t" => ' ',
                "\r\n" => ' ',
                "\r" => ' ',
                "\n" => ' ',
            );
            $arr_shop_info['introduction'] = strtr($arr_shop_info['introduction'], $arr_replace);
            $arr_shop_info['introduction'] = trim($arr_shop_info['introduction']);
            //echo $arr_shop_info['introduction'];exit;
        }
        //echo $arr_shop_info['introduction'];exit;


        //分公司帐号 相关数据过滤
        //  分公司过滤

        //  省列表过滤
        if ($branch_province_id != $this->_global_province_id) {
            foreach ($arr_all_province_list as $tmp_key => $tmp_value) {
                if (isset($tmp_value['id']) && $arr_shop_info['province'] != $tmp_value['id'] && (intval($tmp_value['id']) != $branch_province_id)) {
                    unset($arr_all_province_list[$tmp_key]);
                }
            }
        }
        $arr_all_province_list = array_values($arr_all_province_list);

        $arr_branch_city_list = $this->city_id;
        if (!in_array($this->_global_city_id, $arr_branch_city_list)) {
            foreach ($arr_cur_city_list as $tmp_key => $tmp_value) {
                if (isset($tmp_value['id']) && $arr_shop_info['city'] != $tmp_value['id'] && !in_array(intval($tmp_value['id']), $arr_branch_city_list)) {
                    unset($arr_cur_city_list[$tmp_key]);
                }
            }
        }
        $arr_cur_city_list = array_values($arr_cur_city_list);


        $arr_view_data = array(
            'arr_shop_info' => $arr_shop_info,
            'arr_all_province_list' => $arr_all_province_list,
            'arr_cur_city_list' => $arr_cur_city_list,
            'arr_cur_district_list' => $arr_cur_district_list,
            'arr_all_manage_type_list' => $arr_all_manage_type_list,
            'map_status' => $map_status,
            'map_business_status' => $map_business_status,
            'map_take_point' => $map_take_point,
            'arr_all_branch_list' => $arr_all_branch_list,
            'arr_cur_business' => $arr_cur_business,
            'is_admin_bc' => $is_admin_bc,
        );
        echo $this->render('edit', $arr_view_data);
        return;
    }

    /**
     * 添加页面
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionAdd()
    {
        $model_province = new Province();
        $model_managetype = new ManageType();
        $model_crmbranch = new CrmBranch();
        //$model_shop = new Shop();



        $arr_where = array('status'=> 2);
        $str_field = 'id,name';
        $arr_order = array('sort' => SORT_ASC);
        $arr_all_manage_type_list = $model_managetype->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>";print_r($arr_all_manage_type_list);echo "</pre>";exit;

        $arr_where = array('status'=> 1);
        $str_field = 'id,name,province_id';
        $arr_order = array('sort' => SORT_ASC);
        $arr_all_branch_list = $model_crmbranch->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>";print_r($arr_all_branch_list);echo "</pre>";exit;

        //状态(0、删除 1、禁用 2、正常) key有序
        $map_status = array(
            '1' => '禁用',
            //'0' => '删除',
            '2' => '正常',
        );
        //营业状态，默认1营业，2打烊
        $map_business_status = array(
            '1' => '营业',
            '2' => '打烊'
        );
        //是否是自提点 0否 1是  key有序
        $map_take_point = array(
            '1' => '是',
            '0' => '否'
        );


        $branch_province_id = $this->province_id;

        //$arr_all_province_list = $model_shop->getOpenProvince();
        //echo "<pre>";print_r($arr_all_province_list);echo "</pre>";exit;

        //分公司数据过滤
        //  过滤省
        //if ($branch_province_id != $this->_global_province_id) {
        //    foreach ($arr_all_province_list as $tmp_key => $tmp_value) {
        //        if (isset($tmp_value['id']) && (intval($tmp_value['id']) != $branch_province_id)) {
        //            unset($arr_all_province_list[$tmp_key]);
        //        }
        //    }
        //}
        //$arr_all_province_list = array_values($arr_all_province_list);

        $arr_all_province_list = array();
        //echo "<pre>";print_r($arr_all_province_list);echo "</pre>";exit;
        //  过滤分公司
        $cur_admin_bcid = 0;//当前登录者的分公司id
        $cur_admin_pid = 0;//当前登录者的分公司省id
        if ($branch_province_id != $this->_global_province_id) {
            foreach ($arr_all_branch_list as $tmp_key => $tmp_value) {
                if (isset($tmp_value['province_id']) && (intval($tmp_value['province_id']) != $branch_province_id)) {
                    unset($arr_all_branch_list[$tmp_key]);
                }
            }
            $arr_all_branch_list = array_values($arr_all_branch_list);
            if (isset($arr_all_branch_list[0])
                && isset($arr_all_branch_list[0]['id']) && isset($arr_all_branch_list[0]['province_id'])
            ) {
                $cur_admin_bcid = intval($arr_all_branch_list[0]['id']);
                $cur_admin_pid = intval($arr_all_branch_list[0]['province_id']);
                $arr_all_province_list = $model_province->getList2(
                    array('id' => $cur_admin_pid),
                    $this->_default_str_andwhere,
                    $this->_default_arr_order,
                    'id,name',
                    $this->_default_int_offset,
                    $this->_default_int_limit
                );
            }
        }
        //echo "<pre>";print_r($arr_all_branch_list);echo "</pre>";exit;

        //如果是分公司帐号，确定了分公司、省，同时也能获取到市
        $arr_city_list = array();
        $arr_district_list = array();
        if ($cur_admin_pid > 0) {
            $model_city = new City();
            $arr_where = array('province_id' => $cur_admin_pid);
            $arr_order = array('sort' => SORT_ASC);
            $str_field = 'id,name';
            $arr_city_list = $model_city->getList2(
                $arr_where,
                $this->_default_str_andwhere,
                $arr_order,
                $str_field,
                $this->_default_int_offset,
                $this->_default_int_limit
            );
            //echo "<pre>";print_r($arr_city_list);echo "</pre>";exit;

            //如果市数据只有一个，则可以获取区数据
            if (sizeof($arr_city_list) == 1
                && isset($arr_city_list[0]['id']) && $arr_city_list[0]['id'] > 0
            ) {
                $mode_district = new District();
                $arr_where = array('city_id' => $arr_city_list[0]['id']);
                $arr_order = array('sort' => SORT_ASC);
                $str_field = 'id,name';
                $arr_district_list = $mode_district->getList2(
                    $arr_where,
                    $this->_default_str_andwhere,
                    $arr_order,
                    $str_field,
                    $this->_default_int_offset,
                    $this->_default_int_limit
                );
                //echo "<pre>";print_r($arr_district_list);echo "</pre>";exit;
            }
        }


        $arr_view_data = array(
            'arr_all_province_list' => $arr_all_province_list,
            'arr_all_manage_type_list' => $arr_all_manage_type_list,
            'map_status' => $map_status,
            'map_business_status' => $map_business_status,
            'map_take_point' => $map_take_point,
            'arr_all_branch_list' => $arr_all_branch_list,
            'cur_admin_bcid' => $cur_admin_bcid,
            'cur_admin_pid' => $cur_admin_pid,
            'arr_city_list' => $arr_city_list,
            'arr_district_list' => $arr_district_list,
        );
        echo $this->render('add', $arr_view_data);
        return;
    }

    /**
     * 详情页面
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionDetail()
    {
        $shop_id = RequestHelper::get('id', 0, 'intval');
        if ($shop_id <= 0) {
            echo "参数错误";
            return;
        }

        $model_shop = new Shop();
        $model_province = new Province();
        $model_city = new City();
        $model_district = new District();
        $model_managetype = new ManageType();
        $model_crmbranch = new CrmBranch();
        $model_business = new Business();

        //商家详情
        $arr_where = array('id' => $shop_id);
        $arr_shop_info = $model_shop->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_str_field
        );
        //echo "<pre>arr_shop_info=";print_r($arr_shop_info);echo "</pre>";exit;


        //分公司权限
        $branch_province_id = $this->province_id;
        $arr_branch_city_list = $this->city_id;
        if ($branch_province_id != $this->_global_province_id && intval($arr_shop_info['province']) != $branch_province_id) {
            $this->redirect("/shop/shop/index");
            return;
        }
        if (!in_array($this->_global_city_id, $arr_branch_city_list) && !in_array(intval($arr_shop_info['city']), $arr_branch_city_list)) {
            $this->redirect("/shop/shop/index");
            return;
        }


        $arr_where = array('id' => $arr_shop_info['province']);
        $str_field = 'id,name';
        $arr_cur_province = $model_province->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        //echo "<pre>";print_r($arr_cur_province);echo "</pre>";exit;

        $arr_where = array('id' => $arr_shop_info['city']);
        $str_field = 'id,name';
        $arr_cur_city = $model_city->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        //echo "<pre>";print_r($arr_cur_city);echo "</pre>";exit;

        $arr_where = array('id' => $arr_shop_info['district']);
        $str_field = 'id,name';
        $arr_cur_district = $model_district->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        //echo "<pre>";print_r($arr_cur_district);echo "</pre>";exit;

        $arr_where = array('id' => $arr_shop_info['manage_type']);
        $str_field = 'id,name';
        $arr_cur_manage_type = $model_managetype->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        //echo "<pre>";print_r($arr_cur_manage_type);echo "</pre>";exit;

        $arr_where = array('id' => $arr_shop_info['branch_id']);
        $str_field = 'id,name';
        $arr_cur_branch = $model_crmbranch->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        //echo "<pre>";print_r($arr_cur_branch);echo "</pre>";exit;

        $arr_where = array('id' => $arr_shop_info['business_id']);
        $str_field = 'id,name';
        $arr_cur_business = $model_business->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        //echo "<pre>";print_r($arr_cur_business);echo "</pre>";exit;




        //状态(0、删除 1、禁用 2、正常) key有序
        $map_status = array(
            '1' => '禁用',
            '0' => '删除',
            '2' => '正常',
        );
        //营业状态，默认1营业，2打烊
        $map_business_status = array(
            '1' => '营业',
            '2' => '打烊'
        );
        //是否是自提点 0否 1是  key有序
        $map_take_point = array(
            '1' => '是',
            '0' => '否'
        );
        //是否是i500专营,默认0=否,1=是
        $map_is_i500 = array(
            '0' => '否',
            '1' => '是',
        );

        //介绍字符串处理
        if (isset($arr_shop_info['introduction'])) {
            //过滤html、js、css   数据库中存的是已转实体的html标签
            //echo $arr_shop_info['introduction'];exit;
            $arr_shop_info['introduction'] = htmlspecialchars_decode($arr_shop_info['introduction']);
            //echo $arr_shop_info['introduction'];exit;
            $arr_shop_info['introduction'] = stripslashes($arr_shop_info['introduction']);
            //echo $arr_shop_info['introduction'];exit;
            $arr_shop_info['introduction'] = trim($arr_shop_info['introduction']);
            $arr_shop_info['introduction'] = strip_tags($arr_shop_info['introduction']);
            //echo $arr_shop_info['introduction'];exit;
            $arr_shop_info['introduction'] = preg_replace(
                '/<script[^>]*?>(.*?)<\/script>/si',
                '',
                $arr_shop_info['introduction']
            );
            $arr_shop_info['introduction'] = preg_replace(
                '/<style[^>]*?>(.*?)<\/style>/si',
                '',
                $arr_shop_info['introduction']
            );
            $arr_replace = array(
                "\t" => ' ',
                "\r\n" => ' ',
                "\r" => ' ',
                "\n" => ' ',
            );
            $arr_shop_info['introduction'] = strtr($arr_shop_info['introduction'], $arr_replace);
            $arr_shop_info['introduction'] = trim($arr_shop_info['introduction']);
            //echo $arr_shop_info['introduction'];exit;
        }
        //echo $arr_shop_info['introduction'];exit;


        //z20150806 如果关联了合同，显示银行账户信息
        if ($arr_shop_info['htnumber'] != '') {
            $model_shop_contract = new ShopContract();
            $arr_contract_info = $model_shop_contract->getOneRecord(
                array('htnumber' => $arr_shop_info['htnumber']),
                $this->_default_str_andwhere,
                'bank_name,bank_branch,bank_number,bankcard_username'
            );
            if (!empty($arr_contract_info)
                && isset($arr_contract_info['bank_name'])
                && isset($arr_contract_info['bank_branch'])
                && isset($arr_contract_info['bank_number'])
                && isset($arr_contract_info['bankcard_username'])
            ) {
                $arr_shop_info['bank_name'] = $arr_contract_info['bank_name'];
                $arr_shop_info['bank_branch'] = $arr_contract_info['bank_branch'];
                $arr_shop_info['bank_number'] = $arr_contract_info['bank_number'];
                $arr_shop_info['bankcard_username'] = $arr_contract_info['bankcard_username'];
            }
        }
        //echo "<pre>";print_r($arr_shop_info);echo "</pre>";exit;


        $arr_view_data = array(
            'arr_shop_info' => $arr_shop_info,
            'arr_cur_province' => $arr_cur_province,
            'arr_cur_city' => $arr_cur_city,
            'arr_cur_district' => $arr_cur_district,
            'arr_cur_manage_type' => $arr_cur_manage_type,
            'map_status' => $map_status,
            'map_business_status' => $map_business_status,
            'map_take_point' => $map_take_point,
            'arr_cur_branch' => $arr_cur_branch,
            'arr_cur_business' => $arr_cur_business,
            'map_is_i500' => $map_is_i500,
        );
        echo $this->render('detail', $arr_view_data);
        return;
    }


    /**
     * Ajax操作
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionAjax()
    {
        $act = RequestHelper::post('act', '', 'trim');
        if ($act == 'edit') {
            $this->_editSubmit();
            return;
        }
        if ($act == 'add') {
            $this->_addSubmit();
            return;
        }
        if ($act == 'uploadimg') {
            $this->_uploadImg();
            return;
        }
        if ($act == 'getsalesman') {
            $this->_getSalesman();
            return;
        }
        if ($act == 'select_branch') {
            $this->_selectBranch();
            return;
        }
        if ($act == 'select_province') {
            $this->_selectProvince();
            return;
        }
        if ($act == 'select_city') {
            $this->_selectCity();
            return;
        }
    }

    /**
     * 提交修改操作
     *
     * Author zhengyu@iyangpin.com
     *
     * @return string
     */
    private function _editSubmit()
    {
        $id = RequestHelper::post('id', 0, 'intval');
        $status = RequestHelper::post('status', -1, 'intval');//状态(0、删除 1、禁用 2、正常)
        $business_status = RequestHelper::post('business_status', 0, 'intval');//营业状态，默认1营业，2打烊
        $takepoint = RequestHelper::post('takepoint', -1, 'intval');//是否是自提点 0否 1是
        $manage_type = RequestHelper::post('manage_type', 0, 'intval');
        $province = RequestHelper::post('province', 0, 'intval');
        $city = RequestHelper::post('city', 0, 'intval');
        $district = RequestHelper::post('district', 0, 'intval');
        $branch_id = RequestHelper::post('branch_id', 0, 'intval');
        $is_i500 = RequestHelper::post('is_i500', 0, 'intval');
        $business_id = RequestHelper::post('business_id', 0, 'intval');

        $position_x = RequestHelper::post('position_x', '', 'floatval');
        $position_y = RequestHelper::post('position_y', '', 'floatval');
        $sent_fee = RequestHelper::post('sent_fee', '', 'floatval');
        $free_money = RequestHelper::post('free_money', '', 'floatval');
        $freight = RequestHelper::post('freight', '', 'floatval');

        $shop_name = RequestHelper::post('shop_name', '', 'trim');
        $contact_name = RequestHelper::post('contact_name', '', 'trim');
        $email = RequestHelper::post('email', '', 'trim');
        $mobile = RequestHelper::post('mobile', '', 'trim');
        $phone = RequestHelper::post('phone', '', 'trim');
        $address = RequestHelper::post('address', '', 'trim');
        $hours = RequestHelper::post('hours', '', 'trim');
        $hours_remarks = RequestHelper::post('hours_remarks', '', 'trim');
        $introduction = RequestHelper::post('introduction', '', 'trim');
        $logo = RequestHelper::post('logo', '', 'trim');
        $alipay = RequestHelper::post('alipay', '', 'trim');
        //$chinapay = RequestHelper::post('chinapay', '', 'trim');
        //$account_name = RequestHelper::post('account_name', '', 'trim');
        //$bank_deposit = RequestHelper::post('bank_deposit', '', 'trim');
        //$bank_deposit_branch = RequestHelper::post('bank_deposit_branch', '', 'trim');
        $password = RequestHelper::post('password', '', 'trim');
        $htnumber = RequestHelper::post('htnumber', '', 'trim');

        $arr_where = array();
        if ($id <= 0) {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:缺少必要参数'));
            return;
        } else {
            $arr_where['id'] = $id;
        }


        //分公司权限
        $branch_province_id = $this->province_id;
        $arr_branch_city_list = $this->city_id;
        if ($branch_province_id != $this->_global_province_id && $province != $branch_province_id) {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:无权限修改为此省'));
            return;
        }
        if (!in_array($this->_global_city_id, $arr_branch_city_list) && !in_array($city, $arr_branch_city_list)) {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:无权限修改为此市'));
            return;
        }


        $arr_set = array();
        //status //状态(0、删除 1、禁用 2、正常)
        //business_status //营业状态，默认1营业，2打烊
        //takepoint //是否是自提点 0否 1是
        if (in_array($status, array(0, 1, 2))) {
            $arr_set['status'] = $status;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(状态)'));
            return;
        }
        if (in_array($business_status, array(1, 2))) {
            $arr_set['business_status'] = $business_status;

            //z20150806 如果合同状态是未生效，营业状态只有打烊
            if ($htnumber == '') {
                $arr_set['business_status'] = 2;
            } else {
                $model_shop_contract = new ShopContract();
                $arr_contract_info = $model_shop_contract->getOneRecord(
                    array('htnumber' => $htnumber),
                    $this->_default_str_andwhere,
                    'status'
                );
                if (!$arr_contract_info
                    || (isset($arr_contract_info['status']) && $arr_contract_info['status'] == '0')
                ) {
                    $arr_set['business_status'] = 2;
                }
            }
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(营业状态)'));
            return;
        }
        if (in_array($takepoint, array(0, 1))) {
            $arr_set['takepoint'] = $takepoint;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(自提点)'));
            return;
        }
        if (in_array($is_i500, array(0, 1))) {
            $arr_set['is_i500'] = $is_i500;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(i500专营)'));
            return;
        }
        if ($manage_type > 0) {
            $arr_set['manage_type'] = $manage_type;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(店铺类型)'));
            return;
        }
        if ($province > 0) {
            $arr_set['province'] = $province;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(省)'));
            return;
        }
        if ($city > 0) {
            $arr_set['city'] = $city;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(市)'));
            return;
        }
        if ($district > 0) {
            $arr_set['district'] = $district;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(区)'));
            return;
        }
        if ($branch_id > 0) {
            $arr_set['branch_id'] = $branch_id;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(分公司)'));
            return;
        }
        if ($business_id > 0) {
            $arr_set['business_id'] = $business_id;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(业务员)'));
            return;
        }


        //z20150519a 检查分公司专营商家唯一性
        if ($is_i500 == 1) {
            $int_exist_id = $this->_checkBranchShopExist($arr_set['branch_id']);
            if ($int_exist_id > 0 && $int_exist_id != $id) {
                echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '添加失败:此分公司已设置i500专营商家(id=' . $int_exist_id . ')'));
                return;
            }
        }



        if ($position_x != '') {
            $arr_set['position_x'] = $position_x;
        }
        if ($position_y != '') {
            $arr_set['position_y'] = $position_y;
        }
        if ($sent_fee != '') {
            $arr_set['sent_fee'] = $sent_fee;
        }
        if ($free_money != '') {
            $arr_set['free_money'] = $free_money;
        }
        if ($freight != '') {
            $arr_set['freight'] = $freight;
        }

        if ($shop_name != '') {
            $arr_set['shop_name'] = $shop_name;
        }
        if ($contact_name != '') {
            $arr_set['contact_name'] = $contact_name;
        }
        if ($email != '') {
            $arr_set['email'] = $email;
        }
        if ($mobile != '') {
            $arr_set['mobile'] = $mobile;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(手机号)'));
            return;
        }
        if ($phone != '') {
            $arr_set['phone'] = $phone;
        }
        if ($address != '') {
            $arr_set['address'] = $address;
        }
        if ($hours != '') {
            $arr_set['hours'] = $hours;
        }
        if ($hours_remarks != '') {
            $arr_set['hours_remarks'] = $hours_remarks;
        }
        if ($introduction != '') {
            $arr_set['introduction'] = $introduction;
        }
        if ($logo != '') {
            $arr_set['logo'] = $logo;
        }
        if ($alipay != '') {
            $arr_set['alipay'] = $alipay;
        }
        if ($htnumber != '') {
            $arr_set['htnumber'] = $htnumber;
        }
        //if ($chinapay != '') {
        //    $arr_set['chinapay'] = $chinapay;
        //} else {
        //    echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(银联卡号)'));
        //    return;
        //}
        //if ($account_name != '') {
        //    $arr_set['account_name'] = $account_name;
        //} else {
        //    echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(户名)'));
        //    return;
        //}
        //if ($bank_deposit != '') {
        //    $arr_set['bank_deposit'] = $bank_deposit;
        //} else {
        //    echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(开户行)'));
        //    return;
        //}
        //if ($bank_deposit_branch != '') {
        //    $arr_set['bank_deposit_branch'] = $bank_deposit_branch;
        //} else {
        //    echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:参数错误(开户行支行信息)'));
        //    return;
        //}
        if ($password != '') {
            $salt = $this->_generateRandomString(6);
            $password = md5($salt . md5($password));
            $arr_set['salt'] = $salt;
            $arr_set['password'] = $password;
        }

        //字符型预处理
        //htmlspecialchars(addslashes
        $arr_string_field = array(
            'shop_name', 'contact_name', 'email', 'mobile', 'phone',
            'address', 'hours', 'hours_remarks', 'introduction', 'logo',
            'alipay', 'chinapay', 'account_name', 'bank_deposit', 'bank_deposit_branch',
            'htnumber'
        );
        foreach ($arr_set as $key => $value) {
            if (in_array($key, $arr_string_field)) {
                $arr_set[$key] = $this->_baseStringFilter($value);
                $arr_set[$key] = htmlspecialchars(addslashes($arr_set[$key]));
            }
        }
        //echo "<pre>";print_r($arr_set);echo "</pre>";exit;

        //其他字段处理
        $arr_set['update_time'] = time();

        $model_shop = new Shop();
        $arr_result = $model_shop->updateOneRecord($arr_where, $this->_default_str_andwhere, $arr_set);
        if (1 == $arr_result['result']) {
            //z20150520a 如果成功，调用任仪能的一个接口
            $arr_get = array('shop_id' => $arr_where['id']);
            $arr_tmp = $model_shop->apiShopSync($arr_get);
            if (!is_array($arr_tmp)) {
                $arr_tmp = array('apiShopSync error');
            }

            //日志
            $account_time = date("Y-m-d H:i:s", time());
            $log = new Log();
            $log_info = '管理员 ' . \Yii::$app->user->identity->username . '在【商家管理】中修改了id为' . $id . '的商家信息' . $account_time;
            $log->recordLog($log_info, 2);

            echo json_encode(array('code' => '200', 'data' => array($arr_tmp), 'msg' => ''));
            return;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '修改操作失败'));
            return;
        }
    }

    /**
     * 提交添加操作
     *
     * Author zhengyu@iyangpin.com
     *
     * @return string
     */
    private function _addSubmit()
    {
        //$business_status = RequestHelper::post('business_status', 0, 'intval');//营业状态，默认1营业，2打烊
        $business_status = 2;//营业状态，默认1营业，2打烊//z20150616添加时固定打烊
        $takepoint = RequestHelper::post('takepoint', -1, 'intval');//是否是自提点 0否 1是
        $manage_type = RequestHelper::post('manage_type', 0, 'intval');
        $province = RequestHelper::post('province', 0, 'intval');
        $city = RequestHelper::post('city', 0, 'intval');
        $district = RequestHelper::post('district', 0, 'intval');
        $branch_id = RequestHelper::post('branch_id', 0, 'intval');
        $business_id = RequestHelper::post('business_id', 0, 'intval');
        $is_i500 = RequestHelper::post('is_i500', 0, 'intval');

        $position_x = RequestHelper::post('position_x', '', 'floatval');
        $position_y = RequestHelper::post('position_y', '', 'floatval');
        $sent_fee = RequestHelper::post('sent_fee', '', 'floatval');
        $free_money = RequestHelper::post('free_money', '', 'floatval');
        $freight = RequestHelper::post('freight', '', 'floatval');

        $shop_name = RequestHelper::post('shop_name', '', 'trim');
        $contact_name = RequestHelper::post('contact_name', '', 'trim');
        $email = RequestHelper::post('email', '', 'trim');
        $mobile = RequestHelper::post('mobile', '', 'trim');
        $phone = RequestHelper::post('phone', '', 'trim');
        $address = RequestHelper::post('address', '', 'trim');
        $hours = RequestHelper::post('hours', '', 'trim');
        $hours_remarks = RequestHelper::post('hours_remarks', '', 'trim');
        $introduction = RequestHelper::post('introduction', '', 'trim');
        $logo = RequestHelper::post('logo', '', 'trim');
        $alipay = RequestHelper::post('alipay', '', 'trim');
        //$chinapay = RequestHelper::post('chinapay', '', 'trim');
        //$account_name = RequestHelper::post('account_name', '', 'trim');
        //$bank_deposit = RequestHelper::post('bank_deposit', '', 'trim');
        //$bank_deposit_branch = RequestHelper::post('bank_deposit_branch', '', 'trim');
        $username = RequestHelper::post('username', '', 'trim');
        $password = RequestHelper::post('password', '', 'trim');
        $htnumber = RequestHelper::post('htnumber', '', 'trim');


        //分公司权限
        $branch_province_id = $this->province_id;
        $arr_branch_city_list = $this->city_id;
        if ($branch_province_id != $this->_global_province_id && $province != $branch_province_id) {
            //echo "04";//分公司无权限修改目标省
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:无权限添加此省数据'));
            return;
        }
        if (!in_array($this->_global_city_id, $arr_branch_city_list) && !in_array($city, $arr_branch_city_list)) {
            //echo "05";//分公司无权限修改为此目标市
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败:无权限添加此市数据'));
            return;
        }



        $arr_set = array();
        //status //状态(0、删除 1、禁用 2、正常)
        //business_status //营业状态，默认1营业，2打烊
        //takepoint //是否是自提点 0否 1是
        if (in_array($business_status, array(1, 2))) {
            $arr_set['business_status'] = $business_status;
        }
        if (in_array($takepoint, array(0, 1))) {
            $arr_set['takepoint'] = $takepoint;
        }
        if (in_array($is_i500, array(0, 1))) {
            $arr_set['is_i500'] = $is_i500;
        }
        if ($manage_type > 0) {
            $arr_set['manage_type'] = $manage_type;
        }
        if ($province > 0) {
            $arr_set['province'] = $province;
        }
        if ($city > 0) {
            $arr_set['city'] = $city;
        }
        if ($district > 0) {
            $arr_set['district'] = $district;
        }
        if ($branch_id > 0) {
            $arr_set['branch_id'] = $branch_id;
        }
        if ($business_id > 0) {
            $arr_set['business_id'] = $business_id;
        }


        if ($position_x != '') {
            $arr_set['position_x'] = $position_x;
        }
        if ($position_y != '') {
            $arr_set['position_y'] = $position_y;
        }
        if ($sent_fee != '') {
            $arr_set['sent_fee'] = $sent_fee;
        }
        if ($free_money != '') {
            $arr_set['free_money'] = $free_money;
        }
        if ($freight != '') {
            $arr_set['freight'] = $freight;
        }

        if ($shop_name != '') {
            $arr_set['shop_name'] = $shop_name;
        }
        if ($contact_name != '') {
            $arr_set['contact_name'] = $contact_name;
        }
        if ($email != '') {
            $arr_set['email'] = $email;
        }
        if ($mobile != '') {
            $arr_set['mobile'] = $mobile;
        }
        if ($phone != '') {
            $arr_set['phone'] = $phone;
        }
        if ($address != '') {
            $arr_set['address'] = $address;
        }
        if ($hours != '') {
            $arr_set['hours'] = $hours;
        }
        if ($hours_remarks != '') {
            $arr_set['hours_remarks'] = $hours_remarks;
        }
        if ($introduction != '') {
            $arr_set['introduction'] = $introduction;
        }
        if ($logo != '') {
            $arr_set['logo'] = $logo;
        }
        if ($alipay != '') {
            $arr_set['alipay'] = $alipay;
        }
        //if ($chinapay != '') {
        //    $arr_set['chinapay'] = $chinapay;
        //}
        //if ($account_name != '') {
        //    $arr_set['account_name'] = $account_name;
        //}
        //if ($bank_deposit != '') {
        //    $arr_set['bank_deposit'] = $bank_deposit;
        //}
        //if ($bank_deposit_branch != '') {
        //    $arr_set['bank_deposit_branch'] = $bank_deposit_branch;
        //}
        if ($username != '') {
            $arr_set['username'] = $username;
        }
        if ($password != '') {
            $arr_set['password'] = $password;//非最终
        }
        if ($htnumber != '') {
            $arr_set['htnumber'] = $htnumber;
        }


        $model_shop = new Shop();


        //检查不可缺少的字段是否缺少
        $arr_require_field = array(
            'username', 'shop_name', 'password', 'manage_type', 'province',
            'city', 'district', 'business_id', 'takepoint', 'branch_id',
            'contact_name', 'phone', 'position_x', 'position_y', 'mobile'
        );
        $arr_cur_set_field = array_keys($arr_set);
        foreach ($arr_require_field as $value) {
            if (!in_array($value, $arr_cur_set_field)) {
                //echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '缺少必要字段'));
                echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '缺少必要字段:' . $value));
                return;
            }
        }


        //z20150519a 检查分公司专营商家唯一性
        if ($is_i500 == 1) {
            $int_exist_id = $this->_checkBranchShopExist($arr_set['branch_id']);
            if ($int_exist_id > 0) {
                echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '添加失败:此分公司已设置i500专营商家(id=' . $int_exist_id . ')'));
                return;
            }
        }


        //检查唯一性 username
        $arr_where = array('username' => $arr_set['username']);
        $arr_row = $model_shop->getOneRecord($arr_where, $this->_default_str_andwhere, $str_field = 'id');
        if (!empty($arr_row)) {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '此用户名已存在'));
            return;
        }

        //部分字段数据格式限制 //ztodo

        //密码处理
        $salt = $this->_generateRandomString(6);
        $password = md5($salt . md5($password));
        $arr_set['salt'] = $salt;
        $arr_set['password'] = $password;

        //额外字段处理
        $now = time();
        $arr_set['create_time'] = $now;
        $arr_set['update_time'] = $now;
        $arr_set['status'] = 1;//状态(0、删除 1、禁用 2、正常) //z20150819新建商家为禁用


        //字符型预处理
        //htmlspecialchars(addslashes
        $arr_string_field = array(
            'username', 'password', 'shop_name', 'contact_name', 'email',
            'mobile', 'phone', 'address', 'hours', 'hours_remarks',
            'introduction', 'logo', 'alipay', 'chinapay', 'account_name',
            'bank_deposit', 'bank_deposit_branch', 'htnumber'
        );
        foreach ($arr_set as $key => $value) {
            if (in_array($key, $arr_string_field)) {
                $arr_set[$key] = $this->_baseStringFilter($value);
                $arr_set[$key] = htmlspecialchars(addslashes($arr_set[$key]));
            }
        }


        $arr_result = $model_shop->insertOneRecord($arr_set);
        if (1 == $arr_result['result']) {
            //z20150519a 如果成功，调用任仪能的一个接口
            $arr_get = array('shop_id' => $arr_result['data']['new_id']);
            $arr_tmp = $model_shop->apiShopSync($arr_get);
            $arr_tmp2 = $model_shop->apiShopSyncAddAllCategory($arr_get);
            if (!is_array($arr_tmp)) {
                $arr_tmp = array('apiShopSync error');
            }
            if (!is_array($arr_tmp2)) {
                $arr_tmp2 = array('apiShopSyncAddAllCategory error');
            }

            //日志
            $account_time = date("Y-m-d H:i:s", time());
            $log = new Log();
            $log_info = '管理员 ' . \Yii::$app->user->identity->username . '在【商家管理】中添加了id为' . $arr_result['data']['new_id'] . '的商家信息' . $account_time;
            $log->recordLog($log_info, 2);

            echo json_encode(array('code' => '200', 'data' => array($arr_tmp, $arr_tmp2), 'msg' => ''));
            return;
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => '操作失败'));
            return;
        }
    }

    /**
     * 生成随机字符串
     *
     * Author zhengyu@iyangpin.com
     *
     * @param int $length 长度
     *
     * @return string
     */
    private function _generateRandomString($length = 1)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str_return = '';
        for ($i = 0; $i < $length; $i++) {
            $str_return .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str_return;
    }


    /**
     * 上传返回
     *
     * Author zhengyu@iyangpin.com
     *
     * @param int    $type 类型
     * @param string $msg  数据
     *
     * @return void
     */
    private function _outputUploadResult($type = 0, $msg = '')
    {
        $str_return = "<script"
            ." type='text/javascript'>"
            . "parent.upload_return(" . $type . ",'" . $msg . "');"
            . '</script>';
        //return $str_return;
        echo  $str_return;
        return;
    }

    /**
     * 上传图片
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    private function _uploadImg()
    {
        //echo "<pre>";print_r($_FILES);echo "</pre>";exit;
        //$this->_outputUploadResult(1,'test');return;
        //set_time_limit(0);
        //ini_set('memory_limit', '256M');

        $map_upload_error = array(
            1 => '上传文件的大小超过了php.ini中upload_max_filesize选项设定的值',
            2 => '上传文件的大小超过了浏览器 MAX_FILE_SIZE选项设定的值',
            3 => '文件只有部分被上传',
            4 => '没有文件被上传'
        );
        $file_size_1_m = 1024 * 1024;
        $max_file_size = 1 * $file_size_1_m;//上传文件体积限制


        if ($_FILES['z_input_file']['error'] > 0) {
            //由文件上传导致的错误代码
            $this->_outputUploadResult(0, '上传失败：' . $map_upload_error[$_FILES['z_input_file']['error']]);
            return;
        }

        $upload_file_name = $_FILES['z_input_file']['name'];
        //$arr_split_file_name = explode('.', $upload_file_name);
        //$sizeArrSplitFileName = sizeof($arr_split_file_name);
        //$file_type = strtolower($arr_split_file_name[$sizeArrSplitFileName - 1]);
        $file_type = pathinfo($upload_file_name, PATHINFO_EXTENSION);
        $file_type = strtolower($file_type);
        //上传文件的扩展名//need
        $type_is_allow = in_array($file_type, array('jpg', 'jpeg', 'png', 'gif', 'bmp'));
        if ($type_is_allow === false) {
            //类型不对，结束//扩展名不对
            //$this->_outputUploadResult(0, '上传文件失败：上传的文件类型不正确');
            //return;
        }

        //$upload_file_mimetype = $_FILES['z_input_file']['type'];
        //$arr_img_info = array();
        try {
            $arr_img_info = getimagesize($_FILES["z_input_file"]["tmp_name"]);
        } catch (\Exception $e) {
            $this->_outputUploadResult(0, '上传失败：文件不是图片');
            return;
        }
        $upload_file_mimetype = $arr_img_info['mime'];
        $arr_mimetype_allow = array(
            'image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png',
            'image/bmp'
        );
        if (!in_array($upload_file_mimetype, $arr_mimetype_allow)) {
            //类型不对，结束//扩展名与文件不符
            $this->_outputUploadResult(0, '上传失败：文件类型不正确');
            return;
        }

        //检测文件大小
        $upload_file_size = $_FILES['z_input_file']['size'];
        if ($upload_file_size > $max_file_size) {
            //大小不对，结束
            $this->_outputUploadResult(0, '上传失败：体积过大(大于' . $max_file_size / $file_size_1_m . 'M)');
            return;
        }


        $instance_fsatdfs = new FastDFSHelper();
        $arr_result = $instance_fsatdfs->fdfs_upload('z_input_file');

        //echo "<pre>";var_dump($arr_result);echo "</pre>";exit;

        if (isset($arr_result['group_name']) && isset($arr_result['filename'])) {
            $this->_outputUploadResult(1, json_encode($arr_result));
        } else {
            $this->_outputUploadResult(0, '上传失败:error=uploadimg_1');
        }

        return;
    }



    /**
     * 获取业务员列表
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    private function _getSalesman()
    {
        $keyword = RequestHelper::post('kw', '', 'trim');

        $arr_where = array();
        $arr_where['status'] = 1;

        if ($this->is_head_company != 1) {
            $arr_where['bc_id'] = $this->bc_id;
        }

        $str_andwhere = '';
        $arr_andwhere_param = array();
        if (ctype_digit(strval($keyword)) && intval($keyword) > 0) {
            $arr_where['id'] = $keyword;
        } elseif ($keyword != '') {
            $arr_andwhere = array();
            //$arr_andwhere[] = " name like '%" . $keyword . "%' ";
            $arr_andwhere[] = " name like :name ";
            $arr_andwhere_param[':name'] = "%" . $keyword . "%";
            $str_andwhere = implode(" and ", $arr_andwhere);
        } else {
            echo json_encode(array('code' => '400', 'data' => array(), 'msg' => ''));
            return;
        }

        $str_field = 'id,name';
        $arr_order = array(
            'id' => SORT_DESC
        );

        $model_business = new Business();

        //列表
        $arr_salesman_list = $model_business->getRecordList(
            $arr_where,
            $this->_default_arr_where_param,
            $str_andwhere,
            $arr_andwhere_param,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>arr_salesman_list=";print_r($arr_salesman_list);echo "</pre>";exit;

        $arr_data = array(
            'data' => $arr_salesman_list,
            'info' => array(),
        );
        echo json_encode(array('code' => '200', 'data' => $arr_data, 'msg' => ''));
        return;
    }


    /**
     * 基础字符串过滤
     *
     * Author zhengyu@iyangpin.com
     *
     * @param string $str 待过滤字符串
     *
     * @return string
     */
    private function _baseStringFilter($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/<script[^>]*?>(.*?)<\/script>/si', '', $str);
        $str = preg_replace('/<style[^>]*?>(.*?)<\/style>/si', '', $str);
        $arr_replace = array(
            "\t" => ' ',
            "\r\n" => ' ',
            "\r" => ' ',
            "\n" => ' ',
        );
        $str = strtr($str, $arr_replace);
        $str = trim($str);

        return $str;
    }


    /**
     * 检查指定分公司是否有500m专营商家
     *
     * Author zhengyu@iyangpin.com
     * z20150519注 不考虑status字段，避免修改status字段的其他功能不考虑is_i500限制
     *
     * @param int $branch_id 分公司id
     *
     * @return int -1=不存在 0=参数错误 >0=shop_id
     */
    private function _checkBranchShopExist($branch_id)
    {
        if ($branch_id == 0) {
            return 0;
        }

        $model_shop = new Shop();

        //商家详情
        $arr_where = array(
            'branch_id' => $branch_id,
            //'status' => 2,//状态(0、删除 1、禁用 2、正常)
            'is_i500' => 1,
        );
        $arr_shop_info = $model_shop->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_str_field
        );
        //echo "<pre>arr_shop_info=";print_r($arr_shop_info);echo "</pre>";exit;

        if (!empty($arr_shop_info) && isset($arr_shop_info['id'])) {
            return intval($arr_shop_info['id']);
        } else {
            return -1;
        }
    }


    /**
     * 选择分公司，返回对应省信息及对应市列表
     *
     * Author zhengyu@iyangpin.com
     * 注：考虑了当前登录帐号是否分公司问题
     *
     * @return void
     */
    private function _selectBranch()
    {
        $branch_id = RequestHelper::post('bid', 0, 'intval');
        $open = RequestHelper::post('open', 0, 'intval');
        if ($branch_id <= 0) {
            echo json_encode(array());
            return;
        }

        $model_shop = new Shop();
        $model_branch = new CrmBranch();
        $model_province = new Province();
        $model_city = new City();
        $model_district = new District();

        //获取分公司信息
        $arr_branch_info = $model_branch->getOneRecord(
            array('id' => $branch_id),
            $this->_default_str_andwhere,
            'id,name,province_id,city_id,city_id_arr'
        );

        //获取省信息
        if (!isset($arr_branch_info['province_id'])) {
            echo json_encode(array());
            return;
        }
        $arr_province_info = $model_province->getOneRecord(
            array('id' => $arr_branch_info['province_id']),
            $this->_default_str_andwhere,
            'id,name'
        );

        //获取省对应的城市列表
        if ($open == 0) {
            $arr_where = array('province_id' => $arr_branch_info['province_id']);
            $str_field = 'id,name';
            $arr_order = array('sort' => SORT_ASC);
            $arr_city_list = $model_city->getList2(
                $arr_where,
                $this->_default_str_andwhere,
                $arr_order,
                $str_field,
                $this->_default_int_offset,
                $this->_default_int_limit
            );
        } else {
            $arr_city_list = $model_shop->getOpenP2C($arr_branch_info['province_id']);
        }
        //echo "<pre>";print_r($arr_city_list);echo "</pre>";exit;

        //分公司数据过滤
        $arr_branch_city_list = $this->city_id;
        if (!in_array($this->_global_city_id, $arr_branch_city_list)) {
            foreach ($arr_city_list as $tmp_key => $tmp_value) {
                if (isset($tmp_value['id']) && !in_array(intval($tmp_value['id']), $arr_branch_city_list)) {
                    unset($arr_city_list[$tmp_key]);
                }
            }
        }
        $arr_city_list = array_values($arr_city_list);


        $arr_district_list = array();
        if (sizeof($arr_city_list) == 1) {
            //如果市数量=1，获取全部开通的区
            $arr_district_list = array();
            $city_id = isset($arr_city_list[0]['id']) ? intval($arr_city_list[0]['id']) : 0;
            if ($city_id > 0) {
                $arr_where = array('city_id'=> $city_id);
                $str_field = 'id,name';
                $arr_order = array('sort' => SORT_ASC);
                $arr_district_list = $model_district->getList2(
                    $arr_where,
                    $this->_default_str_andwhere,
                    $arr_order,
                    $str_field,
                    $this->_default_int_offset,
                    $this->_default_int_limit
                );
            }
            //echo "<pre>";print_r($arr_district_list);echo "</pre>";exit;
        }


        $arr_return = array(
            'arr_p_info' => $arr_province_info,
            'arr_c_list' => $arr_city_list,
            'arr_d_list' => $arr_district_list
        );
        echo json_encode($arr_return);
        return;
    }

    /**
     * 选择省，返回对应市列表
     *
     * Author zhengyu@iyangpin.com
     * 注：本函数中不考虑当前登录帐号是否分公司问题
     *
     * @return void
     */
    private function _selectProvince()
    {
        $province_id = RequestHelper::post('pid', 0, 'intval');
        $open = RequestHelper::post('open', 0, 'intval');
        if ($province_id <= 0) {
            echo json_encode(array());
            return;
        }

        $model_shop = new Shop();
        $model_city = new City();
        $model_district = new District();

        //获取省对应的城市列表
        if ($open == 0) {
            $arr_where = array('province_id' => $province_id);
            $str_field = 'id,name';
            $arr_order = array('sort' => SORT_ASC);
            $arr_city_list = $model_city->getList2(
                $arr_where,
                $this->_default_str_andwhere,
                $arr_order,
                $str_field,
                $this->_default_int_offset,
                $this->_default_int_limit
            );
        } else {
            $arr_city_list = $model_shop->getOpenP2C($province_id);
        }
        //echo "<pre>";print_r($arr_city_list);echo "</pre>";exit;

        //分公司数据过滤
        $arr_branch_city_list = $this->city_id;
        if (!in_array($this->_global_city_id, $arr_branch_city_list)) {
            foreach ($arr_city_list as $tmp_key => $tmp_value) {
                if (isset($tmp_value['id']) && !in_array(intval($tmp_value['id']), $arr_branch_city_list)) {
                    unset($arr_city_list[$tmp_key]);
                }
            }
        }
        $arr_city_list = array_values($arr_city_list);


        $arr_district_list = array();
        if (sizeof($arr_city_list) == 1) {
            //如果市数量=1，获取全部开通的区
            $arr_district_list = array();
            $city_id = isset($arr_city_list[0]['id']) ? intval($arr_city_list[0]['id']) : 0;
            if ($city_id > 0) {
                $arr_where = array('city_id'=> $city_id);
                $str_field = 'id,name';
                $arr_order = array('sort' => SORT_ASC);
                $arr_district_list = $model_district->getList2(
                    $arr_where,
                    $this->_default_str_andwhere,
                    $arr_order,
                    $str_field,
                    $this->_default_int_offset,
                    $this->_default_int_limit
                );
            }
            //echo "<pre>";print_r($arr_district_list);echo "</pre>";exit;
        }


        $arr_return = array(
            'arr_c_list' => $arr_city_list,
            'arr_d_list' => $arr_district_list
        );
        echo json_encode($arr_return);
        return;
    }

    /**
     * 选择分公司，返回对应省信息及对应市列表
     *
     * Author zhengyu@iyangpin.com
     * 注：本函数中不考虑当前登录帐号是否分公司问题
     *
     * @return void
     */
    private function _selectCity()
    {
        $city_id = RequestHelper::post('cid', 0, 'intval');
        if ($city_id <= 0) {
            echo json_encode(array());
            return;
        }

        $model_district = new District();

        //获取省对应的区列表
        $arr_where = array('city_id' => $city_id);
        $str_field = 'id,name';
        $arr_order = array('sort' => SORT_ASC);
        $arr_district_list = $model_district->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>";print_r($arr_district_list);echo "</pre>";exit;

        $arr_return = array(
            'arr_d_list' => $arr_district_list
        );
        echo json_encode($arr_return);
        return;
    }



}

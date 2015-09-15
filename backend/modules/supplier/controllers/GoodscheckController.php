<?php
/**
 * 供应商商品审核
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/9/15 09:43
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


namespace backend\modules\supplier\controllers;


use yii;
use yii\data\Pagination;
use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use backend\models\i500m\Supplier;
use backend\models\i500m\SupplierGoods;
use backend\models\i500m\Category;
use backend\models\i500m\Province;


/**
 * 供应商商品审核
 *
 * @category ADMIN
 * @package  CONTROLLER
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class GoodscheckController extends BaseController
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
     * 关闭csrf
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

        $this->is_head_company = intval($this->is_head_company);//是否是总公司 0=否 1=是
        $this->bc_id = intval($this->bc_id);//当前登录帐号所属分公司
        $this->province_id = intval($this->province_id);//当前登录帐号所属分公司的省

    }


    /**
     * 列表页面
     *
     * Author zhengyu@iyangpin.com
     * z20150915注： 各环境都是64位的， int max=9E18=9223372036854775807，可覆盖13位的条形码
     *
     * @return void
     */
    public function actionIndex()
    {
        //echo "actionIndex";exit;
        //echo $this->render('index', array());return;
        //var_dump($this->city_id);exit;

        $cate_id = RequestHelper::get('cate_id', 0, 'intval');
        $bar_code = RequestHelper::get('bar_code', 0, 'intval');//见注释说明

        $like_goods_name = RequestHelper::get('like_goods_name', '', 'trim');

        $page_num = RequestHelper::get('page', 1, 'intval');
        $page_size = RequestHelper::get('page_size', $this->_default_page_size, 'intval');


        $arr_select_param = array();

        $arr_where = array();
        if ($cate_id > 0) {
            $arr_where['category_id'] = $cate_id;
            $arr_select_param['category_id'] = $cate_id;
        } else {
            $arr_select_param['category_id'] = '';
        }
        if ($bar_code > 0) {
            $arr_where['bar_code'] = $bar_code;
            $arr_select_param['bar_code'] = $bar_code;
        } else {
            $arr_select_param['bar_code'] = '';
        }
        $arr_where['status'] = 2;
        //echo "<pre>";print_r($arr_where);echo "</pre>";exit;

        $arr_andwhere = array();
        $arr_andwhere_param = array();
        if ('' != $like_goods_name) {
            $arr_andwhere[] = " title like :title ";
            $arr_andwhere_param[':title'] = "%" . $like_goods_name . "%";

            $arr_select_param['like_goods_name'] = $like_goods_name;
        } else {
            $arr_select_param['like_goods_name'] = '';
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

        //$str_field = '*';
        $str_field = 'id,title,supplier_id,category_id,image,bar_code,supply_price,selling_price';
        $arr_order = array(
            'id' => SORT_DESC
        );
        //echo "<pre>arr_where=";print_r($arr_where);echo "</pre>";exit;

        $model_sp_goods = new SupplierGoods();
        $model_sp = new Supplier();
        $model_cate = new Category();

        //列表
        $arr_goods_list = $model_sp_goods->getRecordList(
            $arr_where,
            $this->_default_arr_where_param,
            $str_andwhere,
            $arr_andwhere_param,
            $arr_order,
            $str_field,
            $int_offset,
            $int_limit
        );
        //echo "<pre>arr_goods_list=";print_r($arr_goods_list);echo "</pre>";exit;

        if (!empty($arr_goods_list)) {
            $str_field = 'count(*) as num';
            $arr_count = $model_sp_goods->getRecordListCount(
                $arr_where,
                $this->_default_arr_where_param,
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

        $arr_sp_id = array();
        $arr_cate_id = array();

        foreach ($arr_goods_list as $tmp_value) {
            $arr_cate_id[] = $tmp_value['category_id'];
            $arr_sp_id[] = $tmp_value['supplier_id'];
        }
        //echo "<pre>arr_sp_id=";print_r($arr_sp_id);echo "</pre>";exit;
        //echo "<pre>arr_cate_id=";print_r($arr_cate_id);echo "</pre>";exit;

        $arr_tmp = $model_sp->getList2(
            array('id' => $arr_sp_id),
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            'id,company_name,status',
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $map_sp_list = array();
        foreach ($arr_tmp as $tmp_value) {
            $map_sp_list[$tmp_value['id']] = $tmp_value;
        }
        //echo "<pre>map_sp_list=";print_r($map_sp_list);echo "</pre>";exit;

        $arr_tmp = $model_cate->getList2(
            array('id' => $arr_cate_id, 'level' => 1),
            $this->_default_str_andwhere,
            $this->_default_arr_order,
            'id,name,status',
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $map_cate_list = array();
        foreach ($arr_tmp as $tmp_value) {
            $map_cate_list[$tmp_value['id']] = $tmp_value;
        }
        //echo "<pre>map_cate_list=";print_r($map_cate_list);echo "</pre>";exit;


        $arr_all_cate = $model_cate->getList2(
            array('level' => 1, 'status' => 2),
            $this->_default_str_andwhere,
            array('id' => SORT_ASC),
            'id,name',
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>arr_all_cate=";print_r($arr_all_cate);echo "</pre>";exit;


        $arr_view_data = array(
            'arr_select_param' => $arr_select_param,
            'arr_goods_list' => $arr_goods_list,
            'pages' => $pages,
            'record_count' => $record_count,
            'map_sp_list' => $map_sp_list,
            'map_cate_list' => $map_cate_list,
            'arr_all_cate' => $arr_all_cate,
        );
        echo $this->render('index', $arr_view_data);
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

    }


}

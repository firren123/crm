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
use backend\models\i500m\SupplierGoodsLimit;
use backend\models\i500m\Category;
use backend\models\i500m\Province;
use backend\models\i500m\Product;


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
        $goods_id = RequestHelper::get('id', 0, 'intval');
        if ($goods_id <= 0) {
            echo "参数错误.";
            return;
        }

        $model_sp_goods = new SupplierGoods();
        $model_sp = new Supplier();
        $model_cate = new Category();
        $model_sp_goodslimit = new SupplierGoodsLimit();
        $model_province = new Province();
        $model_product = new Product();

        //商品详情
        $arr_goods_info = $model_sp_goods->getOneRecord(
            array('id' => $goods_id),
            $this->_default_str_andwhere,
            $this->_default_str_field
        );
        if (empty($arr_goods_info)) {
            echo "商品id错误.";
            return;
        }
        //echo "<pre>arr_goods_info=";print_r($arr_goods_info);echo "</pre>";exit;

        //检查此商品条形码是否在标准库已存在
        $existed_product_id = 0;
        $arr_product_info = $model_product->getOneRecord(
            array('bar_code' => $arr_goods_info['bar_code']),
            $this->_default_str_andwhere,
            'id'
        );
        if (isset($arr_product_info['id'])) {
            $existed_product_id = intval($arr_product_info['id']);
        }


        //供应商详情
        $arr_sp_info = $model_sp->getOneRecord(
            array('id' => $arr_goods_info['supplier_id']),
            $this->_default_str_andwhere,
            $this->_default_str_field
        );
        //echo "<pre>arr_sp_info=";print_r($arr_sp_info);echo "</pre>";exit;

        //获取分类名
        $arr_cate_info = $model_cate->getOneRecord(
            array('id' => $arr_goods_info['category_id']),
            $this->_default_str_andwhere,
            'id,name,status'
        );
        //echo "<pre>arr_cate_info=";print_r($arr_cate_info);echo "</pre>";exit;

        //获取销售限制地区
        $arr_tmp = $model_sp_goodslimit->getList2(
            array('goods_id' => $arr_goods_info['id']),
            $this->_default_str_andwhere,
            array('province_id' => SORT_ASC),
            'goods_id,province_id',
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $arr_province = array();
        foreach ($arr_tmp as $tmp_value) {
            $arr_province[] = $tmp_value['province_id'];
        }

        $arr_province_limit = $model_province->getList2(
            array('id' => $arr_province),
            $this->_default_str_andwhere,
            array('id' => SORT_ASC),
            'id,name',
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //echo "<pre>arr_province_limit=";print_r($arr_province_limit);echo "</pre>";exit;


        $arr_view_data = array(
            'arr_goods_info' => $arr_goods_info,
            'arr_sp_info' => $arr_sp_info,
            'arr_cate_info' => $arr_cate_info,
            'arr_province_limit' => $arr_province_limit,
            'existed_product_id' => $existed_product_id,
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
        $type = RequestHelper::get('type', '', 'trim');
        if ($type == 'pass') {
            $this->_passCheck();
            return;
        } elseif ($type == 'reject') {
            $this->_rejectCheck();
            return;
        } elseif ($type == 'xxxx') {
        } else {
        }
        return;
    }


    /**
     * 审核-通过
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    private function _passCheck()
    {
        echo json_encode(array('result' => 0, 'msg' => 'test操作失败(01).'));return;
        //echo json_encode(array('result' => 1, 'msg' => 'test操作成功.'));return;

        $goods_id = RequestHelper::post('goods_id', 0, 'intval');
        $jhj = RequestHelper::post('jhj', 0, 'floatval');
        $phj = RequestHelper::post('phj', 0, 'floatval');
        $is_cover = RequestHelper::post('is_cover', 0, 'intval');

        $model_sp_goods = new SupplierGoods();

        //修改商品状态
        $arr_result = $model_sp_goods->updateOneRecord(
            array('id' => $goods_id),
            $this->_default_str_andwhere,
            array('status' => 4, 'reason' => '')
        );
        if (!$arr_result || $arr_result['result'] == 0) {
            echo json_encode(array('result' => 0, 'msg' => '操作失败(01).'));
            return;
        }

        //获取供应商商品详情
        $arr_goods_info = $model_sp_goods->getOneRecord(
            array('id' => $goods_id),
            $this->_default_str_andwhere,
            $this->_default_str_field
        );

        $model_product = new Product();

        //同步到标准库
        //  检查此商品条形码是否在标准库已存在
        $existed_product_id = 0;
        $arr_product_info = $model_product->getOneRecord(
            array('bar_code' => $arr_goods_info['bar_code']),
            $this->_default_str_andwhere,
            'id'
        );
        $type = '';// ''=对于标准库无操作， insert=插入新记录  update=更新记录
        if (empty($arr_product_info)) {
            //此条形码在标准库 不存在
            $type = 'insert';
        } else {
            //此条形码在标准库 存在
            $existed_product_id = intval($arr_product_info['id']);
            if ($is_cover == 1) {
                $type = 'update';
            } else {
                //nothing  $type=0
            }
        }


        //标准库 与 供应商 商品属性对应关系
        $arr_data = array();
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];
        $arr_data['xxxx'] = $arr_goods_info['xxxx'];

        $arr_param = array();

        if ($type == 'insert') {
            $arr_data['sale_price'] = $jhj;//进货价
            $arr_data['shop_price'] = $phj;//铺货价

            $arr_result = $this->_insertStandard($arr_data, $arr_param);
            echo json_encode($arr_result);
            return;
        } elseif ($type == 'update') {
            $arr_param['existed_product_id'] = $existed_product_id;

            $arr_result = $this->_updateStandard($arr_data, $arr_param);
            echo json_encode($arr_result);
            return;
        } else {
            //nothing
        }

        echo json_encode(array('result' => 1, 'msg' => '操作成功.'));
        return;
    }


    /**
     * 向标准库插入新记录
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr_data  供应商商品数据，key是标准库的字段名
     * @param array $arr_param 其他参数
     *
     * @return array array('result'=>1/0,'data'=>array,'msg'=>string)
     */
    private function _insertStandard($arr_data, $arr_param = array())
    {
        $model_product = new Product();

        $arr_where = array();
        $arr_where['xxxx'] = $arr_param['xxxx'];


    }

    /**
     * 更新标准库记录
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr_data  供应商商品数据，key是标准库的字段名
     * @param array $arr_param 其他参数
     *
     * @return array array('result'=>1/0,'data'=>array,'msg'=>string)
     */
    private function _updateStandard($arr_data, $arr_param = array())
    {
        $model_product = new Product();

        $arr_where = array();
        $arr_where['xxxx'] = $arr_param['xxxx'];


    }

    /**
     * 审核-驳回
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    private function _rejectCheck()
    {
        echo json_encode(array('result' => 0, 'msg' => 'test操作失败(01).'));return;
        //echo json_encode(array('result' => 1, 'msg' => 'test操作成功.'));return;

        $goods_id = RequestHelper::post('goods_id', 0, 'intval');
        $reason = RequestHelper::post('reason', '', 'trim');

        $model_sp_goods = new SupplierGoods();

        //修改商品状态
        $arr_result = $model_sp_goods->updateOneRecord(
            array('id' => $goods_id),
            $this->_default_str_andwhere,
            array('status' => 3, 'reason' => $reason)
        );
        if (!$arr_result || $arr_result['result'] == 0) {
            echo json_encode(array('result' => 0, 'msg' => '操作失败(01).'));
            return;
        }

        echo json_encode(array('result' => 1, 'msg' => '操作成功.'));
        return;
    }


}

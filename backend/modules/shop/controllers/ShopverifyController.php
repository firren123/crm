<?php
/**
 * 新500m后台-商家审核相关功能控制器
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   BACKEND
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      2015-03-26 10:00
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\modules\shop\controllers;

use yii;
use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use backend\models\shop\ShopVerify;
use backend\models\i500m\Log;

//use backend\models\i500m\City;
//use backend\models\i500m\District;
//use backend\models\i500m\ManageType;
//use backend\models\i500m\Province;
use backend\models\i500m\Shop;
use backend\models\i500m\ShopCommunity;
use yii\data\Pagination;


/**
 * Class 商家相关功能控制器
 *
 * @category ADMIN
 * @package  SHOP
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class ShopverifyController extends BaseController
{

    private $_default_page_size = 10;


    //private $_default_arr_where = array();
    private $_default_str_andwhere = '';
    //private $_default_arr_order = array();
    private $_default_str_field = '*';
    //private $_default_int_offset = -1;
    //private $_default_int_limit = -1;


    /**
     * 申请列表页面
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionList()
    {
        //echo "actionEditVerifyList";exit;

        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $mobile = RequestHelper::get('mobile', 0, 'intval');
        $apply_time_start = RequestHelper::get('apply_time_start', '');
        $apply_time_end = RequestHelper::get('apply_time_end', '');
        //审核状态,默认=0=未审核,1=审核通过,2=审核驳回
        $verify_status = RequestHelper::get('verify_status', -1, 'intval');
        $province = RequestHelper::get('province', 0, 'intval');
        $city = RequestHelper::get('city', 0, 'intval');
        $district = RequestHelper::get('district', 0, 'intval');

        $page_num = RequestHelper::get('page', 0, 'intval');
        $page_size = RequestHelper::get('page_size', 0, 'intval');

        $arr_select_param = array();
        $arr_where = array();
        if ($shop_id > 0) {
            $arr_where['shop_id'] = $shop_id;
            $arr_select_param['shop_id'] = $shop_id;
        } else {
            $arr_select_param['shop_id'] = '';
        }
        if ($mobile > 0) {
            $arr_where['mobile'] = $mobile;
            $arr_select_param['mobile'] = $mobile;
        } else {
            $arr_select_param['mobile'] = '';
        }
        if ('' != $apply_time_start) {
            $arr_where['apply_time_start'] = $apply_time_start;
            $arr_select_param['apply_time_start'] = $apply_time_start;
        } else {
            $arr_select_param['apply_time_start'] = '';
        }
        if ('' != $apply_time_end) {
            $arr_where['apply_time_end'] = $apply_time_end;
            $arr_select_param['apply_time_end'] = $apply_time_end;
        } else {
            $arr_select_param['apply_time_end'] = '';
        }
        if (-1 != $verify_status) {
            $arr_where['verify_status'] = $verify_status;
            $arr_select_param['verify_status'] = $verify_status;
        } else {
            $arr_select_param['verify_status'] = '';
        }
        if (0 != $province) {
            $arr_where['province'] = $province;
            $arr_select_param['province'] = $province;
        } else {
            $arr_select_param['province'] = '';
        }
        if (0 != $city) {
            $arr_where['city'] = $city;
            $arr_select_param['city'] = $city;
        } else {
            $arr_select_param['city'] = '';
        }
        if (0 != $district) {
            $arr_where['district'] = $district;
            $arr_select_param['district'] = $district;
        } else {
            $arr_select_param['district'] = '';
        }

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
            'verify_apply_time' => SORT_DESC
        );

        $model = new ShopVerify();

        $arr_apply_list = $model->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $int_offset,
            $int_limit
        );
        //echo "<pre>arr_apply_list=";print_r($arr_apply_list);echo "</pre>";exit;

        if (!empty($arr_apply_list)) {
            $str_field = 'count(*) as num';
            $arr_count = $model->getListCount($arr_where, $this->_default_str_andwhere, $str_field);
            //echo "<pre>";print_r($arr_count);echo "</pre>";exit;
            $record_count = $arr_count['num'];
        } else {
            $record_count = 0;
        }
        //echo "record_count=".$record_count;exit;

        $pages = new Pagination(['totalCount' => $record_count, 'pageSize' => $page_size]);


        $map_verify_status = array(
            '0' => '待审核',
            '1' => '已通过',
            '2' => '已驳回'
        );

        $arr_view_data = array(
            //'arr_category_list' => $arr_category_list,
            'arr_list' => $arr_apply_list,
            'arr_select_param' => $arr_select_param,
            'pages' => $pages,
            'map_verify_status' => $map_verify_status,
        );
        echo $this->render('list', $arr_view_data);
        return;
    }


    /**
     * 申请详情展示页面
     *
     * Author zhengyu@iyangpin.com
     *
     * @return null
     */
    public function actionDetail()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        if ($id <= 0) {
            echo "参数错误";
            return;
        }

        $model = new ShopVerify();
        $model_shop = new Shop();

        //申请详情
        $arr_where = array('id' => $id);
        $arr_info = $model->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_str_field
        );
        //echo "<pre>arr_info=";print_r($arr_info);echo "</pre>";exit;


        //商家当前详情
        $arr_where = array('id' => $arr_info['shop_id']);
        $arr_shop_info = $model_shop->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_str_field
        );
        //echo "<pre>arr_shop_info=";print_r($arr_shop_info);echo "</pre>";exit;
        if (!isset($arr_shop_info['position_x'])) {
            $arr_shop_info['position_x'] = '';
        }
        if (!isset($arr_shop_info['position_y'])) {
            $arr_shop_info['position_y'] = '';
        }


        $map_takepoint = array(
            '0' => '否',
            '1' => '是'
        );
        $map_verify_status = array(
            '0' => '待审核',
            '1' => '已通过',
            '2' => '已驳回'
        );


        //$is_position_change = 0;
        if ($arr_info['position_x'] == '' && $arr_info['position_y'] == '') {
            $is_position_change = 0;
        } else {
            if ($arr_info['position_x'] == $arr_shop_info['position_x'] && $arr_info['position_y'] == $arr_shop_info['position_y']) {
                $is_position_change = 0;
            } else {
                $is_position_change = 1;
            }
        }


        $arr_view_data = array(
            //'arr_category_list' => $arr_category_list,
            'arr_info' => $arr_info,
            'map_takepoint' => $map_takepoint,
            'map_verify_status' => $map_verify_status,
            'arr_shop_info' => $arr_shop_info,
            'is_position_change' => $is_position_change,
        );
        echo $this->render('detail', $arr_view_data);
        return;
    }

    /**
     * Ajax操作
     *
     * Author zhengyu@iyangpin.com
     *
     * @return null
     */
    public function actionAjaxPost()
    {
        $act = RequestHelper::post('act', '', 'trim');
        if ($act == '') {
            return;
        } elseif ($act == 'pass') {
            $this->_applyPass();
            return;
        } elseif ($act == 'reject') {
            $this->_applyReject();
            return;
        } else {
            return;
        }
    }

    /**
     * 通过申请
     *
     * Author zhengyu@iyangpin.com
     *
     * @return null
     */
    private function _applyPass()
    {
        $shop_id = RequestHelper::post('shop_id', '', 'intval');
        $arr_where = array();
        if ($shop_id <= 0) {
            echo '01';
            return;
        } else {
            $arr_where['shop_id'] = $shop_id;
        }

        $arr_set = array(
            'verify_status' => 1
        );
        $admin_id = Yii::$app->user->identity->id;
        $admin_name = Yii::$app->user->identity->username;
        $arr_set['verify_admin_id'] = $admin_id;
        $arr_set['verify_admin_name'] = $admin_name;
        $arr_set['verify_operate_time'] = date("Y-m-d H:i:s", time());

        $model = new ShopVerify();
        $arr_result = $model->updateOneRecord($arr_where, $this->_default_str_andwhere, $arr_set);
        if (1 == $arr_result['result']) {
            $int_result = $this->_coverShopInfo($shop_id);
            if ($int_result == 1) {
                $is_reset_community = RequestHelper::post('is_reset_community', 0, 'intval');
                if ($is_reset_community == 1) {
                    $model_shopcommunity = new ShopCommunity();
                    $model_shopcommunity->resetCommunity($shop_id);
                }
                //日志
                $account_time = date("Y-m-d H:i:s", time());
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '在【商家资料修改审核】中审核通过了商家id为' . $shop_id . '的商家' . $account_time;
                $log->recordLog($log_info, 2);

                echo "1";

            } else {
                echo "03";
            }
        } else {
            echo "02";
            return;
        }
        return;
    }

    /**
     * 拒绝申请
     *
     * Author zhengyu@iyangpin.com
     *
     * @return null
     */
    private function _applyReject()
    {
        $shop_id = RequestHelper::post('shop_id', '', 'intval');
        $arr_where = array();
        if ($shop_id <= 0) {
            echo '0';
            return;
        } else {
            $arr_where['shop_id'] = $shop_id;
        }

        $arr_set = array();
        $reason = RequestHelper::post('reason', '', 'trim');
        if ($reason == '') {
            echo '03';
            return;
        } else {
            if (mb_strlen($reason, 'utf-8') > 50) {
                echo '04';
                return;
            }
            $arr_set['verify_reject_reason'] = $reason;
        }


        $arr_set['verify_status'] = 2;
        $admin_id = Yii::$app->user->identity->id;
        $admin_name = Yii::$app->user->identity->username;
        $arr_set['verify_admin_id'] = $admin_id;
        $arr_set['verify_admin_name'] = $admin_name;
        $arr_set['verify_operate_time'] = date("Y-m-d H:i:s", time());

        $model = new ShopVerify();
        $arr_result = $model->updateOneRecord($arr_where, $this->_default_str_andwhere, $arr_set);
        if (1 == $arr_result['result']) {

            //日志
            $account_time = date("Y-m-d H:i:s", time());
            $log = new Log();
            $log_info = '管理员 ' . \Yii::$app->user->identity->username . '在【商家资料修改审核】中驳回了商家id为' . $shop_id . '的商家审核' . $account_time;
            $log->recordLog($log_info, 2);

            echo "1";
        } else {
            echo "02";
            return;
        }
        return;
    }

    /**
     * 如果通过审核，覆盖商家信息
     *
     * Author zhengyu@iyangpin.com
     *
     * @param int $shop_id shop_id
     *
     * @return int 1=成功 0=失败
     */
    private function _coverShopInfo($shop_id)
    {
        $model_shop = new Shop();
        $model_shopverify = new ShopVerify();

        //申请详情
        $arr_where = array('shop_id' => $shop_id);
        $arr_apply_info = $model_shopverify->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $this->_default_str_field
        );
        //echo "<pre>arr_apply_info=";print_r($arr_apply_info);echo "</pre>";exit;

        //覆盖shop
        $arr_where = array();
        $arr_where['id'] = $shop_id;

        $arr_set = array();
        $arr_allow_cover_field = array(
            'contact_name', 'email', 'phone', 'mobile', 'logo',
            'address', 'introduction', 'position_x', 'position_y'
        );
        foreach ($arr_apply_info as $field => $value) {
            if (in_array($field, $arr_allow_cover_field) === true) {
                $arr_set[$field] = $value;
            }
        }
        if (isset($arr_set['position_x']) && $arr_set['position_x'] == '') {
            unset($arr_set['position_x']);
        }
        if (isset($arr_set['position_y']) && $arr_set['position_y'] == '') {
            unset($arr_set['position_y']);
        }

        $arr_result = $model_shop->updateOneRecord($arr_where, $this->_default_str_andwhere, $arr_set);
        if (1 == $arr_result['result']) {
            //z20150520a 如果成功，调用任仪能的一个接口
            $arr_get = array('shop_id' => $shop_id);
            $model_shop->apiShopSync($arr_get);

            return 1;
        } else {
            return 0;
        }
    }

}

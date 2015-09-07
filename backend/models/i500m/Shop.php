<?php
/**
 * 商家-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/20 17:14
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\i500m;

use common\helpers\CurlHelper;


/**
 * 商家-model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class Shop extends I500Base
{

    //private $_quanguo_province_id = 35;
    private $_quanguo_city_id = 999;

    //private $_default_arr_where = array();
    private $_default_str_andwhere = '';
    //private $_default_arr_order = array();
    //private $_default_str_field = '*';
    private $_default_int_offset = -1;
    private $_default_int_limit = -1;


    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }

    public function getListId($username){
        $arr = $this->find()
            ->select('id')
            ->where(['username'=>$username])
            ->asArray()
            ->one();
        return $arr;
    }

    public function getListinfo($map,$fileds='*'){
        $arr = $this->find()
            ->select($fileds)
            ->where($map)
            ->asArray()
            ->one();
        return $arr;
    }
    /**
     * 如果商家某些字段修改，调用此接口
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr_get 接口所需参数
     *
     * @return array
     */
    public function apiShopSync($arr_get)
    {
        //$url = "shopsync/add-shop";
        //$arr_result = CurlHelper::post($url, $arr_post, 'server');
        //return $arr_result;

        $arr_param = array();
        foreach ($arr_get as $key => $value) {
            $arr_param[] = $key . "=" . $value;
        }
        $url_param = implode("&", $arr_param);
        $url = "shopsync/edit-shop?" . $url_param;
        $arr_result = CurlHelper::get($url, 'server');
        return $arr_result;
    }

    /**
     * 新增商家，调用此接口
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr_get 接口所需参数
     *
     * @return array
     */
    public function apiShopSyncAddAllCategory($arr_get)
    {
        $arr_param = array();
        foreach ($arr_get as $key => $value) {
            $arr_param[] = $key . "=" . $value;
        }
        $url_param = implode("&", $arr_param);
        $url = "shopsync/add-all-category?" . $url_param;
        $arr_result = CurlHelper::get($url, 'server');
        return $arr_result;
    }


    /**
     * 获取全部已开通的省
     *
     * Author zhengyu@iyangpin.com
     *
     * @return array
     */
    public function getOpenProvince()
    {
        //获取全部省id
        $model_opencity = new OpenCity();

        $arr_where = array(
            'status' => 1,
            //'display' => 1,
        );
        $str_andwhere = " city<>" . $this->_quanguo_city_id . " ";
        $arr_order = array('province' => SORT_ASC);
        $str_field = 'province';
        $arr_tmp_p_list = $model_opencity->getList2(
            $arr_where,
            $str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        $arr_tmp = array();
        foreach ($arr_tmp_p_list as $tmp_value) {
            $arr_tmp[$tmp_value['province']] = 1;
        }
        $arr_p_id = array_keys($arr_tmp);
        if (empty($arr_p_id)) {
            return array();
        }

        //获取对应名称
        $model_province = new Province();
        $arr_where = array(
            'id' => $arr_p_id
        );
        $arr_order = array('id' => SORT_ASC);
        $str_field = 'id,name';
        $arr_province_list = $model_province->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );

        return $arr_province_list;
    }

    /**
     * 获取指定省的全部已开通城市
     *
     * Author zhengyu@iyangpin.com
     *
     * @param int $pid 省id
     *
     * @return array
     */
    public function getOpenP2C($pid)
    {
        $model_opencity = new OpenCity();

        $pid = intval($pid);
        $arr_where = array(
            'province' => $pid,
            'status' => 1,
            //'display' => 1,
        );
        $str_andwhere = " city<>" . $this->_quanguo_city_id . " ";
        $arr_order = array('city' => SORT_ASC);
        $str_field = 'city as id,city_name as name';
        $arr_city_list = $model_opencity->getList2(
            $arr_where,
            $str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );

        //过滤重复数据
        $arr_city_list2 = array();
        $arr_c_id = array();
        foreach ($arr_city_list as $value) {
            if (!in_array($value['id'], $arr_c_id)) {
                $arr_city_list2[] = $value;
                $arr_c_id[] = $value['id'];
            }
        }

        return $arr_city_list2;
    }

    public function total($where=null)
    {
        if($where){
            $total = $this->find()->where($where)->count();
            return $total;
        }else{
            $total = $this->find()->count();
            return $total;
        }

    }

    public function show($data=array(),$offset,$where=null)
    {
        if($where){
            $list = $this->find()
                ->where($where)
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('create_time desc')
                ->asArray()
                ->all();
            return $list;
        }else{
            $list = $this->find()
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('create_time desc')
                ->asArray()
                ->all();
            return $list;
        }
    }
    public function shop_info($shop_id)
    {
        $list = $this->find()->select("shop_name")->where("id = $shop_id")->asArray()->one();
        return $list;
    }
    public function shop_id($andwhere)
    {
        $list = $this->find()->select('id,username')->where($andwhere)->asArray()->all();
        return $list;
    }

    public function details_all($shop_id,$account_id, $page = 1)
    {
        $url = "shop/account/list?shop_id=".$shop_id.'&account_id='.$account_id.'&page='.$page;
        $arr_result = CurlHelper::get($url, 'server');
        return $arr_result;
    }

    public function details_other($shop_id,$order_sn)
    {
        $url = "shop/account/account-order-info?shop_id=".$shop_id.'&order_sn='.$order_sn;
        $arr_result = CurlHelper::get($url, 'server');
        return $arr_result;
    }
}
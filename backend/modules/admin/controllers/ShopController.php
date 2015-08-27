<?php
/**
 * 简介
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   SHOP
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @time      2015/3/12 15:51
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhoujun@iyangpin.com
 */

namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Branch;
use backend\models\i500m\City;
use backend\models\i500m\Log;
use backend\models\i500m\Shop;
use backend\models\shop\Settlement;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
/**
 * Shop
 *
 * @category SHOP
 * @package  Shop
 * @author   zhoujun <zhoujun@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhoujun@iyangpin.com
 */
class ShopController extends BaseController
{
    public $size = 10;

    /**
     * 商家带结算列表
     * @return string
     */
    public function actionIndex()
    {
        //实例化
        $city_model  = new City();
        $branch_model = new Branch();
        $shop_model = new Settlement();
        $model = new Shop();

        //分页
        $data = array();
        $data['page'] = RequestHelper::get('page', 1);
        $data['size'] = RequestHelper::get('per-page', $this->size);
        $offset = ($data['page'] - 1) * $data['size'];

        //获取参数
        $city_id = RequestHelper::get('city_id', 0);  //获取城市ID
        $branch_id = RequestHelper::get('branch_id', 0);  //获取分公司ID
        $settle_status = RequestHelper::get('settle_status', -1, 'intval');
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $positive_minus = RequestHelper::get('positive_minus');

        //根据分公司id取得其下的所有城市
        if ($branch_id) {
            $info = $branch_model->city_all($branch_id);
            $list = explode(',', $info['city_id_arr']);
            $arr = array();
            foreach ($list as $k=>$v) {
                $city_all = $city_model->city_all($v);
                $arr[] = $city_all[0];
            }
            $city_arr = array('id'=>'0', 'name'=>'请选择');
            array_unshift($arr, $city_arr);
        } else {
            $arr = '';
        }

        //验证收索的商家ID，判断是否有此商家，防止收索没有的商家时报错
        if ($shop_id) {
            $verify = $shop_model->all_shop($shop_id);
            if (empty($verify)) {
                return $this->error('没有此商家', '/admin/shop/index');
            }
        }

        //获取全部分公司（放入INDEX页面搜索分公司下拉）
        $branch_all = $branch_model->branch_info();

        //拼装sql语句中的where条件
        $where = array();
        if ($branch_id) {
            $where[] = 'branch_id ='.$branch_id;
        }

        if ($city_id) {
            $where[] = 'city_id ='.$city_id;
        }

        if (in_array($settle_status, array(0, 1, 2))) {
            $where[] = "status =" . $settle_status;
        }

        if ($positive_minus) {
            if ($positive_minus == 1) {
                $where[] = "money < 0";
            } elseif ($positive_minus == 2) {
                $where[] = "money >= 0";
            }
        }

        if ($shop_id) {
            $where[] = "shop_id =" . $shop_id;
        }

        $where = empty($where) ? '' : implode(' and ', $where);

        //根据where条件以分页形式查询出数据
        $list = $shop_model->show($data, $offset, $where);

        //从shop表中查询出商家名称赋予到$list数组里，拼装一下时间
        $number_all = 0;
        foreach ($list as $k=>$v) {
            $number_all += $v['money'];
            $info_shop = $model->shop_info($v['shop_id']);
            $list[$k]['shop_name'] = $info_shop['shop_name'];
            $list[$k]['settle_time'] = substr($list[$k]['start_time'], 0, 10).'--'.substr($list[$k]['end_time'], 0, 10);
            unset($list[$k]['start_time']);
            unset($list[$k]['end_time']);
        }

        $total = $shop_model->total($where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index', ['list'=>$list, 'pages'=>$pages, 'total'=>$total, 'number_all'=>$number_all, 'branch_all'=>$branch_all, 'branch_id'=>$branch_id, 'city_id'=>$city_id, 'settle_status'=>$settle_status, 'arr'=>$arr, 'positive_minus'=>$positive_minus]);
    }


    /**
     * 商家带结算冻结OR解除冻结OR结算
     * @return string
     */
    public function actionFreeze()
    {
        $shop_model = new Settlement();
        $is_freeze = RequestHelper::get('is_freeze', 0);
        $id = RequestHelper::get('id', 0);
        $shop_id = RequestHelper::get('shop_id');
        $account_time = RequestHelper::post('account_time');
        $info = $shop_model->freeze($id, $is_freeze);
        if ($info) {
            $bark = RequestHelper::post('info');
            $log = new Log();
            if ($is_freeze == 0) {
                $str = '冻结了';
            } elseif ($is_freeze == 1) {
                $str = '结算了';
            } elseif ($is_freeze == 2) {
                $str = '解除冻结了';
            }
            $log_info = '管理员 '.\Yii::$app->user->identity->username . $str.'商家id为'.$shop_id.'的账期'.$account_time;
            $log_info .=' 备注：'.$bark;
            $log->recordLog($log_info, 2);
            return $this->success('状态修改成功', '/admin/shop/index');
        } else {
            return $this->error('状态修改失败', '/admin/shop/index');
        }
    }

    /**
     * 根据所选分公司查询出对应的城市
     * @return string
     */
    public function actionCity()
    {
        $city_model  = new City();
        $model = new Branch();
        $bid = RequestHelper::get('bid');
        $info = $model->city_all($bid);
        $list = explode(',', $info['city_id_arr']);
        $arr = array();
        foreach ($list as $k=>$v) {
            $city_all = $city_model->city_all($v);
            $arr[] = $city_all[0];
        }
        $city_arr = array('id'=>'0', 'name'=>'请选择');
        array_unshift($arr, $city_arr);
        echo json_encode($arr);
    }

    /**
     * 商家待结算流水详情页
     * @return string
     */
    public function actionDetails()
    {
        $model = new Shop();
        $account_id = RequestHelper::get('account_id');
        $shop_id = RequestHelper::get('shop_id');

        //取出商家名称shop_name
        $shop_one = $model->shop_info($shop_id);
        $shop_name = $shop_one['shop_name'];

        //调用接口返回该商家的订单信息
        $info = $model->details_all($shop_id, $account_id);

        //结算状态
        $status = $info['data']['status'];

        //拼凑时间
        $ship_merge = $info['data']['account_start_time'].'--'.$info['data']['account_end_time'];
        $arr = $info['data']['data'];

        return $this->render('details', ['arr'=>$arr, 'shop_name'=>$shop_name, 'ship_merge'=>$ship_merge, 'status'=>$status, 'info'=>$info, 'shop_id'=>$shop_id, 'account_id'=>$account_id]);
    }

    /**
     * 商家待结算流水订单详细
     * @return string
     */
    public function actionParticulars()
    {
        $model = new Shop();
        $account_id = RequestHelper::get('account_id');
        $order_sn = RequestHelper::get('order_sn');
        $shop_id = RequestHelper::get('shop_id');
        $list = $model->details_other($shop_id, $order_sn);
        $data = ArrayHelper::getValue($list, 'data.detail', []);
        $coupons = ArrayHelper::getValue($list, 'data.coupons', []);
        $freight = ArrayHelper::getValue($list, 'data.freight', []);
        $params = [
            'list'=>$data,
            'account_id'=>$account_id,
            'coupons'=>$coupons,
            'freight'=>$freight,
            'info'=>ArrayHelper::getValue($list, 'data.info', []),
            'shop_id'=>$shop_id
        ];
        return $this->render('particulars', $params);
    }
} 
<?php
/**
 * 订单分发优惠券规则
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   OrdersendcouponsController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/8/11 0011 上午 11:33
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\modules\user\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\OrdersSendCoupons;
use common\helpers\RequestHelper;

/**
 * OrdersendcouponsController
 *
 * @category CRM
 * @package  OrdersendcouponsController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class OrdersendcouponsController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new OrdersSendCoupons();
        $cond['status'] = [0,1];
        $item = $model->getInfo($cond);
        if ($item) {
            $item['max'] = $item['max']<1 ? $item['max']*100 ."%" : $item['max']."元";
            $item['min'] = $item['min']<1 ? $item['min']*100 ."%" : $item['min']."元";
        }
        return $this->render('index', ['item'=>$item]);
    }

    /**
     * 添加
     *
     * @return string
     */
    public function actionAdd()
    {
        $model = new OrdersSendCoupons();
        $cond['status'] = [0,1];
        $result = $model->getCount($cond);
        if ($result>0) {
            $this->redirect('/user/ordersendcoupons');
        }
        return $this->render('add');
    }

    /**
     * 编辑
     *
     * @return string
     */
    public function actionEdit()
    {
        $model = new OrdersSendCoupons();
        $cond['status'] = [0,1];
        $item = $model->getInfo($cond);
        if ($item) {
            $item['status'] = $item['status']==1 ? 1 : 2;
        }
        return $this->render('edit', ['item'=>$item]);
    }

    /**
     * 添加操作(ajax)
     *
     * @return array
     */
    public function actionInsert()
    {
        $model = new OrdersSendCoupons();
        $data['min'] = RequestHelper::post('min', 0);
        $min_unit = RequestHelper::post('min_unit', 0);
        $data['max'] = RequestHelper::post('max', 0);
        $data['num'] = RequestHelper::post('min', 0);
        $data['validity']=RequestHelper::post('min', 0);
        $data['status'] = RequestHelper::post('status', 0);
        if ($data['max']==0) {
            $this->returnJsonMsg('101', '', '上限值 不能为空');
        } elseif (!is_numeric($data['max'])) {
            $this->returnJsonMsg('101', '', '上限值 必须是数字');
        } elseif ($data['min']==0) {
            $this->returnJsonMsg('101', '', '下限值 不能为空');
        } elseif (!is_numeric($data['min'])) {
            $this->returnJsonMsg('101', '', '下限值 必须是数字');
        } elseif ($min_unit==0) {
            $this->returnJsonMsg('101', '', '上下限值单位 不能为空');
        } elseif (!is_numeric($min_unit)) {
            $this->returnJsonMsg('101', '', '上下限值单位 必须是数字');
        } elseif ($data['num']==0) {
            $this->returnJsonMsg('101', '', '最多领取数量 不能为空');
        } elseif (!is_numeric($data['num'])) {
            $this->returnJsonMsg('101', '', '最多领取数量 必须是数字');
        } elseif ($data['num']<1) {
            $this->returnJsonMsg('101', '', '最多领取数量 必须大于0');
        } elseif ($data['validity']==0) {
            $this->returnJsonMsg('101', '', '优惠券有限期 不能为空');
        } elseif (!is_numeric($data['validity'])) {
            $this->returnJsonMsg('101', '', '优惠券有限期 必须是数字');
        } elseif ($data['validity']<0) {
            $this->returnJsonMsg('101', '', '优惠券有限期 必须大于0');
        } elseif ($data['status']==0) {
            $this->returnJsonMsg('101', '', '状态 不能为空');
        } else {
            $data['min'] = $min_unit==1 ? $data['min'] : $data['min']/100;
            $data['max'] = $min_unit==1 ? $data['max'] : $data['max']/100;
            $data['status'] = $data['status']==1 ? 1 :0;
            $result = $model->insertInfo($data);
            if ($result) {
                $this->returnJsonMsg('200', '', '添加成功');
            } else {
                $this->returnJsonMsg('101', '', '系统繁忙');
            }
        }
    }

    /**
     * 修改操作(ajax)
     *
     * @return array
     */
    public function actionUpdate()
    {
        $model = new OrdersSendCoupons();
        $data['min'] = RequestHelper::post('min', 0);
        $min_unit = RequestHelper::post('min_unit', 0);
        $data['max'] = RequestHelper::post('max', 0);
        $data['num'] = RequestHelper::post('number', 0);
        $data['validity']=RequestHelper::post('validity', 0);
        $data['status'] = RequestHelper::post('status', 0);
        if ($data['max']==0) {
            $this->returnJsonMsg('101', '', '上限值 不能为空');
        } elseif (!is_numeric($data['max'])) {
            $this->returnJsonMsg('101', '', '上限值 必须是数字');
        } elseif ($data['min']==0) {
            $this->returnJsonMsg('101', '', '下限值 不能为空');
        } elseif (!is_numeric($data['min'])) {
            $this->returnJsonMsg('101', '', '下限值 必须是数字');
        } elseif ($min_unit==0) {
            $this->returnJsonMsg('101', '', '上下限值单位 不能为空');
        } elseif (!is_numeric($min_unit)) {
            $this->returnJsonMsg('101', '', '上下限值单位 必须是数字');
        } elseif ($data['num']==0) {
            $this->returnJsonMsg('101', '', '最多领取数量 不能为空');
        } elseif (!is_numeric($data['num'])) {
            $this->returnJsonMsg('101', '', '最多领取数量 必须是数字');
        } elseif ($data['num']<1) {
            $this->returnJsonMsg('101', '', '最多领取数量 必须大于0');
        } elseif ($data['validity']==0) {
            $this->returnJsonMsg('101', '', '优惠券有限期 不能为空');
        } elseif (!is_numeric($data['validity'])) {
            $this->returnJsonMsg('101', '', '优惠券有限期 必须是数字');
        } elseif ($data['validity']<0) {
            $this->returnJsonMsg('101', '', '优惠券有限期 必须大于0');
        } elseif ($data['status']==0) {
            $this->returnJsonMsg('101', '', '状态 不能为空');
        } else {
            $item = $model->getInfo(['status'=>[0,1]]);
            $item_min = $item['min']<1 ? 2 : 1;
            if ($min_unit!=$item_min) {
                $data['min'] = $min_unit == 1 ? $data['min'] : $data['min'] / 100;
                $data['max'] = $min_unit==1 ? $data['max'] : $data['max']/100;
            }
            $data['status'] = $data['status']==1 ? 1 :0;
            $data['update_time'] = date("Y-m-d H:i:s");
            $result = $model->updateInfo($data, ['id'=>$item['id']]);
            if ($result) {
                $this->returnJsonMsg('200', '', '修改成功');
            } else {
                $this->returnJsonMsg('101', '', '系统繁忙');
            }
        }
    }
    /**
     * 返回json
     *
     * @param string $code    code值
     * @param array  $data    数组
     * @param string $message 内容
     *
     * @return array
     */
    public function returnJsonMsg($code='', $data=array(), $message='')
    {
        $arr = array(
            'code' => $code,
            'data' => $data,
            'message' => $message,
        );
        file_put_contents('/tmp/order_send_coupons.log', "执行时间：".date('Y-m-d H:i:s')." 返回结果".var_export($arr, true)."\n", FILE_APPEND);
        $re = json_encode($arr);
        //return $re;
        echo $re;
        exit;
    }
}

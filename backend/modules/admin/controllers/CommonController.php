<?php
/**
 * 公用功能控制器
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/5/26 15:04
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


namespace backend\modules\admin\controllers;


use yii;
//use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use backend\models\i500m\UserOrder;
use backend\models\shop\ShopOrder;


/**
 * 公用功能控制器
 *
 * @category ADMIN
 * @package  CONTROLLER
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class CommonController extends BaseController
{

    //private $_default_arr_where = array();
    private $_default_str_andwhere = '';
    //private $_default_arr_order = array();
    //private $_default_str_field = '*';
    //private $_default_int_offset = -1;
    //private $_default_int_limit = -1;


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

        $this->is_head_company = intval($this->is_head_company);
    }


    /**
     * Ajax操作
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionNotify()
    {
        if ($this->is_head_company != 1) {
            echo 'branch_no_auth';
            return;
        }

        $arr_data = array();
        $bool_result = $this->_getUnconfirmUserOrderNum();
        if ($bool_result === true) {
            $arr_data['userorder'] = 'have';
        } else {
            $arr_data['userorder'] = 'not';
        }

        $bool_result = $this->_getUnconfirmShopOrderNum();
        if ($bool_result === true) {
            $arr_data['shoporder'] = 'have';
        } else {
            $arr_data['shoporder'] = 'not';
        }

        echo json_encode($arr_data);
        return;
    }

    /**
     * 获取是否有未确认的用户订单
     *
     * Author zhengyu@iyangpin.com
     *
     * @return bool true=有 false=无
     */
    private function _getUnconfirmUserOrderNum()
    {
        $model_userorder = new UserOrder();
        $arr_where = array('status' => 0);
        $str_field = 'id';
        $arr_userorder_info = $model_userorder->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        if (empty($arr_userorder_info)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取是否有未确认的商家订单
     *
     * Author zhengyu@iyangpin.com
     *
     * @return bool true=有 false=无
     */
    private function _getUnconfirmShopOrderNum()
    {
        $model_shoporder = new ShopOrder();
        $arr_where = array('status' => 0);
        $str_field = 'id';
        $arr_shoporder_info = $model_shoporder->getOneRecord(
            $arr_where,
            $this->_default_str_andwhere,
            $str_field
        );
        if (empty($arr_shoporder_info)) {
            return false;
        } else {
            return true;
        }
    }

}

<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  WalletController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/9 上午9:46
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\social\controllers;
use backend\controllers\BaseController;
use backend\models\social\Wallet;
use backend\models\social\Withdrawal;
use backend\models\social\WithdrawalLog;
use common\helpers\RequestHelper;
use yii\data\Pagination;


/**
 * Class WalletController
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class WalletController extends BaseController
{
    /**
     * 简介：服务列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $model = new Wallet();
        $mobile = RequestHelper::get('mobile');
        $page = RequestHelper::get('page', 1, 'intval');
        $start_time = RequestHelper::get('start_time');
        $end_time = RequestHelper::get('end_time');
        $where = [];
        $andwhere = ' 1 ';
        if ($mobile != '') {
            $where['mobile'] = $mobile;
        }
        if ($start_time != '') {
            $andwhere .=" and create_time >='$start_time'";
        }
        if ($end_time != '') {
            $andwhere.=" and create_time <='$end_time 23:59:59'";
        }
        $count = $model->getCount($where, $andwhere);
        $list = $model->getPageList($where, "*", "id desc", $page, $this->size, $andwhere);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render(
            'index',
            [
                'list' => $list,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'pages' => $pages,
                'mobile' => $mobile,
            ]
        );
    }

    /**
     * 简介：服务详情
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionDetail()
    {
        $model = new Wallet();
        $Withdrawal = new Withdrawal();
        $id = RequestHelper::get('id', 0, 'intval');
        $page = RequestHelper::get('page', 1, 'intval');
        $list = $model->getInfo(['id'=>$id]);
        if (!$list) {
            return $this->error('信息不存在');
        }
        $count = $Withdrawal->getCount(1);
        $withdrawal_list = $Withdrawal->getPageList(1, "*", "id desc", $page, $this->size);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render(
            'detail',
            [
                'list' => $list,
                'pages'=> $pages,
                'withdrawal_list'=>$withdrawal_list,
            ]
        );
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionWalLog()
    {
        $WithdrawalLog = new WithdrawalLog();
        $id = RequestHelper::get('id', 0, 'intval');
        $list = $WithdrawalLog->getList(['id'=>$id]);
        return $this->render(
            'detail_log',
            [
                'list' => $list,
            ]
        );
    }

}
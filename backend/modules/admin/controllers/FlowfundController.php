<?php
/**
 *
 * @category  CRM
 * @package   资金流水
 * @author    youyong<youyong@iyangpin.com>
 * @time      2015/4/23 18:47
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use common\helpers\RequestHelper;
use backend\models\i500m\Flowfund;
use backend\models\i500m\Shop;
use yii\data\Pagination;

class FlowfundController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 简介：
     * 商家资金流水`
     * @author  lichenjun@iyangpin.com。
     * @return  string
     */
    public function actionIndex()
    {
        $start_time = RequestHelper::get('start_time');   //支付时间开始
        $end_time = RequestHelper::get('end_time');        //支付时间结束
        $p = RequestHelper::get('page', '1', 'intval');                //当前页
        $username = RequestHelper::get('username', '');                 //商家名
        $model = new shop();
        if (!empty($username) && $username != '商家ID') {
            $shopId = $model->getListId($username);
            if (!$shopId) {
                return $this->error('商家名称不存在');
            }
            $where = " ship_status =5";
            if ($start_time) {
                $where .= " and create_time>='" . $start_time . " 00:00:00'";
            }
            if ($end_time) {
                $where .= " and create_time<='" . $end_time . " 23:59:59'";
            }
            $where .= " and shop_id=" . $shopId['id'];
            $model = new Flowfund();
            $list = $model->getPageList($where, '*', 'id desc', $p, $this->size);
            $count = $model->getCount($where);
        } else {
            $count = 0;
            $list = array();

        }
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        $param = [
            'pages' => $pages,
            'list' => $list,
            'username' => $username,
            'count' => $count,
        ];
        return $this->render('index', $param);
    }

}

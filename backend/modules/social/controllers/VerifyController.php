<?php
/**
 * 论坛--验证码管理
 *
 * PHP Version 5
 *
 * @category  Admin
 * @package   VerifyController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/8/18 0018 上午 9:40
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\social\VerifyCode;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * VerifyController
 *
 * @category Admin
 * @package  VerifyController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class VerifyController extends BaseController
{
    public $conf_data=[
        '1' => '登录',
        '2' => '密码找回',
        '3' => '注册',
        '4' => '绑定用户',
    ];
    /**
     * 验证码列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new VerifyCode();
        $cond = [];
        $search = RequestHelper::get('Search', '');
        if (!empty($search['mobile'])) {
            $cond['mobile'] = $search['mobile'];
        }
        if (!empty($search['type'])) {
            $cond['type'] = $search['type'];
        }
        $and_where = ['>', 'id', '0'];
        $page = RequestHelper::get('page', 1);
        $size = 20;
        $data = $model->getPageList($cond, '*', 'id desc', $page, $size, $and_where);
        //商品数量及分页
        $total = $model->getCount($cond, $and_where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $size]);
        $param = [
            'total' => $total,
            'pages' => $pages,
            'data' => $data,
            'search' => $search,
            'conf_data'=>$this->conf_data
        ];
        return $this->render('index', $param);
    }
}

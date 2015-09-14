<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Crm
 * @package   Member
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @time      2015/4/1 10:30
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */
namespace backend\modules\user\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\SampleApply;
use backend\models\i500m\SampleFeedback;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * Class SampleapplyController
 * @category  PHP
 * @package   SampleapplyController
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SampleapplyController extends BaseController
{

    public $enableCsrfValidation = false;
    public static $status_data = array(
        -2 => '全部',
        0 => '未领取',
        1 => '已经领取',
    );

    /**
     * 简介：
     * @return string
     */
    public function actionIndex()
    {
        $model = new SampleApply();
        $data = array();
        $data['page'] = RequestHelper::get('page', 1);
        $data['size'] = RequestHelper::get('per-page', $this->size);
        if ($data['page'] == 1) {
            $offset = 0;
        } else {
            $offset = ($data['page'] - 1) * $data['size'];
        }

        $where = array();
        //$where = empty($where) ? '' : implode(' and ', $where);
        $apply_info = $model->show($data, $offset, $where);
        $total = $model->total($where);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        return $this->render(
            'index',
            [
                'apply_info' => $apply_info,
                'pages' => $pages,
                'status_data' => SampleapplyController::$status_data,
            ]
        );
    }

    /**
     * 简介：
     * @return string
     */
    public function actionView()
    {
        $apply_id = RequestHelper::get('sa_id', 0, 'intval');

        $info = [];

        if ($apply_id) {
            $m_feedback = new SampleFeedback();
            $info = $m_feedback->getInfo(['sa_id' => $apply_id]);
        }

        return $this->render('view', ['info' => $info]);
    }
}

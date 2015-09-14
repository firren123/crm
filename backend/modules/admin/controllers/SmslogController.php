<?php
/**
 * 发送短信及短信发送记录
 * @category  Crm
 * @package   SmsLog.php
 * @author    youyong <youyong@iyangpin.com>
 * @time      2015/5/25 10:19
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   I500M http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use common\helpers\RequestHelper;
use backend\models\i500m\SmsLog;
use backend\models\i500m\QueueSms;
use yii\data\Pagination;
use yii;

/**
 * Class SmslogController
 * @category  PHP
 * @package   SmslogController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SmslogController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 短信发送状态列表
     *
     * Author youyoing@iyangpin.com
     *
     * @param： int $page     页码
     *
     * @return int 返回值说明
     */
    public function actionList()
    {
        $where = 'status!=1';
        $p = RequestHelper::get('page', '1', 'intval');                //当前页
        $model = new SmsLog();
        $result = $model->getPageList($where, '*', 'id desc', $p, $this->size);
        //var_dump($result);exit;
        $count = $model->getCount($where);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        //var_dump($pages);exit;
        return $this->render(
            'list',
            [
                'pages' => $pages,
                'result' => $result,
                'count' => $count,
            ]
        );
    }

    /**
     * 短信发送
     *
     * @return string
     */
    public function actionSend()
    {
        $model = new QueueSms();
        //$model->status = 2;
        $queueSms = RequestHelper::post('QueueSms');
        $queueSms['create_time'] = date("Y-m-d H:i:s", time());
        //var_dump($manageType);exit;
        if (!empty($queueSms)) {
            $result = $model->insertInfo($queueSms);
            if ($result == true) {
                $this->redirect('/admin/smslog/list');
            }
        }
        return $this->render('send', ['model' => $model]);
    }

    /**
     * 重新发送
     *
     * @return string
     */
    public function actionTwo()
    {
        $id = RequestHelper::get('id');
        $model = new SmsLog();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, true, '*');
        //var_dump($item);exit;
        $cond = "mobile='" . $item['mobile'] . "' and content='" . $item['content'] . "'";
        //var_dump($cond);exit;
        $model = new QueueSms();
        $total = $model->getCount($cond);
        //var_dump($total);exit;
        if ($total < 3) {
            $data['mobile'] = $item['mobile'];
            $data['content'] = $item['content'];
            $data['create_time'] = date("Y-m-d H:i:s", time());
            $model = new QueueSms();
            $result = $model->insertInfo($data);
            if ($result == true) {
                return $this->success('发送成功', '/admin/smslog/list');
            }
        } else {
            return $this->success('发送超过3次，不能发送', '/admin/smslog/list');
        }
    }
}

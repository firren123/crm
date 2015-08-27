<?php
/**
 * 论坛--短信管理
 *
 * PHP Version 5
 *
 * @category  Admin
 * @package   SmsController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/8/18 0018 上午 9:40
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\social\Sms;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * SmsController
 *
 * @category Admin
 * @package  SmsController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class SmsController extends BaseController
{
    /**
     * 发送短信列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Sms();
        $cond = [];
        $search = RequestHelper::get('Search', '');
        if (!empty($search['mobile'])) {
            $cond['mobile'] = $search['mobile'];
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
            'search' => $search
        ];
        return $this->render('index', $param);
    }

    /**
     * 短信发送页面
     *
     * @return string
     */
    public function actionSend()
    {
        return $this->render('send');
    }

    /**
     * 短信发送操作ajax
     *
     * @return array
     */
    public function actionAdd()
    {
        $model = new Sms();
        $mobile = RequestHelper::post('mobile', 0);
        $content = RequestHelper::post('content', '');
        $array = ['code'=>101,'msg'=>'参数错误'];
        if ($mobile>0 and !empty($content)) {
            $search ='/^(((1[34578]{1}))+\d{9})$/';
            if (!preg_match($search, $mobile)) {
                $array = ['code'=>102,'msg'=>'手机号不合法'];
            } else {
                $data['mobile'] = $mobile;
                $data['content'] = $content;
                $data['create_time'] = date("Y-m-d H:i:s");
                $result = $model->insertInfo($data);
                if ($result==true) {
                    $array = ['code'=>200,'msg'=>'发送成功'];
                } else {
                    $array = ['code'=>103,'msg'=>'系统繁忙'];
                }
            }
        }
        return json_encode($array);
    }
}

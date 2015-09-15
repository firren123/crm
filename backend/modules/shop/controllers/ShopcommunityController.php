<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/4/28 下午5:53
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\modules\shop\controllers;


use backend\models\i500m\Community;
use Yii;
use backend\controllers\BaseController;
use backend\models\i500m\ShopCommunity;
use backend\models\i500m\Log;
use yii\helpers\ArrayHelper;
use common\helpers\RequestHelper;
use backend\models\i500m\Shop;
use yii\data\Pagination;

/**
 * Class ShopController 商家相关功能控制器
 *
 * @category ADMIN
 * @package  SHOP
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class ShopcommunityController extends BaseController
{


    /**
     * 商家列表
     * @return string
     */
    public function actionIndex()
    {
        $page = RequestHelper::get('page', 1, 'intval');
        $order = RequestHelper::get('order', 'id');
        $sort = RequestHelper::get('sort', 'desc');
        $array = ['id'];
        if (!in_array($order, $array)) {
            $order = 'id';
        }

        $model = new Shop();
        $where = ['status' => 2];
        $data = $model->getList($where, $page, $order, $sort, 20);
        //var_dump($data);exit();
        $count = ArrayHelper::getValue($data, 'count', 0);
        $params['item'] = ArrayHelper::getValue($data, 'list', array());
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => 20]);
        $params['pages'] = $pages;
        return $this->render('index', $params);
    }

    /**
     * 设置关联小区
     * @return string
     * @throws \yii\base\ExitException
     */
    public function actionRelation()
    {
        if (\Yii::$app->request->isPost) {
            $shop_id = RequestHelper::post('shop_id', 0, 'intval');
            //var_dump($shop_id);
            $ids = RequestHelper::post('ids', '');
            if (!empty($shop_id) && !empty($ids)) {
                $ids_str = implode(',', $ids);
                $model = new ShopCommunity();
                $model->RelationCommunity($shop_id, $ids_str);
                $this->redirect('/shop/shopcommunity/relation?id=' . $shop_id);
                \Yii::$app->end();
                //\Yii::$app->response->redirect();

            }

        } else {
            $shop_id = RequestHelper::get('id', 1, 'intval');

            $info = Shop::findOne($shop_id);

            $item = [];
            if (!empty($info)) {
                $model = new ShopCommunity();

                $item = $model->getNearList($info['city'], $info['position_x'], $info['position_y']);
                $item = ArrayHelper::index($item, 'community_id');
            }

            $rs_have = $model->getHaveList($shop_id, $info['city']);
            $rs_have = ArrayHelper::index($rs_have, 'id');
            $params = [
                'info' => $info,
                'community' => $item,
                'have' => $rs_have,
            ];

            return $this->render('relation', $params);
        }


    }

    /**
     * 简介：
     * @return string
     */
    public function actionShow()
    {
        $shop_id = RequestHelper::get('id', 1, 'intval');
        $model = new ShopCommunity();
        //获取商家信息
        $info = Shop::findOne($shop_id);
        $city_id = $info['city'];
        switch ($city_id) {
        case 1:
            $table_name = '_beijing';
            break;
        case 258:
            $table_name = '_chengdu';
            break;
        case 261:
            $table_name = '_luzhou';
            break;
        default:
            $table_name = '_beijing';
        }
        $list = $model->getCommunity($shop_id);
        //var_dump($list);exit();
        $params = [
            'shop_id' => $shop_id,
            'list' => $list,
            'info' => $info,
        ];
        if (!empty($list)) {
            $community_id = [];
            foreach ($list as $v) {
                $community_id[] = $v['community_id'];
            }
            $community = new Community();
            $community->setSuffix($table_name);
            $com_list = $community->getList(['IN', 'id', $community_id]);
            $com_list = ArrayHelper::index($com_list, 'id');
            $params['community'] = $com_list;
        }
        return $this->render('show', $params);
    }

    /**
     * 新增服务器小区
     * @return array
     */
    public function actionDoCommunity()
    {
        $model = new ShopCommunity();
        $community_id = RequestHelper::get('community_id', 0, 'intval');
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $rs = $model->addOne($shop_id, $community_id);
        //var_dump($rs);
        if ($rs['code'] == '200') {
            //日志
            $account_time = date("Y-m-d H:i:s", time());
            $log = new Log();
            $log_info = '管理员 ' . \Yii::$app->user->identity->username . '在【商家管理】中添加了id为' . $community_id . '的小区' . $account_time;
            $log->recordLog($log_info, 2);

            return $this->ajaxReturn('ok', $rs['message'], []);
            //echo json_encode(['code'=>'ok','message'=>'','data'=>'[]']);
        } else {
            $msg = !empty($rs['message']) ? $rs['message'] : '服务器繁忙，请重试';
            return $this->ajaxReturn('error', $msg, []);
            //echo json_encode(['code'=>'error','message'=>$msg,'data'=>[]]);
        }
    }

    /**
     * 删除服务器小区
     * @return array
     */
    public function actionDelRelation()
    {
        $model = new ShopCommunity();
        $community_id = RequestHelper::get('community_id', 0, 'intval');
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $rs = $model->deleteOne($shop_id, $community_id);
        //var_dump($rs);
        if ($rs['code'] == '200') {

            //日志
            $account_time = date("Y-m-d H:i:s", time());
            $log = new Log();
            $log_info = '管理员 ' . \Yii::$app->user->identity->username . '在【商家管理】中删除了id为' . $community_id . '的小区' . $account_time;
            $log->recordLog($log_info, 2);

            return $this->ajaxReturn('ok', $rs['message'], []);
            //echo json_encode(['code'=>'ok','message'=>'','data'=>'[]']);
        } else {
            $msg = !empty($rs['message']) ? $rs['message'] : '服务器繁忙，请重试';
            return $this->ajaxReturn('error', $msg, []);
            //echo json_encode(['code'=>'error','message'=>$msg,'data'=>[]]);
        }
    }
}

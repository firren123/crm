<?php
/**
 * App版本列表
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @time      2015/6/3  下午 2:11
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      weitonghe@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\AppChannel;
use backend\models\i500m\AppVersionList;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * App版本列表
 *
 * @category PHP
 * @package  Admin
 * @author   weitonghe <weitonghe@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class AppversionlistController extends BaseController
{
    /**
     * 简介：首页
     * @return string
     */
    public function actionIndex()
    {
        $AppVersionList_model = new AppVersionList();
        $page = RequestHelper::get('page', 1, 'intval');//获得当前的页数
        $pageSize = 5;//设置每页显示的记录条数
        // 查询条件
        $type = RequestHelper::get('type');
        $major = RequestHelper::get('major', '', 'htmlspecialchars');
        $cond = [];
        $and_cond = '';
        if (strlen($major) || strlen($type)) {
            if (strlen($major)) {
                $cond = ['like', 'major', $major];
                //$cond = "major LIKE'%".$major."%'";
            }
            if (strlen($type)) {
                $and_cond = "type='" . $type . "'";
            }
            $total = $AppVersionList_model->totalNum($cond, $and_cond);//获得总记录条数
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        } else {
            $cond = ['like', 'major', ''];
            $total = $AppVersionList_model->totalNum($cond);//获得总记录条数
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        }
        $fields = '*';
        $order = '';
        $allApp_result = $AppVersionList_model->allApp($cond, $fields, $order, $page, $pageSize, $and_cond);
        return $this->render('index', array('result' => $allApp_result, 'pages' => $pages));
    }

    /**
     * 简介：详细信息
     * @return string
     */
    public function actionShowOneUrl()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel = new AppChannel();
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $cond = "id='" . $id . "'";
            $AppVersionList_result = $AppVersionList_model->showOneUrl($cond);
            $AppChannel_result = "";
            if ($AppVersionList_result['type'] == '0') {
                $cond = "app_id='" . $AppVersionList_result['id'] . "'";
                $AppChannel_result = $AppChannel->showOneUrl($cond);
            }
            return $this->render('show', array('AppVersionList_result' => $AppVersionList_result, 'app_channel_result' => $AppChannel_result));
        }
        return $this->error('没有此ID', 'index');
    }

    /**
     * 简介：添加页面
     * @return string
     */
    public function actionNewVersion()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel = new AppChannel();
        return $this->render('add', array('AppVersionList_model' => $AppVersionList_model, 'app_channel' => $AppChannel));
    }

    /**
     * 简介：修改页面
     * @return string
     */
    public function actionEditOneUrl()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel = new AppChannel();
        $id = RequestHelper::get('id');
        $cond = "id='" . $id . "'";
        $AppVersionList_result = $AppVersionList_model->showOneUrl($cond);
        $AppVersionList_model->setAttribute('subordinate_item', $AppVersionList_result['subordinate_item']);
        $AppVersionList_model->setAttribute('type', $AppVersionList_result['type']);
        $AppVersionList_model->setAttribute('explain', $AppVersionList_result['explain']);
        $AppVersionList_model->setAttribute('status', $AppVersionList_result['status']);
        $AppVersionList_model->setAttribute('update_prompt', $AppVersionList_result['update_prompt']);
        $AppVersionList_model->setAttribute('is_forced_to_update', $AppVersionList_result['is_forced_to_update']);
        if (!empty($AppVersionList_result)) {
            if ($AppVersionList_result['type'] == '0') {
                $app_id = "app_id='" . $AppVersionList_result['id'] . "'";
                $app_channel_result = $AppChannel->showOneUrl($app_id);
                if (!empty($app_channel_result)) {
                    foreach ($app_channel_result as $k => $v) {
                        if ($v['type'] == '0') {
                            $app_channel_result['baidu'] = $v['url'];
                        }
                        if ($v['type'] == '1') {
                            $app_channel_result['360'] = $v['url'];
                        }
                    }
                    $app_data = array('AppVersionList_model' => $AppVersionList_model, 'AppVersionList_result' => $AppVersionList_result, 'app_channel_result' => $app_channel_result);
                    return $this->render('edit', $app_data);
                } else {
                    return $this->render('edit', array('AppVersionList_model' => $AppVersionList_model, 'AppVersionList_result' => $AppVersionList_result));
                }
            } else {
                return $this->render('edit', array('AppVersionList_model' => $AppVersionList_model, 'AppVersionList_result' => $AppVersionList_result));
            }
        }
        return $this->error('修改时查询失败', 'index');
    }

    /**
     * 简介：添加操作
     * @return string
     */
    public function actionAddApp()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel_model = new AppChannel();
        $AppVersionList = RequestHelper::post('AppVersionList');
        //date_default_timezone_set('prc');
        $AppVersionList['create_time'] = time();
        $app_channel['create_time'] = date('Y-m-d H:i:s', $AppVersionList['create_time']);
        $result = $AppVersionList_model->addApp($AppVersionList);
        if ($result) {
            $app_channel['app_id'] = $result;
            $app_channel1['url'] = RequestHelper::post('abaidu');
            $app_channel2['url'] = RequestHelper::post('a360');
            if (empty($app_channel1['url']) && empty($app_channel2['url'])) {
                return $this->success('添加成功', 'index');
            } else {
                if (!empty($app_channel1['url'])) {
                    $app_channel1['type'] = 0;
                    $app_channel1['create_time'] = $app_channel['create_time'];
                    $app_channel1['app_id'] = $app_channel['app_id'];
                    $result = $AppChannel_model->addApp($app_channel1);
                    if (!$result) {
                        return $this->error('添加失败', 'index');
                    }
                }
                if (!empty($app_channel2['url'])) {
                    $app_channel2['type'] = 1;
                    $app_channel2['create_time'] = $app_channel['create_time'];
                    $app_channel2['app_id'] = $app_channel['app_id'];
                    $result = $AppChannel_model->addApp($app_channel2);
                    if ($result) {
                        return $this->success('添加成功', 'index');
                    } else {
                        return $this->error('添加失败', 'index');
                    }
                }
            }
        }
        return $this->error('添加失败', 'index');
    }

    /**
     * 简介：修改操作
     * @return string
     */
    public function actionEditApp()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel_model = new AppChannel();
        $AppVersionList = RequestHelper::post('AppVersionList');
        $id = RequestHelper::post('id');
        //date_default_timezone_set('prc');
        $AppVersionList['update_time'] = time();
        $result = $AppVersionList_model->editApp($id, $AppVersionList);
        if ($result) {
            $types = RequestHelper::post('types');//0 安卓  1 IOS
            $AppChannel['app_id'] = $id;
            $baiDuUrl = RequestHelper::post('abaidu');
            $a360url = RequestHelper::post('a360');
            //$AppChannel['type'] = RequestHelper::post('abaidu');
            //$AppChannel['type'] = RequestHelper::post('a360');
            //$AppChannel['url'] = '';
            $AppChannel['create_time'] = date('Y-m-d H:i:s', $AppVersionList['update_time']);
            $AppChannel['update_time'] = date('Y-m-d H:i:s', $AppVersionList['update_time']);

            if ($types == '1') {
                //add
                if ($AppVersionList['type'] == '0') {
                    if (!empty($baiDuUrl)) {
                        $AppChannel['url'] = $baiDuUrl;
                        $AppChannel['type'] = 0;
                        $result = $AppChannel_model->addApp($AppChannel);
                        if (!$result) {
                            //return $this->error('1-0baidu修改失败', 'index');
                            return $this->error('修改失败', 'index');
                        }
                    }
                    if (!empty($a360url)) {
                        $AppChannel['url'] = $a360url;
                        $AppChannel['type'] = 1;
                        $result = $AppChannel_model->addApp($AppChannel);
                        if (!$result) {
                            return $this->error('修改失败', 'index');
                            //return $this->error('1-0360修改失败','index');
                        } else {
                            return $this->success('修改成功', 'index');
                            //return $this->success('1-0360修改成功','index');
                        }
                    }
                }
                //update
                if ($AppVersionList['type'] == '1') {
                    if ($result) {
                        return $this->success('修改成功', 'index');
                        //return $this->success('1-1修改成功','index');
                    } else {
                        return $this->error('修改失败', 'index');
                        //return $this->error('1-1修改失败','index');
                    }
                }
            }
            if ($types == '0') {
                //update
                if ($AppVersionList['type'] == '0') {
                    if (!empty($baiDuUrl)) {
                        $cond = "app_id='" . $id . "' and type='0'";
                        $AppChannel['url'] = $baiDuUrl;
                        //var_dump($AppChannel);
                        //exit;
                        $result = $AppChannel_model->editApp($cond, $AppChannel);
                        if (!$result) {
                            //return $this->error('0-0baidu修改失败','index');
                            return $this->error('修改失败', 'index');
                        }
                    }
                    if (!empty($a360url)) {
                        $cond = "app_id='" . $id . "' and type='1'";
                        $AppChannel['url'] = $a360url;
                        $result = $AppChannel_model->editApp($cond, $AppChannel);
                        if ($result) {
                            return $this->success('修改成功', 'index');
                            //return $this->success('0-0360修改成功','index');
                        } else {
                            return $this->error('修改失败', 'index');
                            //return $this->error('0-0360修改失败','index');
                        }
                    }
                }
                //del
                if ($AppVersionList['type'] == '1') {
                    $app_id = $id;
                    $result = $AppChannel_model->delOneUrl($app_id);
                    if ($result) {
                        return $this->success('修改成功', 'index');
                        //return $this->success('0-1修改成功','index');
                    } else {
                        return $this->error('0-1修改失败', 'index');
                        //return $this->error('修改失败','index');
                    }
                }
            }
        }
        return $this->error('修改失败', 'index');
    }

    /**
     * 简介：删除操作
     * @return string
     */
    public function actionDelOneUrl()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel_model = new AppChannel();
        $id = RequestHelper::get('id');
        $cond = "id='" . $id . "'";
        $AppVersionList_result = $AppVersionList_model->showOneUrl($cond);
        if ($AppVersionList_result['type'] == '0') {
            //删除安卓类型的url
            $AppVersionList_result = $AppVersionList_model->delOneUrl($id);
            if ($AppVersionList_result) {
                $app_channel_model_result = $AppChannel_model->delOneUrl($id);
                if ($app_channel_model_result) {
                    return $this->success('删除成功', 'index');
                } else {
                    return $this->error('删除失败', 'index');
                }
            } else {
                return $this->error('删除失败', 'index');
            }
        }
        if ($AppVersionList_result['type'] == '1') {
            //删除iOS类型的url
            $AppVersionList_result = $AppVersionList_model->delOneUrl($id);
            if ($AppVersionList_result) {
                return $this->success('删除成功', 'index');
            } else {
                return $this->error('删除失败', 'index');
            }
        }
        return $this->error('删除失败', 'index');
    }

    /**
     * 简介：获得add 时AJAX数据
     * @return int
     */
    public function actionGetNameAjax()
    {
        $AppVersionList_model = new AppVersionList();
        $name = RequestHelper::get('namea');
        $result = '';
        if (strlen($name) > 0) {
            $where = "name='" . $name . "'";
            $cond = ['like', 'major', ''];
            $result = $AppVersionList_model->totalNum($cond, $where);
        }
        if ($result) {
            return 0;
        }
        return 1;
    }
}

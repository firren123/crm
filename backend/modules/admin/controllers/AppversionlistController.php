<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/3
 * Time: 14:11
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\AppChannel;
use backend\models\i500m\AppVersionList;
use common\helpers\RequestHelper;
use yii\data\Pagination;

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
        $allapp_result = $AppVersionList_model->allapp($cond, $fields, $order, $page, $pageSize, $and_cond);
        return $this->render('index', array('result' => $allapp_result, 'pages' => $pages));
    }

    /**
     * 简介：show one add
     * @return string
     */
    public function actionShowoneurl()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel = new AppChannel();
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $cond = "id='" . $id . "'";
            $AppVersionList_result = $AppVersionList_model->showoneurl($cond);
            $AppChannel_result = "";
            if ($AppVersionList_result['type'] == '0') {
                $cond = "app_id='" . $AppVersionList_result['id'] . "'";
                $AppChannel_result = $AppChannel->showoneurl($cond);
            }
            return $this->render('showoneurl', array('AppVersionList_result' => $AppVersionList_result, 'app_channel_result' => $AppChannel_result));
        } else {
            return $this->error('没有此ID', 'index');
        }

    }

    /**
     * @return string
     * show add
     */
    public function actionNewversion()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel = new AppChannel();
        return $this->render('showaddversion', array('AppVersionList_model' => $AppVersionList_model, 'app_channel' => $AppChannel));
    }

    /**
     * 简介：show edit
     * @return string
     */
    public function actionEditoneurl()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel = new AppChannel();
        $id = RequestHelper::get('id');
        $cond = "id='" . $id . "'";
        $AppVersionList_result = $AppVersionList_model->showoneurl($cond);
        $AppVersionList_model->setAttribute('subordinate_item', $AppVersionList_result['subordinate_item']);
        $AppVersionList_model->setAttribute('type', $AppVersionList_result['type']);
        $AppVersionList_model->setAttribute('explain', $AppVersionList_result['explain']);
        $AppVersionList_model->setAttribute('status', $AppVersionList_result['status']);
        if (!empty($AppVersionList_result)) {
            if ($AppVersionList_result['type'] == '0') {
                $app_id = "app_id='" . $AppVersionList_result['id'] . "'";
                $app_channel_result = $AppChannel->showoneurl($app_id);
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
                    return $this->render('editoneurl', $app_data);
                } else {
                    return $this->render('editoneurl', array('AppVersionList_model' => $AppVersionList_model, 'AppVersionList_result' => $AppVersionList_result));
                }
            } else {
                return $this->render('editoneurl', array('AppVersionList_model' => $AppVersionList_model, 'AppVersionList_result' => $AppVersionList_result));
            }
        } else {
            return $this->error('修改时查询失败', 'index');
        }

    }

    /**
     * 简介：添加
     * @return string
     */
    public function actionAddapp()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel_model = new AppChannel();
        $AppVersionList = RequestHelper::post('AppVersionList');
        //date_default_timezone_set('prc');
        $AppVersionList['create_time'] = time();
        $app_channel['create_time'] = date('Y-m-d H:i:s', $AppVersionList['create_time']);
        $result = $AppVersionList_model->addapp($AppVersionList);
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
                    $result = $AppChannel_model->addapp($app_channel1);
                    if (!$result) {
                        return $this->error('添加失败', 'index');
                    }
                }
                if (!empty($app_channel2['url'])) {
                    $app_channel2['type'] = 1;
                    $app_channel2['create_time'] = $app_channel['create_time'];
                    $app_channel2['app_id'] = $app_channel['app_id'];
                    $result = $AppChannel_model->addapp($app_channel2);
                    if ($result) {
                        return $this->success('添加成功', 'index');
                    } else {
                        return $this->error('添加失败', 'index');
                    }
                }
            }
        } else {
            return $this->error('添加失败', 'index');
        }
    }

    /**
     * 简介：修改
     * @return string
     */
    public function actionEditapp()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel_model = new AppChannel();
        $AppVersionList = RequestHelper::post('AppVersionList');
        $id = RequestHelper::post('id');
        //date_default_timezone_set('prc');
        $AppVersionList['update_time'] = time();
        //$AppChannel['update_time'] = date('Y-m-d H:i:s',$AppVersionList['update_time']);
        $result = $AppVersionList_model->editapp($id, $AppVersionList);
        if ($result) {
            $types = RequestHelper::post('types');//0 安卓  1 IOS
            $AppChannel['app_id'] = $id;
            $baiduurl = RequestHelper::post('abaidu');
            $a360url = RequestHelper::post('a360');
            //$AppChannel['type'] = RequestHelper::post('abaidu');
            //$AppChannel['type'] = RequestHelper::post('a360');
            //$AppChannel['url'] = '';
            $AppChannel['create_time'] = date('Y-m-d H:i:s', $AppVersionList['update_time']);
            $AppChannel['update_time'] = date('Y-m-d H:i:s', $AppVersionList['update_time']);

            if ($types == '1') {
                //add
                if ($AppVersionList['type'] == '0') {
                    if (!empty($baiduurl)) {
                        $AppChannel['url'] = $baiduurl;
                        $AppChannel['type'] = 0;
                        $result = $AppChannel_model->addapp($AppChannel);
                        if (!$result) {
                            return $this->error('1-0baidu修改失败', 'index');
                            //return $this->error('修改失败','index');
                        }
                    }
                    if (!empty($a360url)) {
                        $AppChannel['url'] = $a360url;
                        $AppChannel['type'] = 1;
                        $result = $AppChannel_model->addapp($AppChannel);
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
                    if (!empty($baiduurl)) {
                        $cond = "app_id='" . $id . "' and type='0'";
                        $AppChannel['url'] = $baiduurl;
                        //var_dump($AppChannel);
                        //exit;
                        $result = $AppChannel_model->editapp($cond, $AppChannel);
                        if (!$result) {
                            //return $this->error('0-0baidu修改失败','index');
                            return $this->error('修改失败', 'index');
                        }
                    }
                    if (!empty($a360url)) {
                        $cond = "app_id='" . $id . "' and type='1'";
                        $AppChannel['url'] = $a360url;
                        $result = $AppChannel_model->editapp($cond, $AppChannel);
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
                    $result = $AppChannel_model->deloneurl($app_id);
                    if ($result) {
                        return $this->success('修改成功', 'index');
                        //return $this->success('0-1修改成功','index');
                    } else {
                        return $this->error('0-1修改失败', 'index');
                        //return $this->error('修改失败','index');
                    }
                }
            }
        } else {
            return $this->error('修改失败', 'index');
        }
    }

    /**
     * 简介：删除
     * @return string
     */
    public function actionDeloneurl()
    {
        $AppVersionList_model = new AppVersionList();
        $AppChannel_model = new AppChannel();
        $id = RequestHelper::get('id');

        $cond = "id='" . $id . "'";
        $AppVersionList_result = $AppVersionList_model->showoneurl($cond);
        $AppChannel_result = "";
        if ($AppVersionList_result['type'] == '0') {
            //删除安卓类型的url
            $AppVersionList_result = $AppVersionList_model->deloneurl($id);
            if ($AppVersionList_result) {
                $app_channel_model_result = $AppChannel_model->deloneurl($id);
                if ($app_channel_model_result) {
                    return $this->success('删除成功', 'index');
                } else {
                    return $this->error('删除失败', 'index');
                }
            } else {
                return $this->error('删除失败', 'index');
            }
        } else {
            //删除iOS类型的url
            $AppVersionList_result = $AppVersionList_model->deloneurl($id);
            if ($AppVersionList_result) {
                return $this->success('删除成功', 'index');
            } else {
                return $this->error('删除失败', 'index');
            }
        }

    }

    /**
     * 简介：获得add 时AJAX数据
     * @return int
     */
    public function actionGetnameajax()
    {
        $AppVersionList_model = new AppVersionList();
        $name = RequestHelper::get('namea');
        if (strlen($name) > 0) {
            $where = "name='" . $name . "'";
            $cond = ['like', 'major', ''];
        }
        $result = $AppVersionList_model->totalNum($cond, $where);
        if ($result) {
            return 0;
        } else {
            return 1;
        }
    }
}
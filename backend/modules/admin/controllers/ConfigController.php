<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  ConfigController.php
 * @author    lichenjun <liAchenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/10 下午1:50
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\CrmConfig;
use backend\models\i500m\Log;
use common\helpers\RequestHelper;
use yii\data\Pagination;


/**
 * Class ConfigController
 * @category  PHP
 * @package   Admin
 * @filename  ConfigController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/10 下午1:50
 * @link      http://www.i500m.com/
 */
class ConfigController extends BaseController
{
    /**
     * 简介：管理员列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $title = RequestHelper::get('title', '');
        $page = RequestHelper::get('page', 1, 'intval');

        $where = array();
        if ($title) {
            $where['title'] = $title;
        }
        if (empty($where)) {
            $where = 1;
        }
        $crmConfigModel = new CrmConfig();
        $count = $crmConfigModel->getListCount($where);
        $list = $crmConfigModel->getPageList($where, "*", 'id desc ', $page, $this->size);
        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        $data = [
            'list' => $list,
            'pages' => $pages,
            'title' => $title
        ];
        return $this->render('index', $data);
    }


    /**
     * 简介：管理员添加
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAdd()
    {
        die("功能不开放");
        $model = new CrmConfig();
        $CrmConfig = RequestHelper::post('CrmConfig');
        if (!empty($CrmConfig)) {
            $where = array('key' => $CrmConfig['key']);
            $count = $model->getCount($where);
            if ($count == 0) {
                $result = $model->insertInfo($CrmConfig);
                if ($result == true) {
                    $log = new Log();
                    $log_info = '管理员 ' . \Yii::$app->user->identity->username . '添加网站配置' . $CrmConfig['key'];
                    $log->recordLog($log_info, 11);
                    return $this->success('添加成功', '/admin/config/index');
                }
            } else {
                $model->addError('key', '字段名已经存在');
            }
        }
        return $this->render('add', ['model' => $model]);

    }

    /**
     * 简介：管理员修改
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new CrmConfig();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, false, '*');
        $CrmConfig = RequestHelper::post('CrmConfig');
        if (!empty($CrmConfig)) {
            unset($CrmConfig['key']);
            unset($CrmConfig['title']);
            $model->attributes = $CrmConfig;
            $result = $model->updateInfo($CrmConfig, $cond);
            if ($result == true) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '修改了网站配置' . var_export($CrmConfig, true);
                $log->recordLog($log_info, 11);
                $this->redirect('/admin/config/index');
            }
        }
        return $this->render('add', ['model' => $item]);
    }

    /**
     * 简介：删除
     * @author  lichenjun@iyangpin.com。
     * @return int
     */
    /*
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $model = new CrmConfig();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了id为' . $id . '的网站配置';
                $log->recordLog($log_info, 11);
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }
    */

    /**
     * 简介：AJAX提交
     * @author  lichenjun@iyangpin.com。
     * @return int
     */
    /*
    public function actionAjaxDelete()
    {
        $code = 0;
        $id = RequestHelper::post('ids');
        if (!empty($id)) {
            $model = new CrmConfig();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了id为' . $id . '的网站配置';
                $log->recordLog($log_info, 11);
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }
    */
}

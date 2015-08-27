<?php
/**
 * 简介:敏感词管理
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   I500
 * @filename  SensitiveKeywordsController.php
 * @author    lichenjun+zhoujun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/23 下午8:21
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\modules\admin\controllers;
use backend\controllers\BaseController;
use backend\models\i500m\SensitiveKeywords;
use backend\models\i500m\Log;
use common\helpers\RequestHelper;
use yii\data\Pagination;
class SensitiveKeywordsController extends BaseController
{
    public $size = 10;

    /**
     * @purpose:敏感词列表
     * @name: actionIndex
     * @return string
     */
    public function actionIndex()
    {
        $data = array();
        $data['page'] = RequestHelper::get('page', 1, 'intval');
        $data['size'] = RequestHelper::get('per-page', $this->size, 'intval');
        $offset = ($data['page'] - 1) * $data['size'];
        $model = new SensitiveKeywords();
        $list = $model->show($data, $offset);
        $total = $model->total();
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        return $this->render('index', ['info' => $list, 'pages' => $pages]);
    }

    /**
     * @purpose:敏感词添加
     * @name: actionAdd
     * @return string
     */
    public function actionAdd()
    {
        $model = new SensitiveKeywords();
        if ($_POST) {
            $data = RequestHelper::post('SensitiveKeywords', array());
            $info = $model->insertInfo($data);
            if ($info == true) {
                $log = new Log();
                $log_info = '管理员 '.\Yii::$app->user->identity->username .'添加了敏感词'.$data['keyword'];
                $log->recordLog($log_info, 10);
                return $this->success('添加成功', '/admin/sensitivekeywords/index');
            } else {
                return $this->error('添加失败', '/admin/sensitivekeywords/index');
            }

        }
        return $this->render('add', ['model' => $model]);
    }

    /**
     * @purpose:敏感词删除
     * @name: actionDel
     * @return string
     */
    public function actionDel()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        if ($id) {
            $model = new SensitiveKeywords();
            $where = "id=" . $id;
            $info = $model->delOneRecord($where);
            if ($info == true) {
                $log = new Log();
                $log_info = '管理员 '.\Yii::$app->user->identity->username .'删除了ID为'.$id.'的敏感词';
                $log->recordLog($log_info, 10);
                return $this->success('删除成功', '/admin/sensitivekeywords/index');
            } else {
                return $this->error('删除失败', '/admin/sensitivekeywords/index');
            }

        } else {
            return $this->error('参数失败', '/admin/sensitivekeywords/index');
        }
    }
    /**
     * @purpose:敏感词状态更新
     * @name: actionUp
     * @return string
     */
    public function actionUp()
    {
        $id = RequestHelper::get('id',1,'intval');
        $model = new SensitiveKeywords();
        $info = $model->UpdateStatus(array('id'=>$id));
        if ($info) {
            $log = new Log();
            $log_info = '管理员 '.\Yii::$app->user->identity->username .'更改了ID为'.$id.'的敏感词状态';
            $log->recordLog($log_info, 10);
            echo "1";exit;
        } else {
            echo "0";exit;
        }
    }
}
//    /**
//     * @purpose:敏感词状态更新
//     * @name: actionUp
//     * @return string
//     */
//    public function actionUp()
//    {
//        $id = RequestHelper::get('id',1,'intval');
//        $model = new SensitiveKeywords();
//        $info = $model->UpdateStatus(array('id'=>$id));
//        if ($info['code']==200) {
//            echo "1";exit;
//        } else {
//            echo "0";exit;
//        }
//    }
//}
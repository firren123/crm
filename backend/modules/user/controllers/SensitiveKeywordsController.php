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

namespace backend\modules\user\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\SensitiveKeywords;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * Class SensitiveKeywordsController
 * @category  PHP
 * @package   SensitiveKeywordsController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SensitiveKeywordsController extends BaseController
{
    public $size = 10;

    /**
     * 简介：
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
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index', ['info' => $list, 'pages' => $pages]);
    }
    /**
     * 敏感词添加
     * @name: actionAdd
     * @return string
     */
    public function actionAdd()
    {
        $model = new SensitiveKeywords();
        if ($_POST) {
            $data = RequestHelper::post('SensitiveKeywords', array());
            $verify = $model->verify($data['keyword']);
            if ($verify) {
                return $this->redirect("/user/sensitivekeywords/link");
            } else {
                $model->add($data['keyword'], $data['status']);
                return $this->redirect("/user/sensitivekeywords/index");
            }
        } else {
            return $this->render('add', ['model'=>$model]);
        }
    }

    /**
     * 敏感词删除
     * @name: actionDel
     * @return string
     */
    public function actionDel()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        if ($id) {
            $model = new SensitiveKeywords();
            $info = $model->del(array('id'=>$id));
            return $this->redirect("/user/sensitivekeywords/index");
        } else {
            return $this->error('参数失败', '/user/sensitivekeywords/');
        }
    }

    /**
     * 敏感词状态更新
     * @name: actionUp
     * @return string
     */
    public function actionUp()
    {
        $id = RequestHelper::get('id', 1, 'intval');
        $model = new SensitiveKeywords();
        $info = $model->UpdateStatus(array('id'=>$id));
        if ($info) {
            echo "1";
            exit;
        } else {
            echo "0";
            exit;
        }
    }

    /**
     * 敏感词重复添加提示
     * @name: actionLink
     * @return string
     */
    public function actionLink()
    {
        $model = new SensitiveKeywords();
        return $this->render('link', ['model' => $model]);
    }
}

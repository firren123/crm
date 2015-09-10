<?php
/**
 * 用户管理
 *
 * PHP Version 5
 * 用户想过操作
 *
 * @category  CRM
 * @package   UserController
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/4/18 下午1:52
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   2015 http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */
namespace backend\modules\user\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Log;
use backend\models\i500m\User;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * Class UserController
 * @category  PHP
 * @package   UserController
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class UserController extends BaseController
{
    /**
     * 简介：用户列表
     * @return string
     */
    public function actionIndex()
    {
        $list = new User();
        //分页
        $page = RequestHelper::get('page', 1, 'intval');//获得当前的页数
        $pageSize = 12;//设置每页显示的记录条数
        // 查询条件
        $username = RequestHelper::get('username', '', 'htmlspecialchars');
        if (!empty($username)) {
            //$cond="username LIKE '%".$username."%'";
            //$cond = $username;
            $cond = ['like', 'username', $username];
            $total = $list->totalNum($cond);//获得总记录条数
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        } else {
            $cond = ['like', 'username', ''];
            $total = $list->totalNum($cond);//获得总记录条数
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        }
        $fields = '*';
        $order = 'id desc';
        $allUsers = $list->getAllUser($cond, $fields, $order, $page, $pageSize);
        return $this->render('index', array('list' => $allUsers, 'pages' => $pages, 'model' => $list));
    }

    /**
     * 简介：显示个人详情页
     * @return string
     */
    public function actionShowoneuser()
    {
        $list = new User();
        $id = RequestHelper::get('id');
        $cond = array();
        $cond['id'] = $id;
        $fields = '*';
        $allUsers = $list->getOneuser($cond, $fields);
        return $this->render('showoneuser', array('list' => $allUsers));
    }

    /**
     * 简介：重置一个用户的密码  显示个人修改页
     * @return string
     */
    public function actionEditoneuser()
    {
        $list = new User();
        $id = RequestHelper::get('id');
        $cond = array();
        $cond['id'] = $id;
        $fields = '*';
        $allUsers = $list->getOneuser($cond, $fields);
        if (RequestHelper::get('psw')) {
            //记录日志  刘伟
            $content = "管理员：" . \Yii::$app->user->identity->username . ',修改了:' . $allUsers['username'] . '(' . $id . ')' . ' 的密码';
            $log_model = new Log();
            $log_model->recordLog($content, 5);
            return $this->render('editoneuserpsw', array('list' => $allUsers, 'model' => $list));
        } else {

            return $this->render('editoneuser', array('list' => $allUsers, 'model' => $list));
        }
        //var_dump($allUsers);
    }

    /**
     * 简介：修改个人信息
     * @return string
     */
    public function actionEditoneusermsg()
    {
        $model = new User();
        //1。接收VIEW值
        $editid = RequestHelper::post('editid');
        $editarrpswmsg = RequestHelper::post('password');
        $editarrmsg = array(
            'email' => RequestHelper::post('email'),
            'mobile' => RequestHelper::post('mobile'),
            'status' => RequestHelper::post('status'),
            'balance' => RequestHelper::post('balance')
        );
        //2.更新
        if (!empty($editarrpswmsg)) {
            $result = $model->editUserPswMsg($editid, $editarrpswmsg);

        } else {
            $item = $model->getInfo(['id' => $editid]);
            $array = array_diff($editarrmsg, $item);
            //记录日志  刘伟
            if ($array or $editarrmsg['status'] != $item['status']) {
                $content = "管理员：" . \Yii::$app->user->identity->username . ',修改了:' . $item['username'] . '(' . $editid . ')';
                if (!empty($array['email'])) {
                    $content .= ',邮箱:' . $editarrmsg['email'];
                }
                if (!empty($array['mobile'])) {
                    $content .= ',手机号:' . $editarrmsg['mobile'];
                }
                if ($editarrmsg['status'] != $item['status']) {
                    $status = $editarrmsg['status'] == 1 ? '冻结' : '激活';
                    $content .= ',状态id:' . $editarrmsg['status'] . ' 状态值:' . $status;
                }
                if (!empty($array['balance'])) {
                    $content .= ',账户余额:' . $editarrmsg['balance'];
                }
                $log_model = new Log();
                $log_model->recordLog($content, 5);
            }
            $result = $model->editUserMsg($editid, $editarrmsg);
        }
        if ($result) {

            return $this->success('修改成功！', 'index');
        } else {
            return $this->error('修改失败！', 'index');
        }
    }

    /**
     * 简介：锁定一个用户
     * @return string
     */
    public function actionLockoneuser()
    {
        $list = new User();
        $id = RequestHelper::get('id');
        $item = $list->getInfo(['id' => $id]);
        $oneuser = $list->lock($id);
        //判断是否更改成功
        if ($oneuser) {
            //记录日志  刘伟
            $content = "管理员：" . \Yii::$app->user->identity->username . ',把:' . $item['username'] . '(' . $id . ')' . ' 的状态修改为:锁定';
            $log_model = new Log();
            $log_model->recordLog($content, 5);
            return $this->success('锁定成功！', 'index');
        } else {
            return $this->error('锁定失败！(用户信息需要完善)', 'index');
        }
    }

    /**
     * 简介：锁定大批用户
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAlllock()
    {
        $list = new User();
        $arrid = array();
        $id = RequestHelper::get('arrid');
        $arrid = explode(',', $id);
        $arruser = $list->alllock($arrid);
        //判断是否更改成功
        if ($arruser) {
            //记录日志  刘伟
            $content = "管理员：" . \Yii::$app->user->identity->username . ',把id为:(' . $id . ')' . ' 的状态修改为:锁定';
            $log_model = new Log();
            $log_model->recordLog($content, 5);
            return $this->success('全部锁定成功！', 'index');
        } else {
            return $this->error('全部锁定失败！(用户信息需要完善)', 'index');
        }
    }

    /**
     * 简介：解锁一个用户
     * @return string
     */
    public function actionUnlockoneuser()
    {
        $list = new User();
        $id = RequestHelper::get('id');
        $item = $list->getInfo(['id' => $id]);
        $oneuser = $list->lock($id);
        //var_dump($oneuser);
        //exit;
        //判断是否更改成功
        if ($oneuser) {
            //记录日志  刘伟
            $content = "管理员：" . \Yii::$app->user->identity->username . ',把:' . $item['username'] . '(' . $id . ')' . ' 的状态修改为:激活';
            $log_model = new Log();
            $log_model->recordLog($content, 5);
            return $this->success('解锁成功！', 'index');
        } else {
            return $this->error('解锁失败！', 'index');
        }

    }

    /**
     * 简介：解锁大批用户
     * @return string
     */
    public function actionAllunlock()
    {
        $list = new User();
        $arrid = array();
        $id = RequestHelper::get('arrid');
        $arrid = explode(',', $id);
        $arruser = $list->allunlock($arrid);
        //判断是否更改成功
        if ($arruser) {
            //记录日志  刘伟
            $content = "管理员：" . \Yii::$app->user->identity->username . ',把id为:(' . $id . ')' . ' 的状态修改为:激活';
            $log_model = new Log();
            $log_model->recordLog($content, 5);
            return $this->success('全部解锁成功！', 'index');
        } else {
            return $this->error('全部解锁失败！', 'index');
        }
    }

    /**
     * 简介：
     * @return null
     */
    public function actionShowd()
    {
        $list = new User();
        $arruser = $list->showdatatime();
    }
}

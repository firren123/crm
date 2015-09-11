<?php
/**
 * 用户管理
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   CRM
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @time      15/4/20 下午2:50
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      weitonghe@iyangpin.com
 */
namespace backend\modules\user\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Log;
use backend\models\i500m\User;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * 用户管理
 *
 * @category PHP
 * @package  CRM
 * @author   weitonghe <weitonghe@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     weitonghe@iyangpin.com
 */
class UserController extends BaseController
{
    /**
     * 用户列表
     * @return string
     */
    public function actionIndex()
    {
        $list = new User();
        $page = RequestHelper::get('page', 1, 'intval');//获得当前的页数
        $pageSize = 12;                                 //设置每页显示的记录条数
        // 查询条件
        $username = RequestHelper::get('username', '', 'htmlspecialchars');
        $cond   = ['like','username',$username];
        $total  = $list->totalNum($cond);//获得总记录条数
        $pages  = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        $fields = '*';
        $order  = '';
        $allUsers = $list->getAllUser($cond, $fields, $order, $page, $pageSize);
        return $this->render('index', array('list' => $allUsers,'pages' => $pages,'model' => $list));
    }
    /**
     * 显示个人详情页
     * @return string
     */
    public function actionShowoneuser()
    {
        $list = new User();
        $id = RequestHelper::get('id');
        $cond['id'] = $id;
        $fields = '*';
        $allUsers = $list->getOneuser($cond, $fields);
        return $this->render('showoneuser', array('list' => $allUsers));
    }

    /**
     * 重置一个用户的密码/显示个人修改页
     * @return string
     */
    public function actionEditoneuser()
    {
        $list = new User();
        $id = RequestHelper::get('id');
        $cond['id'] = $id;
        $fields = '*';
        $allUsers = $list->getOneuser($cond, $fields);
        $Pwd = RequestHelper::get('psw');
        if ($Pwd) {
            //记录日志  刘伟
            $content = "管理员：" . \Yii::$app->user->identity->username . ',修改了:' . $allUsers['username'] . '(' . $id . ')' . ' 的密码';
            $log_model = new Log();
            $log_model->recordLog($content, 5);
            return $this->render('editoneuserpsw', array('list' => $allUsers, 'model' => $list));
        } else {
            return $this->render('editoneuser', array('list' => $allUsers, 'model' => $list));
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
        $oneUser = $list->lock($id);
        //判断是否更改成功
        if ($oneUser) {
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
     * 修改个人信息
     * @return string
     */
    public function actionEditoneusermsg()
    {
        $model = new User();
        $editId  = RequestHelper::post('editid');
        $editPwd = RequestHelper::post('password');
        $editMsg = array(
            'email'=>RequestHelper::post('email'),
            'mobile'=>RequestHelper::post('mobile'),
            'status'=>RequestHelper::post('status'),
            'balance'=>RequestHelper::post('balance')
        );
        if (!empty($editPwd)) {
            $result = $model->editUserPswMsg($editId, $editPwd);
        } else {
            $item = $model->getInfo(['id'=>$editId]);
            $array = array_diff($editMsg, $item);
            //记录日志  刘伟
            if ($array or $editMsg['status']!=$item['status']) {
                $content = "管理员：".\Yii::$app->user->identity->username.',修改了:'.$item['username'].'('.$editId.')';
                if (!empty($array['email'])) {
                    $content .=  ',邮箱:'.$editMsg['email'];
                }
                if (!empty($array['mobile'])) {
                    $content .=  ',手机号:'.$editMsg['mobile'];
                }
                if ($editMsg['status']!=$item['status']) {
                    $status = $editMsg['status']==1 ? '冻结' : '激活';
                    $content .=  ',状态id:'.$editMsg['status'].' 状态值:'.$status;
                }
                if (!empty($array['balance'])) {
                    $content .=  ',账户余额:'.$editMsg['balance'];
                }
                $log_model = new Log();
                $log_model->recordLog($content, 5);
            }
            $result = $model->editUserMsg($editId, $editMsg);
        }
        if ($result) {
            return $this->success('修改成功！', 'index');
        } else {
            return $this->error('修改失败！', 'index');
        }
    }

    /**
     * 锁定大批用户
     * @return string
     */
    public function actionAlllock()
    {
        $list  = new User();
        $id = RequestHelper::get('arrid');
        $arrId = explode(',', $id);
        $arrUser = $list->alllock($arrId);
        //判断是否更改成功
        if ($arrUser) {
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
     * 解锁一个用户
     * @return string
     */
    public function actionUnlockoneuser()
    {
        $list = new User();
        $id   = RequestHelper::get('id');
        $item = $list->getInfo(['id'=>$id]);
        $oneUser = $list->lock($id);
        //判断是否更改成功
        if ($oneUser) {
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
     * 解锁大批用户
     * @return string
     */
    public function actionAllunlock()
    {
        $list = new User();
        $id = RequestHelper::get('arrid');
        $arrId = explode(',', $id);
        $arrUser = $list->allunlock($arrId);
        //判断是否更改成功
        if ($arrUser) {
            //记录日志  刘伟
            $content = "管理员：" . \Yii::$app->user->identity->username . ',把id为:(' . $id . ')' . ' 的状态修改为:激活';
            $log_model = new Log();
            $log_model->recordLog($content, 5);
            return $this->success('全部解锁成功！', 'index');
        } else {
            return $this->error('全部解锁失败！', 'index');
        }
    }
}


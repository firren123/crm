<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
<<<<<<< HEAD
 * @package   Admin
=======
 * @package   CRM
>>>>>>> crmwth
 * @filename  User.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/20 下午2:50
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\models\i500m;

<<<<<<< HEAD

/**
 * Class User
 * @category  PHP
 * @package   User
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class User extends I500Base
{
=======
/**
 * 用户表
 *
 * @category MODEL
 * @package  User
 * @author   weitonghe <weitonghe@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     weitonghe@iyangpin.com
 */
class User extends I500Base
{

>>>>>>> crmwth
    /**
     * 设置表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
<<<<<<< HEAD
            //不可为空的字段
            [['password'], 'required', 'message' => '密码不能为空！'],
            [['email'], 'required', 'message' => '电子邮箱不能为空！'],
            [['mobile'], 'required', 'message' => '手机号不能为空！'],
            [['status'], 'required', 'message' => '状态不能为空！'],
            [['balance'], 'required', 'message' => '余额不能为空！'],
=======
            [['password'],'required','message' => '密码不能为空！'],
            [['email'],'required','message' => '电子邮箱不能为空！'],
            [['mobile'],'required','message' => '手机号不能为空！'],
            [['status'],'required','message' => '状态不能为空！'],
            [['balance'],'required','message' => '余额不能为空！'],
>>>>>>> crmwth
        ];
    }

    /**
<<<<<<< HEAD
     * 简介：获得总的记录条数
     * @param $where
     * @return int|string
     */
    public function totalNum($where)
    {
        $listcount = User::find()->andFilterWhere($where)->count();
        return $listcount;
=======
     * 获得总的记录条数
     * @param Array $where 查询条件
     * @return array|int|string
     */
    public function totalNum($where)
    {
        $listCount = User::find()->andFilterWhere($where)->count();
        return $listCount;
>>>>>>> crmwth
    }

    /**
     * 根据条件查询记录 并分页
<<<<<<< HEAD
     * @param array  $where  X
     * @param string $fields X
     * @param string $order  X
     * @param int    $page   X
     * @param int    $size   X
     * @return array
     */
    public function getAllUser($where = array(), $fields = '*', $order = '', $page = 1, $size = 2)
    {
        $allGoods = [];
=======
     * @param array  $where  条件
     * @param string $fields 字段
     * @param string $order  排序
     * @param int    $page   页数
     * @param int    $size   每页记录数
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllUser($where=array() , $fields='*' , $order='' , $page = 1 , $size = 2)
    {
>>>>>>> crmwth
        $allGoods = $this->find()
            ->where($where)
            ->select($fields)
            ->orderBy($order)
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->asArray()
            ->all();
        return $allGoods;
    }

    /**
     * 获得一个人的信息
<<<<<<< HEAD
     * @param array  $where X
     * @param string $field X
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOneuser($where = array(), $field = '*')
    {
        $oneuser = [];
        $oneuser = $this->find()
=======
     * @param array  $where 条件
     * @param string $field 字段
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getOneuser($where = array() , $field = '*')
    {
        $oneUser = $this->find()
>>>>>>> crmwth
            ->where($where)
            ->select($field)
            ->asArray()
            ->one();
        return $oneUser;
    }

    /**
     * 修改一个人的信息
<<<<<<< HEAD
     * @param int   $id  x
     * @param array $msg x
     * @return bool
     */
    public function editUserMsg($id, $msg = [])
=======
     * @param int   $id  ID
     * @param array $msg Data
     * @return bool
     */
    public function editUserMsg($id,$msg=[])
>>>>>>> crmwth
    {
        $user = $this->findOne($id);

        $user->email = $msg['email'];
        $user->mobile = $msg['mobile'];
        $user->status = $msg['status'];
        $user->balance = $msg['balance'];

        $result = $user->save();
        return $result;
    }

    /**
     * 重置密码
<<<<<<< HEAD
     * @param int    $id  x
     * @param string $psw x
     * @return bool
     */
    public function editUserPswMsg($id, $psw)
=======
     * @param int    $id  ID
     * @param string $psw Password
     * @return bool
     */
    public function editUserPswMsg($id,$psw)
>>>>>>> crmwth
    {
        $user = $this->findOne($id);
        $salt = rand(100000, 999999);
        $user->salt = $salt;
<<<<<<< HEAD
        $user->password = md5($salt . md5($psw));
        $result = $user->save();  // 等同于 $supplier->update();
=======
        $user->password = md5($salt.md5($psw));
        $result = $user->save();
>>>>>>> crmwth
        return $result;
    }

    /**
<<<<<<< HEAD
     * 简介：冻结/解锁用户
     * @param int $id x
=======
     * 冻结/解锁用户
     * @param int $id ID
>>>>>>> crmwth
     * @return bool
     */
    public function lock($id)
    {
        $user = $this->findOne($id);
        if (!empty($user)) {
<<<<<<< HEAD
            if ($user['status'] == 1) {
                $user['status'] = 2;
            } else {
                $user['status'] = 1;
=======
            if ($user['status']==1) {
                $user['status']=2;
            } else {
                $user['status']=1;
>>>>>>> crmwth
            }
            $result = $user->save();
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 全部锁定
<<<<<<< HEAD
     * @param array $arrid x
     * @return bool
     */
    public function alllock($arrid = array())
    {

        foreach ($arrid as $k => $v) {
            if (!$v) {
                unset($arrid[$k]);
            }
        }

        foreach ($arrid as $k => $v) {
            $userid = User:: findOne($v);
            if (!empty($userid)) {
                $userid->status = 1;
                $result = $userid->save();
                if ($result != 1) {
=======
     * @param array $arrId ArrID
     * @return bool
     */
    public function alllock($arrId = array())
    {
        foreach ($arrId as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $userId = User :: findOne($v);
            if (!empty($userId)) {
                $userId->status = 1;
                $result = $userId->save();
                if ($result!=1) {
>>>>>>> crmwth
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 全部激活
<<<<<<< HEAD
     * @param array $arrid x
     * @return bool
     */
    public function allunlock($arrid = array())
    {
        foreach ($arrid as $k => $v) {
            if (!$v) {
                unset($arrid[$k]);
            }
        }
        foreach ($arrid as $k => $v) {
            $userid = User:: findOne($v);
            if (!empty($userid)) {
                $userid->status = 2;
                $result = $userid->save();
                if ($result != 1) {
=======
     * @param array $arrId ArrID
     * @return bool
     */
    public function allunlock($arrId = array())
    {
        foreach ($arrId as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $userId = User :: findOne($v);
            if (!empty($userId)) {
                $userId->status = 2;
                $result = $userId->save();
                if ($result!=1) {
>>>>>>> crmwth
                    return false;
                }
            }
        }
        return true;
    }

<<<<<<< HEAD
    /**
     * 简介：
     * @return bool
     */
    public function showdatatime()
    {
        //date_default_timezone_set("PRC");
        $show = User:: find()
            ->where(['username' => '18310051157'])
            ->select('id,username,password,status')
            ->asArray()
            ->all();
        //login 登录时间 晚
        var_dump($show);
        //var_dump($v['login_time']);
        exit;
        foreach ($show as $k => $v) {
            //$v['login_time']=60*60*24+strtotime($v['add_time']);
            //$v['login_time']=date("Y-m-d H:i:s", $v['login_time']);
            //$v+=10;
            //var_dump($v['login_time']);
            $userid = User:: findOne($v['id']);
            $userid->password = $v['id'];
            $result = $userid->save();
            if ($result != 1) {
                return false;
            }
        }
        return true;
    }
}
=======
}
>>>>>>> crmwth

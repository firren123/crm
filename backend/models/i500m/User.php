<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   CRM
 * @filename  User.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/20 下午2:50
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\models\i500m;

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
            [['password'], 'required', 'message' => '密码不能为空！'],
            [['email'], 'required', 'message' => '电子邮箱不能为空！'],
            [['mobile'], 'required', 'message' => '手机号不能为空！'],
            [['status'], 'required', 'message' => '状态不能为空！'],
            [['balance'], 'required', 'message' => '余额不能为空！'],
        ];
    }

    /**
     * 获得总的记录条数
     * @param Array $where 查询条件
     * @return array|int|string
     */
    public function totalNum($where)
    {
        $listCount = User::find()->andFilterWhere($where)->count();
        return $listCount;
    }

    /**
     * 根据条件查询记录 并分页
     * @param array  $where  条件
     * @param string $fields 字段
     * @param string $order  排序
     * @param int    $page   页数
     * @param int    $size   每页记录数
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllUser($where = array(), $fields = '*', $order = '', $page = 1, $size = 2)
    {
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
     * @param array  $where 条件
     * @param string $field 字段
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getOneuser($where = array(), $field = '*')
    {
        $oneUser = $this->find()
            ->where($where)
            ->select($field)
            ->asArray()
            ->one();
        return $oneUser;
    }

    /**
     * 修改一个人的信息
     * @param int   $id  ID
     * @param array $msg Data
     * @return bool
     */
    public function editUserMsg($id, $msg = [])
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
     * @param int    $id  ID
     * @param string $psw Password
     * @return bool
     */
    public function editUserPswMsg($id, $psw)
    {
        $user = $this->findOne($id);
        $salt = rand(100000, 999999);
        $user->salt = $salt;
        $user->password = md5($salt . md5($psw));
        $result = $user->save();
        return $result;
    }

    /**
     * 冻结/解锁用户
     * @param int $id ID
     * @return bool
     */
    public function lock($id)
    {
        $user = $this->findOne($id);
        if (!empty($user)) {
            if ($user['status'] == 1) {
                $user['status'] = 2;
            } else {
                $user['status'] = 1;
            }
            $result = $user->save();
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 全部锁定
     * @param array $arrId ArrID
     * @return bool
     */
    public function alllock($arrId = array())
    {
        foreach ($arrId as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $userId = User:: findOne($v);
            if (!empty($userId)) {
                $userId->status = 1;
                $result = $userId->save();
                if ($result != 1) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 全部激活
     * @param array $arrId ArrID
     * @return bool
     */
    public function allunlock($arrId = array())
    {
        foreach ($arrId as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $userId = User:: findOne($v);
            if (!empty($userId)) {
                $userId->status = 2;
                $result = $userId->save();
                if ($result != 1) {
                    return false;
                }
            }
        }
        return true;
    }
}
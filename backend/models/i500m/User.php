<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  User.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/20 下午2:50
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\models\i500m;
use backend\models\i500m\I500Base;
class User extends I500Base{
    /**
     * 数据库
     *user 表
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
            //不可为空的字段
            [['password'],'required','message' => '密码不能为空！'],
            [['email'],'required','message' => '电子邮箱不能为空！'],
            [['mobile'],'required','message' => '手机号不能为空！'],
            [['status'],'required','message' => '状态不能为空！'],
            [['balance'],'required','message' => '余额不能为空！'],
        ];
    }
    /**
     * @param array $where
     * 获得总的记录条数
     */
    public function totalNum($where){
//        $listcount = $this->find()
//            ->where($where)
//            ->count();
        $listcount = User::find()->andFilterWhere($where)->count();
        return $listcount;
    }
    /**
     * @param array $where
     * @param string $fields
     * @param string $order
     * @param int $page
     * @param int $size
     * @return array
     * 根据条件查询记录 并分页
     */
    public function getAllUser($where=array() , $fields='*' , $order='' , $page = 1 , $size = 2){
        $allGoods=[];
        $allGoods=$this->find()
            ->where($where)
            ->select($fields)
            ->orderBy($order)
            ->offset(($page-1) * $size)
            ->limit($size)
            ->asArray()
            ->all();
        return $allGoods;
    }

    /**
     * @param array $where
     * @param string $field
     * @return array|\yii\db\ActiveRecord[]
     * 获得一个人的信息
     */
    public function getOneuser($where = array() , $field = '*'){
        $oneuser = [];
        $oneuser = $this->find()
            ->where($where)
            ->select($field)
            ->asArray()
            ->one();
        return $oneuser;
    }

    /**
     * @param $id
     * @param $msg
     * @return bool
     * 修改一个人的信息
     */
    public function editUserMsg($id,$msg=[]){
        $user = $this->findOne($id);

        $user->email= $msg['email'];
        $user->mobile=$msg['mobile'];
        $user->status=$msg['status'];
        $user->balance=$msg['balance'];

        $result = $user->save();  // 等同于 $supplier->update();
        return $result;
    }

    /**
     * @param $id
     * @param $psw
     * @return bool
     * 重置密码
     */
    public function editUserPswMsg($id,$psw){
        $user = $this->findOne($id);
        $salt = rand(100000,999999);
        $user->salt = $salt;
        $user->password = md5($salt.md5($psw));
        $result = $user->save();  // 等同于 $supplier->update();
        return $result;
    }
    /**
     * @param $id
     * 冻结/解锁用户
     */
    public function lock($id){
        $user = $this->findOne($id);
        if(!empty($user)){
            if($user['status']==1){
                $user['status']=2;
            }else{
                $user['status']=1;
            }
        $result=$user->save();
        return $result;
        }else{return false;}
    }
    /**
     * @param array $arrid
     * @return bool
     * 全部锁定
     */
    public function alllock($arrid=array()){

        foreach( $arrid as $k=>$v){
            if( !$v )
                unset( $arrid[$k] );
        }

        foreach($arrid as $k=>$v){
            $userid = User :: findOne($v);
            if(!empty($userid)){
                $userid->status=1;
                $result=$userid->save();
                if($result!=1){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param array $arrid
     * @return bool
     * 全部激活
     */
    public function allunlock($arrid=array()){
        foreach( $arrid as $k=>$v){
            if( !$v )
                unset( $arrid[$k] );
        }
        foreach($arrid as $k=>$v){
            $userid = User :: findOne($v);
            if(!empty($userid)){
                $userid->status=2;
                $result=$userid->save();
                if($result!=1){
                    return false;
                }
            }
        }
        return true;
    }
    public function showdatatime(){
        //date_default_timezone_set("PRC");
        $show=User:: find()
            ->where(['username' => '18310051157'])
            ->select('id,username,password,status')
            ->asArray()
            ->all();
        //login 登录时间 晚
        var_dump($show);
        //var_dump($v['login_time']);
        exit;
        foreach($show as $k=>$v){
            //$v['login_time']=60*60*24+strtotime($v['add_time']);
            //$v['login_time']=date("Y-m-d H:i:s", $v['login_time']);
            //$v+=10;
            //var_dump($v['login_time']);
            $userid = User :: findOne($v['id']);
            $userid->password = $v['id'] ;
            $result=$userid->save();
            if($result!=1){
                return false;
            }
        }
        return true;
        }
}
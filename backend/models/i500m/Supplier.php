<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/12
 * Time: 12:49
 */
namespace backend\models\i500m;
use yii\db\ActiveRecord;
class Supplier extends I500Base{
    /**
     * *数据库  表名称
     * @return string
     */
    public static function tableName(){
        return "{{%supplier}}";
    }
    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['company_name'],'required','message' => '公司名称不能为空！'],
            [['account'],'required','message' => '账号不能为空！'],
            [['password'],'required','message' => '密码不能为空！'],
            [['contact'],'required','message' => '联系人不能为空！'],
            [['sex'],'required','message' => '性别不能为空！'],
            [['email'],'required','message' => '电子邮箱不能为空！'],
            //[['mobile'],'required','message' => '手机号不能为空！'],
            [['phone'],'required','message' => '固定电话不能为空！'],
            [['qq'],'required','message' => 'QQ不能为空！'],

        ];
    }

    /**
     * @param $account
     * @return array|\yii\db\ActiveRecord[]
     * 查找是否有重复账号
     */
    public function checkaccount($account){
            $result=$this->find()
                ->where($account)
                ->asArray()
                ->all();
            return $result;
    }
    /**
     * 显示所有的信息
     */
    public  function allGoods( $page = 1, $size = 2){
        $allGoods=[];
        $allGoods=$this->find()
            ->offset(($page-1) * $size)
            ->limit($size)
            ->asArray()
            ->all();
        return $allGoods;
    }
    /**
     * 获得总的记录数
     */
    public function totalNum($cond){
//        $total=$this->find()
//            ->where($cond)
//            ->count();
        $total = Supplier::find()->andFilterWhere($cond)->count();
        return $total;
    }
    /**
     * 根据$id查找
     * @param $id
     * @return array
     */
   public function  getPartGoods($cond = array(), $field = '*', $order = '', $page = 1, $size = 2){
       $PartGoods=array();
       if(!empty($cond)){
           $PartGoods=$this->find()
               ->select($field)
               ->where($cond)
               ->offset(($page-1) * $size)
               ->limit($size)
               ->orderBy($order)
               ->asArray()
               ->all();
       }
       return $PartGoods;
   }
/**
 *添加信息
 *@param array
 * @return boolean
 **/
    public  function addGoods($msg){
        $supplier = new Supplier();
        foreach($msg as $k=>$v){
            $supplier->$k = $v;
        }
        $salt = rand(100000,999999);
        $supplier->salt = $salt;
        $supplier->password = md5($salt.md5($msg['password']));
        $result = $supplier->save();  // 等同于 $supplier->insert();
        if($result){
            return $supplier->primaryKey;
        }else{
            return false;
        }
    }
    //添加供应商信息以后用 主键Id  update supplier_code字段
    public function update_supplier_code($id){
        $supplier = Supplier::findOne($id);
        $supplier->supplier_code = $id;
        $result = $supplier->save();  // 等同于 $customer->update();
        return $result;
    }
/**
* 删除信息
* @param $ID
* @return boolean
**/
    public  function delGoods($id){
        $supplier = new Supplier();
        $supplier = Supplier::findOne($id);
        $result=$supplier->delete();
        return $result;
    }
/**
* 全部删除
*/
    public  function delAll($arrId){
        $supplier = new Supplier();
        foreach($arrId as $k=>$v){
            $supplier = Supplier::findOne($v);
            $result=$supplier->delete();
            if(!$result){
                return false;
            }
        }
        return true;
    }
/**
 * 修改信息
 * @param $ID array
 * @return boolean
 */
    public  function editGoods($editid,$list){
        $supplier = new Supplier();
        $supplier = Supplier::findOne($editid);
        if(!empty($supplier)) {
            foreach ($list as $k => $v) {
                $supplier->$k = $v;
            }
            $salt = rand(100000, 999999);
            $supplier->salt = $salt;
            $supplier->password = md5($salt . md5($list['password']));
            $result = $supplier->save();  // 等同于 $supplier->update();
            return $result;
        }else{
            return false;
        }
    }
}
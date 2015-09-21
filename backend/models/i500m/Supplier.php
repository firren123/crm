<?php
/**
 * xxx
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    xxx <xxx@iyangpin.com>
 * @time      15/8/24 10:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      xxx@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Class Supplier
 * @category  PHP
 * @package   Supplier
 * @author    xxx <xxx@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Supplier extends I500Base
{
    /**
     * *数据库  表名称
     * @return string
     */
    public static function tableName()
    {
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
            [['company_name'], 'required', 'message' => '公司名称不能为空！'],
            [['account'], 'required', 'message' => '账号不能为空！'],
            [['password'], 'required', 'message' => '密码不能为空！'],
            [['contact'], 'required', 'message' => '联系人不能为空！'],
            [['sex'], 'required', 'message' => '性别不能为空！'],

//            [['email'], 'required', 'message' => '电子邮箱不能为空！'],
            [['mobile'],'required','message' => '手机号不能为空！'],
//            [['phone'], 'required', 'message' => '固定电话不能为空！'],
//            [['qq'], 'required', 'message' => 'QQ不能为空！'],

        ];
    }

    /**
     * 简介：查找是否有重复账号
     * @param int $account account
     * @return array|\yii\db\ActiveRecord[]
     */
    public function checkaccount($account)
    {
        $result = $this->find()
            ->where($account)
            ->asArray()
            ->all();
        return $result;
    }

    /**
     * 简介：显示所有的信息
     * @param int $page page
     * @param int $size size
     * @return array|\yii\db\ActiveRecord[]
     */
    public function allGoods($page = 1, $size = 2)
    {
        $allGoods = [];
        $allGoods = $this->find()
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->asArray()
            ->all();
        return $allGoods;
    }

    /**
     * 简介：获得总的记录数
     * @param array $cond cond
     * @return int|string
     */
    public function totalNum($cond)
    {
        $total = Supplier::find()->andFilterWhere($cond)->count();
        return $total;
    }

    /**
     * 简介：根据$id查找
     * @param array  $cond  cond
     * @param string $field field
     * @param string $order order
     * @param int    $page  page
     * @param int    $size  size
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPartGoods($cond = array(), $field = '*', $order = '', $page = 1, $size = 2)
    {
        $PartGoods = array();
        if (!empty($cond)) {
            $PartGoods = $this->find()
                ->select($field)
                ->where($cond)
                ->offset(($page - 1) * $size)
                ->limit($size)
                ->orderBy($order)
                ->asArray()
                ->all();
        }
        return $PartGoods;
    }

    /**
     * 简介：添加信息
     * @param string $msg x
     * @return bool|mixed
     */
    public function addGoods($msg)
    {
        $supplier = new Supplier();
        foreach ($msg as $k => $v) {
            $supplier->$k = $v;
        }
        $salt = rand(100000, 999999);
        $supplier->salt = $salt;
        $supplier->password = md5($salt . md5($msg['password']));
        $result = $supplier->save();  // 等同于 $supplier->insert();
        if ($result) {
            return $supplier->primaryKey;
        } else {
            return false;
        }
    }

    /**
     * 简介：添加供应商信息以后用 主键Id  update supplier_code字段
     * @param int $id id
     * @return bool
     */
    public function update_supplier_code($id)
    {
        $supplier = Supplier::findOne($id);
        $supplier->supplier_code = $id;
        $result = $supplier->save();  // 等同于 $customer->update();
        return $result;
    }

    /**
     * 删除信息
     * @param int $id id
     * @return boolean
     */
    public function delGoods($id)
    {
        $supplier = new Supplier();
        $supplier = Supplier::findOne($id);
        $result = $supplier->delete();
        return $result;
    }

    /**
     * 简介：全部删除
     * @param array $arrId array
     * @return bool
     * @throws \Exception
     */
    public function delAll($arrId)
    {
        $supplier = new Supplier();
        foreach ($arrId as $k => $v) {
            $supplier = Supplier::findOne($v);
            $result = $supplier->delete();
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    /**
     * 简介：修改信息
     * @param int   $editid id
     * @param array $list   array
     * @return bool
     */
    public function editGoods($editid, $list)
    {
        $supplier = new Supplier();
        $supplier = Supplier::findOne($editid);
        if (!empty($supplier)) {
            foreach ($list as $k => $v) {
                $supplier->$k = $v;
            }
            $salt = rand(100000, 999999);
            $supplier->salt = $salt;
            $supplier->password = md5($salt . md5($list['password']));
            $result = $supplier->save();  // 等同于 $supplier->update();
            return $result;
        } else {
            return false;
        }
    }
}

<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   MODEL
 * @filename  ShopContract.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/5 下午3:16
 * @link      http://www.i500m.com/
 */
namespace backend\models\shop;
/**
 * ShopContract - model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   weitonghe <weitonghe@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     weitonghe@iyangpin.com
 */
class ShopContract extends ShopBase
{
    /**
     * 数据库
     * ShopContract 表
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_contract}}';
    }

    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            //[['username'],'required','message' => '用户名不能为空！'],
            //[['image'],'required','message' => '合同主图 不能为空.'],
            //[['image'],'match','pattern'=>'/^.*[^a][^b][^c]\.(?:jpg|gif|bmp|bnp|png)$/','message'=>'合同主图 格式不正确'],
        ];
    }

    /**
     * 简介：所有数据
     * @author  weitonghe@iyangpin.com
     * @return int
     */
    public function allInfo()
    {
        $all = $this->find()
            ->asArray()
            ->all();
        return $all;
    }

    /**
     * 简介：添加商家合同基本信息
     *
     * @param array $msg 数据
     *
     * @return primaryKey int
     */
    public function insertOneData($msg)
    {
        $ShopContract_model = new ShopContract();
        foreach ($msg as $k => $v) {
            $ShopContract_model->$k = $v;
        }
        $result = $ShopContract_model->save();
        return $ShopContract_model->primaryKey;
    }

    /**
     * 首页index 显示的  count(记录的条数)
     *
     * @param array  $cond      条件
     * @param array  $and_Cond1 and条件
     * @param array  $and_Cond2 and条件
     * @param array  $and_Cond3 and条件
     * @param array  $and_Cond4 and条件
     * @param array  $and_Cond5 and条件
     * @param string $field     字段
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function countId($cond = [], $and_Cond1 = [], $and_Cond2 = [], $and_Cond3 = [], $and_Cond4 = [], $and_Cond5 = [], $field = '*')
    {
        $list = $this->find()
            ->select($field)
            ->where($cond)
            ->andWhere($and_Cond1)
            ->andWhere($and_Cond2)
            ->andWhere($and_Cond3)
            ->andWhere($and_Cond4)
            ->andWhere($and_Cond5)
            ->count();
        return $list;
    }

    /**
     * 首页index 显示的数据
     *
     * @param array  $cond      条件
     * @param array  $and_Cond1 and条件
     * @param array  $and_Cond2 and条件
     * @param array  $and_Cond3 and条件
     * @param array  $and_Cond4 and条件
     * @param array  $and_Cond5 and条件
     * @param string $field     字段
     * @param string $order     排序
     * @param int    $page      第几页
     * @param int    $pageSize  每一页的数据个数
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function selInfo($cond = [], $and_Cond1 = [], $and_Cond2 = [], $and_Cond3 = [], $and_Cond4 = [], $and_Cond5 = [], $field = '*', $order = 'id DESC', $page = 1, $pageSize = 10)
    {
        $list = $this->find()
            ->select($field)
            ->where($cond)
            ->andWhere($and_Cond1)
            ->andWhere($and_Cond2)
            ->andWhere($and_Cond3)
            ->andWhere($and_Cond4)
            ->andWhere($and_Cond5)
            ->orderBy($order)
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->asArray()
            ->all();
        return $list;
    }

    /**
     * 查询一条记录
     *
     * Author weitonghe@iyangpin.com
     *
     * @param array  $cond     条件
     * @param array  $and_Cond and条件
     * @param string $field    查询的字段
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function selOneInfo($cond = [], $and_Cond = [], $field = '*')
    {
        $list = $this->find()
            ->select($field)
            ->where($cond)
            ->andWhere($and_Cond)
            ->asArray()
            ->one();
        return $list;
    }
}

<?php
/**
 * 供应商入库商品表-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      15/8/24 10:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      sunsong@iyangpin.com
 */
namespace backend\models\i500m;

/**
 * SupplierInfo
 *
 * @category CRM
 * @package  SupplierGood
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class StorageOutGood extends I500Base
{
    /**
     * *数据库  供应商入库商品表
     * @return string
     */
    public static function tableName()
    {
        return "{{%crm_storage_out_goods}}";
    }

    /**
     * 带分组的分页的sql方法
     *
     * @param array  $cond      条件
     * @param string $field     字段
     * @param string $group     分组
     * @param string $order     排序
     * @param string $and_where 第二条件
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAll($cond = array(), $field = '*', $group = '', $order = '', $and_where = '')
    {
        $list = [];
        if ($cond || $and_where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and_where)
                ->groupBy($group)
                ->orderBy($order)
                ->asArray()
                ->all();
        }
        return $list;
    }

    /**
     * 获取一条分组记录
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array  $arr_where    where条件
     * @param string $group        分组
     * @param string $str_andwhere 字符串where条件
     * @param string $str_field    字段
     *
     * @return array
     */
    public function getOneInfo($arr_where, $group = '', $str_andwhere = '', $str_field = '*')
    {
        $arr = $this->find()
            ->select($str_field)
            ->where($arr_where)
            ->andWhere($str_andwhere)
            ->groupBy($group)
            ->asArray()
            ->one();
        if (!$arr) {
            $arr = array();
        }
        return $arr;
    }
}


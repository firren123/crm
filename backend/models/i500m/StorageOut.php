<?php
/**
 * 商品出库信息单表-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      15/8/25 10:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      sunsong@iyangpin.com
 */
namespace backend\models\i500m;

/**
 * SupplierInfo
 *
 * @category CRM
 * @package  StorageOutInfo
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class StorageOut extends I500Base
{
    /**
     * *数据库  供应商入库商品表
     * @return string
     */
    public static function tableName()
    {
        return "{{%crm_storage_out_info}}";
    }

    /**
     * 带分组的分页的sql方法
     *
     * @param array  $cond      条件
     * @param string $field     字段
     * @param string $order     排序
     * @param int    $size      分页
     * @param string $and_where 第二条件
     *
     * @return array
     */
    public function getoneinfo($cond = array(), $field = '*', $order = '', $size = 1, $and_where = '')
    {
        $list = [];
        if ($cond || $and_where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and_where)
                ->orderBy($order)
                ->limit($size)
                ->asArray()
                ->one();
        }
        return $list;
    }
}


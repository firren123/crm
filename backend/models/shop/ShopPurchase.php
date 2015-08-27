<?php
/**
 * 商家进货记录
 *
 * PHP Version 5
 *
 * @category  Admin
 * @package   ShopPurchase.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/6/16 0016 上午 11:01
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */
namespace backend\models\shop;
/**
 * ShopPurchase
 *
 * @category Admin
 * @package  ShopPurchase
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ShopPurchase extends ShopBase
{
    /**
     * 数据表
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_purchase}}';
    }

    /**
     * 单行函数说明
     * @param array $cond
     * @param string $field
     * @param string $order
     * @param int $page
     * @param int $size
     * @param string $and_where
     * @param string $where
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPageLists($cond = [], $field = '*', $order = '', $page = 1, $size = 10, $and_where = '', $where = '')
    {
        $list = [];
        if ($cond || $and_where || $where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and_where)
                ->andWhere($where)
                ->orderBy($order)
                ->offset(($page-1) * $size)
                ->limit($size)
                ->asArray()
                ->all();
        }
        return $list;
    }
    /**
     * 单行函数说明
     *
     * @param array $cond
     * @param string $and_where
     * @param string $where
     *
     * @return int|string
     */
    public function getCounts($cond = array(), $and_where = '', $where = '')
    {
        $num = 0;
        if ($cond || $and_where ||$where) {
            $num = $this->find()->where($cond)->andWhere($and_where)->andWhere($where)->count();
        }
        return $num;
    }
}
<?php
/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  Crm
 * @package   Order.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/9/5 0005 上午 11:59
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\social;

/**
 * Order
 *
 * @category CRM
 * @package  Order
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class Order extends SocialBase
{
    /**
     * 连接表
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_order}}";
    }
    /**
     * 列表
     * @author  lichenjun@iyangpin.com。
     * @param array  $cond      条件1
     * @param string $field     字段
     * @param string $order     排序
     * @param string $and_where 条件2
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList($cond = array(), $field = '*', $order = '', $and_where = '', $where = '')
    {
        $list = [];
        if ($cond || $and_where || $where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($where)
                ->andWhere($and_where)
                ->orderBy($order)
                ->asArray()
                ->all();
        }
        return $list;
    }
    /**
     * 带分页的列表
     * @author  liuwei@iyangpin.com。
     * @param array  $cond      条件1
     * @param string $field     字段
     * @param string $order     排序
     * @param int    $page      分页默认1
     * @param int    $size      每页数量默认10
     * @param string $and_where 条件2
     * @param string $where     条件3
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPageLists($cond = array(), $field = '*', $order = '', $page = 1, $size = 10, $and_where = '', $where = '')
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
     * 简介：获取总数
     * @author  lichenjun@iyangpin.com。
     * @param array  $cond      条件一
     * @param string $and_where 条件二
     * @param string $and       条件三
     * @return int|string
     */
    public function getCounts($cond = array(), $and_where = '', $where = '')
    {
        $num = 0;
        if ($cond || $and_where || $where) {
            $num = $this->find()->where($cond)->andWhere($and_where)->andWhere($where)->count();
        }
        return $num;
    }
}

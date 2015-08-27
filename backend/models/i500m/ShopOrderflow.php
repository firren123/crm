<?php
/**
 * 商家订单每日流水表
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      15/8/11 13:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      sunsongsong@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * 业务员-model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsongsong@iyangpin.com
 */
class ShopOrderflow extends I500Base
{

    /**
     * 商家订单每日流水表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_shop_orderflow}}';
    }

    /**
     * 带分组的分页的sql方法
     *
     * @param array  $cond      条件
     * @param string $field     字段
     * @param string $group     分组
     * @param string $order     排序
     * @param int    $page      分页
     * @param int    $size      分页
     * @param string $and_where 第二条件
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPage($cond = array(), $field = '*', $group = '', $order = '', $page = 1, $size = 10, $and_where = '')
    {
        $list = [];
        if ($cond || $and_where) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and_where)
                ->groupBy($group)
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
     * @param array  $cond      条件
     * @param string $and_where 第二条件
     *
     * @return int|string
     */
    public function getviewCount($cond = array(), $and_where = '')
    {
        $num = 0;
        if ($cond || $and_where) {
            $num = $this->find()->where($cond)->groupBy($and_where)->count();
        }
        return $num;
    }

    /**
     * 带分组查询
     *
     * @param array  $cond      条件
     * @param string $field     字段
     * @param string $group     分组
     * @param string $order     排序
     * @param string $and_where 第二条件
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function groupList($cond = array(), $field = '*', $group = '',$order = '', $and_where = '')
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

}

<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  ActivityShop.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/6/4 上午9:56
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

/**
 * ActivityShop
 *
 * @category CRM
 * @package  ActivityShop
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class ActivityShop extends I500Base
{

    /**
     * 参加活动表
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%crm_activity_shop}}";
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

}
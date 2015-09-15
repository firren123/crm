<?php
/**
 * Model-supplier_goods_limit
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/9/15 18:35
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Model-supplier_goods_limit
 *
 * @category SUPPLIER
 * @package  MODEL
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class SupplierGoodsLimit extends I500Base
{
    /**
     * 设置表名
     *
     * Author zhengyu@iyangpin.com
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%supplier_goods_limit}}";
    }
}

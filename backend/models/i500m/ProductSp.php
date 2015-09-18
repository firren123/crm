<?php
/**
 * Model-product_sp
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/9/18 14:57
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Model-product_sp
 *
 * @category ADMIN
 * @package  MODEL
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class ProductSp extends I500Base
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
        return "{{%product_sp}}";
    }
}

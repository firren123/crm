<?php
/**
 * 商家修改审核表-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/20 17:29 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\shop;

/**
 * 商家修改审核表-model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class ShopVerify extends ShopBase
{

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_verify}}';
    }

}
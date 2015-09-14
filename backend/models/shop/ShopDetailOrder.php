<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  ShopDetailOrder.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/5 上午10:49
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\shop;

/**
 * Class ShopDetailOrder
 * @category  PHP
 * @package   ShopDetailOrder
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ShopDetailOrder extends ShopBase
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return "{{%shop_order_detail}}";
    }
}

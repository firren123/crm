<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  RefundGoods.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/20 下午6:06
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

/**
 * Class RefundGoods
 * @category  PHP
 * @package   RefundGoods
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class RefundGoods extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return "{{%refund_goods}}";
    }
}

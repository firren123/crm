<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  UserOrder.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/20 上午11:55
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\models\i500m;

/**
 * Class Barcode
 * @category  PHP
 * @package   Barcode
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Barcode extends I500Base
{

    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%product_barcode}}';
    }
}

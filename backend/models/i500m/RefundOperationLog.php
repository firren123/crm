<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  RefundOperationLog.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/20 下午6:02
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

/**
 * Class RefundOperationLog
 * @category  PHP
 * @package   RefundOperationLog
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class RefundOperationLog extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return "{{%refund_operation_log}}";
    }
}

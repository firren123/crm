<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  FlowRun.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/22 下午3:21
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\oa;

/**
 * Class FlowRun
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class FlowRun extends OaBase
{
    /**
     * 简介：连接数据库
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%flow_rum}}";
    }
}

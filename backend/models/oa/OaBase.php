<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  OaBase.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/22 下午5:26
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\models\oa;

use backend\models\Base;


/**
 * Class OaBase
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class OaBase extends Base
{
    /**
     * 设置默认数据库连接
     *
     * Author zhengyu@iyangpin.com
     *
     * @return \yii\db\Connection
     */
    public static function getDB()
    {
        return \Yii::$app->db_oa;
    }
}

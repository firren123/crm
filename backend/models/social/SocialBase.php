<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  SocialBase.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 上午10:15
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\models\social;

use backend\models\Base;

/**
 * Class SocialBase
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SocialBase extends Base
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return \yii\db\Connection
     */
    public static function getDB()
    {
        return \Yii::$app->db_social;
    }
}

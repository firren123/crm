<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  UserBasicInfo.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/14 下午2:43
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;


/**
 * Class UserBasicInfo
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class UserBasicInfo extends SocialBase
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_user_basic_info}}";
    }
}

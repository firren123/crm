<?php
/**
 * 论坛-短信
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   Sms.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/8/13 0013 上午 11:01
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\models\social;

/**
 * User
 *
 * @category Admin
 * @package  Sms
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class Sms extends SocialBase
{
    /**
     * 连接表
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_user_sms}}";
    }
}

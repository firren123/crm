<?php
/**
 * 论坛--用户发送验证码
 *
 * PHP Version 5
 *
 * @category  Admin
 * @package   VerifyCode.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/8/18 0018 下午 1:02
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\models\social;

/**
 * VerifyCode
 *
 * @category Admin
 * @package  VerifyCode
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class VerifyCode extends SocialBase
{
    /**
     * 连接表
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_user_verify_code}}";
    }
}

<?php
/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  Crm
 * @package   Order.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/9/5 0005 上午 11:59
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\social;

/**
 * Order
 *
 * @category CRM
 * @package  Order
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ExChange extends SocialBase
{
    /**
     * 连接表
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_exchange}}";
    }
}


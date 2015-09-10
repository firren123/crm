<?php
/**
 * 审核数据表
 *
 * PHP Version 5
 *
 * @category  Crm
 * @package   ExChangeChecked.php
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      2015/9/5 0005 上午 11:59
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      sunsongsong@iyangpin.com
 */

namespace backend\models\social;

/**
 * ExChangeChecked
 *
 * @category CRM
 * @package  Order
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsongsong@iyangpin.com
 */
class ExChangeChecked extends SocialBase
{
    /**
     * 连接表
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_exchange_checked}}";
    }
}


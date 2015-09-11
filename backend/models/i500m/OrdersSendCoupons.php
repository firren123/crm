<?php
/**
 * 分发优惠券配置
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   OrdersSendCoupons.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/8/11 0011 上午 11:35
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * OrdersSendCoupons
 *
 * @category Admin
 * @package  OrdersSendCoupons
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class OrdersSendCoupons extends I500Base
{
    /**
     * 数据表连接
     * @return string
     */
    public static function tableName()
    {
        return "{{%orders_send_coupons}}";
    }
}

<?php
/**
 * 商家登录日志
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ShopLoginLog.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/7/28 0028 下午 3:29
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */
namespace backend\models\shop;
/**
 * ShopLoginLog
 *
 * @category CRM
 * @package  ShopLoginLog
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ShopLoginLog extends ShopBase
{
    /**
     * 数据表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_login_log}}';
    }
}
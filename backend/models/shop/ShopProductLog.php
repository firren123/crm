<?php
/**
 * 商品修改分类 品牌  条形码 日志表
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ShopProductLog.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/5/26 0026 下午 3:41
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\models\shop;

/**
 * ShopProductLog
 *
 * @category CRM
 * @package  ShopProductLog
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ShopProductLog extends ShopBase
{
    /**
     * 数据表连接
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_product_log}}';
    }
}

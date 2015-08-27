<?php
/**
 * 商家返利
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ShopRebate.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/6/10 0028 下午 3:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */
namespace backend\models\shop;
/**
 * ShopRebate
 *
 * @category CRM
 * @package  ShopRebate
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ShopRebate extends ShopBase
{
    /**
     * 数据表
     *
     * @return string
     */
    public static function tableName(){
        return '{{%shop_rebate}}';
    }
}
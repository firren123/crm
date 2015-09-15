<?php
/**
 * 商品属性记录表页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   Brand.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/18 11:19
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\models\i500m;

/**
 * Product
 *
 * @category CRM
 * @package  Product
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ProductAttr extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%product_attr}}';
    }
}

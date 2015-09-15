<?php
/**
 * 商品属性列表
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ProductSku.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/27 0027 下午 3:32
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\models\i500m;

/**
 * Class ProductSku
 * @category  PHP
 * @package   ProductSku
 * @author    liuwei <liuwei@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ProductSku extends I500Base
{
    /**
     * 数据库
     * @return string
     */
    public static function tableName()
    {
        return '{{%product_sku}}';
    }
}

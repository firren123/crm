<?php
/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  Admin
 * @package   ShopAccount.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/5/29 0029 下午 6:07
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\models\shop;

/**
 * Class ShopAccount
 * @category  PHP
 * @package   ShopAccount
 * @author    liuwei <liuwei@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ShopAccount extends ShopBase
{
    /**
     * 数据表
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_accounts}}';
    }
}

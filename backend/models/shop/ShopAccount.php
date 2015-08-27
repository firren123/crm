<?php
/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  admin
 * @package   ShopAccount.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/5/29 0029 下午 6:07
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\models\shop;


class ShopAccount extends ShopBase
{
    /**
     * 数据表
     *
     * @return string
     */
    public static function tableName(){
        return '{{%shop_accounts}}';
    }
}
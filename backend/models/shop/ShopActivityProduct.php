<?php
/**
 * 商家活动商品列表
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  ShopActivity
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/29 下午3:16
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\shop;

/**
 * ShopActivityProduct
 *
 * @category CRM
 * @package  ShopActivityProduct
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class ShopActivityProduct extends ShopBase
{
    /**
     * 简介：商家活动列表
     * @author  sunsongsong@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_activity_product}}';
    }

}


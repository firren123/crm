<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  ShopGrade.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/20 上午11:10
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;


/**
 * Class ShopGrade
 * 用户评分表
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ShopGrade extends SocialBase
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_shop_grade}}";
    }
}

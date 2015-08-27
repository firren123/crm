<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   MODEL
 * @filename  AuthorityShop.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/6/4 上午9:58
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

/**
 * Class AuthorityShop
 * @category  PHP
 * @package   Admin
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class AuthorityShop extends I500Base
{
    /**
     * 简介：
     * @author  weitonghe@iyangpin.com
     * @return string
     */
    public static function tableName()
    {
        return "{{%crm_authority_shop}}";
    }
    /**
     * 简介：
     * @author  weitonghe@iyangpin.com
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'shop_id' => '商家ID',
            'shop_name' =>'商家名称',
            'status' =>'状态',
        );
    }
    /**
     * 简介：定义过滤规则
     * @author  weitonghe@iyangpin.com
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['shop_id','shop_name','status'],'required'],
        ];
    }
}
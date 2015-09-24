<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  ServiceUnit.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 下午7:09
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;


/**
 * Class ServiceUnit
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ServiceUnit extends SocialBase
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_service_unit}}";
    }

    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'unit' => '单位',
            'status' =>'是否禁用',
        );
    }

    /**
     * 简介：定义过滤规则
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['unit','status'],'required']
        ];
    }
}

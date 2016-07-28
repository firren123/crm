<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  ServiceSetting.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 上午9:59
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;


/**
 * Class ServiceSetting
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ServiceSetting extends SocialBase
{
    /**
     * 简介：连接数据库
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_service_setting}}";
    }

    /**
     * 简介：定义过滤规则
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['mobile','name','description','province_id','search_address','details_address','lng','lat','status'],'required'],
            ['mobile','match','pattern'=>'/^1\d{10}/']
        ];
    }
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'mobile' => '手机号',
            'name' =>'店铺名称',
            'description'=>'店铺描述',
            'province_id' => '省份',
            'search_address' =>'检索出的详细地址',
            'details_address' =>'详细地址',
            'lng' => '经度',
            'lat' =>'纬度',
            'status' =>'是否禁用',
            'create_time' =>'创建时间',
        );
    }

}

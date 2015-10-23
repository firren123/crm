<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  Service.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 上午9:49
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;


/**
 * Class Service
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Service extends SocialBase
{
    /**
     * 简介：连接数据库
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_service}}";
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
            [['mobile','category_id','son_category_id','image','title','price','unit','service_way','description','community_city_id','community_id'],'required'],
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
            'category_id' =>'分类',
            'son_category_id'=>'子分类',
            'image' => '服务图片',
            'title' =>'标题',
            'price' =>'价格',
            'unit' => '单位',
            'service_way' =>'服务方式',
            'description' =>'服务描述',
            'community_city_id' =>'小区城市',
            'community_id' =>'小区',
        );
    }
}

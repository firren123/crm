<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  Role.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/23 上午10:41
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

/**
 * Class Role
 * @category  PHP
 * @package   Role
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Role extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_role}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'name' => '角色名称',
            'city_id' => '分公司',
            'status' => '状态',
            'sort' => '排序'
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
            [['name', 'status'], 'required'],
        ];
    }
}

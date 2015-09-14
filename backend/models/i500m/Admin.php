<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  Admin.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/21 下午4:39
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

/**
 * Class Admin
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Admin extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_admin}}';
    }
    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'username' => '用户名',
            'name' =>'姓名',
            'password'=>'密码',
            'email' => '邮箱',
            'role_id' =>'角色',
            'status' =>'状态',
            'bc_id' => '分公司',
            'ip_access' =>'外网访问权限',
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
            [['username','name','email','role_id','ip_access','status'],'required'],
            ['email','email']
        ];
    }

}

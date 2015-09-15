<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  Menu.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/22 下午7:21
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

/**
 * Class Menu
 * @category  PHP
 * @package   Menu
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Menu extends I500Base
{
    /**
     * 简介：数据库表名
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_menu}}';
    }
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'name' => '菜单名称',
            'module_id' =>'所属模块',
            'modules'=>'模块',
            'controller' => '控制器',
            'action' =>'方法',
            'status' =>'状态',
            'sort'=>'排序'
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
            [['name','module_id','modules','controller','action','status'],'required'],
        ];
    }

    /**
     * 简介：
     * @param int $role_id x
     * @return array
     */
    public function getMenuList($role_id = 0)
    {
        $list = [];
        if ($role_id) {
            $sql = "select `modules`,`controller`,`action`, `module_id` from crm_menu menu
                    left join crm_role_privilege privilege on privilege.menu_id = menu.id
                    where privilege.role_id = {$role_id} and privilege.status = 1 and menu.status = 1 and module.status = 1 ";
            $list = \Yii::$app->db_500m->createCommand($sql)->queryAll();
        }
        return $list;
    }

}

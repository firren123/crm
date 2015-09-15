<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  RolePrivilege.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/24 下午4:52
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

/**
 * Class RolePrivilege
 * @category  PHP
 * @package   RolePrivilege
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class RolePrivilege extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_role_privilege}}';
    }

    /**
     * 一次插入多条数据 
     * @param int   $role_id  角色id
     * @param array $menu_ids 菜单id
     * @return string
     */
    public function insertMore($role_id = 0, $menu_ids = array())
    {
        $re = false;
        if ($role_id && $menu_ids) {
            $sql = "INSERT INTO " . $this->tableName() . "(`role_id`, `menu_id`) VALUES";
            $value = [];
            foreach ($menu_ids as $k => $v) {
                if ($v) {
                    $value[] = "('{$role_id}', '{$v}')";
                }
            }
            $sql .= implode(',', $value);
            $re = \Yii::$app->db_500m->createCommand($sql)->execute();
        }
        return $re;
    }
}

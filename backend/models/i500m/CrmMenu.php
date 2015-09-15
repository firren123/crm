<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Wap
 * @package   Member
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/5/13 下午6:36
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Class CrmMenu
 * @category  PHP
 * @package   CrmMenu
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CrmMenu extends I500Base
{

    /**
     * 表名
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_menu_new}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'name' => '节点名称',
            'title' => '菜单名称',
            'description'=>'菜单描述',
            'level' => '导航级别',
            'nav_id' => '所属导航',
            'display' =>'导航显示',
            'status' =>'状态',
            'p_id' => '',
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
            [['name','title','status'],'required'],
        ];
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param int  $role_id x
     * @param int  $level   x
     * @param null $display x
     * @param int  $nav_id  x
     * @return array
     */
    public function getNav($role_id = 0, $level = 1, $display = null, $nav_id = 0)
    {
        $list = [];
        if ($role_id && $level) {
            $sql = "SELECT menu.id nav_id, menu.name, menu.p_name, menu.title,menu.module_name FROM crm_menu_new menu";
            $sql .= " LEFT JOIN crm_role_privilege privilege on privilege.menu_id = menu.id ";
            $sql .= " WHERE privilege.role_id = {$role_id} AND menu.level = {$level} ";
            if (isset($nav_id) && $nav_id) {
                $sql .= " AND nav_id={$nav_id}";
            }
            if (isset($display)) {
                $sql .= " AND menu.display = {$display}";
            }
            $sql .= " AND menu.status = 1 ";
            $sql .= " ORDER BY menu.sort asc";
            $list = \Yii::$app->db_500m->createCommand($sql)->queryAll();
        }
        return $list;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param array  $cond  x
     * @param string $and   x
     * @param string $field x
     * @param string $order x
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMenuList($cond = array(), $and = '', $field = '*', $order = '')
    {
        $list = [];
        if ($cond) {
            $list = $this->find()
                ->select($field)
                ->where($cond)
                ->andWhere($and)
                ->orderBy($order)
                ->asArray()
                ->all();
        }
        return $list;
    }
}

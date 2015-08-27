<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 权限管理
 *
 * @category  PHP
 * @package   admin
 * @filename  AuthController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/24 下午4:29
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\CrmMenu;
use backend\models\i500m\Menu;
use backend\models\i500m\Module;
use backend\models\i500m\RolePrivilege;
use common\helpers\RequestHelper;

class AuthController extends BaseController
{

    /**
     * 简介：执行一次查询
     * @return string
     */
    public function actionIndex()
    {
        $role_id = RequestHelper::get('id', 4, 'intval');
        $role_p_model = new RolePrivilege();
        $menu_id = $role_p_model->getList(array('role_id' => $role_id), 'menu_id');
        $menu_ids = array();
        foreach ($menu_id as $k => $v) {
            $menu_ids[] = $v['menu_id'];
        }
        $m_menu = new CrmMenu();
        $menu_list = $m_menu->getMenuList(['status' => 1], 'level > 0', 'id, title, nav_id', 'level asc, sort asc');

        $new_list = [];
        foreach ($menu_list as $k => $v) {
            if (0 == $v['nav_id'] && !isset($new_list[$v['id']])) {
                $new_list[$v['id']]['id'] = $v['id'];
                $new_list[$v['id']]['title'] = $v['title'];
            } else {
                if (isset($new_list[$v['nav_id']])) {
                    $new_list[$v['nav_id']]['child'][] = $v;
                }
            }
        }
        $data = [
            'module_list' => $new_list,
            'role_id' => $role_id,
            'menu_ids' => $menu_ids,
        ];
        return $this->render('index', $data);

    }

    /**
     * 简介：添加
     * @return string
     */
    public function actionAdd()
    {
        $data = RequestHelper::post('access');
        $rid = RequestHelper::post('rid');
        if (isset($rid) && !empty($rid)) {
            //删除已有的权限
            $role_p_model = new RolePrivilege();
            $role_p_model->deleteAll(array('role_id' => $rid));

            //添加新的权限
            $role_p_model->insertMore($rid, $data);

            return $this->success('添加成功', '/admin/role/index');
        }
        $this->redirect('/admin/role/index');
    }

}
<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  MenuController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/22 下午7:19
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\CrmMenu;
use backend\models\i500m\Menu;
use backend\models\i500m\Module;
use common\helpers\RequestHelper;
use yii\data\Pagination;

class MenuController extends BaseController{

    /**
     * 简介：管理员列表
     * @author  lichenjun@iyangpin.com。
     */
    public function actionTree(){
        $name = RequestHelper::get('name','');
        $page = RequestHelper::get('page',1,'intval');

        $where = [];
        $admin_model = new CrmMenu();
        $where['p_id'] = 0;
        //$where['level'] = 0;
        $module_list = $admin_model->getList($where,"*",'sort asc');

        foreach($module_list as $k=>$v){

            $where=[];
            $where['p_id']=$v['id'];
            //$where['level'] = 1;
            $admin_model = new CrmMenu();
            $controll_list = $admin_model->getList($where,"*",'sort asc');

            $module_list[$k][$v['id']] = $controll_list;

            foreach($controll_list as $kk =>$vv){
                $where=[];
                $where['p_id']=$vv['id'];
                //$where['level'] = 2;
                $module_list[$k][$v['id']][$kk][$vv['id']] = $admin_model->getList($where,"*",'sort asc');
            }
        }

//        echo "<pre>";
//        print_r($module_list);exit;


        return $this->render('tree',[
            'list'=> $module_list,
        ]);
    }

    /**
     * 简介：管理员列表
     * @author  lichenjun@iyangpin.com。
     */
    public function actionIndex(){
    $name = RequestHelper::get('name','');
    $page = RequestHelper::get('page',1,'intval');

    $where = array();
    if($name){
        $where = "name like '%$name%'";
    }else{
        $where = 1;
    }
    $admin_model = new Menu();
    $count = $admin_model->getListCount($where);
    $list = $admin_model->getPageList($where,"*",'id desc ',$page,$this->size);
    $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
    $module = new Module();
    $module_list = $module->getList(1,"*",'sort asc');
    $list_m = array();
    foreach($module_list as $k=>$v){
        $list_m[$v['id']] = $v['name'];
    }
    return $this->render('index',[
        'list'=> $list,
        'pages'=>$pages,
        'name'=>$name,
        'module_list'=>$list_m,

    ]);
}

    /**
     * 简介：管理员添加
     * @author  lichenjun@iyangpin.com。
     */
    public function actionAdd()
    {
        $p_id = RequestHelper::get('p_id', 0, 'intval');
        $act  = RequestHelper::get('act', 0, 'intval');     // 方法标识
        $model = new CrmMenu();
        $admin = RequestHelper::post('CrmMenu');
        if (!empty($admin)) {

            $admin['sort'] = $admin['sort']?$admin['sort']:999;
            if (isset($admin['level']) && $admin['level'] == 0) {
                $admin['level'] = 2;  //默认二级导航
            }
            if ($admin['p_id']) {
                $p = $model->getInfo(['id'=>$admin['p_id']], true, 'name, p_name');
                $admin['p_name'] = $p['name'];
                $admin['module_name'] = $p['p_name'];
            }
            $result = $model->insertInfo($admin);
            if ($result == true) {
                return $this->success('添加成功', '/admin/menu/tree');
            }
        }
        $list_m = array('0'=>'请选择');
        if ($act) {   //针对方法设置导航
            $module_list = $model->getList(['level'=>1, 'status'=>1, 'display'=>1], 'id, title', 'sort');
            foreach ($module_list as $k=>$v) {
                $list_m[$v['id']] = $v['title'];
            }
        }
        return $this->render('add', ['model' => $model,'module_list'=>$list_m, 'p_id'=>$p_id, 'act'=>$act]);

    }

    /**
     * 简介：管理员修改
     * @author  lichenjun@iyangpin.com。
     */
    public function actionEdit(){
        $id = RequestHelper::get('id', 0, 'intval');
        $act = RequestHelper::get('act', 0, 'intval');
        $cond = ['id'=>$id];
        $model = new CrmMenu();
        $item = $model->getInfo($cond, false, '*');
        $admin = RequestHelper::post('CrmMenu');
        if (!empty($admin)) {
            $admin['sort'] = $admin['sort']?$admin['sort']:999;
            if (isset($admin['level']) && $admin['level'] == 0) {
                $admin['level'] = 2;  //默认二级导航
            }
            $model->attributes = $admin;
            $result = $model->updateInfo($admin,$cond);
            if ($result == true) {
                $this->redirect('/admin/menu/tree');
            }
        }
        $list_m = array('0'=>'请选择');
        $module_list = $model->getList(['level'=>1, 'status'=>1, 'display'=>1], 'id, title', 'sort');
        foreach ($module_list as $k=>$v) {
            $list_m[$v['id']] = $v['title'];
        }

        return $this->render('add', ['model' => $item,'module_list'=>$list_m, 'p_id'=>$item['p_id'], 'act'=>$act]);
    }

   /*
    * 删除导航
    *
    * return int
    */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id', 0, 'intval');
        if (!empty($id)) {
            $model = new CrmMenu();
            $ret = $model->deleteAll(array('id'=>$id));
            if ($ret) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }

    /**
     * 简介：AJAX提交
     * @author  lichenjun@iyangpin.com。
     * @return int
     */
    public function actionAjaxDelete()
    {
        $code = 0;
        $id = RequestHelper::post('ids');
        if (!empty($id)) {
            $model = new Menu();
            $ret = $model->deleteAll(array('id'=>$id));
            if ($ret) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }


    public function actionEditSort()
    {
        $id   = RequestHelper::get('id', 0, 'intval');
        $sort = RequestHelper::get('sort', 0, 'intval');

        if ($id) {
            $menu = CrmMenu::findOne($id);
            if ($menu) {
                $menu->sort = $sort;
                $menu->save();
            }
        }
        echo 1;
    }

    public function actionTest()
    {
        $m_menu = new CrmMenu();
        $m_menu->updateInfo(['module_name'=>'goods'], ['id'=>['243','244','245','246','247','248','249','250','251','252','253']]);
    }
}
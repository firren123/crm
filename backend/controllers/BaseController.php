<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  BaseController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/3 下午5:53
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\controllers;


use backend\models\i500m\Branch;
use backend\models\i500m\CrmMenu;
use backend\models\i500m\RolePrivilege;
use yii\web\Controller;

/**
 * Class BaseController
 * @category  PHP
 * @package   BaseController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class BaseController extends Controller
{
    public $size = 20;
    public $city_id = 0;
    public $province_id = 0;
    public $bc_id = 0;
    public $is_head_company = 0;   //是否是总公司 0=否 1=是
    public $quanguo_province_id = 35;  //省ID为全国字段
    public $quanguo_city_id = 999;
    public $cesshi = '';
    public $admin_id = 0;        //当前登录人id

    /**
     * 简介：构造方法
     * @return void|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function init()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
            //\Yii::$app->end();
            //return;
        }

        $this->admin_id = \Yii::$app->user->identity->id;

        //获取分公司ID
        $bc_id = \Yii::$app->user->identity->bc_id;
        //获取角色ID
        $role_id = \Yii::$app->user->identity->role_id;

        //实例化分公司表
        $branch_m = new Branch();
        if (!$bc_id) {

            $province_id = 1;  //默认北京
            $bcom = $branch_m->getInfo(['province_id' => $province_id], true, 'id');
            $bc_id = $bcom['id'];
            $this->cesshi = '<span style="color:red;">账号没有归属分公司,默认北京</span>';

        }
        $this->bc_id = $bc_id;

        //获取角色地区

        $city_id = $branch_m->getInfo(array('id' => $bc_id), true, 'city_id_arr,province_id');
        //总公司
        if ($city_id['province_id'] == $this->quanguo_province_id) {
            $this->is_head_company = 1;
            $city_id_arr = array($this->quanguo_city_id);
        } else {
            //分公司
            $city_id_arr = explode(',', $city_id['city_id_arr']);
        }
        $this->city_id = $city_id_arr;
        $this->province_id = $city_id['province_id'];


        //获取角色导航
        $role_p_model = new RolePrivilege();
        $role_menu_ids = $role_p_model->getList(array('role_id' => $role_id), "menu_id");
        $role_menu_id_arr = [];
        foreach ($role_menu_ids as $k => $v) {
            $role_menu_id_arr[] = $v['menu_id'];
        }
        $url = \Yii::$app->requestedRoute;
        $url_arr = explode('/', $url);
        $url_arr[0] = !empty($url_arr[0]) ? $url_arr[0] : 'admin';
        $url_arr[1] = isset($url_arr[1]) ? $url_arr[1] : 'site';
        $url_arr[2] = isset($url_arr[2]) ? $url_arr[2] : 'index';

        $m_menu = new CrmMenu();
        $nav_list = $m_menu->getNav($role_id, 1, 1);
        $new_list = [];
        foreach ($nav_list as $k => $v) {
            $new_list[$k] = ['label' => $v['title'], 'url' => '/' . $v['module_name'] . '/' . $v['p_name'] . '/' . $v['name']];
        }
        $menu_info = $m_menu->getInfo(['name' => $url_arr[2], 'p_name' => $url_arr[1], 'module_name' => $url_arr[0]], true, 'id, nav_id');

        $menu_l = [];
        if ($menu_info) {
            //权限判断，去掉首页
            if ('/admin/site/index' != '/' . $url_arr[0] . '/' . $url_arr[1] . '/' . $url_arr[2]) {
                if (!in_array($menu_info['id'], $role_menu_id_arr)) {
                    echo "<script>alert('没有权限');location='/admin/site/index';</script>";
                    exit;
                }
            }

            $nav_id = $menu_info['nav_id'] ?: $menu_info['id'];

            $child = $m_menu->getNav($role_id, 2, 1, $nav_id);

            foreach ($child as $k => $v) {
                $menu_l[$k] = ['label' => $v['title'], 'url' => '/' . $v['module_name'] . '/' . $v['p_name'] . '/' . $v['name']];
            }
        }

        $view = \Yii::$app->view;
        $view->params['menu_list'] = $menu_l;
        $view->params['module_list'] = $new_list;

    }

    /**
     * 简介：成功操作
     * @author  lichenjun@iyangpin.com。
     * @param string $message 消息
     * @param bool   $jumpUrl URL
     * @return string
     */
    public function success($message, $jumpUrl = false)
    {
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = \Yii::$app->params['baseUrl'];
        }

        $jumpUrl = $jumpUrl ? $jumpUrl : $url;
        $waitSecond = 3;
        return $this->renderPartial(
            '/../../../views/layouts/success',
            [
            'message' => $message,
            'jumpUrl' => $jumpUrl,
            'waitSecond' => $waitSecond,
            ]
        );
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $error   错误信息
     * @param bool   $jumpUrl URL
     * @return string
     */
    public function error($error, $jumpUrl = false)
    {
        $message = 0;
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = \Yii::$app->params['baseUrl'];
        }

        $jumpUrl = $jumpUrl ? $jumpUrl : $url;
        $waitSecond = 3;
        return $this->renderPartial(
            '/../../../views/layouts/success',
            [
            'message' => $message,
            'error' => $error,
            'jumpUrl' => $jumpUrl,
            'waitSecond' => $waitSecond,
            ]
        );
    }

    /**
     * 返回数据
     * @param string $code 状态码
     * @param string $msg  错误信息
     * @param array  $data 返回数据
     * @return string
     */
    public static function ajaxReturn($code = '', $msg = '', $data = [])
    {
        return json_encode(['code' => $code, 'msg' => $msg, 'data' => $data]);
    }

    /**
     * 简介：获取分公司信息
     * @author  lichenjun@iyangpin.com。
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCommonBC()
    {
        $m_bc = new Branch();
        //35为全国省字段
        if ($this->province_id == $this->quanguo_province_id) {
            $bc_arr = $m_bc->getList(['status' => 1], 'id,name');
        } else {
            $bc_arr = $m_bc->getList(['status' => 1, 'id' => $this->bc_id], 'id,name');
        }
        return $bc_arr;
    }
}

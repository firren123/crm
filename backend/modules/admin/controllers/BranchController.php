<?php
/**
 * 已开通城市管理Controller
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ShopcontractController.php
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @time      2015/8/13  下午 5:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhoujun@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Branch;
use backend\models\i500m\Log;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use backend\models\i500m\Province;
use backend\models\i500m\City;

/**
 * Class BranchController
 * @category  PHP
 * @package   BranchController
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class BranchController extends BaseController
{
    /**
     * 简介：
     * @return string
     */
    public function actionIndex()
    {
        $data = array();
        $data['page'] = RequestHelper::get('page', 1);
        $data['size'] = RequestHelper::get('per-page', $this->size);
        $offset = ($data['page'] - 1) * $data['size'];
        $model = new Branch();
        $list = $model->show_branch($data, $offset);

        $province_model = new Province();
        $city_model = new City();

        foreach ($list as $k => $v) {
            $city_name = $city_model->city_one($v['city_id']);
            $list[$k]['city_name'] = $city_name['name'];
            $province_name = $province_model->province_one($v['province_id']);
            $list[$k]['province_name'] = $province_name['name'];
        }

        $total = $model->total();

        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);

        return $this->render('index', ['list' => $list, 'pages' => $pages]);
    }

    /**
     * 简介：添加
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        unset(\Yii::$app->session['name']);
        $province_model = new Province();
        $model = new Branch();
        if ($_POST) {
            $data['branch'] = RequestHelper::post('Branch');
            $city_id_arr = $data['branch']['city_id_arr'];
            $city_id_str = implode(',', $city_id_arr);
            $data['branch']['city_id_str'] = $city_id_str;
            unset($data['branch']['city_id']);
            $branch_model = new Branch();
            $log = new Log();
            $log_info = '管理员 '.\Yii::$app->user->identity->username  .'添加了分公司'.$data['branch']['name'];
            $log->recordLog($log_info, 10);
            $branch_model->add($data['branch']);
            return $this->redirect("/admin/branch/index");
        } else {
            $info = $province_model->getAllProvince();
            $arr = array();
            foreach ($info as $k => $v) {
                $arr[$v['id']] = $v['name'];
            }
            $city_model = new City();
            $city = $city_model->city($info[0]['id']);
            $city_list = [];
            foreach ($city as $k => $v) {
                $city_list[$v['id']] = $v['name'];
            }
            return $this->render('add', ['model' => $model, 'province_model' => $province_model, 'arr' => $arr, 'city_list' => $city_list]);
        }
    }

    /**
     * 简介：删除
     * @return \yii\web\Response
     */
    public function actionDel()
    {
        $id = RequestHelper::get('id');
        $model = new Branch();
        $info = $model->del($id);
        $log = new Log();
        $log_info = '管理员 '.\Yii::$app->user->identity->username  .'删除了ID为'.$id.'的分公司';
        $log->recordLog($log_info, 10);
        return $this->redirect("/admin/branch/index");
    }

    /**
     * 简介：更新
     * @return string
     */
    public function actionUp()
    {
        $province_model = new Province();
        $model = new Branch();
        $id = RequestHelper::get('id');
        if ($_POST) {
            $info = RequestHelper::post('Branch');
            $city_id_str = implode(',', $info['city_id_arr']);
            $info['city_id_arr'] = $city_id_str;
            unset($info['city_id']);
            $customer = Branch::findOne($id);
            $customer->name = $info['name'];
            $customer->status = $info['status'];
            $customer->sort = $info['sort'];
            $customer->province_id = $info['province_id'];
            $customer->city_id_arr = $info['city_id_arr'];
            $customer->save();  // 等同于 $customer->update();
            $log = new Log();
            $log_info = '管理员 '.\Yii::$app->user->identity->username  .'修改了ID为'.$id.'的分公司';
            $log->recordLog($log_info, 10);
            return $this->success('编辑成功', '/admin/branch/index');
        } else {
            $branch_model = $model::findOne($id);
            \Yii::$app->session['name']=$branch_model['name'];
            $branch_model->city_id_arr = explode(',', $branch_model->city_id_arr);

            $info = $province_model->getAllProvince();

            $arr = array();
            foreach ($info as $k => $v) {
                $arr[$v['id']] = $v['name'];
            }
            $city_model = new City();
            $city = $city_model->city($branch_model->province_id);

            $city_list = [];
            foreach ($city as $k => $v) {
                $city_list[$v['id']] = $v['name'];
            }
            return $this->render('add', ['model' => $branch_model, 'id' => $id, 'arr' => $arr, 'city_list' => $city_list]);
        }
    }

    /**
     * 查询出对应省级下面的所有市
     * @name: actionCity
     * @return string
     */
    public function actionCity()
    {
        $id = RequestHelper::get('pid', 3, 'intval');
        $branch_model = new Branch();
        $model = new City();
        $arr = array();
        $arr['id'] = $id;
        $all_city_info = $model->city($arr['id']);
        $city_branch = $branch_model->branch_city_all($arr['id']);
        $info_new = array();
        $info_new1 = $info_new2 = [];
        foreach ($city_branch as $k => $v) {
            $info_new[] = $v['city_id_arr'];
        }
        $city_new_one = implode(',', $info_new); //string '6,3,4,5,4,5,7' (length=13)
        $city_new_two = explode(',', $city_new_one);
        $city_new_three = array_unique($city_new_two);
        $city_new_four = implode(',', $city_new_three);
        foreach ($all_city_info as $k => $v) {
            if (false !== strpos($city_new_four, $v['id'])) {
                unset($all_city_info[$k]);
            } else {
                $info_new1['id'] = $v['id'];
                $info_new1['name'] = $v['name'];
                $info_new2[] = $info_new1;
                unset($info_new);
            }
        }
        echo json_encode($info_new2);
    }

    /**
     * 简介：提交
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionCheck()
    {
        $province_id = RequestHelper::post('province_id');
        $name = RequestHelper::post('branch_name');
        $model = new Branch();
        $arr_where = array();
        $arr_where['name'] = $name;
        $count = $model->getCount($arr_where);
        if ($count > 0) {
            echo json_encode(array('status' => 0, 'message' => '该分公司已存在，请重新添加'));
        } else {
            echo json_encode(array('status' => 1, 'message' => '添加成功'));

        }
    }
}

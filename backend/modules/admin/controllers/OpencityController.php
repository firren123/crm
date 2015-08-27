<?php
/**
 * 简介：已开通城市管理Controller
 * @category WAP
 * @package 模块名称
 * @author zhoujun@iyangpin.com
 * @time 2015/3/31 12:27
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license http://www.i500m.com
 * @link zhoujun@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\OpenCity;
use backend\models\i500m\City;
use backend\models\i500m\Log;
use backend\models\i500m\Province;
use common\helpers\RequestHelper;
use yii\data\Pagination;
class OpenCityController extends BaseController
{
    public $size = 10;
    /**
     * @purpose:省市分页显示
     * @name: actionIndex
     * @return string
     */
    public function actionIndex(){
        $data = array();
        $city = RequestHelper::get('city');
        $data['page'] = RequestHelper::get('page',1);
        $data['size'] = RequestHelper::get('per-page',$this->size);
        if($data['page'] == 1){
            $offset = 0;
        }else{
            $offset = ($data['page']-1) * $data['size'];
        }
        $model = new OpenCity();
        $list = $model->show($data,$offset,$city);
        $total = $model->total($city);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index',['list'=>$list,'pages'=>$pages]);
    }



    /**
     * @purpose:省市添加
     * @name: actionAdd
     * @return string
     */
    public function actionAdd()
    {
        $model = new OpenCity();
        $province = new Province();
        if($_POST){
            $data = RequestHelper::post('OpenCity', '', '');
            $verify = $model->verifyDredgeCity($data['city']);
            if($verify){
                return $this->error('此城市已开通过',"/admin/opencity/index");
            }else{
                $city_model = new City();
                $city_info = $city_model->findOne($data['city']);
                $model->province = $data['province'];
                $model->city = $data['city'];
                $city_name = $model->city_name = $city_info['name'];
                $city_pinyin = RequestHelper::pinyin("$city_name","UTF-8");
                $model->pinyin = $city_pinyin;
                $model->status = $data['status'];
                $model->display = $data['display'];
                $model->save();
                $log = new Log();
                $log_info = '管理员 '.\Yii::$app->user->identity->username .'添加了开通城市'.$city_name;
                $log->recordLog($log_info, 10);
                return $this->success('添加成功','/admin/opencity/index');
            }
        }else{
            $info = $province->province();
            $arr = array();
            foreach($info as $k=>$v){
                $arr[$v['id']] = $v['name'];
            }
            $model->display = 1;
            return $this->render('add',['model'=>$model,'arr'=>$arr, 'city_list'=>[]]);
        }
    }


    /**
     * @purpose:省市状态更新
     * @name: actionUp
     * @return string
     */
    public function actionUp()
    {
        $id = RequestHelper::get('id',1,'intval');
        $model = new OpenCity();
        $info = $model->update_status($id);
        if($info){
            $log = new Log();
            $log_info = '管理员 '.\Yii::$app->user->identity->username  .'更新了开通城市id为'.$id.'的状态';
            $log->recordLog($log_info, 10);
            echo "1";
        }else {
            echo "2";
        }
    }

    /**
     * @purpose:查询出对应省级下面的所有市
     * @name: actionCity
     * @return string
     */
    public function actionCity(){
        $id = RequestHelper::get('pid',9,'intval');
        $model = new City();
        $info = $model->city($id);
        echo json_encode($info);
    }

    public function actionLink()
    {
        $model = new OpenCity();
        return $this->render('link',['model'=>$model]);
    }

    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $city_model = new City();
        if ($_POST) {
            $model = new OpenCity();
            $info = RequestHelper::post('OpenCity');
            $city_info = $city_model->city_one($info['city']);
            $name = RequestHelper::pinyin("$city_info[name]","UTF-8");
            $info['city_name'] = isset($city_info['name']) ? $city_info['name'] : '';
            $info['pinyin'] = isset($name) ? $name : '';
            $data = $model->check_city($info['city_name'],$id);
           if($data){
              return  $this->error('此城市已开通过',"/admin/opencity/index");
           }else{
               $result = $model->updateInfo($info, ['id'=>$id]);
               if($result){
                   $log = new Log();
                   $log_info = '管理员 '.\Yii::$app->user->identity->username  .'修改了开通城市id为'.$id.'的资料';
                   $log->recordLog($log_info, 10);
                   return $this->success('编辑成功','/admin/opencity/index');
               }else{
                   return $this->error('编辑失败','/admin/opencity/index');
               }
           }
        } else {
            $info = OpenCity::findOne($id);

            $m_province = new Province();
            $province = $m_province->province();
            $arr = array();
            foreach ($province as $k=>$v) {
                $arr[$v['id']] = $v['name'];
            }

            $city = $city_model->city($info->province);
            $city_list = [];
            if ($city) {
                foreach ($city as $k=>$v) {
                    $city_list[$v['id']] = $v['name'];
                }
            }


            return $this->render('add',['model'=>$info, 'arr'=>$arr, 'city_list'=>$city_list]);
        }
    }
}

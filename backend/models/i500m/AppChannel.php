<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/4
 * Time: 10:56
 */
namespace backend\models\i500m;
class AppChannel extends I500Base
{
    /**
     * *数据库  表名称
     * @return string
     */
    public static function tableName()
    {
        return "{{%app_channel}}";
    }

    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['url','type'],'required']
        ];
    }

    /**
     * @param $msg
     * @return bool|mixed
     * add
     */
    public function addapp($msg){
        $AddApp_model = new AppChannel();
        foreach($msg as $k=>$v){
            $AddApp_model->$k = $v;
        }
        $result = $AddApp_model->save();
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    /**
     * @param $wherebaidu
     * @param $where360
     * @param $list
     * @return bool
     * edit
     */
    public function editapp($where,$list){
        $result = AppChannel::updateAll(['update_time'=>$list['update_time'],'url'=>$list['url']],$where);
        return $result;
    }

    /**
     * @param $id
     * del
     */
    public function deloneurl($app_id){
        $result = AppChannel::deleteAll('app_id = :app_id', [':app_id' => $app_id]);
        //$result = Customer::deleteAll('age > :age AND gender = :gender', [':age' => 20, ':gender' => 'M']);
        return $result;
    }
    /**
     * @param array $where
     * @return array|null|\yii\db\ActiveRecord
     * show one app
     */
    public function showoneurl($where){
        $AddApp_model = new AppChannel();
        $oneapp_result = $AddApp_model->find()
            ->where($where)
            ->asArray()
            ->all();
//        var_dump($oneapp_result);
//        exit;
        return $oneapp_result;
    }
}
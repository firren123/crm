<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/4
 * Time: 9:12
 */
namespace backend\models\i500m;
class AppVersionList extends I500Base{
    /**
     * *数据库  表名称
     * @return string
     */
    public static function tableName(){
        return "{{%app_log}}";
    }
    public function attributeLabels()
    {
        return array(
            'name' => '版本名称',
            'subordinate_item' => '所属项目',
            'major' => '主版本号',
            'minor' => '副版本号',
            'type' =>'操作系统',
            'url' => '下载地址',
            'explain' => '升级提示',
            'status'=>'有效性',
        );
    }
    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['name','subordinate_item','major','type','url','explain','status'],'required'],
            //[['name'],'unique','message'=>'{attribute}已存在'],
            [['major','minor'],'match','pattern'=>'/^\d+(\.\d+){0,2}$/','message'=>'{attribute}格式输入不正确'],
            [['subordinate_item','type'],'match','pattern'=>'/^\d+$/','message'=>'请选择{attribute}'],
            //['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}格式输入不正确'],
        ];
    }

    /**
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function totalNum($where,$and_cond = ''){
//        $allapp_result = $this->find()
//            ->where($where)
//            ->count();
        $allapp_result = AppVersionList::find()
            ->andFilterWhere($where)
            ->andWhere($and_cond)
            ->count();
        return $allapp_result;
    }

    /**
     * @param array $where
     * @return array|null|\yii\db\ActiveRecord
     * show one app
     */
    public function showoneurl($where = array()){
        $oneapp_result = $this->find()
            ->where($where)
            ->asArray()
            ->one();
        return $oneapp_result;
    }

    /**
     * @param array $where
     * @param string $fields
     * @param string $order
     * @param int $page
     * @param int $size
     * @return array|\yii\db\ActiveRecord[]
     */
    public function allapp($where=array() , $fields='*' , $order='' , $page = 1 , $size = 2,$and_cond){
        $allapp_result = $this->find()
            ->where($where)
            ->andWhere($and_cond)
            ->select($fields)
            ->orderBy($order)
            ->offset(($page-1) * $size)
            ->limit($size)
            ->asArray()
            ->all();
        return $allapp_result;
    }

    /**
     * @param $msg
     * @return bool|mixed
     * add
     */
    public function addapp($msg){
        $AddApp_model = new AppVersionList();
        foreach($msg as $k=>$v){
            $AddApp_model->$k = $v;
        }
        $result = $AddApp_model->save();
        if($result){
            return $AddApp_model->primaryKey;
        }else{
            return false;
        }
    }

    /**
     * del
     */
    public function deloneurl($id){
        $AddApp_model = AppVersionList::findOne($id);
        $result = $AddApp_model->delete();
        return $result;
    }

    /**
     * @param $id
     * @param $msg
     * @return bool
     * edit
     */
    public function editapp($id,$msg){
        $id = AppVersionList::findOne($id);
        if (!empty($id)) {
            foreach ($msg as $k => $v) {
                $id->$k = $v;
            }
            $result = $id->save();
            return $result;
        }
    }
}
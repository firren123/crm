<?php
/**
 * App版本列表
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  AppVersionList.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  2015/6/4 上午9:12
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\models\i500m;

/**
 * Class app_log
 * @category  PHP
 * @package   Admin
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class AppVersionList extends I500Base
{
    /**
     * 数据库  表名称
     * @return string
     */
    public static function tableName()
    {
        return "{{%app_log}}";
    }
    /**
     * 字段名称
     * @return string
     */
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
            'is_forced_to_update'=>'是否强制更新',
            'update_prompt'=>'更新提示时间',
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
            [['name','subordinate_item','major','type','url','explain','status','is_forced_to_update','update_prompt'],'required'],
            //[['name'],'unique','message'=>'{attribute}已存在'],
            [['major','minor'],'match','pattern'=>'/^\d+(\.\d+){0,2}$/','message'=>'{attribute}格式输入不正确'],
            [['subordinate_item','type'],'match','pattern'=>'/^\d+$/','message'=>'请选择{attribute}'],
            //['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}格式输入不正确'],
        ];
    }

    /**
     * 统计记录数
     * @param array  $where    条件
     * @param string $and_cond And条件
     * @return array|\yii\db\ActiveRecord[]
     */
    public function totalNum($where,$and_cond = '')
    {
        $allApp_result = AppVersionList::find()
            ->andFilterWhere($where)
            ->andWhere($and_cond)
            ->count();
        return $allApp_result;
    }

    /**
     * 查询
     * @param array $where 条件
     * @return array|null|\yii\db\ActiveRecord
     * show one app
     */
    public function showOneUrl($where = array())
    {
        $oneApp_result = $this->find()
            ->where($where)
            ->asArray()
            ->one();
        return $oneApp_result;
    }

    /**
     * 查询分页数据
     * @param array  $where    条件
     * @param string $fields   字段
     * @param string $order    排序
     * @param int    $page     第几页
     * @param int    $size     每页数量
     * @param string $and_cond And条件
     * @return array|\yii\db\ActiveRecord[]
     */
    public function allApp($where=array(), $fields='*', $order='', $page = 1, $size = 2, $and_cond='')
    {
        $allApp_result = $this->find()
            ->where($where)
            ->andWhere($and_cond)
            ->select($fields)
            ->orderBy($order)
            ->offset(($page-1) * $size)
            ->limit($size)
            ->asArray()
            ->all();
        return $allApp_result;
    }

    /**
     * 添加
     * @param array $msg Data
     * @return bool|mixed
     */
    public function addApp($msg)
    {
        $AddApp_model = new AppVersionList();
        foreach ($msg as $k=>$v) {
            $AddApp_model->$k = $v;
        }
        $result = $AddApp_model->save();
        if ($result) {
            return $AddApp_model->primaryKey;
        } else {
            return false;
        }
    }

    /**
     * 删除
     * @param array $id Data
     * @return bool|mixed
     */
    public function delOneUrl($id)
    {
        $AddApp_model = AppVersionList::findOne($id);
        $result = $AddApp_model->delete();
        return $result;
    }

    /**
     * 修改
     * @param string $id  ID
     * @param Array  $msg Data
     * @return bool
     * edit
     */
    public function editApp($id, $msg)
    {
        $id = AppVersionList::findOne($id);
        if (!empty($id)) {
            foreach ($msg as $k => $v) {
                $id->$k = $v;
            }
            $result = $id->save();
            return $result;
        }
        return false;
    }
}
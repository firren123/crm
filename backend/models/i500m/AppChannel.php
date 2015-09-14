<?php
/**
 * App版本列表
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  AppChannel.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  2015/6/4 上午10:56
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\models\i500m;

/**
 * Class app_channel
 * @category  PHP
 * @package   Admin
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class AppChannel extends I500Base
{
    /**
     * 数据库  表名称
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
     * 添加
     * @param array $msg Data
     * @return bool|mixed
     */
    public function addApp($msg)
    {
        $AddApp_model = new AppChannel();
        foreach ($msg as $k => $v) {
            $AddApp_model->$k = $v;
        }
        $result = $AddApp_model->save();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 修改
     * @param Array $where 条件 baidu 360
     * @param Array $list  Data
     * @return bool
     */
    public function editApp($where, $list)
    {
        $result = AppChannel::updateAll(['update_time'=>$list['update_time'], 'url'=>$list['url']], $where);
        return $result;
    }

    /**
     * 删除
     * @param int $app_id AppID
     * @return bool
     */
    public function delOneUrl($app_id)
    {
        $result = AppChannel::deleteAll('app_id = :app_id', [':app_id' => $app_id]);
        //$result = Customer::deleteAll('age > :age AND gender = :gender', [':age' => 20, ':gender' => 'M']);
        return $result;
    }

    /**
     * 查询一条记录
     * @param array $where 条件
     * @return array|null|\yii\db\ActiveRecord
     */
    public function showOneUrl($where)
    {
        $AddApp_model = new AppChannel();
        $oneApp_result = $AddApp_model->find()
            ->where($where)
            ->asArray()
            ->all();
        return $oneApp_result;
    }
}
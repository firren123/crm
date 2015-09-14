<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   AppChannel
 * @filename  Admin.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/21 下午4:39
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\models\i500m;
/**
 * Class AppChannel
 * @category  PHP
 * @package   AppChannel
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
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
     * $msg
     * @return bool|mixed
     * add
     */
    public function addapp($msg)
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
     * 简介：xx
     * @param array $where x
     * @param array $list  x
     * @return int
     */
    public function editapp($where,$list)
    {
        $result = AppChannel::updateAll(['update_time' => $list['update_time'], 'url' => $list['url']], $where);
        return $result;
    }

    /**
     * 简介：
     * @param int $app_id id
     * @return int
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
    /**
     * 简介：
     * @param array $where $where
     * @return int
     */
    public function showoneurl($where)
    {
        $AddApp_model = new AppChannel();
        $oneapp_result = $AddApp_model->find()
            ->where($where)
            ->asArray()
            ->all();
        return $oneapp_result;
    }
}

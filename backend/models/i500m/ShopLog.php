<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/6/2 上午10:37
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models\i500m;
use common\helpers\CommonHelper;

class ShopLog extends I500Base
{

    public static function getDB(){
        return \Yii::$app->db;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return "{{%shop_log}}";
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'log_type','shop_id'], 'integer'],
            [['id', 'log_type'], 'required'],
            ['log_time', 'default', 'value' => date("Y-m-d H:i:s", time())],
            [['log_info', 'os', 'browser', 'ip_address'], 'string', 'max' => 255]
        ];
    }
    /**
     * 记录日志
     * @param string $info     日志内容
     * @param int    $log_type 日志类型
     * @return bool|mixed
     */
    public function recordLog($info = '', $log_type=1)
    {
        $admin_id = \Yii::$app->user->identity->id;
        if (empty($admin_id)) return false;
        $model = clone $this;
        $model->admin_id = $admin_id;
        $model->ip_address = CommonHelper::getIp();
        $model->browser = CommonHelper::getBrowser();
        $model->os = CommonHelper::getOS();
        $model->log_info = $info;
        $model->log_type = $log_type;
        $re = $model->insert();
        return $re;
    }

    public function total($where=null)
    {
        if($where){
            $total = $this->find()->where($where)->count();
            return $total;
        }else{
            $total = $this->find()->count();
            return $total;
        }

    }

    public function show($data=array(),$offset,$where=null)
    {
        if($where){
            $list = $this->find()
                ->where($where)
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        }else{
            $list = $this->find()
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        }
    }

}
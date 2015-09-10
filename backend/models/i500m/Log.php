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

/**
 * Class Log
 * @category  PHP
 * @package   Log
 * @author    renyineng <renyineng@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Log extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return "{{%crm_log}}";
    }

    /**
     * 简介：
     * @return string
     */
    public function rules()
    {
        return [
            [['admin_id', 'log_type'], 'integer'],
            [['admin_id', 'log_type'], 'required'],
            ['add_time', 'default', 'value' => date("Y-m-d H:i:s", time())],
            [['log_info', 'os', 'browser', 'ip_address'], 'string', 'max' => 255]
        ];
    }

    /**
     * 记录日志
     * @param string $info     日志内容
     * @param int    $log_type 日志类型
     * @return bool|mixed
     */
    public function recordLog($info = '', $log_type = 1)
    {
        $admin_id = \Yii::$app->user->identity->id;
        if (empty($admin_id)) {
            return false;
        }
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

    /**
     * 简介：
     * @param null $where
     * @return int|string
     */
    public function total($where = null)
    {
        if ($where) {
            $total = $this->find()->where($where)->count();
            return $total;
        } else {
            $total = $this->find()->count();
            return $total;
        }

    }

    /**
     * 简介：
     * @param array $data   x
     * @param int   $offset x
     * @param null  $where  x
     * @return array|\yii\db\ActiveRecord[]
     */
    public function show($data = array(), $offset= null, $where = null)
    {
        if ($where) {
            $list = $this->find()
                ->where($where)
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        } else {
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

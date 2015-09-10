<?php
/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   OrderLog.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/23 15:55
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\i500m;


use common\helpers\CommonHelper;

/**
 * Class OrderLog
 * @category  PHP
 * @package   OrderLog
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class OrderLog extends I500Base
{
    /**
     * 简介：连接表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return '{{%order_log}}';
    }

    /**
     * 简介：总数
     * @param null $where xx
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
    public function show($data = array(), $offset = null, $where = null)
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

    /**
     * 记录日志
     * @param array $data 日志内容
     * @return bool|mixed
     */
    public function recordLog($data = [])
    {
        $admin_id = \Yii::$app->user->id;
        if (empty($admin_id)) {
            return false;
        }
        $re = false;
        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
            $this->add_time = date('Y-m-d H:i:s');
            $this->admin_id = $admin_id;
            $this->ip_address = CommonHelper::getIp();
            $this->browser = CommonHelper::getBrowser();
            $this->type = 3;
            $re = $this->insert();
        }
        return $re;
    }
}

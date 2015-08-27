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

class OrderLog extends I500Base{
    public static function tableName(){
        return '{{%order_log}}';
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

    /**
     * 记录日志
     * @param array $data     日志内容
     * @return bool|mixed
     */
    public function recordLog($data = [])
    {
        $admin_id = \Yii::$app->user->id;
        if (empty($admin_id)) return false;
        $re = false;
        if ($data) {
            foreach ($data as $k=>$v) {
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
<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  OrderLog.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/27 下午6:18
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\shop;


use common\helpers\CommonHelper;

class OrderLog extends ShopBase{
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
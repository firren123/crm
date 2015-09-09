<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  OrderLog.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/9 下午1:37
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;
use common\helpers\CommonHelper;


/**
 * Class OrderLog
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class OrderLog extends SocialBase
{
    /**
     * 简介：连接表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_order_log}}";
    }
    /**
     * 记录日志
     * @param array $data 日志内容
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
<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  ShopOrder.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/22 上午10:23
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\models\shop;


use common\helpers\CommonHelper;

/**
 * Class CupboardAgreementLog
 * @category  PHP
 * @package   CupboardAgreementLog
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CupboardAgreementLog extends ShopBase
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%cupboard_agreement_log}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array();
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * 简介：
     * @param array $cond     x
     * @param bool  $as_array x
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getInfo($cond = array(), $as_array = true)
    {
        $info = array();
        if ($cond) {
            if ($as_array) {
                $info = $this->find()->where($cond)->asArray()->one();
            } else {
                $info = $this->find()->where($cond)->one();
            }
        }
        return $info;

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
            $this->create_time = date('Y-m-d H:i:s');
            $this->admin_id = $admin_id;
            $this->ip_address = CommonHelper::getIp();
            $re = $this->insert();
        }
        return $re;
    }
}

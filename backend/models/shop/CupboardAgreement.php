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

use linslin\yii2\curl;

/**
 * Class CupboardAgreement
 * @category  PHP
 * @package   CupboardAgreement
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CupboardAgreement extends ShopBase
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%cupboard_agreement}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'status' => '协议审核状态',
        );
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['cupboard_period'], 'required'],
            [['description'], 'required'],
            ['status', 'default', 'value' => 0],

        ];
    }

    /**
     * 简介：
     * @param array $cond     条件
     * @param bool  $as_array 是否数组
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
     * 简介：
     * @param array $status x
     * @param array $id     x
     * @return bool
     */
    public function updateInfo($status, $id)
    {
        if ($id) {
            $coupon = CupboardAgreement::findOne($id);
            $coupon->status = $status;
            $coupon->update_time = date("Y-m-d H:i:s");
            return $coupon->save();
        } else {
            return false;
        }
    }

}

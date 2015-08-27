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

use backend\models\i500m\OrderDetail;
use backend\models\i500m\OrderLog;
use backend\models\i500m\Product;
use common\helpers\CurlHelper;
use linslin\yii2\curl;
class CupboardAgreement extends ShopBase{
    public static function tableName(){
        return '{{%cupboard_agreement}}';
    }
    public function attributeLabels()
    {
        return array(
            'status' => '协议审核状态',
        );
    }
    public function rules(){
        return [
            //不可为空的字段
            [['cupboard_period'],'required'],
            [['description'],'required'],
            ['status','default','value'=>0] ,

        ];
    }

    public function getInfo($cond = array(), $as_array = true){
        $info = array();
        if($cond){
            if($as_array){
                $info = $this->find()->where($cond)->asArray()->one();
            }else{
                $info = $this->find()->where($cond)->one();
            }
        }
        return $info;

    }

    public function updateInfo($status,$id)
    {
        if($id) {
            $coupon = CupboardAgreement::findOne($id);
            $coupon->status = $status;
            $coupon->update_time = date("Y-m-d H:i:s");
            return $coupon->save();
        }else{
            return false;
        }
    }

}
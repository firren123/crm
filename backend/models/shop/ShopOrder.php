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
class ShopOrder extends ShopBase{
    public static function tableName(){
        return '{{%shop_order}}';
    }
    public function attributeLabels()
    {
        return array(
            'status' => '订单确认状态',
            'ship_status' => '订单物流状态',
            'pay_status' => '订单物流状态',
            'remark' => '备注',

        );
    }
    public function rules(){
        return [
            //不可为空的字段
            [['remark'],'required'],

        ];
    }

    public function getDetail($map){
        $data = $this->find()->where($map)->asArray()->one();
        $m_detail = new ShopDetailOrder();
        $data['detail'] = $m_detail->find()->where($map)->asArray()->All();
        $p_model = new Product();
        foreach($data['detail'] as $k=>$v){
            $image = $p_model->getInfo(['id'=>$v['p_id']],true,'image');
            $data['detail'][$k]['image'] = $image['image'];
        }
        return $data;
    }

}
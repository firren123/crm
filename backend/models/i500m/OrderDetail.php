<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  OrderDetail.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/20 下午3:24
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;


class OrderDetail extends I500Base{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%order_detail}}';
    }

    /**
     * 简介：根据订单号查询订单详情
     * @author  lichenjun@iyangpin.com。
     * @param $order_sn
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOrderDetailBySn($order_sn)
    {
        if (empty($order_sn)) return array();
        $info = $this::find()->select('p_id,order_sn,name,num,price,attribute_str,total')->where(['order_sn'=>$order_sn])->asArray()->all();
        return $info;
    }

}
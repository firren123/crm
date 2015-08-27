<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  UserOrder.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/20 上午11:55
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;


class UserOrder extends I500Base{

    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%order}}';
    }
    /**
     *
     */
    public function UserDetailByOrderId($order_sn)
    {
        if (empty($order_sn)) return array();
        $detail = $this::find()->where(['order_sn'=>$order_sn])->asArray()->one();


        return $detail;
    }




}
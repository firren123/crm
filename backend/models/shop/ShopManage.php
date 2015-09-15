<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   MODEL
 * @filename  ShopManage.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/5 下午3:16
 * @link      http://www.i500m.com/
 */
namespace backend\models\shop;

/**
 * ShopManage - model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   weitonghe <weitonghe@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     weitonghe@iyangpin.com
 */
class ShopManage extends ShopBase
{
    /**
     * 数据库
     *ShopContract 表
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_manage}}';//经营信息表
    }

    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
        ];
    }

    /**
     * 简介：所有数据
     * @author  weitonghe@iyangpin.com
     * @return int
     */
    public function getAllData()
    {
        $all = $this->find()
            ->asArray()
            ->all();
        return $all;
    }

    /**
     * 简介：添加商家合同经营基本信息
     *
     * @param array $msg 数据
     *
     * @return boolean
     */
    public function insertOneData($msg)
    {
        $ShopManage_model = new ShopManage();
        foreach ($msg as $k => $v) {
            $ShopManage_model->$k = $v;
        }
        $result = $ShopManage_model->save();
        return $result;
    }
}

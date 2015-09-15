<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   MODEL
 * @filename  ShopBcBank.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/21 下午14:16
 * @link      http://www.i500m.com/
 */
namespace backend\models\shop;

/**
 * ShopBcBank - model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   weitonghe <weitonghe@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     weitonghe@iyangpin.com
 */
class ShopBcBank extends ShopBase
{
    /**
     * 数据库
     *ShopContract 表
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_bc_bank}}';//开户支行信息表
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
     * 查询一条数据  显示字段值
     *
     * @param array $where 条件
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAllData($where)
    {
        $all = $this->find()
            ->where($where)
            ->asArray()
            ->all();
        return $all;
    }

    /**
     * 查询数据
     * @param array $where 条件
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getBcBank($where)
    {
        $all = $this->find()
            ->select("id,name")
            ->where($where)
            ->asArray()
            ->all();
        return $all;
    }

    /**
     * 查询一条数据
     * @param array $where 条件
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOneBcBank($where)
    {
        $all = $this->find()
            ->select("id,name")
            ->where($where)
            ->asArray()
            ->one();
        return $all;
    }
}

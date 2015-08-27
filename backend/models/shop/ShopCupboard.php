<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/24 上午10:23
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\models\shop;

class ShopCupboard extends ShopBase{
    public static function tableName(){
        return '{{%shop_cupboard}}';
    }
    public function attributeLabels()
    {
        return array(
            'status' => '状态',
        );
    }
    public function rules(){
        return [
            //不可为空的字段
            //[['remark'],'required'],

        ];
    }

    public function getlist($where=null)
    {
        if($where){
            $list = $this->find()
                ->where($where)
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        }else{
            $list = $this->find()
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        }
    }
}
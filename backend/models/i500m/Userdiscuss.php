<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 用户评论MODEL
 *
 * @category  PHP
 * @package   Admin
 * @filename  Userdiscuss.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/31 上午9:56
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;
use yii\db\ActiveRecord;

class Userdiscuss extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName(){
        return '{{%user_discuss}}';
    }

    public function rules(){
        return [
            ['user_id','default','value'=>''] ,
            ['user_name','default','value'=>'匿名'] ,
            ['shop_name','default','value'=>'无'] ,
            ['shop_id','default','value'=>1] ,
            ['content','default','value'=>''] ,
            ['status','default','value'=>0] ,
            ['type','default','value'=>1] ,
            ['add_time','default','value'=>date('Y-m-d H:i:s')] ,
            ['remark','default','value'=>''] ,
            ['discuss','default','value'=>0] ,
            ['order_sn','default','value'=>''] ,
            ['img','default','value'=>''] ,

        ];
    }

}
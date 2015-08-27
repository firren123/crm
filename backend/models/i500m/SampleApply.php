<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   User
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @time      15/7/24 上午09:37
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhaochengqiang@iyangpin.com
 */
namespace backend\models\i500m;
use common\helpers\CommonHelper;

class SampleApply extends I500Base
{

    public static function getDB(){
        return \Yii::$app->db_500m;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return "{{%sample_apply}}";
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }



    public function total($where=null)
    {
        if($where){
            $total = $this->find()->where($where)->count();
            return $total;
        }else{
            $total = $this->find()->count();
            return $total;
        }

    }

    public function show($data=array(),$offset,$where=null)
    {
        if($where){
            $list = $this->find()
                ->where($where)
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        }else{
            $list = $this->find()
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        }
    }

}
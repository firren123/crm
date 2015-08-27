<?php
/**
 * 简介
 * @category  admin
 * @package   意见反馈
 * @author     <youyong@iyangpin.com>
 * @time      2015/4/1 11:10
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
namespace backend\models\i500m;
use yii\db\ActiveRecord;

class Opinion extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * 规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['user_id'],'required','message' => '用户ID 不能为空.'],
            [['username'],'required','message' => '用户名 不能为空.'],
            [['content'],'required','message' => '反馈内容 不能为空.'],
            [['status'],'required','message' => '状态 不能为空.'],
            [['create_time'],'required','message' => '反馈时间 不能为空.'],

        ];
    }

    /**
     * 意见反馈列表（后台）
     *
     * Author youyong@iyangpin.com
     *
     * @param： int $size    显示条数
     * @param： int $page    页码
     * @param： string where 条件
     *
     * @return array 反馈列表集合
     */
    public function getListOpinion($size=NULL,$offset=NULL,$is_number=NULL,$where=NULL){
        if(!empty($is_number)){
            $list = $this->find()->where($where)->count();
        }else {
            $list = $this->find()
                ->where($where)
                ->offset($offset)
                ->limit($size)
                ->orderBy('id desc')
                ->asArray()
                ->all();
        }
        return $list;
    }
}
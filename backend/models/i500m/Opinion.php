<?php
/**
 * 意见反馈
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  UserorderController.php
 * @author    youyong <youyong@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/20 下午5:47
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\models\i500m;

class Opinion extends I500Base
{
    /**
     * 数据库
     * @return string
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * 规则
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
     * Author youyong@iyangpin.com
     *
     * @param int    $size      显示条数
     * @param int    $offset    页码
     * @param int    $is_number 页码
     * @param string $where     条件
     *
     * @return array 反馈列表集合
     */
    public function getListOpinion($size = null, $offset = null, $is_number = null, $where = null)
    {
        if (!empty($is_number)) {
            $list = $this->find()->where($where)->count();
        } else {
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

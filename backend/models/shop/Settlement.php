<?php
/**
 * 简介
 * @category WAP
 * @package 模块名称
 * @author zhoujun<dj652262790@126.com>
 * @time 2015/5/29 13:34
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license http://www.i500m.com
 * @link zhoujun@iyangpin.com
 */
namespace backend\models\shop;
class Settlement extends ShopBase{

    public static function tableName()
    {
        return '{{%shop_accounts}}';//开户银行信息表
    }
    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
//            [['name'],'required','message' => '银行名称不能为空！'],
//            [['bank_code'],'required','message' => '银行代码不能为空！'],
//            [['status'],'required','message' => '银行状态不能为空！']
        ];
    }

    public function settle($id)
    {
        $list = $this->find()->where("shop_id = $id")->asArray()->one();
        return $list;
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
                ->orderBy('end_time desc')
                ->asArray()
                ->all();
            return $list;
        }else{
            $list = $this->find()
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('end_time desc')
                ->asArray()
                ->all();
            return $list;
        }
    }

    public function all_shop($shop_id)
    {
        $list = $this->find()->select('shop_id')->where("shop_id = $shop_id")->asArray()->one();
        return $list;
    }

    public function freeze($id,$is_freeze=false){
        if($is_freeze == 1){
            $list = Settlement::findOne($id);
            $list->status = 1;
            $status = $list->save();
            return $status;
        }elseif($is_freeze == 0){
            $list = Settlement::findOne($id);
            $list->status = 2;
            $status = $list->save();
            return $status;
        }else{
            $list = Settlement::findOne($id);
            $list->status = 0;
            $status = $list->save();
            return $status;
        }
    }

    public function ship($account_id,$shop_id)
    {
        $list = $this->find()->select('start_time,end_time,status')->where(['id'=>$account_id,'shop_id'=>$shop_id])->asArray()->one();
        return $list;
    }
} 
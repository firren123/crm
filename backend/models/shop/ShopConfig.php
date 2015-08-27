<?php
namespace backend\models\shop;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/25 0025
 * Time: 下午 9:28
 */
class ShopConfig extends ShopBase{
    public static function tableName(){
        return '{{%shop_config}}';
    }

    public function add($config=array()){
        if(isset($config)){
            $this->free_shipping = $config['free_shipping'];
            $this->freight = $config['freight'];
            $this->send_price = $config['send_price'];
            $this->community_num=$config['community_num'];
            $this->bc_id=$config['bc_id'];
            $this->price_limit=$config['price_limit'];
            $this->type=$config['type'];
            $this->save();
        }
    }

    public function check($bc_id,$id){
        $list= array();
        if(!empty($bc_id)) {
            if (0 == $id) {
                $list = $this->find()->where("bc_id=" . $bc_id)->asArray()->one();
            } else {
                $list = $this->find()->where("bc_id="."'".$bc_id."' and id!=".$id)->asArray()->one();
            }
        }
        return $list;
    }


}
<?php

namespace backend\models\i500m;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attribute_value".
 *
 * @property integer $id
 * @property integer $attr_id
 * @property string $attr_value
 * @property integer $weight
 */
class AttributeValue extends I500Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attribute_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attr_id', 'weight'], 'integer'],
            [['attr_value'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attr_id' => '属性值id',
            'attr_value' => '属性值',
            'weight' => '属性值权重，默认从小到大',
        ];
    }
    public function insertValue($attr_id, $data)
    {
        if (empty($attr_id) || empty($data)) return false;
        //$attribute['Attribute']
        $value = [];
        $re = false;
        if (!empty($data['attr_value'])) {
            foreach ($data['attr_value'] as $k=>$v) {
                if (empty($v)){
                    continue;
                }
                //$value[$k]['attr_value'] = $v;
                $value[] = [$attr_id,$v,ArrayHelper::getValue($data,'weight.'.$k,99)];


            }
            //var_dump($value);exit();
            if (!empty($value)) {
                $re = self::getDB()->createCommand()->batchInsert('attribute_value',['attr_id','attr_value','weight'],$value)->execute();

            }

        }
        return $re;
    }
    public function updateValue($data)
    {
        if (empty($data)) return false;
        //var_dump($data);
        foreach ($data as $k=>$v) {
            if (empty($v[0])){
                unset($data[$k]);
                continue;
            }
            //$model = $this->findOne($k);
            //var_dump($model);
            //$value['AttributeValue'] = ['attr_value'=>$v[0],'weight'=>ArrayHelper::getValue($v,1,99)];
            //$value[] = ['attr_value'=>$v[0],'weight'=>ArrayHelper::getValue($v,1,99)];
            self::getDB()->createCommand()
                ->update('attribute_value',['attr_value'=>$v[0],'weight'=>ArrayHelper::getValue($v,1,99)],'id='.$k)
                ->execute();
           // var_dump($value);
//            if (!empty($model)) {
//                $model->load($value['AttributeValue']);
//                //var_dump($model->errors);
//                $re = $model->save();
//                //var_dump($re);
//            }
           // var_dump($value);exit();
        }


       // var_dump($value);exit();

        return true;
    }
}

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
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%attribute_value}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
            [['attr_id', 'weight'], 'integer'],
            [['attr_value'], 'string', 'max' => 45]
        ];
    }

    /**
     * 简介：
     * @return array
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

    /**
     * 简介：
     * @param int   $attr_id ID
     * @param array $data    数据
     * @return bool
     */
    public function insertValue($attr_id, $data)
    {
        if (empty($attr_id) || empty($data)) {
            return false;
        }
        //$attribute['Attribute']
        $value = [];
        $re = false;
        if (!empty($data['attr_value'])) {
            foreach ($data['attr_value'] as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                //$value[$k]['attr_value'] = $v;
                $value[] = [$attr_id, $v, ArrayHelper::getValue($data, 'weight.' . $k, 99)];


            }
            //var_dump($value);exit();
            if (!empty($value)) {
                $re = self::getDB()->createCommand()->batchInsert('attribute_value', ['attr_id', 'attr_value', 'weight'], $value)->execute();

            }

        }
        return $re;
    }

    /**
     * 简介：
     * @param array $data 数据
     * @return bool
     */
    public function updateValue($data)
    {
        if (empty($data)) {
            return false;
        }
        foreach ($data as $k => $v) {
            if (empty($v[0])) {
                unset($data[$k]);
                continue;
            }
            self::getDB()->createCommand()
                ->update('attribute_value', ['attr_value' => $v[0], 'weight' => ArrayHelper::getValue($v, 1, 99)], 'id=' . $k)
                ->execute();
        }
        return true;
    }
}

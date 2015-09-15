<?php
/**
 * 属性操作类
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   Admin
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/4/17 上午10:36
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models\i500m;

use Yii;

/**
 * This is the model class for table "attribute".
 *
 * @property integer $id
 * @property string $attr_name
 * @property string $admin_name
 * @property integer $weight
 * @property integer $is_search
 */
class Attribute extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return 'attribute';
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
            [['weight', 'is_search'], 'integer'],
            [['attr_name', 'admin_name'], 'required'],
            [['attr_name', 'admin_name'], 'string', 'max' => 45]
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
            'attr_name' => '前台显示名称',
            'admin_name' => '后台显示名称',
            'weight' => '权重',
            'is_search' => '是否检索属性',
        ];
    }
    /**
     * 获取属性列表
     * @return array
     */
    public function getListAttribute()
    {
        $list = $this->find()
            ->asArray()
            ->all();
        return $list;
    }

    /**
     * 插入属性
     * @param array $data 插入的数据
     * @return bool
     */
    public function insertAttribute($data)
    {
        if (empty($data)) {
            return false;
        }
        //$attribute['Attribute']
        $attribute = [];
        $attribute['Attribute'] = $data;

        $this->load($attribute);

        $re = $this->save();


        return isset($re)?$this->attributes['id']:false;
    }

    /**
     * 简介：
     * @param array $model x
     * @param array $data  x
     * @return bool
     */
    public function updateAttribute($model, $data)
    {
        if (empty($model) || empty($data)) {
            return false;
        }
        //$attribute['Attribute']
        $attribute = [];
        $attribute['Attribute'] = $data;
        $model->load($attribute);
        $re = $model->save();

        return isset($re)?$this->attributes['id']:false;
    }

    /**
     * 简介：
     * @param int $id x
     * @return bool
     * @throws \Exception
     */
    public function delAttribute($id)
    {
        $model = $this->findOne($id);
        $re = $model->delete();
        if ($re) {
            //删除属性值
            $r = AttributeValue::deleteAll(['attr_id'=>$id]);
            return true;
        }
        return false;
    }
}

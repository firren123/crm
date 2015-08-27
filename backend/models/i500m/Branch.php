<?php
/**
 * 简介：已开通省市管理model
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  CouponType.php
 * @author    zhoujun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m
 * @datetime  15/4/20 下午16:30
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;
use Yii;
use yii\base\Model;
use common\helpers\CurlHelper;
use yii\db\ActiveRecord;
use linslin\yii2\curl;
class Branch extends I500Base
{
    public function attributeLabels()
    {
        return array(
            'name' => '分公司名称',
            'status' => '状态',
            'sort' => '排序',
            'province_id'=>'省名',
            'city_id_arr'=>'市名',
        );
    }

    public static function tableName()
    {
        return '{{%crm_branch}}';
    }

    public function rules()
    {
        return [
            //不可为空的字段
            [['name', 'status','sort', 'city_id_arr', 'province_id'], 'required'],
            [['sort'], 'integer'],
        ];
    }


    /**
     * @purpose:省市分页显示
     * @name: show
     * @return string
     */
    public function show_branch($data = array(), $offset)
    {
        $list = $this->find()
            ->orderBy('sort desc')
            ->offset($offset)
            ->orderBy("sort asc")
            ->limit($data['size'])
            ->asArray()
            ->all();
        return $list;
    }

    public function total()
    {
        $total = $this->find()->count();
        return $total;
    }

    public function add($data)
    {
        $this->province_id = $data['province_id'];
        $this->city_id_arr = $data['city_id_str'];
        $this->name = $data['name'];
        $this->status = $data['status'];
        $this->sort = $data['sort'];
        $this->save();
    }

    public function del($id)
    {
        $data = $this::findOne($id);
        $info = $data->delete();
        return $info;
    }

    public function display_back($id)
    {
        $list = $this->find()->where("id = $id")->asArray()->one();
        return $list;
    }
    public function branch_info()
    {
        $list = $this->find()->asArray()->all();
        return $list;
    }

    public function city_id($pid)
    {
        $list = $this->find()->where("$pid in (city_id_arr)")->asArray()->one();
        return $list;
    }

    public function branch_city_all($pid)
    {
        $list = $this->find()->select('city_id_arr')->where("province_id = $pid")->asArray()->all();
        return $list;
    }

    public function city_all($bid)
    {
        $list = $this->find()->select('city_id_arr')->where("id = $bid")->asArray()->one();
        return $list;
    }
}
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

/**
 * Class OpenCity
 * @category  PHP
 * @package   OpenCity
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class OpenCity extends I500Base
{
    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => '',
            'city_name' => '开通城市名称',
            'status' =>'状态',
            'province'=>'省份',
            'city'=>'城市',
            'display'=>'是否显示',
        );
    }

    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%open_city}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['status','city','province','display'],'required'],
        ];
    }


    /**
     * 简介：省市分页显示
     * @param array $data   data
     * @param null  $offset x
     * @param int   $city   x
     * @return array|\yii\db\ActiveRecord[]
     */
    public function show($data = array(), $offset = null, $city = 0)
    {
        if ($city) {
            $list = $this->find()
                ->where(['like', 'city_name', $city])
                ->offset($offset)
                ->limit($data['size'])
                ->asArray()
                ->all();
            return $list;
        } else {
            $list = $this->find()
                ->offset($offset)
                ->limit($data['size'])
                ->asArray()
                ->all();
            return $list;
        }
    }

    /**
     * 简介：
     * @param int $city 城市
     * @return int|string
     */
    public function total($city)
    {
        if ($city) {
            $total = $this->find()->where(['like', 'city_name', $city])->count();
            return $total;
        } else {
            $total = $this->find()->count();
            return $total;
        }
    }

    /**
     * 简介：
     * @param int $id ID
     * @return array|null|ActiveRecord
     */
    public function verifyDredgeCity($id)
    {
        $arr = $this->find()
            ->select('*')
            ->where(['city'=>$id])
            ->asArray()
            ->one();
        return $arr;
    }

    /**
     * 修改省市状态
     * @param int $id ID
     * @return string
     */
    public function update_status($id)
    {
        $customer = OpenCity::findOne($id);
        $customer->status = $customer['status']==1?2:1;
        $info = $customer->save();
        return $info;
    }
    /**
     * 修改省市状态
     * @param int $id ID
     * @return string
     */
    public function add_city_info($id)
    {
        $list = $this->find()->select("city_name")->where("branch_id = $id")->asArray()->one();
        return $list;
    }
    /**
     * X
     * @return string
     */
    public function open_city_all()
    {
        $list = $this->find()->select('city,city_name')->asArray()->all();
        return $list;
    }
    /**
     * X
     * @param int $city ID
     * @return string
     */
    public function city_one($city)
    {
        $list = $this->find()->select('city,city_name')->where("city = $city")->andWhere('status=1')->asArray()->one();
        return $list;
    }
    /**
     * X
     * @param int $city ID
     * @return string
     */
    public function all_city($city = null)
    {
        if ($city) {
            $list = $this->find()->select("city,city_name,province,city,status")->where("id = $city")->asArray()->all();
            return $list;
        } else {
            $list = $this->find()->select("city,city_name,province,city,status")->where("status = 1")->asArray()->all();
            return $list;
        }
    }

    /**
     * 简介：
     * @param string $name X
     * @param string $id   X
     * @return array|null|ActiveRecord
     */
    public function check_city($name = '', $id = '')
    {
        $data = [];
        if ($id) {
            $data = $this->find()->where("city_name=" . "'" . $name . "' and id!=" . $id)->asArray()->one();
        } else {
            $data = $this->find()->where("city_name=" . "'" . $name . "'")->asArray()->one();
        }
        return $data;
    }
}

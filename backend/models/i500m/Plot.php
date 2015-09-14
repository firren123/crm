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
use linslin\yii2\curl;

/**
 * Class Plot
 * @category  PHP
 * @package   Plot
 * @author    zhoujun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Plot extends I500Base
{
    /**
     * 小区表后缀
     * @var string 小区表后缀，形如 _beijing
     */
    private static $_table_suffix = '_beijing';

    /**
     * 表名
     * @return string
     */
    public static function tableName()
    {
        return '{{%community' . Plot::$_table_suffix . '}}';
    }

    /**
     * 简介：设置后缀
     * @author  zhengyu@iyangpin.com
     * @param string $str x
     * @param array  $arr x
     * @return null
     */
    public static function setSuffix($str, $arr)
    {
        if (in_array($str, $arr)) {
            Plot::$_table_suffix = $str;
            return;
        } else {
            Plot::$_table_suffix = '_beijing';
            return;
        }
    }

    /**
     * 简介：
     * @param string $str x
     * @return null
     */
    public static function setSuffix1($str = 'beijing')
    {
        Plot::$_table_suffix = $str;
    }

    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'name' => '小区名称',
            'province' => '省名',
            'city' => '市名',
            'area' => '区域',
            'address' => '地址',
            'lng' => '经度',
            'lat' => '纬度',
            'average' => '均价',
            'total_area' => '总面积',
            'total_number' => '总户数',
            'buildings' => '建筑年代',
            'developer' => '开发商',
            'volume_rate' => '容积率',
            'property' => '物业公司',
            'letting_rate' => '出租率',
            'property_type' => '物业类型',
            'parking' => '停车位',
            'property_fee' => '物业费用',
            'greening_rate' => '绿化率',
            'status' => '状态',
        );
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['name', 'area', 'status', 'address', 'province', 'city', 'average', 'total_area', 'buildings', 'developer', 'volume_rate', 'property', 'letting_rate', 'property_type', 'parking', 'property_fee', 'greening_rate'], 'required'],
            [['total_number'], 'integer'],
            [['lng', 'lat'], 'double'],
        ];
    }

    /**
     * 简介：判断数据库中是否有表$tablename
     * @param string $tablename ckeck_table_is
     * @return bool
     */
    public function ckeck_table_is($tablename)
    {
        $sql = "SHOW TABLES FROM 500m_new LIKE '" . $tablename . "'";
        $row = self::getDB()->createCommand($sql)->queryAll();
        if (!empty($row)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 简介：省市分页显示
     * @author  lichenjun@iyangpin.com。
     * @param array $data   x
     * @param null  $offset x
     * @param null  $area   x
     * @return array|\yii\db\ActiveRecord[]
     */
    public function show($data = array(), $offset = null, $area = null)
    {
        if ($area) {
            $list = $this->find()
                ->where(['like', 'name', $area])
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('create_time desc')
                ->asArray()
                ->all();
            return $list;
        } else {
            $list = $this->find()
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('create_time desc')
                ->asArray()
                ->all();
            return $list;
        }
    }

    /**
     * 简介：
     * @param null $area x
     * @return int|string
     */
    public function total($area = null)
    {
        if ($area) {
            $total = $this->find()->where(['like', 'name', $area])->count();
            return $total;
        } else {
            $total = $this->find()->count();
            return $total;
        }
    }

    /**
     * 简介：
     * @param string $name x
     * @return array|null|\yii\db\ActiveRecord
     */
    public function verifyDredgeCity($name)
    {
        $arr = $this->find()
            ->select('*')
            ->where(['name' => $name])
            ->asArray()
            ->one();
        return $arr;
    }

    /**
     * 简介：
     * @param array $data     x
     * @param null  $is_exist x
     * @return bool
     */
    public function add_info($data, $is_exist = null)
    {
        $this->name = $data['name'];
        $this->province = $data['province'];
        $this->city = $data['city'];
        $this->area = $data['area'];
        $this->address = $data['address'];
        $this->average = $data['average'];
        $this->total_area = $data['total_area'];
        $this->total_number = $data['total_number'];
        $this->buildings = $data['buildings'];
        $this->developer = $data['developer'];
        $this->volume_rate = $data['volume_rate'];
        $this->property = $data['property'];
        $this->letting_rate = $data['letting_rate'];
        $this->property_type = $data['property_type'];
        $this->parking = $data['parking'];
        $this->property_fee = $data['property_fee'];
        $this->greening_rate = $data['greening_rate'];
        $this->status = $data['status'];
        $this->create_time = $data['create_time'];
        if ($is_exist == 2) {
            $this->lng = $data['lng'];
            $this->lat = $data['lat'];
        } elseif ($is_exist == 1) {
            $this->lng = $data['lng'];
            $this->lat = '00.000000';
        } else {
            $this->lng = '000.000000';
            $this->lat = '00.000000';
        }
        $re = false;
        $result = $this->save();
        if ($result == true) {
            $re = $this->attributes['id'];
        }

        return $re;
    }

    /**
     * 简介：
     * @param int $id x
     * @return false|int
     * @throws \Exception
     */
    public function del($id)
    {
        $data = $this::findOne($id);
        $info = $data->delete();
        return $info;
    }

    /**
     * 简介：
     * @param int $id x
     * @return array|null|\yii\db\ActiveRecord
     */
    public function display_back($id)
    {
        $list = $this->find()->where("id = $id")->asArray()->one();
        return $list;
    }

    /**
     * 简介：
     * @param int $id x
     * @return array|null|\yii\db\ActiveRecord
     */
    public function look($id)
    {
        $list = $this->find()->where("id = $id")->asArray()->one();
        return $list;
    }
}

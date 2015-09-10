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
 * Class Province
 * @category  PHP
 * @package   Admin
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Province extends I500Base
{
    /**
     * 简介：
     * @author  zhoujun@iyangpin.com
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'city_name' => '开通城市名称',
            'status' =>'状态',
            'province'=>'省份',
            'city'=>'城市'
        );
    }
    /**
     * 简介：连接数据库
     * @author  zhoujun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return '{{%province}}';
    }
    /**
     *  简介：rules
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['city_name','status'],'required'],
        ];
    }

    /**
     * 简介：查询出所有省
     * @return array|\yii\db\ActiveRecord[]
     */
    public function province()
    {
        $province_all = $this->find()
            ->select('id,name')
            ->asArray()
            ->all();
        return $province_all;
    }

    /**
     * 获取全部省
     *
     * Author zhengyu@iyangpin.com
     *
     * @return array
     */
    public function getAllProvince()
    {
        $list = $this->find()
            ->select('id,name')
            ->asArray()
            ->all();
        return $list;
    }

    /**
     * 获取全部省
     * @param string $city_id city_id
     * Author zhoujun@iyangpin.com
     * @return array
     */
    public function province_one($city_id)
    {
        if ($city_id) {
            $list = $this->find()
                ->select('name')
                ->where("id = $city_id")
                ->asArray()
                ->one();
            return $list;
        } else {
            $city_id = 1;
            $list = $this->find()
                ->select('name')
                ->where("id = $city_id")
                ->asArray()
                ->one();
            return $list;
        }
    }
}

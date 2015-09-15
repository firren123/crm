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
use yii\db\ActiveRecord;
use linslin\yii2\curl;

/**
 * Class Branch
 * @category  PHP
 * @package   Branch
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Branch extends I500Base
{
    /**
     * 简介：
     * @return array
     */
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

    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_branch}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['name', 'status','sort', 'city_id_arr', 'province_id'], 'required'],
            [['sort'], 'integer'],
        ];
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param array $data   x
     * @param null  $offset x
     * @return array|\yii\db\ActiveRecord[]
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

    /**
     * 简介：
     * @return int|string
     */
    public function total()
    {
        $total = $this->find()->count();
        return $total;
    }

    /**
     * 简介：
     * @param array $data data
     * @return int|string
     */
    public function add($data)
    {
        $this->province_id = $data['province_id'];
        $this->city_id_arr = $data['city_id_str'];
        $this->name = $data['name'];
        $this->status = $data['status'];
        $this->sort = $data['sort'];
        $this->save();
    }

    /**
     * 简介：x
     * @param  int $id id
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
     * @param  int $id id
     * @return array|null|ActiveRecord
     */
    public function display_back($id)
    {
        $list = $this->find()->where("id = $id")->asArray()->one();
        return $list;
    }

    /**
     * 简介：
     * @return array|null|ActiveRecord
     */
    public function branch_info()
    {
        $list = $this->find()->asArray()->all();
        return $list;
    }

    /**
     * 简介：
     * @param int $pid pid
     * @return array|null|ActiveRecord
     */
    public function city_id($pid)
    {
        $list = $this->find()->where("$pid in (city_id_arr)")->asArray()->one();
        return $list;
    }

    /**
     * 简介：
     * @param int $pid pid
     * @return array|\yii\db\ActiveRecord[]
     */
    public function branch_city_all($pid)
    {
        $list = $this->find()->select('city_id_arr')->where("province_id = $pid")->asArray()->all();
        return $list;
    }
    /**
     * 简介：
     * @param int $bid pid
     * @return array|\yii\db\ActiveRecord[]
     */
    public function city_all($bid)
    {
        $list = $this->find()->select('city_id_arr')->where("id = $bid")->asArray()->one();
        return $list;
    }
}

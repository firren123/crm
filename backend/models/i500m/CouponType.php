<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  CouponType.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/23 下午8:44
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;

use backend\models\i500m\I500Base;
use Yii;
use yii\base\Model;
use common\helpers\CurlHelper;
use yii\db\ActiveRecord;

/**
 * Class CouponType
 * @category  PHP
 * @package   CouponType
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CouponType extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%coupons_type}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'type_name' => '类别名称',
            'send_type' => '发放类型',
            'par_value' =>'现金劵面额',
            'min_amount' =>'最小订单金额',
            'coupon_thumb' =>'缩略图',
            'consumer_points' =>'消费积分',
            'add_time' =>'添加时间',
            'start_time' => '开始时间',
            'expired_time' =>'过期时间',
            'used_status'=>'是否可用',
            'number' => '数量',
            'rules' => '规则',
            'limit_num' => '用户最多兑换张数',
            'source' => '券来源',
            'is_all' => '全场通用',
            'coupon_type' => '券类型',
            'status' => '状态',
            'shop_id' => '活动商家',
            'cate_id' => '活动分类',
            'remark' => '详情',
            'product_id' => '活动产品',
            'brand_id' => '活动品牌'

        );
    }

    /**
     * 简介：
     * @return array
     */
    public function rules(){
        return [
            //不可为空的字段
          [['type_name','send_type','par_value','min_amount','consumer_points','start_time','expired_time','used_status','number','limit_num'],'required'],
            //去掉首尾空格
        ];
    }

    /**
     * 简介：
     * @param int $type_id type_id
     * @return array|null|ActiveRecord
     */
    public function getTypeArr($type_id)
    {
        $arr = $this->find()
            ->select('type_id,type_name')
            ->where(['type_id' => $type_id])
            ->asArray()
            ->one();
        return $arr;
    }

    /**
     * 简介：优惠券分类列表
     * @param array $data   x
     * @param int   $offset x
     * @return array|\yii\db\ActiveRecord[]
     */
    public function show_coupon($data = array(),$offset)
    {
        $list = $this->find()
            ->offset($offset)
            ->limit($data['size'])
            ->orderBy('type_id desc')
            ->asArray()
            ->all();
        return $list;
    }
    /**
     * 优惠券分类详情
     * name getDetails
     * @param int $id x
     * @return mixed
     */
    public function getDetails($id)
    {
        $list = $this->find()->asArray()->where('type_id=' . $id)->one();
        return $list;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param int $id     id
     * @param int $status id
     * @return bool|string
     */
    public function getUpdateStatus($id, $status)
    {
        $result = '';
        $customer = $this->findOne($id);
        $customer->status = $status;
        $result = $customer->save();
        return $result;
    }

    /**
     * 添加
     * @param array $data data
     * @return mixed
     */
    public function add($data)
    {
        $model = new CurlHelper();
        $url   = 'admin/couponstype/add';
        $response  = $model->post($url, $data, 'server');
        return $response;
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
     * @param int $id x
     * @return array|null|ActiveRecord
     */
    public function getDetail($id)
    {
        $list= $this->find()->asArray()->where('type_id='.$id)->one();
        return $list;
    }
    /**
     * 添加
     * @param array $data data
     * @return bool
     */
    public function getInsert($data = array())
    {
        $re = 0;
        if ($data) {
            $model = clone $this;
            foreach ($data as $k=>$v) {
                $model->$k = $v;
            }
            $result = $model->save();
            if ($result==true) {
                $re = $model->attributes['type_id'];
            }
        }
        return $re;
    }
}

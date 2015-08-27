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


class CouponType extends I500Base
{
    public static function tableName()
    {
        return '{{%coupons_type}}';
    }
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
    public function rules(){
        return [
            //不可为空的字段
          [['type_name','send_type','par_value','min_amount','consumer_points','start_time','expired_time','used_status','number','limit_num'],'required'],
            //去掉首尾空格
//          [['shop_id', 'cate_id','product_id', 'brand_id'], 'trim'],
//            //
//            [['start_time', 'expired_time'], 'date'],
//            //设置默认值
//            ['add_time', 'default', 'value' => date('Y-m-d H:i:s',time())],
//            //integer 是否是整数
//            [['consumer_points', 'limit_num','par_value','number','min_amount'], 'number'],
        ];
    }


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
     * @purpose:优惠券分类列表
     * @name: getList
     * @param array $data
     * @return mixed
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
     * @purpose:优惠券分类详情
     * @name: getDetails
     * @param $id
     * @return mixed
     */
    public function getDetails($id){
        $list= $this->find()->asArray()->where('type_id='.$id)->one();
        return $list;
    }
    public function getUpdateStatus($id,$status){
        $result ='';
        $customer = $this->findOne($id);
        $customer->status = $status;
        $result=$customer->save();
        return $result;
    }

    /**
     * @purpose:添加
     * @name: add
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $model = new CurlHelper();
        $url   = 'admin/couponstype/add';
        $response  = $model->post($url, $data, 'server');
        return $response;
    }

    public function total()
    {
        $total = $this->find()->count();
        return $total;
    }
    public function getDetail($id){
        $list= $this->find()->asArray()->where('type_id='.$id)->one();
        return $list;
    }
    /**
     * 添加
     *
     * @param: array $data
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
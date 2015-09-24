<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  YpContract.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/24 下午1:37
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;


/**
 * Class YpContract
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class YpContract extends I500Base
{
    /**
     * 简介：连接表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%yp_contract}}";
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'contract_code' => '合同编号',
            'company_people' => '公司对接人',
            'customer_people' => '客户对接人',
            'contact' => '联系方式',
            'shop_name' => '商家名称',
            'shop_phone' => '商家电话',
            'brand_name' => '品牌',
            'brand_info' => '品牌简介',
            'contract_valid_start_time' => '合同有效期-开始时间',
            'contract_valid_end_time' => '合同有效期-结束时间',
            'online_time' => '上线时间',
            'store_event' => '店面接待量',
            'store_info' => '店铺信息',
            'per_consumption' => '人均消费',
            'get_time_limit' => '领取时间限制',
            'is_chain' => '是否连锁',
            'is_scan_code' => '是否需要二维码扫描',
            'is_appointment' => '是否预约',
            'traffic_routes' => '交通路线',
            'industry_id' => '行业',
            'release_form' => '发布形式',
            'qualification' => '相关资质',
            'product_img' => '产品图片',
            'product_logo_img' => '产品LOGO图片',
            'shop_logo_img' => '商家LOGO图片',
            'brand_logo' => '品牌LOGO',
            'code_validity' => '验证码有效期',
            'special_requirements' => '特殊要求',
            'product_info' => '产品说明',
            'remark' => '备注',
        );
    }

    /**
     * 简介：定义过滤规则
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['contract_code', 'company_people', 'customer_people', 'contact', 'shop_name', 'shop_phone', 'brand_name', 'brand_info',
                'contract_valid_start_time', 'contract_valid_end_time', 'online_time', 'store_event', 'store_info', 'per_consumption',
                'get_time_limit', 'is_chain', 'is_scan_code', 'is_appointment', 'traffic_routes', 'industry_id', 'release_form',
                'qualification', 'product_img', 'product_logo_img', 'shop_logo_img', 'brand_logo', 'code_validity',
                'special_requirements', 'product_info', 'remark'
            ], 'required'],
            //['phone', 'match', 'pattern' => '/^1[0-9]{10}$/', 'message' => '{attribute}格式输入不正确'],
            //['sn', 'match', 'pattern' => '/^[a-zA-Z0-9]{20}$/', 'message' => '{attribute}是由20英文＋数字组成'],
        ];
    }

}

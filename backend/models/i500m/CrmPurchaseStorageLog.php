<?php
/**
 * 采购入库单日志模型
 *
 * PHP Version 5
 * 采购入库单日志管理
 *
 * @category  Admin
 * @package   Storage
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/5/26 下午3:09
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Class CrmPurchaseStorageLog
 * @category  PHP
 * @package   CrmPurchaseStorageLog
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CrmPurchaseStorageLog extends I500Base
{
    /**
     * 表名
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_purchase_storage}}';
    }

    /**
     * 简介：
     * @author  liubaocheng@iyangpin.com。
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'sp_id' => '供应商',
            'storage_id' => '库房',
            'create_time'=>'入库日期',
            'remark' => '入库说明',
        );
    }
    /**
     * 简介：定义过滤规则
     * @author  liubaocheng@iyangpin.com。
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            //[['name','title','status'],'required'],
        ];
    }
}

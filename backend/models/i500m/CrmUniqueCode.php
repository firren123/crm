<?php
/**
 * 商品唯一码模型
 *
 * PHP Version 5
 * 商品唯一码管理
 *
 * @category  Admin
 * @package   Storage
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/5/26 下午3:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Class CrmUniqueCode
 * @category  PHP
 * @package   CrmUniqueCode
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CrmUniqueCode extends I500Base
{
    /**
     * 表名
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_unique_code}}';
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

    /**
     * 一次插入多条数据
     * @param array $data xx
     * @return bool
     */
    public function insertMore($data = [])
    {
        $re = false;
        if ($data) {
            $sql = "INSERT INTO ".$this->tableName()."(`unique_code`, `bar_code`, `status`, `purchase_storage_id`, `create_time`) VALUES";
            $value = [];
            foreach ($data as $k => $v ) {
                $value[] = "('".$v['unique_code']."','".$v['bar_code']."','".$v['status']."','".$v['purchase_storage_id']."','".$v['create_time']."')";
            }
            $sql .= implode(',', $value);
            $re = \Yii::$app->db_500m->createCommand($sql)->execute();
        }
        return $re !== false;
    }
}

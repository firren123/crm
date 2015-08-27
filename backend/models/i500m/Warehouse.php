<?php
/**
 *  库房管理
 * @category  crm
 * @package   Warehouse.php
 * @author    youyong <youyong@iyangpin.com>
 * @time      2015/5/26 11:53 
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
namespace backend\models\i500m;

class Warehouse extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_warehouse}}';
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'sn' =>'库房编号',
            'name' => '库房名称',
            'username' => '库房联系人',
            'phone' => '联系电话',
            'bc_id' =>'分公司ID',
            'province_id' => '省ID',
            'city_id'=>'市ID',
            'district_id'=>'县ID',
            'address'=>'详细地址',
            'remarks'=>'仓库备注',
            'status'=>'有效性',
        );
    }
    /**
     *
     * 简介：定义过滤规则
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['sn','name','username','phone','bc_id','province_id','city_id','district_id','address','remarks','status'],'required'],
            ['phone','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}格式输入不正确'],
            ['sn','match','pattern'=>'/^[a-zA-Z0-9]{20}$/','message'=>'{attribute}是由20英文＋数字组成'],
        ];
    }

    /**
     * 库房删除
     *
     * @param: $id
     * @return int
     * @throws \Exception
     */
    public function getDelete($id)
    {
        if (empty($id)) {
            return 0;
        } else {
            $list = $this->findOne($id);
            $result = $list->delete();
            if ($result==true) {
                return 200;
            } else {
                return 0;
            }
        }

    }

    /**
     * 库房名称是否存在
     *
     * @param: $name
     * @param: NULL $id
     * @return array|null|ActiveRecord
     */
    public function getDetailsByName($name, $id=NULL)
    {
        $list = array();
        if (!empty($name)) {
            if (empty($id)) {
                $list = $this->find()->where("name="."'".$name."'")->asArray()->one();
            } else {
                $list = $this->find()->where("name="."'".$name."' and id!=".$id)->asArray()->one();
            }
        }
        return $list;
    }
}
<?php
/**
 * 店铺类型（经营种类）-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/20 17:18
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * 店铺类型（经营种类）-model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class ManageType extends I500Base
{

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%manage_type}}';
    }




    /**
     * 规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['name'],'required','message' => '种类名称 不能为空.'],
            [['sort'],'required','message' => '排序 不能为空.'],
            [['status'],'required','message' => '状态 不能为空.'],
        ];
    }
    /**
     * 友情链接删除
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
     * 名称是否存在
     *
     * @param: $name
     * @param: null $id
     * @return array|null|ActiveRecord
     */
    public function getDetailsByName($name, $id = null)
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

    /**
     * 批量删除
     * @param string $ids
     * @return int
     */
    public function getBatchDelete($ids)
    {
        if (empty($ids)) {
            return 0;
        } else {
            $result = $this->deleteAll(" id in (".$ids.")");
            if ($result==true) {
                return 200;
            } else {
                return 0;
            }
        }
    }

    /**
     * 简介：
     * @return array|\yii\db\ActiveRecord[]
     */
    public function type_all()
    {
        $list = $this->find()->select('id,name')->where("status=2")->asArray()->all();
        return $list;
    }

    /**
     * 简介：
     * @param int $manage_type id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function type_name($manage_type)
    {
        $list = $this->find()->select('id,name')->where("id=$manage_type")->asArray()->one();
        return $list;
    }

}

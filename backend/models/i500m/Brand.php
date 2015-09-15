<?php
/**
 * 商品品牌页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   Brand.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/18 11:19
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\i500m;


use yii\db\ActiveRecord;

/**
 * Brand
 *
 * @category CRM
 * @package  Brand
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class Brand extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%brand}}';
    }

    /**
     * 添加
     *
     * @param array $data 数据
     *
     * @return bool
     */
    public function getInsert($data = array())
    {
        $re = false;
        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
            $result = $this->save();
            if ($result==true) {
                $re = $this->attributes['id'];
            }
        }
        return $re;
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
            [['name'],'required','message' => '品牌名称 不能为空.'],
            [['description'],'required','message' => '品牌描述 不能为空.'],
            [['sort'],'required','message' => '排序 不能为空.'],
            [['status'],'required','message' => '状态 不能为空.'],
        ];
    }

    /**
     * 品牌删除
     *
     * @param int $id 品牌id
     *
     * @return int
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
     * 品牌名称是否存在
     *
     * @param string $name 名称
     * @param null   $id   id
     *
     * @return array|null|ActiveRecord
     */
    public function getDetailsByName($name, $id=null)
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
     *
     * @param string $ids id集合
     *
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
}

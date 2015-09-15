<?php
/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   Category.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/17 10:41
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\i500m;


use yii\db\ActiveRecord;

/**
 * Category
 *
 * @category CRM
 * @package  Category
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class Category extends I500Base
{
    /**
     * 数据库
     * @return string
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['name'],'required','message' => '分类名称 不能为空.'],
            [['sort'],'required','message' => '排序 不能为空.'],

        ];
    }
    /**
     * 删除商品分类
     * @param int $id id
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
     * 批量删除
     * @param string $ids ids
     * @return int
     */
    public function getBatchDelete($ids)
    {
        if (empty($ids)) {
            return 0;
        } else {
            $result = $this->deleteAll(" id in (" . $ids . ")");
            if ($result == true) {
                return 200;
            } else {
                return 0;
            }
        }
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $name x
     * @param null   $id   x
     * @return array|null|ActiveRecord
     */
    public function getDetailsByName($name, $id = null)
    {
        $list = array();
        if (!empty($name)) {
            if (empty($id)) {
                $list = $this->find()->where("name="."'".$name."' and level=1 and type=0 and status!=0")->asArray()->one();
            } else {
                $list = $this->find()->where("name="."'".$name."' and id!=".$id." and level=1 and type=0 and status!=0")->asArray()->one();
            }
        }
        return $list;
    }
    /**
     * 添加
     * @param array $data x
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
     * 简介：
     * @param int $cate_first_id x
     * @return array|null|ActiveRecord
     */
    public function cate_info($cate_first_id)
    {
        $list = $this->find()->select('name')->where("id=$cate_first_id")->asArray()->one();
        return $list;
    }

}

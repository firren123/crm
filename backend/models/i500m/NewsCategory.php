<?php
/**
 * 新500m后台-资讯分类管理
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   NEWS_Category
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015-04-20 13:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace backend\models\i500m;

use Yii;

/**
 * NEWS_Category
 *
 * @category ADMIN
 * @package  NEWS_Category
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */

class NewsCategory extends I500Base
{
    /**
     * 定义表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_news_category}}';
    }

    /**
     * 获取列表
     * @param array  $where 条件
     * @param string $field 字段
     * @param string $order 排序
     * @return array
     */
    public function getCategoryList($where = [], $field = '*', $order = 'id desc')
    {
        $rs = [];
        if (!empty($where)) {
            $rs = $this->getList($where, $field, $order);
        }
        return $rs;
    }
    /**
     * 新增资讯
     *
     * @param array $data 新增数据
     *
     * @return bool
     */
    public function add($data=[])
    {
        $rs = false;
        if (!empty($data)) {
            $data['create_time'] = date('Y-m-d H:i:s', time());  //创建时间
            $rs = $this->insertInfo($data);
        }
        return $rs;
    }

    /**
     * 编辑资讯
     *
     * @param array $data 编辑数据
     *
     * @return bool
     */
    public function edit($data=[])
    {
        $rs = false;
        if (!empty($data)) {
            if (!empty($data['id'])) {
                $where = [];
                $where['id'] = $data['id'];
                unset($data['id']);
                $rs = $this->updateInfo($data, $where);
            }
        }
        return $rs;
    }

    /**
     * 获取详情
     *
     * @param int $id 资讯ID
     *
     * @return array
     */
    public function getDetails($id=0)
    {
        $rs = [];
        if (!empty($id)) {
            $where = [];
            $where['id'] = $id;
            $rs = $this->getInfo($where);
        }
        return $rs;
    }

    /**
     * 删除资讯
     *
     * @param int $id 资讯ID
     *
     * @return bool
     */
    public function del($id=0)
    {
        $rs = false;
        if (!empty($id)) {
            $where['id'] = $id;
            $data['is_deleted'] = '2';
            $rs = $this->updateInfo($data, $where);
        }
        return $rs;
    }

    /**
     * 获取分类的名称
     * @param int $category_id 分类ID
     * @return string
     */
    public function getCategoryName($category_id=0)
    {
        $category_name = '';
        if (!empty($category_id)) {
            $fields = 'name';
            $where = ['id'=>$category_id];
            $info = $this->getInfo($where, $fields);
            $category_name =  $info['name'];
        }
        return $category_name;
    }
}

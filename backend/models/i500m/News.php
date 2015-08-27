<?php
/**
 * 新500m后台-资讯管理
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   NEWS
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015-04-17 11:48
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace backend\models\i500m;

use Yii;

/**
 * NEWS
 *
 * @category ADMIN
 * @package  NEWS
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */

class News extends I500Base
{
    /**
     * 定义表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_news}}';
    }

    /**
     * 获取列表
     * @param array  $where     条件
     * @param string $field     字段
     * @param string $order     排序
     * @param int    $page      当前页数
     * @param int    $size      每页显示条数
     * @param array  $and_where 特殊条件
     * @return array
     */
    public function getNewsList($where=[],$field='*',$order='id desc',$page=1,$size=10,$and_where)
    {
        $rs = [];
        if (!empty($where)) {
            $rs = $this->getPageList($where, $field, $order, $page, $size, $and_where);
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
     * 检测分类下是否有文章
     * @param int $category_id 分类ID
     * @return array
     */
    public function checkCategory($category_id=0)
    {
        $rs = [];
        if (!empty($category_id)) {
            $where = 'is_deleted = 1 and category_id='.$category_id;
            $rs = $this->getList($where, 'id');
        }
        return $rs;
    }
}
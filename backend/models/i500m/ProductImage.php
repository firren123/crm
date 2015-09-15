<?php
/**
 * 标准库图集页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ProductImage
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/21 16:03
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * ProductImage
 *
 * @category CRM
 * @package  ProductImage
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ProductImage extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%product_image}}';
    }

    /**
     * 多行插入
     * @param array $data x
     * @return int
     */
    public function getBulkInsert($data = array())
    {
        $re = false;
        if ($data) {
            $model = clone $this;
            foreach ($data as $k => $v) {
                $model->$k = $v;
            }
            $re= $model->save();
        }
        return $re !== false;
    }

    /**
     * 删除
     * @param string $ids x
     * @return int
     */
    public function getDelete($ids)
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
     * 添加
     * @param array $data x
     * @return bool
     */
    public function getInsert($data = array())
    {
        $re = 0;
        if ($data) {
            $model = clone $this;
            foreach ($data as $k => $v) {
                $model->$k = $v;
            }
            $result = $model->save();
            if ($result==true) {
                $re = $model->attributes['id'];
            }
        }
        return $re;
    }
}

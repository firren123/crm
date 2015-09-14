<?php
/**
 * 品牌和分类管理
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   BrandCategory.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/20 14:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Brand
 *
 * @category CRM
 * @package  Brand
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class BrandCategory extends I500Base
{
    /**
     * 数据库
     * @return string
     */
    public static function tableName()
    {
        return '{{%brand_category}}';
    }

    /**
     * 多行插入
     * @param int   $brand_id c
     * @param array $ids      c
     * @return int
     */
    public function getBulkInsert($brand_id ,$ids)
    {
        $code = 0;
        if (!empty($brand_id) and !empty($ids)) {
            foreach ($ids as $k=>$v) {
                $model = clone $this;
                $model->brand_id = $brand_id;
                $model->category_id = $v;
                $model->add_time = date('Y-m-d H:i:s');
                if ($model->save()) {
                    echo $code = 200;
                }
            }
        }
        return $code;
    }

    /**
     * 简介：批量删除
     * @author  lichenjun@iyangpin.com。
     * @param int    $bid x
     * @param string $ids x
     * @return int
     */
    public function getBatchDelete($bid,$ids)
    {
        if (empty($ids) and empty($bid)) {
            return 0;
        } else {
            $result = $this->deleteAll(" brand_id=".$bid." and category_id in (".$ids.")");
            if ($result==true) {
                return 200;
            } else {
                return 0;
            }
        }
    }
}

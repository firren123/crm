<?php
/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   CategoryAttribute.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/23 14:25
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Class CategoryAttribute
 * @category  PHP
 * @package   CategoryAttribute
 * @author    liuwei <liuwei@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CategoryAttribute  extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%category_attribute}}';
    }
    /**
     * 多个添加
     * @param array $data data
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
                $re = $this->attributes['id'];
            }
        }
        return $re;
    }
}

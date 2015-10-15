<?php
/**
 * 供应商样品-model
 *
 * PHP Version 5
 *
 * @category  SUPPLIER
 * @package   MODEL
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @time      15/9/18 11:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      sunsongsong@iyangpin.com
 */


namespace backend\models\i500m;

/**
 * 供应商样品-model
 *
 * @category SUPPLIER
 * @package  MODEL
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     sunsong@iyangpin.com
 */
class SupplierSampleGive extends I500Base
{

    /**
     * 设置表名
     *
     * Author sunsongsong@iyangpin.com
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%supplier_sample_give}}';
    }


}


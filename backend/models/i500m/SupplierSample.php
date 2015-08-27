<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Wap   
 * @package   Member     (这里写模块名)
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/7/24 下午2:03 
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\models\i500m;


class SupplierSample extends I500Base{

    public static function tableName()
    {
        return "{{%supplier_sample}}";
    }
}
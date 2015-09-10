<?php
/**
 * I500m库
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Wap
 * @package   Member
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/4/18 上午11:44
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */
namespace backend\models\i500m;

use backend\models\Base;

/**
 * Class I500Base
 * @category  PHP
 * @package   I500Base
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class I500Base extends Base
{
    /**
     * 简介：
     * @return mixed
     */
    public static function getDB()
    {
        return \Yii::$app->db_500m;
    }
}

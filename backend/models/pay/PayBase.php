<?php
/**
 * Shop数据库model基类
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   SHOP
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      2015-04-20
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\pay;

use backend\models\Base;

/**
 * Shop数据库model基类
 *
 * @category ADMIN
 * @package  SHOP
 * @author   weitonghe <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class PayBase extends Base
{
    /**
     * 设置默认数据库连接
     *
     * Author zhengyu@iyangpin.com
     *
     * @return \yii\db\Connection
     */
    public static function getDB()
    {
        return \Yii::$app->db_pay;
    }
}

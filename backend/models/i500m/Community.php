<?php
/**
 * 小区-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/20 17:02
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * 小区-model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class Community extends I500Base
{
    /**
     * 小区表后缀
     *
     * @var string 小区表后缀，形如 _beijing
     */
    private static $_table_suffix = '';

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%community' . Community::$_table_suffix . '}}';
    }

    /**
     * 设置后缀
     *
     * Author zhengyu@iyangpin.com
     *
     * @param string $str 后缀
     *
     * @return void
     */
    public static function setSuffix($str)
    {
        Community::$_table_suffix = $str;
        return;
    }

}

<?php
/**
 * 业务员-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/27 13:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * 业务员-model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   sunsong <sunsongsong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class BusinessReport extends I500Base
{

    /**
     * 业务员提交信息表
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_business_report}}';
    }

}
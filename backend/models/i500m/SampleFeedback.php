<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Wap
 * @package   Member
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/7/27 上午10:11
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * Class SampleFeedback
 * @category  PHP
 * @package   SampleFeedback
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SampleFeedback extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return "{{%sample_feedback}}";
    }
}

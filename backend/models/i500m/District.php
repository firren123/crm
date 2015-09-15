<?php
/**
 * 区县-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/20 17:18
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * 区县-model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class District extends I500Base
{

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%district}}';
    }

    /**
     * @purpose:查询出所有县或者区
     * @name: city
     * @return string
     */
    public function district($cid)
    {
        $list = $this->find()->select('id,name')->where("city_id = $cid")->asArray()->all();
        return $list;
    }

}

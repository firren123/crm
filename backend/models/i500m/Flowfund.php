<?php
/**
 *
 * @category  CRM
 * @package   资金流水 Flowfund.php
 * @author    youyong <youyong@iyangpin.com>
 * @time      2015/4/24 11:44
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
namespace backend\models\i500m;

use linslin\yii2\curl;

/**
 * Class Flowfund
 * @category  PHP
 * @package   Flowfund
 * @author    youyong <youyong@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Flowfund extends I500Base
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * 简介：
     * @return string
     */
    public function attributeLabels()
    {
        return array(
            'ship_status' => '发货状态',
        );
    }

    /**
     * 简介：
     * @return string
     */
    public function rules()
    {
        return [
            //不可为空的字段

        ];
    }
}

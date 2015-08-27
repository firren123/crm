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

use backend\models\i500m\OrderLog;
use common\helpers\CurlHelper;
use linslin\yii2\curl;
class Flowfund extends I500Base{

    public static function tableName(){
        return '{{%order}}';
    }
    public function attributeLabels()
    {
        return array(
            'ship_status' => '发货状态',
        );
    }
    public function rules(){
        return [
            //不可为空的字段

        ];
    }
}
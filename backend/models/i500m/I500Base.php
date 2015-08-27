<?php
/**
 * I500m库
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Wap   
 * @package   Member     (这里写模块名)
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/4/18 上午11:44 
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */
namespace backend\models\i500m;
use backend\models\Base;
use yii\db\ActiveRecord;

class I500Base extends Base{

    public static function getDB(){
        return \Yii::$app->db_500m;
    }



}
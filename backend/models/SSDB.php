<?php
/**
 * SSDB类
 *
 * PHP Version 5
 *
 * @category  SUPPLIER
 * @package   FRONTEND
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/6/10 15:42
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


namespace backend\models;

use Yii;
use yii\base\Model;
use SSDB\Client;
use SSDB\Exception;

/**
 * SSDB类
 *
 * @category SUPPLIER
 * @package  FRONTEND
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class SSDB extends Model
{
    /**
     * 实例化
     *
     * Author zhengyu@iyangpin.com
     *
     * @return array|object|Client
     */
    public function instance()
    {
        try {
            $ssdb = new Client(\Yii::$app->params['ssdb']['host'], \Yii::$app->params['ssdb']['port']);
            $ssdb->auth(\Yii::$app->params['ssdb']['auth']);
        } catch (Exception $e) {
            $ssdb = array('result' => 0, 'msg' => $e->getMessage());
            $ssdb = (object)$ssdb;
        }
        //var_dump($ssdb);exit;
        return $ssdb;
    }

}

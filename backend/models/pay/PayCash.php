<?php
/**
 * 付款记录信息
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   PayCash.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @time      2015/8/3  下午 2:13
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      weitonghe@iyangpin.com
 */
namespace backend\models\pay;
use backend\models\pay\PayBase;
/**
 * PayCash
 *
 * @category CRMs
 * @package  PayCash
 * @author   weitonghe <weitonghe@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     weitonghe@iyangpin.com
 */
class PayCash extends PayBase
{
    /**
     * 数据表
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%pay_cash}}';
    }

    /**
     *  检索数据
     *
     * @param array  $Cond        条件
     * @param array  $andCond     and条件
     * @param string  $filed      字段
     *
     * @return array
     */
    public function selinfo($Cond = array(), $andCond = array(), $filed = '*')
    {
        $info = array();
        if ($Cond) {
            $info = $this->find()
                ->select($filed)
                ->where($Cond)
                ->andWhere($andCond)
                ->asArray()
                ->one();
            if(empty($info)){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }
    public function insertinfo($list = array())
    {
        $model = new PayCash();
        foreach($list as $k=>$v){
            $model->$k = $v;
        }
        $result = $model->save();
        return $result;
    }
}
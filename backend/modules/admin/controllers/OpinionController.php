<?php
/**
 * 简介
 * @category  admin
 * @package   意见反馈列表
 * @author     <youyong@iyangpin.com>
 * @time      2015/4/1 10:30     
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\models\i500m\Opinion;
use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use yii\data\Pagination;


/**
 * Category
 *
 * @category CRM
 * @package  OpinionController
 * @author   youyong <youyong@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     youyong@iyangpin.com
 */
class OpinionController extends BaseController
{
    /**
     * 意见反馈列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $size = RequestHelper::get('size', 10);
        $page = RequestHelper::get('page', 1);
        $where = RequestHelper::get('where');
        $offset = ($page - 1) * $size;
        $model = new Opinion();
        $list = $model->getListOpinion($size,$offset,NULL,$where);
        $number = $model->getListOpinion(NULL,NUll,$is_number=1,$where);
        //var_dump($number);exit;
        $item = array();
        if($list){
            foreach($list as $k=>$v){
                $item[$k] = $v;
                $item[$k]['id'] = preg_match("/^1(3|5|8|4)\d{9}$/",$v['id']) ? substr_replace($v['id'],'****',3,4) : $v['id'] ;
                $item[$k]['user_id'] = preg_match("/^1(3|5|8|4)\d{9}$/",$v['user_id']) ? substr_replace($v['user_id'],'****',3,4) : $v['user_id'] ;
                $item[$k]['username'] = preg_match("/^1(3|5|8|4)\d{9}$/",$v['username']) ? substr_replace($v['username'],'****',3,4) : $v['username'] ;
                $item[$k]['content'] = preg_match("/^1(3|5|8|4)\d{9}$/",$v['content']) ? substr_replace($v['content'],'****',3,4) : $v['content'] ;
//                $item[$k]['status'] = preg_match("/^1(3|5|8|4)\d{9}$/",$v['status']) ? substr_replace($v['status'],'****',3,4) : $v['status'] ;
                //  var_dump( $item[$k]['status']);exit;
            }
        }
       // var_dump($item);exit;
        $pages = new Pagination(['totalCount' =>$number, 'pageSize' => $this->size]);
        return $this->render('index',['item'=>$item,'number'=>$number,'pages'=>$pages]);
    }
}
<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  supplier.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/12 上午11:15
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\modules\supplier\controllers;
use backend\controllers\BaseController;
use backend\models\i500m\Supplier;
use backend\models\i500m\Menu;
use backend\models\i500m\Category;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\web\Controller;

class SupplierController extends BaseController{
    public function actionIndex()
    {
        $cate_model = new Supplier();
//        $all=$cate_model->allGoods();
//        var_dump($all);
//        exit;
        $page=RequestHelper::get('page',1,'intval');//获得当前的页数
        $pageSize=5;//设置每页显示的记录条数

        // 查询条件
        $id = RequestHelper::get('supplierid');
        if(!empty($id)){
            //$cond="supplier_code LIKE'%".$id."%'";
            $cond = ['like','supplier_code',$id];
            $total = $cate_model->totalNum($cond);//获得总记录条数
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        }else{
            $cond = ['like','supplier_code',''];
            $total = $cate_model->totalNum($cond);//获得总记录条数
            $pages = new Pagination(['totalCount' => $total, 'pageSize' => $pageSize]);
        }
        $fields='*';
        $order='';
        $allUsers=$cate_model->getPartGoods($cond , $fields, $order, $page, $pageSize);
        return $this->render('index', array('list' => $allUsers,'control' => 'sel','pages' => $pages,'model' => $cate_model));
        //var_dump($alluser);
        exit;
    }
    //获得AJAX数据
    public function actionGetajax(){
        $cate_model = new Supplier();
        $account = RequestHelper::get('account');
        if(!empty($account)){
            $where="account='".$account."'";
        }
        $result = $cate_model->checkaccount($where);
        if($result){
            return 0;
        }else{
            return 1;
        }
    }
    //显示一个供应商的信息
    public function actionShowonesupplier(){
        $cate_model = new Supplier();
        $id = RequestHelper::get('id');
        $cond="id ='".$id."'";
        $fields='*';
        $order='';
        $allUsers=$cate_model->getPartGoods($cond , $fields, $order);
        return $this->render('showonesupplier', ['list' => $allUsers]);
    }
    /*
     * 显示add页面
     */
    public function actionShowadd(){
        $m = new Supplier();
        return $this->render('add',['model'=>$m]);
    }
    /*
     * 执行ADD操作
     */
    public function actionAddgoods(){
        $cate_model = new Supplier();
        //RequestHelper::post('id',0,'intval');
        //把POST的各项数据封装在$arrmsg数组里
        date_default_timezone_set('prc');
        //var_dump(date('Y-m-d H:i:s',time()));
        //var_dump($_SERVER["REMOTE_ADDR"]);
        $arrmsg=array(
            'company_name'=>RequestHelper::post('company_name'),
            'account'=>RequestHelper::post('account'),
            'password'=>RequestHelper::post('password'),
            'contact'=>RequestHelper::post('contact'),
            'sex'=>RequestHelper::post('sex'),
            'email'=>RequestHelper::post('email'),
            'mobile'=>RequestHelper::post('mobile'),
            'phone'=>RequestHelper::post('phone'),
            'qq'=>RequestHelper::post('qq'),
            'last_login_time'=> date('Y-m-d H:i:s',time()),
            'last_login_ip'=>$_SERVER["REMOTE_ADDR"]
    );
        //执行添加功能
//        var_dump($arrmsg);
//        exit;
        $result = $cate_model->addGoods($arrmsg);//调用 Supplier模型的addGoods方法
        if($result){
            $result = $cate_model->update_supplier_code($result);
            return $this->error('添加操作成功！','index');
            //return $this->success('添加操作成功！','index');
        }else{
            return $this->error('添加操作失败！','index');
        }
    }
    /*
     * 执行删除操作
     */
    public function actionDelgoods(){
        $cate_model = new Supplier();
        $result=$cate_model->delGoods(RequestHelper::get('id'));
        if($result){
            return $this->success('删除操作成功！','index');
        }else{
            return $this->error('删除操作失败！','index');}
    }
    /**
     * 执行全部删除操作
     * @return string
     */
    public function actionAlldel(){
        $cate_model = new Supplier();
        $arrdelid=RequestHelper::get('arrid');
        $arr=explode(',',RequestHelper::get('arrid'));
        $arr=array_filter($arr);
        $result=$cate_model->delAll($arr);
        if($result){
            return $this->success('全部删除操作成功！','index');
        }else{
            return $this->error('全部删除操作失败！','index');}
    }
    /*
     *显示修改页面，需要对照旧的信息进行修改
     */
    public  function actionShowedit(){
        $cate_model = new Supplier();
        $where='id='.RequestHelper::get('id');

        $PartGoods = $cate_model->getPartGoods($where);//调用 Supplier模型的getPartGoods方法
        return $this->render('edit',['editid'=>RequestHelper::get('id'),'list' => $PartGoods,'model' => $cate_model]);
    }
    /*
     *执行修改操作
     */
    public  function actionEditgoods(){
        //1。接收VIEW值
        $editid=RequestHelper::post('editid');
        $editarrmsg=array(
//            'supplier_code'=>RequestHelper::post('editid'),
            'company_name'=>RequestHelper::post('company_name'),
            'account'=>RequestHelper::post('account'),
            'password'=>RequestHelper::post('password'),
            'contact'=>RequestHelper::post('contact'),
            'sex'=>RequestHelper::post('sex'),
            'email'=>RequestHelper::post('email'),
            'mobile'=>RequestHelper::post('mobile'),
            'phone'=>RequestHelper::post('phone'),
            'qq'=>RequestHelper::post('qq'));
        //2.更新
        $cate_model = new Supplier();
        $result=$cate_model->editGoods($editid,$editarrmsg);
        if($result){
            return $this->success('修改操作成功！','index');
        }else{
            return $this->error('修改操作失败！','index');}
    }
}
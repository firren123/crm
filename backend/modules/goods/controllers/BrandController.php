<?php
/**
 * 商品品牌页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   BrandController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/18 11:05
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\modules\goods\controllers;

use backend\models\i500m\Brand;
use backend\models\i500m\BrandCategory;
use backend\models\i500m\Category;
use backend\models\i500m\Log;
use backend\models\i500m\Product;
use common\helpers\FastDFSHelper;
use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * Brand
 *
 * @category CRM
 * @package  Brand
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class BrandController extends BaseController
{
    /**
     * 品牌列表
     *
     * @return array
     */
    public function actionIndex()
    {
        $product_model = new Product();
        //获取所有分类
        $cate_model = new Category();
        $cate_cond['level'] = 1;
        $cate_cond['type'] = 0;
        $cate_cond['status'] = 2;
        $brand_model = new BrandCategory();
        $search = RequestHelper::get('Search');
        $where = [];

        if (!empty($search['name'])) {
            $where = ['like', 'name' , $search['name']];
        }
        $ids_data = [];
        if (!empty($search['cate_id'])) {
            $brand_cond = 'category_id='.$search['cate_id'];
            $brand_list = $brand_model->getList($brand_cond, 'brand_id');
            if (!empty($brand_list)) {
                $ids_data = array();
                foreach ($brand_list as $k=>$v) {
                    $ids_data[] = $v['brand_id'];
                }
            }
            $cond['id']= $ids_data;
        }
        if (!empty($search['status'])) {
            $cond['status'] = $search['status'];
        } else {
            $cond['status'] = [1,2];
        }
        $cate_list = $cate_model->getList($cate_cond, 'id,name', 'id desc');
        $order = 'id desc';
        $page = RequestHelper::get('page', 1);
        $pageSize = $this->size;
        $model =new Brand();
        $list = $model->getPageList($cond, '*', $order, $page, $pageSize, $where);
        $brand_data = [];
        if ($list) {
            foreach ($list as $k=>$v) {
                $brand_cond['brand_id'] = $v['id'];
                $number = $product_model->getCount($brand_cond);
                $brand_data[]=$v;
                $brand_data[$k]['brand_status'] = $number;
            }
        }
        $data['is_number'] = 1;
        $total = $model->getCount($cond, $where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index', ['list'=>$brand_data,'pages'=>$pages,'search'=>$search,'cate_list'=>$cate_list]);
    }

    /**
     * 品牌添加
     *
     * @return string
     */
    public function actionAdd()
    {
        $model = new Brand();
        $cate_model = new Category();
        $brand_cate = new BrandCategory();
        $model->status = 2;
        //获取所有分类
        $cate_cond = 'level=1 and type=0 and status=2';
        $cate_list = $cate_model->getList($cate_cond, 'id,name', 'id desc');
        $brand = RequestHelper::post('Brand');

        if (!empty($brand)) {
            $cate = RequestHelper::post('cate');
            $model->attributes = $brand;
            $list = $model->getInfo(['name'=>$brand['name']]);
            $file = UploadedFile::getInstance($model, 'img');
            $result = 0;
            if (!empty($list)) {
                $model->addError('name', '品牌名称 不能重复');
            } elseif (empty($cate)) {
                \Yii::$app->getSession()->setFlash('error', '所属分类 不能为空');
            } else {
                if ($file) {
                    $file_size=$file->size;//大小
                    $file_type=$file->type;//类型
                    $size = 1024*1024*1;
                    if ($file_type!='image/jpeg' and $file->type!='image/png') {
                        $model->addError('img', '品牌图片 仅支持JPG/PNG格式.');
                    } elseif ($file_size > $size) {
                        $model->addError('img', '品牌图片 不能大于1m.');
                    } else {
                        //上传图片
                        $fdfs = new FastDFSHelper();
                        $ret = $fdfs->fdfs_upload_name_size($file->tempName, $file->name);
                        $brand['img'] = '/'.$ret['group_name'] . '/' . $ret['filename'];
                        $result = $model->getInsert($brand);
                    }
                } else {
                    $brand['add_time'] = date('Y-m-d H:i:s');
                    $result = $model->getInsert($brand);
                }

                if ($result != false) {
                    //日志
                    $content = "管理员：".\Yii::$app->user->identity->username.",增加了品牌id为:".$result.",品牌名称为:".$brand['name'];
                    $log_model = new Log();
                    $log_model->recordLog($content);
                    $results = $brand_cate->getBulkInsert($result, $cate);
                    if ($results ==200) {
                        $this->redirect('/goods/brand');
                    }
                }
            }
        }
        return $this->render('add', ['model'=>$model,'cate_list'=>$cate_list]);
    }

    /**
     * 品牌编辑
     *
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Brand();
        $cate_model = new Category();
        $brand_model = new BrandCategory();
        $cond = 'id='.$id;
        $item = $model->getInfo($cond, true, '*');
        $model->attributes = $item;
        $brand = RequestHelper::post('Brand');
        //获取所有分类
        $cate_cond = 'level=1 and type=0 and status=2';
        $cate_list = $cate_model->getList($cate_cond, 'id,name', 'id desc');
        //获取该品牌下的分类
        $brand_cond = 'brand_id='.$id;
        $brand_list = $brand_model->getList($brand_cond, 'category_id');
        $data = array();
        $cate_array = array();
        if (!empty($brand_list)) {
            foreach ($brand_list as $k => $v) {
                $cate_array[] = $v['category_id'];//用于比较是否和所选的值一样
                $list_brand[$v['category_id']] = $v['category_id'];//把键值变成和category_id一样的值

            }
            foreach ($cate_list as $k=>$v) {
                $data[] = $v;
                $data[$k]['checked'] =  empty($list_brand[$v['id']]) ? '' : 1;//是否已经选中
            }
        } else {
            $data = $cate_list;
        }
        if (!empty($brand)) {
            $model->attributes = $brand;
            $cate = RequestHelper::post('cate');
            $list = $model->getDetailsByName($brand['name'], $id);
            $file = UploadedFile::getInstance($model, 'img');
            $result = 0;
            if (!empty($list)) {
                $model->addError('name', '品牌名称 不能重复');
            } elseif (empty($cate)) {
                \Yii::$app->getSession()->setFlash('error', '所属分类 不能为空');
            } else {

                if ($file) {
                    $file_size=$file->size;//大小
                    $file_type=$file->type;//类型
                    $size = 1024*1024*1;
                    if ($file_type!='image/jpeg' and $file_type!='image/png') {
                        $model->addError('img', '品牌图片 仅支持JPG/PNG格式.');
                    } elseif ($file_size > $size) {
                        $model->addError('img', '品牌图片 不能大于1m.');
                    } else {
                        //上传图片
                        $fdfs = new FastDFSHelper();
                        $ret = $fdfs->fdfs_upload_name_size($file->tempName, $file->name);
                        $brand['img'] = '/'.$ret['group_name'] . '/' . $ret['filename'];
                        $result = $model->updateInfo($brand, $cond);
                    }
                } else {
                    $brand['img'] = $item['img'];
                    $result = $model->updateInfo($brand, $cond);
                }
                if ($result == true) {
                    $array = array_diff($brand, $item);
                    if (!empty($array)) {
                        $content = "管理员：" . \Yii::$app->user->identity->username . ",修改了品牌id为:" . $id . " 品牌的";
                        if (!empty($array['name'])) {
                            $content .= " 名称,内容为:".$array['name'];
                        }
                        if (!empty($array['img'])) {
                            $content .= " 图片,内容为:".$array['img'];
                        }
                        if (!empty($array['sort'])) {
                            $content .= " 排序,内容为:".$array['sort'];
                        }
                        if (!empty($array['status'])) {
                            $status_name = $array['status']==2 ? '有效' : '无效';
                            $content .= " 状态,内容为:".$status_name;
                        }
                        if (!empty($array['description'])) {
                            $content .= " 描述,内容为:".$array['description'];
                        }
                        $log_model = new Log();
                        $log_model->recordLog($content);
                    }
                    //如果修改资料成功 进行分类和品牌的关联
                    $results = 0;
                    //没有关联的情况
                    if (empty($brand_list)) {
                        $results = $brand_model->getBulkInsert($id, $cate);
                    } else {
                        $array_del = array_diff($cate_array, $cate);//减少的项
                        $array_add = array_diff($cate, $cate_array);//增加的项
                        //有改动的情况
                        if (!empty($array_add) or !empty($array_del)) {
                            //有增加的情况
                            if (!empty($array_add)) {
                                $add_result = $brand_model->getBulkInsert($id, $array_add);
                                if ($add_result==200) {
                                    $results = 200;
                                    //日志
                                    $content = "管理员：" . \Yii::$app->user->identity->username . ",增加了品牌id为:" . $id . " 品牌的所属分类，分类id集合是:".implode(',', $array_add);
                                    $log_model = new Log();
                                    $log_model->recordLog($content);
                                }
                            }
                            //有删除的情况
                            if (!empty($array_del)) {
                                $ids = implode(',', $array_del);
                                $del_result = $brand_model->getBatchDelete($id, $ids);
                                if ($del_result==200) {
                                    $results = 200;
                                    //日志
                                    $content = "管理员：" . \Yii::$app->user->identity->username . ",删除了品牌id为:" . $id . " 品牌的所属分类，分类id集合是:".implode(',', $array_del);
                                    $log_model = new Log();
                                    $log_model->recordLog($content);
                                }
                            }
                            //既有增加也有删除的情况
                            if (!empty($array_del) and !empty($array_add)) {
                                $ids = implode(',', $array_del);
                                $del_result = $brand_model->getBatchDelete($id, $ids);
                                $add_result = $brand_model->getBulkInsert($id, $array_add);
                                if ($del_result==200 and $add_result==200) {
                                    $results = 200;
                                }
                            }
                        } else {
                            //没有改动情况
                            $results = 200;
                        }
                    }
                    if ($results==200) {
                        $this->redirect('/goods/brand');
                    }

                }
            }
        }
        return $this->render('edit', ['model'=>$model,'cate_list'=>$data]);
    }

    /**
     * 所属分类
     *
     * @return string
     */
    public function actionView()
    {
        $id = RequestHelper::get('id');
        $cond = 'brand_id='.$id;
        $model = new Category();
        $brand_model = new BrandCategory();
        $list = $brand_model->getList($cond, 'category_id');
        $ids_array = array();
        $ids = '';
        $data = [];
        if (!empty($list)) {
            foreach ($list as $v) {
                $ids_array[] = $v['category_id'];
            }
            $ids = implode(',', $ids_array);
        }
        if ($ids!='') {
            $cate_cond = 'id in (' . $ids . ')';
            $data = $model->getList($cate_cond, 'name');
        }
        return $this->render('view', ['data'=>$data]);
    }

    /**
     * 品牌删除
     *
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $model = new Brand();
            $item = $model->getInfo(['id'=>$id]);
            $result = $model->getDelete($id);
            if ($result==200) {
                //日志
                $content = "管理员：".\Yii::$app->user->identity->username.",删除了品牌id为:".$id.",品牌名称为:".$item['name']." 的品牌";
                $log_model = new Log();
                $log_model->recordLog($content);
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }

    /**
     * 批量删除
     *
     * @return int
     */
    public function actionAjaxDelete()
    {
        $code = 0;
        $ids = RequestHelper::post('ids');
        $model = new Brand();
        $result = $model->getBatchDelete($ids);
        if ($result==200) {
            $code =1;
        }
        return $code;
    }
    /**
     * 获取品牌列表
     *
     * @return int
     */
    public function actionList()
    {
        $data = array();
        $cate_id = RequestHelper::get('cate_id');
        if ($cate_id!=0) {
            $cate_model = new BrandCategory();
            $model = new Brand();
            //分类下的品牌id列表
            $cate_cond = "category_id=".$cate_id;
            $cate_list = $cate_model->getList($cate_cond, 'brand_id', 'id desc');
            if (!empty($cate_list)) {
                $cate_data = array();
                foreach ($cate_list as $v) {
                    $cate_data[] = $v['brand_id'];
                }
                $ids = implode(',', $cate_data);
                $cond = "id in (" . $ids . ") and status=2";
                $list = $model->getList($cond, 'name', 'id desc');
                $data = ArrayHelper::getColumn($list, 'name');
            }
        }
        echo json_encode($data);
    }
}

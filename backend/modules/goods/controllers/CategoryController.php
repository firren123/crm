<?php
/**
 * 商品分类页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   Category
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/17 10:35
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\modules\goods\controllers;
use backend\controllers\BaseController;
use backend\models\i500m\Attribute;
use backend\models\i500m\Category;
use backend\models\i500m\CategoryAttribute;
use backend\models\i500m\Log;
use backend\models\i500m\Product;
use common\helpers\CommonHelper;
use common\helpers\FastDFSHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * Category
 *
 * @category CEM
 * @package  CategoryController
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class CategoryController extends BaseController
{
    /**
     * 商品分类列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $product_model = new Product();
        $cond['level'] = 1;
        $cond['type'] = 0;
        $where = ['>', 'status', '0'];
        $name= RequestHelper::get('name');
        if (!empty($name)) {
            $cond = ['like', 'name', $name];
        }
        $order = 'id desc';
        $page = RequestHelper::get('page', 1);
        $pageSize = $this->size;
        $model = new Category();
        $list = $model->getPageList($cond , '*', $order, $page, $pageSize, $where);
        $cate_list = [];
        if ($list) {
            foreach ($list as $k=>$v) {
                $cate_cond['cate_first_id'] = $v['id'];
                $number = $product_model->getCount($cate_cond);
                $cate_list[] = $v;
                $cate_list[$k]['cate_status'] = $number;
            }
        }
        $data['is_number'] = 1;
        $total = $model->getCount($cond, $where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index', ['data'=>$cate_list,'pages'=>$pages,'name'=>$name]);
    }

    /**
     * 分类添加/修改
     *
     * @return string
     */
    public function actionAdd()
    {
        $id = RequestHelper::get('id');
        $model = new Category();
        $model_attribute = new CategoryAttribute();
        $cond ='';
        $cate_array = array();
        //属性列表
        $attribute_model = new Attribute();
        $attribute_cond = 'id!=0';
        $attribute_list = $attribute_model->getList($attribute_cond, 'id,admin_name', 'weight asc');
        if (empty($id)) {
            $model->status = 2;
            $model->sort = 999;
        } else {
            $cond = 'id='.$id;
            $list = $model->getInfo($cond, true, '*');
            $model->attributes = $list;
            $model->status = $list['status'];
            //获取该分类下的属性
            $category_cond = "category_id=".$id;
            $category_list = $model_attribute->getList($category_cond, 'attribute_id');
            $category_data = array();

            if (!empty($category_list)) {
                foreach ($category_list as $value) {
                    $cate_array[] =$value['attribute_id'];
                    $category_data[$value['attribute_id']] = $value['attribute_id'];
                }
            }
            foreach ($attribute_list as $k=>$v) {
                $attribute_list[$k]['checked'] = empty($category_data[$v['id']]) ? 0 : 1;
            }
        }
        $category = RequestHelper::post('Category');
        if (!empty($category)) {
            $category['name'] = CommonHelper::semiangle($category['name']);
            $category['sort'] = CommonHelper::semiangle($category['sort']);
            $model->attributes = $category;
            $category['level'] = 1;
            $category['parent_id'] = 0;
            $category['type']= 0;
            $attribute = RequestHelper::post('attribute');
            if (empty($id)) {
                //添加
                //分类图片
                $file = UploadedFile::getInstance($model, 'img');
                if (empty($file)) {
                    $model->addError('img', '分类图片 不能为空.');
                } elseif ($file->type!='image/jpeg' and $file->type!='image/png') {
                    $model->addError('img', '分类图片 仅支持JPG/PNG格式.');
                } elseif ($file->size > 1024*1024*1) {
                    $model->addError('img', '分类图片 不能大于1m.');
                } else {
                    //上传图片
                    $fast = new FastDFSHelper();
                    $ret = $fast->fdfs_upload_name_size($file->tempName, $file->name);
                    $category['img'] = '/'.$ret['group_name'] . '/' . $ret['filename'];
                    $list = $model->getDetailsByName($category['name']);
                    if (empty($list)) {
                        $result = $model->getInsert($category);
                        if ($result != 0) {
                            //日志
                            $content = "管理员：".\Yii::$app->user->identity->username.",添加了分类id为:".$result.",分类名称为:".$category['name']." 的分类";
                            $log_model = new Log();
                            $log_model->recordLog($content);
                            if (!empty($attribute)) {
                                foreach ($attribute as $v) {
                                    $attribute_data['category_id'] = $result;
                                    $attribute_data['attribute_id'] = $v;
                                    $model_attribute->getInsert($attribute_data);
                                }
                            }
                            $this->redirect('/goods/category');
                        }
                    } else {
                        $model->addError('name', '分类名称 不能重复');
                    }
                }
            } else {
                //修改
                //分类图片
                $file = UploadedFile::getInstance($model, 'img');
                if (empty($file)) {
                    $category['img'] = $list['img'];
                } else {
                    if ($file->type!='image/jpeg' and $file->type!='image/png') {
                        $model->addError('img', '分类图片 仅支持JPG/PNG格式.');
                    } elseif ($file->size > 1024*1024*1) {
                        $model->addError('img', '分类图片 不能大于1m.');
                    } else {
                        //上传图片
                        $fast = new FastDFSHelper();
                        $ret = $fast->fdfs_upload_name_size($file->tempName, $file->name);
                        $category['img'] = '/' . $ret['group_name'] . '/' . $ret['filename'];
                    }
                }

                $detail = $model->getDetailsByName($category['name'], $id);
                if (empty($detail)) {
                    $results = $model->updateInfo($category, $cond);
                    if ($results == true) {
                        //日志
                        $array = array_diff($category, $list);
                        if (!empty($array)) {
                            $content = "管理员：" . \Yii::$app->user->identity->username . ",修改了分类id为:" . $list['id'] . " 分类的";
                            if (!empty($array['name'])) {
                                $content .= " 名称,修改为了:".$array['name'];
                            }
                            if (!empty($array['img'])) {
                                $content .= " 图片,修改为了:".$array['img'];
                            }
                            if (!empty($array['sort'])) {
                                $content .= " 排序,修改为了:".$array['sort'];
                            }
                            if (!empty($array['status'])) {
                                $status_name = $array['status']==2 ? '显示' : '隐藏';
                                $content .= " 显示状态,修改为了:".$status_name;
                            }
                            if ($list['status']==2) {
                                if ($list['status'] != $category['status']) {
                                    $status_name = $category['status']==2 ? '显示' : '隐藏';
                                    $content .= " 显示状态,修改为了:".$status_name;
                                }
                            }
                            $log_model = new Log();
                            $log_model->recordLog($content);
                        }
                        if (empty($category_list) and !empty($attribute)) {
                            foreach ($attribute as $v) {
                                $attribute_data['category_id'] = $id;
                                $attribute_data['attribute_id'] = $v;
                                $attribute_result = $model_attribute->getInsert($attribute_data);
                            }
                        } else {
                            if (!empty($attribute)) {
                                $array_del = array_diff($cate_array, $attribute);//减少的项
                                $array_add = array_diff($attribute, $cate_array);//增加的项
                                //有改动的情况
                                if (!empty($array_add) or !empty($array_del)) {
                                    //有增加的情况
                                    if (!empty($array_add)) {
                                        $add_result = 0;
                                        foreach ($array_add as $v) {
                                            $array_data['category_id'] = $id;
                                            $array_data['attribute_id'] = $v;
                                            $add_result = $model_attribute->getInsert($array_data);
                                        }
                                        //日志
                                        $content = "管理员：" . \Yii::$app->user->identity->username . ",增加了分类id为:" . $id . " 分类的属性，属性id集合是:".implode(',', $array_add);
                                        $log_model = new Log();
                                        $log_model->recordLog($content);
                                        if ($add_result == 200) {
                                            $results = 200;

                                        }
                                    }
                                    //有删除的情况
                                    if (!empty($array_del)) {
                                        $ids = implode(',', $array_del);
                                        $condition = "category_id =" . $id . " and attribute_id in (" . $ids . ")";
                                        //日志
                                        $content = "管理员：" . \Yii::$app->user->identity->username . ",删除了分类id为:" . $id . " 分类的属性，属性id集合是:".$ids;
                                        $log_model = new Log();
                                        $log_model->recordLog($content);
                                        $del_result = $model_attribute->deleteAll($condition);
                                        if ($del_result == 200) {
                                            $results = 200;

                                        }
                                    }
                                    //既有增加也有删除的情况
                                    if (!empty($array_del) and !empty($array_add)) {
                                        $ids = implode(',', $array_del);
                                        $condition = "category_id =" . $id . " and attribute_id in (" . $ids . ")";
                                        $del_result = $model_attribute->deleteAll($condition);
                                        $add_result = 0;
                                        foreach ($array_add as $v) {
                                            $array_data['category_id'] = $id;
                                            $array_data['attribute_id'] = $v;
                                            $add_result = $model_attribute->getInsert($array_data);
                                        }
                                        if ($del_result == 200 and $add_result == 200) {
                                            $results = 200;

                                        }
                                    }

                                } else {
                                    //没有改动情况
                                    $results = 200;
                                }
                            } else {
                                $condition = "category_id =" . $id;
                                $del_result = $model_attribute->deleteAll($condition);
                                if ($del_result == 200) {
                                    $results = 200;
                                }
                            }

                        }
                        if (!empty($attribute_result) and $attribute_result==true and $results==200) {
                            $this->redirect('/goods/category');
                        } else {
                            $this->redirect('/goods/category');
                        }

                    }
                } else {
                    $model->addError('name', '分类名称 不能重复');
                }

            }
        }
        return $this->render('add', ['model'=>$model, 'attribute_list'=>$attribute_list]);
    }

    /**
     * 删除
     *
     * @return int
     */
    public function actionDelete()
    {
        $id = RequestHelper::get('id');
        if (empty($id)) {
            return 0;
        } else {
            $model = new Category();
            $list = $model->getInfo(['id'=>$id]);
            $result = $model->getDelete($id);
            if ($result==200) {
                //日志
                $content = "管理员：".\Yii::$app->user->identity->username.",删除了分类id为:".$id.",分类名称为:".$list['name']." 的分类";
                $log_model = new Log();
                $log_model->recordLog($content);
                return 1;
            } else {
                return 0;
            }
        }
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
        $model = new Category();
        $result = $model->getBatchDelete($ids);
        if ($result==200) {
            $code =1;
        }
        return $code;
    }

}
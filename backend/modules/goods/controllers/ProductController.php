<?php
/**
 * 页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ProductController
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/21 9:51
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\modules\goods\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Attribute;
use backend\models\i500m\AttributeValue;
use backend\models\i500m\Brand;
use backend\models\i500m\BrandCategory;
use backend\models\i500m\Category;
use backend\models\i500m\CategoryAttribute;
use backend\models\i500m\CrmBranch;
use backend\models\i500m\CrmConfig;
use backend\models\i500m\Log;
use backend\models\i500m\Product;
use backend\models\i500m\ProductAttr;
use backend\models\i500m\ProductImage;
use backend\models\i500m\ProductSku;
use backend\models\i500m\Province;
use backend\models\shop\ShopProduct;
use backend\models\shop\ShopProductLog;
use common\helpers\CommonHelper;
use common\helpers\CurlHelper;
use common\helpers\FastDFSHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * Product
 *
 * @category CRM
 * @package  Product
 * @author   liuwei <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ProductController extends BaseController
{
    public $channel_url;

    /**
     * 初始化init
     *
     * @return string
     */
    public function init()
    {
          parent::init();
          $config_model = new CrmConfig();
          $cond['key'] = 'channel';
          $item = $config_model->getInfo($cond);
          $channel_url = empty($item['value']) ? '' : $item['value'];
          $this->channel_url = $channel_url;
    }

    /**
     * 标准库首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $province_model = new Province();
        $bc_id = $this->bc_id;//分公司id
        $branch_model =new CrmBranch();
        $data_cond['name'] = '总公司';
        $branch_item = $branch_model->getInfo($data_cond);
        $branch_id = empty($branch_item['id']) ? 0 :$branch_item['id'];//总公司id
        $branch_cond['status'] = 1;
        if ($bc_id!=$branch_id) {
            $id = array($bc_id,$branch_id);
            $branch_cond['id'] = $id;
        }
        $province_item = $branch_model->getList($branch_cond, 'province_id');
        $province_conf = [];
        foreach ($province_item as $v) {
                $province_conf['id'][] = $v['province_id'];
        }
        $city_data = $province_model->getlist($province_conf);
        //商品分类列表
        $cate_model = new Category();
        $cate_cond['level'] = 1;
        $cate_cond['type'] = 0;
        $cate_cond['status'] = 2;
        $cate_list = $cate_model->getList($cate_cond, 'id,name', 'id desc');
        //商品品牌列表
        $brand_model = new Brand();
        $brand_cond['status'] = 2;
        $brand_list = $brand_model->getList($brand_cond, 'id,name', 'id desc');
        //商品列表
        $cond['single'] = 1;
        $where = ['>', 'status', '0'];
        if ($bc_id != $branch_id) {
            $cond['bc_id'] = [$branch_id ,$bc_id];
        }
        $brand_item = [];
        //搜索
        $search = RequestHelper::get('Search');
        //商品名称搜索
        if (!empty($search['name'])) {
            $where = ['like', 'name', $search['name']];
        }
        //分类搜索
        if (!empty($search['cate_id'])) {
            $cond['cate_first_id'] = $search['cate_id'];
            $category_model = new BrandCategory();
            //分类下的品牌id列表
            $category_cond['category_id'] = $search['cate_id'];
            $category_list = $category_model->getList($category_cond, 'brand_id', 'id desc');
            if (!empty($category_list)) {
                $category_data = array();
                foreach ($category_list as $v) {
                    $category_data[] = $v['brand_id'];
                }
                $model_cond['status'] = 2;
                $model_cond['id'] = $category_data;
                $brand_item = $brand_model->getList($model_cond, 'id,name', 'id desc');
            }
        }
        //商品id
        if (!empty($search['id'])) {
            if (is_numeric($search['id'])) {
                $cond['id'] = $search['id'];
            } else {
                $cond['id'] = 0;
            }
        }
        //所否热销搜索
        if (!empty($search['type'])) {
            $type = $search['type'];
            if ($search['type']==2) {
                $type =0 ;
            }
            $cond['is_hot']= $type;
        }
        //上下架搜索
        if (!empty($search['status'])) {
            $cond['status']= $search['status'];
        };
        //区域搜索
        if (!empty($search['city_id'])) {
            $cond['province_id']= $search['city_id'];
        }
        //条形码搜索
        if (!empty($search['bar_code'])) {
            if (is_numeric($search['bar_code'])) {
                $cond['bar_code']= $search['bar_code'];
            } else {
                $cond['bar_code']= '';
            }
        }
        $model = new Product();
        $order = 'id desc';
        if ($bc_id!=$branch_id) {
            $order = 'bc_id asc,id desc';
        }
        $page = RequestHelper::get('page', 1);
        $list = $model->getPageList($cond, '*', $order, $page, $this->size, $where);
        //商品和分类品牌集合
        $cate_data = array();
        if (!empty($cate_list)) {
            foreach ($cate_list as $k => $v) {
                $cate_data[$v['id']] = $v['name'];
            }
        }
        $brand_data = array();
        if (!empty($brand_list)) {
            foreach ($brand_list as $k => $v) {
                $brand_data[$v['id']] = $v['name'];
            }
        }
        $data = array();
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $data[] = $value;
                //开通省
                if ($value['province_id']==0) {
                    $area_name = '--';
                } else {
                    $open_cond['id'] = $value['province_id'];
                    $open_list = $province_model->getInfo($open_cond, 'name');
                    $area_name = empty($open_list) ? '' : $open_list['name'];
                }
                $data[$key]['attr_value'] = implode(' ', explode('_', $data[$key]['attr_value']));
                $data[$key]['area_name'] = $area_name;
                $data[$key]['cate_name'] = empty($cate_data[$value['cate_first_id']]) ? "--" : $cate_data[$value['cate_first_id']];
                $data[$key]['brand_name'] = empty($brand_data[$value['brand_id']]) ? "--" : $brand_data[$value['brand_id']];
            }
        }
        //商品数量及分页
        $total = $model->getCount($cond, $where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        $param = [
            'list'=>$data,
            'pages'=>$pages ,
            'cate_list'=>$cate_list,
            'brand_list'=>$brand_item,
            'search'=>$search,'bc_id'=>$bc_id,
            'city_data'=>$city_data,
            'branch_id'=>$branch_id,
            'total'=>$total
        ];
        return $this->render('index', $param);
    }
    /**
     * 标准库添加
     *
     * @return string
     */
    public function actionAdd()
    {
        $bc_id = $this->bc_id;//城市id
        $model = new Product();
        $brand_item = array(array('id'=>'','name'=>'选择品牌'));
         //商品分类列表
        $cate_model = new Category();
        $cate_cond['level'] = 1;
        $cate_cond['type'] = 0;
        $cate_cond['status'] = 2;
        $cate_list = $cate_model->getList($cate_cond, 'id,name', 'id desc');
        $cate_data = [];
        if ($cate_list) {
            foreach ($cate_list as $k => $v) {
                $cate_data[0]['id'] = '';
                $cate_data[0]['name'] = '选择分类';
                $cate_data[$k+1] = $v;
            }
        }
        $cate_list[0] = array('id'=>'0','name'=>'选择分类');
        //所属分公司
        $branch_model =new CrmBranch();
        $data_cond['name'] = '总公司';
        $branch_item = $branch_model->getInfo($data_cond);
        $branch_id = empty($branch_item['id']) ? 0 :$branch_item['id'];//总公司id
        $branch_cond['status'] = 1;
        $city_data = $branch_model->getList($branch_cond, 'id,name');
        //是否热销
        $model['is_hot'] = 0;
        //是否能自营
        $model['is_self'] = 1;
        //是否固定价
        $model['fixed_price'] = 0;
        $product = RequestHelper::post('Product');
        $products = RequestHelper::post('Products');
        if (!empty($product)) {
            $model->attributes = $product;
            if ($model['cate_first_id']!=0) {
                $category_model = new BrandCategory();
                $brand_model = new Brand();
                //分类下的品牌id列表
                $category_cond['category_id'] = $model['cate_first_id'];
                $category_list = $category_model->getList($category_cond, 'brand_id', 'id desc');
                if (!empty($category_list)) {
                    $category_data = array();
                    foreach ($category_list as $v) {
                        $category_data[] = $v['brand_id'];
                    }
                    $cond['id'] = $category_data;
                    $cond['status'] = 2;
                    $brand_item = $brand_model->getList($cond, 'id,name', 'id desc');
                }
            }
            //产品主图
            $file = $_FILES['image'];
            $product['keywords'] = empty($product['keywords']) ? '' : $product['keywords'];//关键字
            $product['sale_price'] = empty($product['sale_price']) ? 0 : $product['sale_price'];//售价
            $product['single'] = 1;//标准库
            $product['create_time'] = time();//添加时间
            $result = $model->getInfo(['name'=>$product['name']]);//商品名称是否存在
            $product['description'] = empty($product['description']) ? '' : htmlspecialchars_decode($product['description']);
            $product['description'] = empty($product['description']) ? '' : stripslashes($product['description']);
            $model['description'] = $product['description'];
            $attr_result = $this->attrValue($products);
            if (!empty($result)) {
                $model->addError('name', '商品名称 不能重复');
            } elseif (count($file['name']) != count(array_filter($file['name']))) {
                \Yii::$app->getSession()->setFlash('attr_value', '主图 不能为空');
            } elseif ($product['cate_first_id']=="") {
                $model->addError('cate_first_id', '商品分类 不能为空');
            } elseif ($product['brand_id']=="") {
                $model->addError('brand_id', '商品品牌 不能为空');
            } elseif ($attr_result['code'] != 200) {
                \Yii::$app->getSession()->setFlash('attr_value', $attr_result['msg']);
            } elseif (empty($product['description'])) {
                \Yii::$app->getSession()->setFlash('error', '商品详情 不能为空');
            } else {
                $list = 0;
                foreach ($products['attr_value'] as $k => $v) {
                    //上传图片
                    $file_tmp = $file['tmp_name'][$k];
                    $real_name = $file['name'][$k];
                    $filename = dirname($file_tmp) . "/" . $real_name;
                    $fast = new FastDFSHelper();
                    @rename($file_tmp, $filename);
                    $ret = $fast->fdfs_upload_by_filename($filename);
                    $product['image'] = '/'.$ret['group_name'] . '/' . $ret['filename'];
                    $product['attr_value'] = $v;
                    $product['origin_price'] = $products['origin_price'][$k];
                    $product['sale_price'] = $products['sale_price'][$k];
                    $product['shop_price'] = $products['shop_price'][$k];
                    $product['total_num'] = $products['total'][$k];
                    $product['bar_code'] = $products['bar_code'][$k];
                    $product['name'] = CommonHelper::semiangle($product['name']);
                    $product['title'] = CommonHelper::semiangle($product['title']);
                    $product['keywords'] = CommonHelper::semiangle($product['keywords']);
                    if ($bc_id != $branch_id) {
                        $product['bc_id'] =  $bc_id;
                    }
                    //分公司详情
                    $city_item = $branch_model->getInfo(array('id'=>$product['bc_id']));
                    $product['sku'] = time().mt_rand(1000, 9999);
                    $product['province_id'] = $city_item['province_id'];
                    $product['status'] = 2;
                    $list = $model->getInsert($product);
                    if ($list > 0) {
                        //记录日志
                        $content = "管理员：".\Yii::$app->user->identity->username.",添加了一个商品id为:".$list.",商品名称为:".$product['name'].' 的商品';
                        $log_model = new Log();
                        $log_model->recordLog($content);
                        $img_model = new ProductImage();
                        //新增商品实时同步到sphinx
                        $url = $this->channel_url.'/sphinx/insert-goods?goods_id='.$list;
                        CurlHelper::get($url, 'channel');
                        //主图添加
                        $img_data = array();
                        $img_data['image'] = $product['image'];
                        $img_data['product_id'] = $list;
                        $img_data['status'] = 2;
                        $img_data['sort'] = 99;
                        $img_data['create_time'] = time();
                        $img_data['type'] = 1;
                        $img_model->getBulkInsert($img_data);
                    }
                }
                if ($list>0) {
                    $this->redirect('/goods/product');
                }

            }
        }
        $param = array(
            'model'=>$model,
            'cate_list'=>$cate_data,
            'brand_list'=>$brand_item,
            'city_list'=>$city_data,
            'bc_id'=>$bc_id,
            'branch_id'=>$branch_id,
            'product'=>$product
        );
        return $this->render('add', $param);
    }

    /**
     * 图集上传
     *
     * @return string
     */
    public function actionImages()
    {
        $id = RequestHelper::get('id');
        $product_model = new Product();
        $product_item = $product_model->getInfo(['id'=>$id]);
        $sku = empty($product_item) ? 0 : $product_item['sku'];
        //sku为空时，只针对当前商品上传图片
        if ($sku) {
            $product_list = $product_model->getList(['sku'=>$sku], 'id');
        } else {
            $product_list[] = ['id'=>$id];
        }
        $model = new ProductImage();
        $submit = RequestHelper::post('add');
        if (!empty($submit)) {
            //上传图片
            $fast = new FastDFSHelper();
            //图集上传
            $img1 = $_FILES['img1'];
            $img2 = $_FILES['img2'];
            $img3 = $_FILES['img3'];
            $img4 = $_FILES['img4'];
            $img5 = $_FILES['img5'];
            $image_data = array();
            if (!empty($img1['name'])) {
                 $image_data[] = $fast->fdfs_upload('img1');
            }
            if (!empty($img2['name'])) {
                 $image_data[] = $fast->fdfs_upload('img2');
            }
            if (!empty($img3['name'])) {
                 $image_data[] = $fast->fdfs_upload('img3');
            }
            if (!empty($img4['name'])) {
                 $image_data[] = $fast->fdfs_upload('img4');
            }
            if (!empty($img5['name'])) {
                 $image_data[] = $fast->fdfs_upload('img5');
            }
            $image_result = true;
            foreach ($product_list as $list) {
                foreach ($image_data as $k => $v) {
                      $data = array();
                      $data['image'] = '/' . $v['group_name'] . '/' . $v['filename'];
                      $data['product_id'] = $list['id'];
                      $data['status'] = 2;
                      $data['sort'] = 99;
                      $data['create_time'] = time();
                      $data['type'] = 0;
                      $image_result = $model->getBulkInsert($data);
                }
                $item = $product_model->getInfo(['id'=>$list['id']]);
                //记录日志
                $content = "管理员：".\Yii::$app->user->identity->username.",修改了商品id为:".$list['id'].",商品名称为:".$item['name'].' 的商品图集';
                $log_model = new Log();
                $log_model->recordLog($content);
            }
            if ($image_result == true) {
                 $this->redirect('/goods/product');
            }
        }
        return $this->render('_images');
    }

     /**
      * 商品属性---添加
      *
      * @return string
      */
    public function actionAttribute()
    {
        $log_model = new Log();
        $id = RequestHelper::get('id');
        $model = new Product();
        $model['sort'] = 999;
        $product_attr = new ProductAttr();
        $attr_model = new Attribute();
        $model_attr = new CategoryAttribute();
        $cond['id'] = $id;
        $list = $model->getInfo($cond);
        $ids_data = array();
        if (!empty($list)) {
            $model_conf['category_id'] = $list['cate_first_id'];
            $data =$model_attr->getList($model_conf, 'attribute_id');
            if (!empty($data)) {
                foreach ($data as $v) {
                      $ids_data[] = $v['attribute_id'];
                }
            }
        }
        $attr_conf['id'] = $ids_data;
        $item = $attr_model->getList($attr_conf, 'id,admin_name', 'weight asc');
        $attr_name_id = RequestHelper::post('attr_name_id');
        $attr_values = RequestHelper::post('attr_values');
        $origin_price = RequestHelper::post('origin_price');
        $sale_price = RequestHelper::post('sale_price');
        $shop_price = RequestHelper::post('shop_price');
        $total = RequestHelper::post('total');
        $attr_names = RequestHelper::post('attr_names');
        $bar_code = RequestHelper::post('bar_code');
        if (!empty($attr_name_id)) {
            if ($attr_name_id[0]==0) {
                 $this->redirect('/goods/product/images?id=' . $id);
            } else {
                $price_number = 1;
                foreach ($sale_price as $key => $value) {
                    if ($value > $origin_price[$key]) {
                        $price_number *= 0;
                    } else {
                        $price_number *= 1;
                    }
                }
                if ($price_number!=1) {
                    return $this->error('建议售价 不能小于进货价', '/goods/product/attribute?id=' . $id);
                } else {
                    $attribute_value_model = new AttributeValue();
                    $product_result = false;
                    foreach ($origin_price as $k => $v) {
                        $ids = $attr_values[$k];
                        $id_data = explode(',', $ids);
                        $attribute_value_list = $attribute_value_model->getList(['id' => $id_data]);
                        $data = [];
                        $data['origin_price'] = $v;
                        $data['sale_price'] = $sale_price[$k];
                        $data['shop_price'] = $shop_price[$k];
                        $data['total_num'] = !empty($total[$k]) ? $total[$k] : 0;
                        $data['bar_code'] = $bar_code[$k];
                        $data['attr_value'] = $attr_names[$k];
                        if ($k == 0) {
                            $product_result = $model->updateInfo($data, array('id' => $list['id']));
                            $product_id = $id;
                            //记录日志
                            $content = "管理员：".\Yii::$app->user->identity->username.",把商品id为:".$id.",商品名称为:".$list['name'].' 商品的条形码修改成了:'.$data['bar_code'];
                            if ($data['origin_price']!=$list['origin_price']) {
                                $content .= ",建议售价修改成了:".$data['origin_price'];
                            }
                            if ($data['sale_price']!=$list['sale_price']) {
                                $content .= ",进货价修改成了:".$data['sale_price'];
                            }
                            if ($data['shop_price']!=$list['shop_price']) {
                                $content .= ",铺货价修改成了:".$data['shop_price'];
                            }
                            if ($data['total_num']!=$list['total_num']) {
                                $content .= ",库存修改成了:".$data['total_num'];
                            }
                            $log_model->recordLog($content);
                            //修改商品实时同步到sphinx
                            $url = $this->channel_url.'/sphinx/sync-goods?goods_id='.$product_id;
                            CurlHelper::get($url, 'channel');
                        } else {
                             $data['name'] = $list['name'];
                             $data['sku'] = $list['sku'];
                             $data['image'] = $list['image'];
                             $data['cate_first_id'] = $list['cate_first_id'];
                             $data['brand_id'] = $list['brand_id'];
                             $data['status'] = 2;
                             $data['create_time'] = time();
                             $data['single'] = 1;
                             $data['description'] = $list['description'];
                             $data['bc_id'] = $list['bc_id'];
                             $data['province_id'] = $list['province_id'];
                             $data['is_self'] = $list['is_self'];
                             $data['fixed_price'] = $list['fixed_price'];
                             $img_model = new ProductImage();
                             $product_result = $model->getInsert($data);
                             //记录日志
                             $content = "管理员：".\Yii::$app->user->identity->username.",添加了一个商品id为:".$product_result.",商品名称为:".$list['name'].' 的商品';
                             $log_model = new Log();
                             $log_model->recordLog($content);
                             $product_id = $product_result;
                             //主图
                             $img['image'] = $list['image'];
                             $img['product_id'] = $product_id;
                             $img['status'] = 2;
                             $img['sort'] = 99;
                             $img['create_time'] = time();
                             $img['type'] = 1;
                             $img_model->insertInfo($img);
                             //新添的商品实时同步到sphinx
                             $url = $this->channel_url.'/sphinx/insert-goods?goods_id='.$product_result;
                             CurlHelper::get($url, 'channel');
                        }
                        foreach ($attribute_value_list as $attr_value) {
                            $attr_data['product_id'] = $product_id;
                            $attr_data['attr_name_id'] = $attr_value['attr_id'];
                            $attr_data['attr_value_id'] = $attr_value['id'];
                            $product_attr->insertInfo($attr_data);
                        }
                    }
                    if ($product_result > 0) {
                        $this->redirect('/goods/product/images?id=' . $id);
                    }
                }
            }
        }
        return $this->render('_attribute', ['item'=>$item]);
    }

    /**
    * 获取属性值列表
    *
    * @return array
    */
    public function actionAttrValues()
    {
        $attribute = new Attribute();
        $attribute_value = new AttributeValue();
        $product_attr = new ProductAttr();
        $attr_name_ids =RequestHelper::get('attr_name_ids');
        $pid = RequestHelper::get('pid');
        if (!empty($pid) && $pid > 0) {
            $product_cond['product_id']=$pid;
            $product_data = $product_attr->getList($product_cond);
            foreach ($product_data as $each) {
                 $attr_list[$each['attr_name_id']][$each['attr_value_id']] = $each['attr_value_id'];
            }
        }
        $attr_name_ids = rtrim($attr_name_ids, ",");
        if (!empty($attr_name_ids)) {
            $attr_name_data = explode(',', $attr_name_ids);
            $attr_list = $attribute->getList(['id'=>$attr_name_data], '*', 'weight asc');
            foreach ($attr_list as $v) {
                $attribute_value_cond['attr_id'] = $v['id'];
                $data = $attribute_value->getList($attribute_value_cond, 'id,attr_value', 'weight asc');
                if (!empty($data)) {
                    echo '<div class="sku_group" id="sku_group_'.$v['id'].'" data-id="'.$v['id'].'" data-name="'.$v['attr_name'].'">'.$v['attr_name'].'：';
                    foreach ($data as $each) {
                        $checked = '';
                        echo '<input type="checkbox" name="attr_value_id[]" value="'.$each['id'].'"'.$checked.' onclick="return addPriceList();">'.$each['attr_value'];
                    }
                    echo '</div>';
                }
            }

        }

    }
    /**
    * 获取价格表格
    * @return array
    */
    public function actionPriceTable()
    {
        $model = new Product();
        $pid = RequestHelper::get('pid'); //产品id
        $cond['id'] = $pid;
        $list = $model->getInfo($cond);
        $attr_name_ids = rtrim(RequestHelper::get('attr_name_ids'), ","); //选中的属性名称
        $attr_value_ids = rtrim(RequestHelper::get('attr_value_ids'), ","); //选中的属性值
        $origin_price = empty($list['origin_price']) ? '0.00' : $list['origin_price'];//建议售价
        $sale_price = empty($list['sale_price']) ? '0.00' : $list['sale_price'];//进货价售价
        $result = '';
        $value_array = $rs_array = array();
        if (!empty($attr_value_ids) && !empty($attr_name_ids)) {
            //属性名称列表
            $attribute = new Attribute();
            $attribute_value = new AttributeValue();
            $name_list = $attribute->getList(['id'=>explode(',', $attr_name_ids)], 'id,attr_name,weight,is_search', 'weight asc');
            //属性值列表
            $data = $attribute_value->getList(['id'=>explode(',', $attr_value_ids)], 'id,attr_value,attr_id,weight', 'weight asc');
            if (!empty($data)) {
                foreach ($data as $key => $each) {
                    $value_array[$each['attr_id']][] = $each;
                }
            }
            $result .='<table class="items table table-striped table-bordered table-condensed">';
            $rs_array = [];
            if (!empty($name_list)) {
                $rows = 1; // 表格的行数
                $result .='<tr>';
                $flag = $val_flag = array();
                foreach ($name_list as $key => $v) {
                    $result .= '<th>'.$v['attr_name'].'</th>'; //表头
                    $v['id'];
                    $rs_array[] = $value_array[$v['id']];
                    $rows = $rows*count($value_array[$v['id']]);
                    $flag[$key] = 0;
                }
                $result .='<th>建议售价</th>';
                $result .='<th>进货价</th>';
                $result .='<th>铺货价</th>';
                $result .='<th>库存</th>';
                $result .='<th>条形码</th>';
                $result .='</tr>';
                $val_name = [];
                for ($i=1; $i<=$rows; $i++) {
                    $result .='<tr>';
                    for ($j=1; $j<=count($rs_array); $j++) {
                        $num = 1;
                        for ($k=0; $k<=($j-1); $k++) {
                             $num = $num*count($rs_array[$k]);
                        }
                        $row_span = $rows/$num;
                        if ($flag[$j-1] >= count($rs_array[$j-1])) {
                             $flag[$j-1] = 0;
                        }
                        if (($i-1)%$row_span==0) {
                             $val_name[$j-1] = $rs_array[$j-1][$flag[$j-1]]['attr_value'];
                             $val_flag[$j-1] = $rs_array[$j-1][$flag[$j-1]]['id'];
                             $result .='<td rowspan="'.$row_span.'">'.$rs_array[$j-1][$flag[$j-1]]['attr_value'].'</td>';
                             $flag[$j-1]++;
                        }
                    }
                    $attr_values = implode(",", $val_flag);
                    $attr_name = implode("_", $val_name);
                    $p_num = 0;
                    if (!empty($pid) && $pid > 0) {
                        $product_sku = new ProductSku;
                        $info = $product_sku->getInfo("product_id={$pid} and attr_values='{$attr_values}'");
                        $p_num = isset($info['num']) ? $info['num'] : '10';
                    }
                    $result .='<td>
                        <input name="attr_values[]" type="hidden" value="'.$attr_values.'">
                        <input name="attr_names[]" type="hidden" value="'.$attr_name.'">
                        <input name="origin_price[]" type="text" value="'.$origin_price.'"  style="width:100px;">

                    </td>
                    <td>
                        <input name="sale_price[]" type="text" value="'.$sale_price.'"  style="width:100px;">

                    </td>
                    <td>
                        <input name="shop_price[]" type="text" value="'.$sale_price.'"  style="width:100px;">

                    </td>
                    <td>
                        <input maxlength="9" name="total[]" type="text" value="'.$p_num.'" style="width:100px;">
                    </td>
                    <td>
                        <input name="bar_code[]" type="text" id="bar_code" >

                    </td>';
                    $result .='</tr>';
                }
            }
            $result .='</table>';
            $result .='<div style="color: red">提示:进货价<=铺货价<=建议售价</div>';
        }
        echo json_encode(array('result'=>$result));
    }

    /**
    * 内容编辑
    *
    * @return string
    */
    public function actionEdit()
    {
        $cate_list = [];
        $bc_id = $this->bc_id;//城市id
        $model = new Product();
        $id = RequestHelper::get('id');
        $cond['id'] = $id;
        $item = $model->getInfo($cond, true, '*');
        $brand_list = array(array('id'=>'','name'=>'选择品牌'));
        //商品分类列表
        $cate_model = new Category();
        $cate_cond['level'] = 1;
        $cate_cond['status'] = 2;
        $cate_cond['type'] = 0;
        $cate_data = $cate_model->getList($cate_cond, 'id,name', 'id desc');
        if ($cate_data) {
            foreach ($cate_data as $k => $v) {
                 $cate_list[0]['id'] = '';
                 $cate_list[0]['name'] = '选择分类';
                 $cate_list[$k+1] = $v;


            }
        }
        //商品品牌列表
        $category_model = new BrandCategory();
        $brand_model = new Brand();
        //分类下的品牌id列表
        $category_cond['category_id'] = $item['cate_first_id'];
        $category_list = $category_model->getList($category_cond, 'brand_id', 'id desc');
        if (!empty($category_list)) {
            $category_data = array();
            foreach ($category_list as $v) {
                 $category_data[] = $v['brand_id'];
            }
            $cond['id'] = $category_data;
            $cond['status'] = 2;
            $brand_list = $brand_model->getList($cond, 'id,name', 'id desc');
        }
        //所属分公司
        $branch_model =new CrmBranch();
        $data_cond['name'] = '总公司';
        $branch_item = $branch_model->getInfo($data_cond);
        $branch_id = empty($branch_item['id']) ? 0 :$branch_item['id'];//总公司id
        $branch_cond['status'] = 1;
        $city_data = $branch_model->getList($branch_cond, 'id,name,province_id');
        $model->attributes  = $item;
        //是否热销
        $model['is_hot'] = $item['is_hot'];
        //上下架
        $model['title']= $item['title'];
        $model['keywords']= $item['keywords'];
        $model['bc_id']= $item['bc_id'];
        //是否能自营
        $model['is_self'] = $item['is_self'];
        //是否固定价
        $model['fixed_price'] = $item['fixed_price'];
        $item['description'] = empty($item['description']) ? '' : htmlspecialchars_decode($item['description']);
        $item['description'] = empty($item['description']) ? '' : stripslashes($item['description']);
        $model['description'] = $item['description'];
        $product = RequestHelper::post('Product');
        $log_model = new Log();
        $img_model = new ProductImage();
        if (!empty($product)) {
            $file = $_FILES['image'];
            $products = RequestHelper::post('Products');
            $attr_result = $this->attrValue($products, $id);
            $model->attributes = $product;
            if ($product['brand_id']=="") {
                 $model->addError('brand_id', '商品品牌 不能空');
            } elseif (empty($product['description'])) {
                 \Yii::$app->getSession()->setFlash('error', '商品详情 不能为空');
            } elseif ($attr_result['code']!=200) {
                \Yii::$app->getSession()->setFlash('attr_value', $attr_result['msg']);
            } elseif (count($file['name']) - count(array_filter($file['name'])) > 1) {
                \Yii::$app->getSession()->setFlash('attr_value', '主图 不能为空');
            } else {
                foreach ($products['attr_value'] as $k => $v) {
                    $product['attr_value'] = $v;
                    $product['origin_price'] = $products['origin_price'][$k];
                    $product['sale_price'] = $products['sale_price'][$k];
                    $product['shop_price'] = $products['shop_price'][$k];
                    $product['total_num'] = $products['total'][$k];
                    $product['bar_code'] = $products['bar_code'][$k];
                    $log_data = [];
                    $product['name'] = CommonHelper::semiangle($product['name']);
                    $product['title'] = CommonHelper::semiangle($product['title']);
                    $product['keywords'] = CommonHelper::semiangle($product['keywords']);
                    $product['bc_id'] = $bc_id != $branch_id ? $bc_id : $product['bc_id'];
                    //分公司详情
                    $city_item = $branch_model->getInfo(array('id' => $product['bc_id']));
                    $product['province_id'] = $city_item['province_id'];
                    $product_cond['id'] = $id;
                    if ($k == 0) {
                        if ($file['name'][0]!="") {
                            //上传图片
                            $file_tmp = $file['tmp_name'][0];
                            $real_name = $file['name'][0];
                            $filename = dirname($file_tmp) . "/" . $real_name;
                            $fast = new FastDFSHelper();
                            @rename($file_tmp, $filename);
                            $ret = $fast->fdfs_upload_by_filename($filename);
                            $product['image'] = '/'.$ret['group_name'] . '/' . $ret['filename'];
                            //主图添加
                            $img_data = array();
                            $img_data['image'] = $product['image'];
                            $img_data['product_id'] = $id;
                            $img_data['status'] = 2;
                            $img_data['sort'] = 99;
                            $img_data['create_time'] = time();
                            $img_data['type'] = 1;
                            $img_model->updateInfo(['type'=>0], ['product_id'=>$id]);
                            $img_model->getBulkInsert($img_data);
                        } else {
                            $product['image'] = $item['image'];
                        }
                        $result = $model->updateInfo($product, $product_cond);
                        if ($result == 1) {
                            $shop_product_log_model = new ShopProductLog();
                            //记录日志
                            $product['description'] = empty($product['description']) ? '' : htmlspecialchars_decode($product['description']);
                            $product['description'] = empty($product['description']) ? '' : stripslashes($product['description']);
                            $array = array_diff($product, $item);
                            $content = \Yii::$app->user->identity->username . ",把:" . $item['name'] . "(" . $id . ")";
                            $log_model = new Log();
                            if (!empty($array)) {
                                if (!empty($array['name'])) {
                                    $content .= "修改为:" . $array['name'];
                                }
                                if (!empty($array['title'])) {
                                    $content .= ",副标题:" . $array['title'];
                                }
                                if (!empty($array['keywords'])) {
                                    $content .= ",关键词:" . $array['keywords'];
                                }
                                if (!empty($array['cate_first_id'])) {
                                    $cate_item = $cate_model->getInfo(['id' => $array['cate_first_id']]);
                                    $content .= ",分类:" . $cate_item['name'];
                                }
                                if (!empty($array['brand_id'])) {
                                    $brand_item = $brand_model->getInfo(['id' => $array['brand_id']]);
                                    $content .= ",品牌:" . $brand_item['name'];
                                }
                                if (!empty($array['description'])) {
                                    $content .= ",详情被修改了";
                                }
                                $log_model->recordLog($content);
                            }
                            if ($product['bc_id'] != $item['bc_id']) {
                                $branch_item = $branch_model->getInfo(['id' => $product['bc_id']]);
                                $content .= ",限定区域:" . $branch_item['name'];
                                $log_model->recordLog($content);
                            }
                            if ($product['is_hot'] != $item['is_hot']) {
                                $is_hot = $product['is_hot'] == 1 ? '推荐' : '不推荐';
                                $content .= ",推荐:" . $is_hot;
                                $log_model->recordLog($content);
                            }
                            if ($product['is_hot'] != $item['is_hot']) {
                                $is_self = $product['is_self'] == 1 ? '可以' : '不可以';
                                $content .= ",自营:" . $is_self;
                                $log_model->recordLog($content);
                            }
                            if ($product['fixed_price'] != $item['fixed_price']) {
                                $fixed_price = $product['fixed_price'] == 1 ? '是' : '否';
                                $content .= ",固定价:" . $fixed_price;
                                $log_data['fixed_price'] = $product['fixed_price'];
                                $log_model->recordLog($content);
                            }
                            if ($product['origin_price'] != $item['origin_price']) {
                                $content .= ",属性值:" . $product['origin_price'];
                                $log_model->recordLog($content);
                            }
                            if ($product['sale_price'] != $item['sale_price']) {
                                $content .= ",建议售价:" . $product['sale_price'];;
                                $log_model->recordLog($content);
                            }
                            if ($product['sale_price'] != $item['sale_price']) {
                                $content .= ",进货价:" . $product['sale_price'];
                                $log_model->recordLog($content);
                            }
                            if ($product['shop_price'] != $item['shop_price']) {
                                $content .= ",铺货价:" . $product['shop_price'];
                                $log_model->recordLog($content);
                            }
                            if ($product['total_num'] != $item['total_num']) {
                                $content .= ",库存:" . $product['total_num'];
                                $log_model->recordLog($content);
                            }
                            if ($product['bar_code'] != $item['bar_code']) {
                                $content .= ",条形码:" . $product['bar_code'];
                                $log_model->recordLog($content);
                            }
                            if ($product['cate_first_id'] != $item['cate_first_id']) {
                                $log_data['cat_id'] = $product['cate_first_id'];
                            }
                            if ($product['brand_id'] != $item['brand_id']) {
                                $log_data['brand_id'] = $product['brand_id'];
                            }
                            if (!empty($log_data)) {
                                $log_data['product_id'] = $id;
                                $result = $shop_product_log_model->getInfo(['product_id' => $id]);
                                if (empty($result)) {
                                    $shop_product_log_model->insertInfo($log_data);
                                } else {
                                    $shop_product_log_model->updateInfo($log_data, ['product_id' => $id]);
                                }
                            }
                            //修改商品实时同步到sphinx
                            $url = $this->channel_url . '/sphinx/sync-goods?goods_id=' . $id;
                            CurlHelper::get($url, 'channel');
                            $this->redirect('/goods/product');
                        }
                    } else {
                        //上传图片
                        $file_tmp = $file['tmp_name'][$k];
                        $real_name = $file['name'][$k];
                        $filename = dirname($file_tmp) . "/" . $real_name;
                        $fast = new FastDFSHelper();
                        @rename($file_tmp, $filename);
                        $ret = $fast->fdfs_upload_by_filename($filename);
                        $product['image'] = '/'.$ret['group_name'] . '/' . $ret['filename'];
                        //分公司详情
                        $city_item = $branch_model->getInfo(array('id'=>$product['bc_id']));
                        $product['sku'] = time().mt_rand(1000, 9999);
                        $product['province_id'] = $city_item['province_id'];
                        $product['status'] = 2;
                        $product['single'] = 1;
                        $product['create_time'] = date("Y-m-d H:i:s");
                        $list = $model->getInsert($product);
                        if ($list > 0) {
                            //记录日志
                            $content = "管理员：".\Yii::$app->user->identity->username.",添加了一个商品id为:".$list.",商品名称为:".$product['name'].' 的商品';
                            $log_model->recordLog($content);
                            //新增商品实时同步到sphinx
                            $url = $this->channel_url.'/sphinx/insert-goods?goods_id='.$list;
                            CurlHelper::get($url, 'channel');
                            //主图添加
                            $img_data = array();
                            $img_data['image'] = $product['image'];
                            $img_data['product_id'] = $list;
                            $img_data['status'] = 2;
                            $img_data['sort'] = 99;
                            $img_data['create_time'] = time();
                            $img_data['type'] = 1;
                            $img_model->getBulkInsert($img_data);
                        }
                    }
                }
            }
        }
        $list = array(
            'model'=>$model,
            'item'=>$item,
            'cate_list'=>$cate_list,
            'brand_list'=>$brand_list,
            'city_list'=>$city_data,
            'bc_id' =>$bc_id,
            'branch_id'=>$branch_id
        );
        return $this->render('edit', $list);
    }

    /**
    * 属性增加
    *
    * @return string
    */
    public function actionProductAttribute()
    {
        $id = RequestHelper::get('id');
        $model = new Product();
        $data['id'] = $id;
        $item = $model->getInfo($data);
        $item['attr_value'] = empty($item['attr_value']) ? '' : implode(' ', explode('_', $item['attr_value']));
        return $this->render('attribute', ['item'=>$item]);
    }

    /**
    * 商品属性增加
    *
    * @return string
    */
    public function actionAttributeSave()
    {
        $id = RequestHelper::get('id');
        $model = new Product();
        $attr_model = new ProductAttr();
        $attribute_value_model = new AttributeValue();
        $attribute_model = new Attribute();
        $cond['product_id'] = $id;
        $attr_list = $attr_model->getList($cond, '*', 'id asc');
        $list = [];
        if (!empty($attr_list)) {
            foreach ($attr_list as $k => $v) {
                 $attr['id'] = $v['attr_name_id'];
                 $attr_list = $attribute_model->getInfo($attr);
                 $data['attr_id'] = $v['attr_name_id'];
                 $list[$k]['data'] = $attribute_value_model->getList($data);
                 $list[$k]['name'] = $attr_list['admin_name'];
            }
        }
        $item = $model->getInfo(array('id'=>$id));
        $attr_id = RequestHelper::post('attr_id');
        if (!empty($attr_id)) {
            $attr_value = RequestHelper::post('attr');
            $origin_price = RequestHelper::post('origin_price');
            $sale_price = RequestHelper::post('sale_price', 0);
            $shop_price = RequestHelper::post('shop_price');
            $total_num = RequestHelper::post('total_num');
            $bar_code = RequestHelper::post('bar_code');
            $number = mb_strlen($bar_code, 'utf8');
            $bar_cond['bar_code'] = $bar_code;
            $bar_list = $model->getInfo($bar_cond);
            if (empty($origin_price)) {
                 return $this->error('进货价 不能为空');
            } elseif ($shop_price<0) {
                 return $this->error('铺货价 不能小于0');
            } elseif ($origin_price<$sale_price) {
                 return $this->error('建议售价 不能小于进货价');
            } elseif (empty($bar_code)) {
                 return $this->error('条形码 不能为空');
            } elseif (!is_numeric($bar_code)) {
                 return $this->error('条形码 必须是数字');
            } elseif ($number!=13) {
                 return $this->error('条形码 必须13位');
            } elseif (!empty($bar_list)) {
                 return $this->error('条形码 已经存在');
            } else {
                $attr_ids = [];
                $attr_values = [];
                foreach ($attr_value as $k => $v) {
                    $attr_data = $attribute_value_model->getInfo(array('id'=>$v));
                    $attr_values[] = $attr_data['attr_value'];
                    $attr_ids[$k]['attr_name_id'] =$attr_data['attr_id'];
                    $attr_ids[$k]['attr_value_id'] =$attr_data['id'];
                }
                $item_data = [];
                $item_data['origin_price'] = $origin_price;
                $item_data['sale_price'] = $sale_price;
                $item_data['shop_price'] = $shop_price;
                $item_data['total_num'] = $total_num;
                $item_data['bar_code'] = $bar_code;
                $item_data['attr_value'] = implode('_', $attr_values);
                $item_data['name'] = $item['name'];
                $item_data['sku'] = $item['sku'];
                $item_data['image'] = $item['image'];
                $item_data['cate_first_id'] = $item['cate_first_id'];
                $item_data['brand_id'] = $item['brand_id'];
                $item_data['status'] = 2;
                $item_data['create_time'] = time();
                $item_data['single'] = 1;
                $item_data['description'] = $item['description'];
                $item_data['bc_id'] = $item['bc_id'];
                $item_data['province_id'] = $item['province_id'];
                $item_data['is_self'] = $item['is_self'];
                $item_data['fixed_price'] = $item['fixed_price'];
                $item_cond['name'] =  $item_data['name'];
                $item_cond['attr_value'] =  $item_data['attr_value'];
                $cond_result = $model->getInfo($item_cond);
                if (empty($cond_result)) {
                    $img_model = new ProductImage();
                    $product_result = $model->getInsert($item_data);
                    $product_id = $product_result;
                    //主图
                    $img['image'] = $item['image'];
                    $img['product_id'] = $product_id;
                    $img['status'] = 2;
                    $img['sort'] = 99;
                    $img['create_time'] = time();
                    $img['type'] = 1;
                    $img_model->insertInfo($img);
                    //属性
                    foreach ($attr_ids as $value) {
                        $data_item['product_id'] = $product_id;
                        $data_item['attr_name_id'] = $value['attr_name_id'];
                        $data_item['attr_value_id'] = $value['attr_value_id'];
                        $attr_model->insertInfo($data_item);
                    }
                    if ($product_result > 0) {
                        //日志
                        $content = "管理员：".\Yii::$app->user->identity->username.",添加了一个商品id为:".$product_result.",商品名称为:".$item['name'].' 的商品';
                        $log_model = new Log();
                        $log_model->recordLog($content);
                        //新增商品实时同步到sphinx
                        $url = $this->channel_url.'/sphinx/insert-goods?goods_id='.$product_result;
                        CurlHelper::get($url, 'channel');
                        return $this->success('添加成功', '/goods/product');
                    }
                } else {
                    return $this->error('属性已经存在');
                }
            }

        }
        return $this->render('_save', ['id'=>$id,'list'=>$list,'item'=>$item]);
    }

    /**
    * 属性修改
    *
    * @return string
    */
    public function actionAttributeEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Product();
        $attr_model = new ProductAttr();
        $attribute_value_model = new AttributeValue();
        $attribute_model = new Attribute();
        $cond['product_id'] = $id;
        $attr_list = $attr_model->getList($cond, '*', 'id asc');
        $list = [];
        if (!empty($attr_list)) {
            foreach ($attr_list as $k => $v) {
                 $attr['id'] = $v['attr_name_id'];
                 $attr_list = $attribute_model->getInfo($attr);
                 $data['attr_id'] = $v['attr_name_id'];
                 $list[$k]['data'] = $attribute_value_model->getList($data);
                 $list[$k]['name'] = $attr_list['admin_name'];
                 $list[$k]['attr_id'] = $v['attr_value_id'];

            }
        }

        $item = $model->getInfo(array('id' => $id), true, 'origin_price,sale_price,shop_price,total_num,bar_code,attr_value,name,fixed_price');
        $attr_id = RequestHelper::post('attr_id');
        if (!empty($attr_id)) {
            $attr_value = RequestHelper::post('attr');
            $origin_price = RequestHelper::post('origin_price');
            $sale_price = RequestHelper::post('sale_price', 0);
            $shop_price = RequestHelper::post('shop_price');
            $total_num = RequestHelper::post('total_num');
            $bar_code = RequestHelper::post('bar_code');
            $number = mb_strlen($bar_code, 'utf8');
            $bar_cond['bar_code'] = $bar_code;
            $where = ['!=', 'id', $id];
            $bar_list = $model->getCount($bar_cond, $where);
            if (empty($cond_result)) {
                if (empty($origin_price)) {
                    return $this->error('进货价 不能为空');
                } elseif ($shop_price<0) {
                    return $this->error('铺货价 不能小于0');
                } elseif ($origin_price<$sale_price) {
                    return $this->error('建议售价 不能小于进货价');
                } elseif (empty($bar_code)) {
                    return $this->error('条形码 不能为空');
                } elseif (!is_numeric($bar_code)) {
                    return $this->error('条形码 必须是数字');
                } elseif ($number != 13) {
                    return $this->error('条形码 必须13位');
                } elseif ($bar_list!=0) {
                    return $this->error('条形码 已经存在');
                } else {
                    $attr_ids = [];
                    $attr_values = [];
                    foreach ($attr_value as $k => $v) {
                        $attr_data = $attribute_value_model->getInfo(array('id' => $v));
                        $attr_values[] = $attr_data['attr_value'];
                        $attr_ids[$k]['attr_name_id'] = $attr_data['attr_id'];
                        $attr_ids[$k]['attr_value_id'] = $attr_data['id'];
                    }
                    $data = [];
                    $data['origin_price'] = $origin_price;
                    $data['sale_price'] = $sale_price;
                    $data['shop_price'] = $shop_price;
                    $data['total_num'] = $total_num;
                    $data['bar_code'] = $bar_code;
                    $data['attr_value'] = implode('_', $attr_values);
                    $data_conf['id'] = $id;
                    $item_cond['name'] = $item['name'];
                    $item_cond['attr_value'] = $data['attr_value'];
                    $item_where = ['!=', 'id', $id];
                    $cond_result = $model->getCount($item_cond, $item_where);
                    if ($cond_result!=0) {
                        return $this->error('该属性已经存在');
                    } else {
                        if ($data['origin_price']!=$item['origin_price']) {
                            if ($item['fixed_price']==1) {
                                $shop_product_model = new ShopProduct();
                                $shop_product_model->updateInfo(['price' => $origin_price], ['product_id' => $id]);
                            }
                        }
                        $result = $model->updateInfo($data, $data_conf);
                        foreach ($attr_ids as $value) {
                             $data_item['attr_name_id'] = $value['attr_name_id'];
                             $data_item['attr_value_id'] = $value['attr_value_id'];
                             $data_where['product_id'] = $id;
                             $data_where['attr_name_id'] = $value['attr_name_id'];
                             $attr_model->updateInfo($data_item, $data_where);
                        }
                        if ($result == true) {
                            $shop_product_log_model = new ShopProductLog();
                            //记录日志
                            $array = array_diff($data, $item);
                            if (!empty($array)) {
                                $content = "管理员：" . \Yii::$app->user->identity->username . ",把商品id为:" . $id . ",商品名称为:" . $item['name'] . ' 的';
                                if (!empty($array['attr_value'])) {
                                    $content .= " 商品属性修改成".$array['attr_value'];
                                }
                                if (!empty($array['origin_price'])) {
                                    $content .= " 商品建议售价修改成".$array['origin_price'];
                                }
                                if (!empty($array['sale_price'])) {
                                    $content .= " 商品进货价修改成".$array['sale_price'];
                                }
                                if (!empty($array['shop_price'])) {
                                    $content .= " 商品铺货价修改成".$array['shop_price'];
                                }
                                if (!empty($array['total_num'])) {
                                    $content .= " 商品库存修改成".$array['total_num'];
                                }
                                if (!empty($array['bar_code'])) {
                                    $content .= " 商品条形码修改成".$array['bar_code'];
                                }
                                $log_model = new Log();
                                $log_model->recordLog($content);
                            }
                            $log_data = [];
                            if ($bar_code!=$item['bar_code']) {
                                $log_data['bar_code'] = $bar_code;
                            }
                            if (!empty($log_data)) {
                                $log_data['product_id'] = $id;
                                $result = $shop_product_log_model->getInfo(['product_id'=>$id]);
                                if (empty($result)) {
                                    $shop_product_log_model->insertInfo($log_data);
                                } else {
                                    $shop_product_log_model->updateInfo($log_data, ['product_id'=>$id]);
                                }
                            }
                            //修改商品实时同步到sphinx
                            $url = $this->channel_url.'/sphinx/sync-goods?goods_id='.$id;
                            CurlHelper::get($url, 'channel');
                            return $this->success('修改成功', '/goods/product/product-attribute?id='.$id);
                        }
                    }
                }
            }
        }
        return $this->render('_edit', ['id' => $id, 'list' => $list, 'item' => $item]);
    }
    /**
    * 详情页
    *
    * @return string
    */
    public function actionDetails()
    {
        $id = RequestHelper::get('id');
        $model = new Product();
        $cond['id'] = $id;
        $list = $model->getInfo($cond, '*');
        if (!empty($list)) {
            $list['description'] = htmlspecialchars_decode(stripslashes($list['description']));
            //分类名称
            $list['cate_name'] = '';
            if ($list['cate_first_id']) {
                $cate_model = new Category();
                $cate_cond['id'] = $list['cate_first_id'];
                $cate_list = $cate_model->getInfo($cate_cond, 'name');
                $list['cate_name'] = empty($cate_list) ? '' : $cate_list['name'];
            }
            //分类品牌
            $list['brand_name'] = '';
            if ($list['brand_id']) {
                $brand_model = new Brand();
                $brand_cond['status'] = 2;
                $brand_cond['id'] = $list['brand_id'];
                $brand_list = $brand_model->getInfo($brand_cond, 'name');
                $list['brand_name'] = empty($brand_list) ? '' : $brand_list['name'];
            }
            //开通城市
            //所属分公司
            $province_model = new Province();
            $province_list = $province_model->getInfo(array('id'=>$list['province_id']));
            $list['area_name'] = empty($province_list) ? '' : $province_list['name'];
            //图集
            $img_model = new ProductImage();
            $img_cond['product_id'] = $id;
            $list['atlas'] = $img_model->getList($img_cond, 'image', 'type desc,id desc');
            $list['description'] = empty($list['description']) ? '' : htmlspecialchars_decode($list['description']);
            $list['description'] = empty($list['description']) ? '' : stripslashes($list['description']);
            $list['attr_value'] = empty($list['attr_value']) ? '' : implode(' ', explode('_', $list['attr_value']));
        }
        return $this->render('details', ['list'=>$list]);
    }

    /**
    * 商品图集
    *
    * @return string
    */
    public function actionList()
    {
        $id =RequestHelper::get('id');
        $model = new ProductImage();
        $cond['product_id'] = $id;
        $list = $model->getList($cond, '*', 'type desc,id desc');
        $cond['type']= 1;
        $data = $model->getInfo($cond, 'id');
        return $this->render('list', ['list'=>$list,'data'=>$data]);
    }

    /**
     * 图片添加
     *
     * @return string
     */
    public function actionSave()
    {
        $model = new ProductImage();
        $id = RequestHelper::get('id');
        $ProductImage = RequestHelper::post('ProductImage');
        $file = UploadedFile::getInstance($model, 'image');
        $product_model = new Product();
        $item = $product_model->getInfo(['id'=>$id], true, 'name');
        if (!empty($ProductImage)) {
            if ($file) {
                $file_size = $file->size;//大小
                $file_type = $file->type;//类型
                $size = 1024 * 1024 * 1;
                if ($file_type != 'image/jpeg' and $file_type!='image/png') {
                    $model->addError('img', '品牌图片 仅支持JPG/PNG格式.');
                } elseif ($file_size > $size) {
                    $model->addError('img', '品牌图片 不能大于1m.');
                } else {
                    //上传图片
                    $data = array();
                    $fast = new FastDFSHelper();
                    $ret = $fast->fdfs_upload_name_size($file->tempName, $file->name);
                    $data['image'] = '/' . $ret['group_name'] . '/' . $ret['filename'];
                    $data['product_id'] = $id;
                    $data['status'] = 2;
                    $data['sort'] = 99;
                    $data['create_time'] = time();
                    $result = $model->getInsert($data);
                    if ($result > 0) {
                        //日志
                        $content = "管理员：".\Yii::$app->user->identity->username.",给商品id为".$id.",商品名称为".$item['name']." 的商品图集添加了一张图片,图片id为:".$result;
                        $log_model = new Log();
                        $log_model->recordLog($content);
                        $this->redirect('/goods/product/list?id=' . $id);
                    }
                }
            } else {
                $model->addError('image', '商品图片 不能为空');
            }
        }
        return $this->render('save', ['model'=>$model]);
    }

    /**
     * 删除商品
     *
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $ids = RequestHelper::post('ids');
        if (!empty($ids)) {
            $model = new Product();
            $result = $model->getDelete($ids);
            if ($result==200) {
                $code = 1;
            }
        }
        return $code;
    }

    /**
     * 删除图片
     *
     * @return int
     */
    public function actionDeleteImg()
    {
        $code = 0;
        $ids = RequestHelper::get('ids');
        if (!empty($ids)) {
            $model = new ProductImage();
            $list = $model->getInfo(['id'=>$ids]);
            $product_model = new Product();
            $item = $product_model->getInfo(['id'=>$list['product_id']]);
            $result = $model->getDelete($ids);
            if ($result==200) {
                //日志
                $content = "管理员：".\Yii::$app->user->identity->username.",删除了商品id为:".$list['product_id'].",商品名称为:".$item['name']." 的图集，图集id是:".$ids.",路径是:".$list['image'];
                $log_model = new Log();
                $log_model->recordLog($content);
                $code = 1;
            }
        }
        return $code;
    }

    /**
    * 设置主图
    *
    * @return int
    */
    public function actionUpdateImg()
    {
        $code = 0;
        $id = RequestHelper::get('id', 0);
        $old_id = RequestHelper::get('old_id', 0);
        if ($id!=0 and $old_id!=0) {
            $model = new ProductImage();
            $product_model = new Product();
            $list = $model->getInfo(array('id'=>$id));
            $product_id = $list['product_id'];
            $item = $product_model->getInfo(['id'=>$product_id], true, 'name');
            $image = $list['image'];
            $new_data = array();
            $old_data = array();
            $new_cond['id'] = $id;
            $old_cond['id'] = $old_id;
            $new_data['type'] = 1;
            $old_data['type'] = 0;
            $new_result = $model->updateInfo($new_data, $new_cond);
            $old_result = $model->updateInfo($old_data, $old_cond);
            if ($new_result==true and $old_result==true) {
                $data['image'] = $image;
                $product_model->updateInfo($data, array('id'=>$product_id));
                //日志
                $content = "管理员：".\Yii::$app->user->identity->username.",更改了商品id为:".$product_id.",商品名称为:".$item['name']." 的主图，主图id是:".$id.",路径是:".$list['image'];
                $log_model = new Log();
                $log_model->recordLog($content);
                $code = 1;
                //修改商品实时同步到sphinx
                $url = $this->channel_url.'/sphinx/sync-goods?goods_id='.$id;
                CurlHelper::get($url, 'channel');
            }
        }
        return $code;
    }

    /**
    * 批量更新 上下架 推荐
    *
    * @return int
    */
    public function actionAjaxUpdate()
    {
        $code = 0;
        $ids = RequestHelper::post('ids');
        $number = RequestHelper::post('number', 0);
        $shop_product_model = new ShopProduct();
        if (!empty($ids) and $number!=0) {
            $model = new Product();
            $cond['id'] = explode(',', $ids);
            $data = array();
            $shop_data = [];
            $list = [];
            if ($number==1) {
                $data['status'] = 1;
                $shop_data['status'] = 2;
                $list['name'] = '上下架';
                $list['status'] = '上架';
            }
            if ($number==2) {
                $data['status'] = 2;
                $shop_data['status'] = 3;
                $list['name'] = '上下架';
                $list['status'] = '下架';
            }
            if ($number==3) {
                $data['is_hot'] = 1;
                $list['name'] = '是否推荐';
                $list['status'] = '推荐';
            }
            $result = $model->updateInfo($data, $cond);
            if ($result==true) {
                //日志
                $content = "管理员：".\Yii::$app->user->identity->username.",修改了商品id集合为:".$ids." 商品的".$list['name']."状态,".$list['name']."状态变成了:".$list['status'];
                $log_model = new Log();
                $log_model->recordLog($content);
                if ($number!=3) {
                    $product_ids = explode(',', $ids);
                    $shop_product_model->updateInfo($shop_data, ['product_id'=>$product_ids]);
                }
                $code = 1;
            }

        }
        return $code;
    }

    /**
    * 判断条形码是否已经使用
    *
    * @return int
    */
    public function actionBarCode()
    {
        $result = 1;
        $code = RequestHelper::get('code');
        $product_id = RequestHelper::get('product_id');
        $array = explode(',', $code);
        if (count($array) != count(array_unique($array))) {
            $result = 2;
        } else {
            $model = new Product();
            $cond = [];
            $cond['bar_code'] = $array;
            if ($product_id) {
                $cond['id'] = $product_id;
            }
            $list = $model->getList($cond);
            if ($list) {
                $result = 0;
            }
        }
        echo json_encode($result);
    }

    /**
    * 修改 进货价 铺货价 建议售价
    * @return array
    */
    public function actionUpprice()
    {
        $ret = '';
        $sale_price = RequestHelper::post('sale_price', -1);
        $origin_price = RequestHelper::post('origin_price', -1);
        $shop_price = RequestHelper::post('shop_price', -1);
        $id = RequestHelper::post('id', -1);
        if ($id ==-1) {
            return $this->ajaxReturn(100, '参数错误');
        }
        $m_product = new Product();
        $list = $m_product->getInfo(['id'=>$id]);
        //进货价
        if ($sale_price !=-1) {
            if ($sale_price > $list['origin_price']) {
                return $this->ajaxReturn(100, '进货价不能大于建议售价');
            } else {
                $ret = $m_product->updateInfo(['sale_price' => $sale_price], ['id' => $id]);
            }
            //记录日志
            if ($sale_price != $list['sale_price']) {
                 $content = "管理员：".\Yii::$app->user->identity->username.",修改了商品id为:".$list['id'].",商品名称为:".$list['name'].' 商品的进货价,进货价变成了:'.$sale_price;
                $log_model = new Log();
                $log_model->recordLog($content);
            }
        }
        //建议售价
        if ($origin_price !=-1) {
            if ($origin_price < $list['sale_price']) {
                return $this->ajaxReturn(100, '建议售价不能小于进货价');
            } else {
                $ret = $m_product->updateInfo(['origin_price' => $origin_price], ['id' => $id]);
            }
            //记录日志
            if ($origin_price != $list['origin_price']) {
                $content = "管理员：".\Yii::$app->user->identity->username.",修改了商品id为:".$list['id'].",商品名称为:".$list['name'].' 商品的建议售价,建议售价变成了:'.$origin_price;
                $log_model = new Log();
                $log_model->recordLog($content);
                if ($list['fixed_price']==1) {
                    $shop_product_model = new ShopProduct();
                    $shop_product_model->updateInfo(['price' => $origin_price], ['product_id' => $id]);
                }
            }
        }
        //铺货价
        if ($shop_price !=-1) {
            if ($shop_price >= $list['sale_price'] and  $shop_price <= $list['origin_price'] or $shop_price==0) {
                $ret = $m_product->updateInfo(['shop_price'=>$shop_price], ['id'=>$id]);
            } else {
                return $this->ajaxReturn(100, '铺货价不能大于建议售价 不能小于进货价');
            }

            //记录日志
            if ($shop_price != $list['shop_price']) {
                $content = "管理员：".\Yii::$app->user->identity->username.",修改了商品id为:".$list['id'].",商品名称为:".$list['name'].' 商品的铺货价,普通价变成了:'.$shop_price;
                $log_model = new Log();
                $log_model->recordLog($content);
            }
        }
        if ($ret) {
            return $this->ajaxReturn(200, '成功');
        } else {
            return $this->ajaxReturn(101, '修改失败，请重试');
        }
    }

    /**
    * 进货价 铺货价 建议售价 比较
    * @return int
    */
    public function actionAjaxPrice()
    {
        $sale_price = RequestHelper::get('sale_price');
        $origin_price = RequestHelper::get('origin_price');
        $shop_price = RequestHelper::get('shop_price');
        if ($origin_price and $sale_price) {
            $origin_list = explode(',', $origin_price);
            $sale_list = explode(',', $sale_price);
            $shop_list = explode(',', $shop_price);
            $price_number = 1;
            foreach ($sale_list as $key => $value) {
                if (!is_numeric($value) or !is_numeric($origin_list[$key]) or !is_numeric($shop_list[$key])) {
                    $price_number *= 2;
                } else {
                    if ($value <= $origin_price[$key] or $shop_list[$key]>=$value and  $shop_list[$key] <=$origin_list[$key] or $shop_list[$key]==0) {
                        $price_number *= 1;
                    } else {
                        $price_number *= 0;
                    }
                }
            }
            if ($price_number==0) {
                $code = 0;
            } elseif ($price_number>1) {
                $code = 3;
            } else {
                $code = 1;
            }

        } else {
            $code =2;
        }
        return $code;
    }

    /**
     * 属性验证
     *
     * @param string $data       数组
     * @param null   $product_id 商品id
     *
     * @return array
     */
    public function attrValue($data, $product_id=null)
    {
        $result = [];
        $attr_value = array_filter($data['attr_value']);
        $origin_price = array_filter($data['origin_price']);
        $sale_price = array_filter($data['sale_price']);
        $shop_price  = array_filter($data['shop_price']);
        $total = array_filter($data['total']);
        $bar_code = array_filter($data['bar_code']);
        if (empty($attr_value) or count($data['attr_value'])!=count($attr_value)) {
            $result = ['code'=>101, 'msg'=>'属性 不能为空'];
        } elseif (empty($origin_price) or count($data['origin_price'])!=count($origin_price)) {
            $result = ['code'=>101, 'msg'=>'建议售价 不能为空'];
        } elseif (empty($sale_price) or count($data['sale_price'])!=count($sale_price)) {
            $result = ['code'=>101, 'msg'=>'进货价 不能为空'];
        } elseif (empty($shop_price) or count($data['shop_price'])!=count($shop_price)) {
            $result = ['code'=>101, 'msg'=>'铺货价 不能为空'];
        } elseif (empty($total) or count($data['total'])!=count($total)) {
            $result = ['code'=>101, 'msg'=>'库存 不能为空'];
        } elseif (empty($attr_value) or count($data['bar_code'])!=count($bar_code)) {
            $result = ['code'=>101, 'msg'=>'条形码 不能为空'];
        } else {
            if (count($data['attr_value']) != count(array_unique($data['attr_value']))) {
                $result = ['code'=>101, 'msg'=>'属性 不能重复'];
            } elseif (count($data['bar_code']) != count(array_unique($data['bar_code']))) {
                $result = ['code'=>101, 'msg'=>'条形码 不能重复'];
            } else {
                $code_number = 1;
                $price_number = 1;
                foreach ($data['sale_price'] as $k => $v) {
                    if (!is_numeric($data['bar_code'][$k]) and mb_strlen($data['bar_code'][$k], 'utf8')<13) {
                        $code_number *= 2;
                    } else {
                        $model = new Product();
                        $cond['bar_code'] = $data['bar_code'][$k];
                        $where = [];
                        if (!empty($product_id)) {
                            $where = ['!=', 'id', $product_id];
                        }
                        $count = $model->getCount($cond, $where);
                        if ($count==0) {
                            $code_number *= 0;
                        } else {
                            $code_number *= 1;
                        }
                    }
                    if (!is_numeric($v) or !is_numeric($data['origin_price'][$k]) or !is_numeric($data['shop_price'][$k])) {
                        $price_number *= 2;
                    } else {
                        if ($v <= $data['origin_price'][$k]  and  $data['shop_price'][$k] <=$data['origin_price'][$k] and ($data['shop_price'][$k]==0 or $data['shop_price'][$k]>=$v)) {
                            $price_number *= 0;
                        } else {
                            $price_number *= 1;
                        }
                    }
                }
                if ($price_number==0 and $code_number==0) {
                    $result = ['code'=>200, 'msg'=>'成功'];
                } else {
                    if ($price_number>1) {
                        $result = ['code'=>101, 'msg'=>'建议售价或进货价或铺货价 必须是数字!'];
                    } elseif ($price_number==1) {
                        $result = ['code'=>101, 'msg'=>'价格不合法'];
                    } elseif ($code_number>1) {
                        $result = ['code'=>101, 'msg'=>'条形码 不能小于13位的数字'];
                    } elseif ($code_number==1) {
                        $result = ['code'=>101, 'msg'=>'条形码 已经存在'];
                    }
                }
            }
        }
        return $result;
    }
    /**
     * 发布
     *
     * @return string
     */
    public function actionUpdateSingle()
    {
        $model = new Product();
        $id = RequestHelper::get('product_id', 0);
        $array = ['code'=>101, 'msg'=>'缺少参数'];
        if ($id>0) {
            $cond['id'] = explode(',', $id);
            $cond['single'] = 1;
            $number = $model->getCount($cond);
            if ($number==0) {
                $array = ['code'=>102, 'msg'=>'产品不存在'];
            } else {
                $data['single'] = 2;
                $data['status'] = 2;
                $result = $model->updateInfo($data, $cond);
                if ($result==true) {
                    $array = ['code'=>200, 'msg'=>'发布成功'];
                } else {
                    $array = ['code'=>103, 'msg'=>'系统繁忙'];
                }
            }
        }
        return json_encode($array);
    }
}

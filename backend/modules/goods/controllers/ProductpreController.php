<?php
/**
 * 待发布商品管理
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
use backend\models\i500m\Brand;
use backend\models\i500m\BrandCategory;
use backend\models\i500m\Category;
use backend\models\i500m\CrmBranch;
use backend\models\i500m\CrmConfig;
use backend\models\i500m\Log;
use backend\models\i500m\Product;
use backend\models\i500m\ProductImage;
use backend\models\i500m\Province;
use backend\models\shop\ShopProduct;
use backend\models\shop\ShopProductLog;
use common\helpers\CommonHelper;
use common\helpers\CurlHelper;
use common\helpers\FastDFSHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
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
class ProductpreController extends BaseController
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
     * 待发布商品首页
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
        $cond['single'] = 2;
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
        $cate_second_data = [];
        //分类搜索
        if (!empty($search['cate_id'])) {
            $cond['cate_first_id'] = $search['cate_id'];
            //二级分类
            $cate_second_cond['parent_id'] = $search['cate_id'];
            $cate_second_cond['status'] = 2;
            $cate_second_cond['type'] = 0;
            $cate_second_cond['level'] = 2;
            $cate_second_data = $cate_model->getList($cate_second_cond, 'id,name', 'id desc');
        }
        if (!empty($search['cate_second_id'])) {
            $cond['cate_second_id'] = $search['cate_second_id'];
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
                //二级分类
                $cate_second_item = $cate_model->getInfo(['id'=>$value['cate_second_id']], true, 'name');
                $data[$key]['cate_second_name'] = empty($cate_second_item) ? "--" : $cate_second_item['name'];
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
            'total'=>$total,
            'cate_second_data' => $cate_second_data
        ];
        return $this->render('index', $param);
    }
    /**
     * 待发布商品添加
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
        $brand_cate = new BrandCategory();
        $cate_cond['level'] = 1;
        $cate_cond['type'] = 0;
        $cate_cond['status'] = 2;
        $cate_list = $cate_model->getList($cate_cond, 'id,name', 'id desc');
        $cate_data = [];
        if ($cate_list) {
            foreach ($cate_list as $k => $v) {
                $cate_data[0]['id'] = '';
                $cate_data[0]['name'] = '选择顶级分类';
                $cate_data[$k+1] = $v;
            }
        }
        $cate_list[0] = array('id'=>'0','name'=>'选择顶级分类');
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
        $brand_model = new Brand();
        if (!empty($product)) {
            $model->attributes = $product;
            if ($model['cate_first_id']!=0) {
                $category_model = new BrandCategory();
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
            $product['single'] = 2;//待发布商品
            $product['create_time'] = time();//添加时间
            $result = $model->getInfo(['name'=>$product['name']]);//商品名称是否存在
            $product['description'] = empty($product['description']) ? '' : htmlspecialchars_decode($product['description']);
            $product['description'] = empty($product['description']) ? '' : stripslashes($product['description']);
            $model['description'] = $product['description'];
            $model['brand_id'] = $product['brand_name'];
            $attr_result = $this->attrValue($products);
            if (!empty($result)) {
                $model->addError('name', '商品名称 不能重复');
            } elseif ($product['cate_first_id']=="") {
                $model->addError('cate_first_id', '商品分类 不能为空');
            } elseif ($product['brand_name']=="") {
                \Yii::$app->getSession()->setFlash('brand_name', '商品品牌 不能为空');
            } elseif ($attr_result['code'] != 200) {
                \Yii::$app->getSession()->setFlash('attr_value', $attr_result['msg']);
            } elseif (count($file['name']) != count(array_filter($file['name']))) {
                \Yii::$app->getSession()->setFlash('attr_value', '主图 不能为空');
            } elseif (empty($product['description'])) {
                \Yii::$app->getSession()->setFlash('description', '商品详情 不能为空');
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
                    $list_category = $brand_cate->getList(['category_id'=>$product['cate_first_id']], 'brand_id');
                    $cond_brand['id'] = empty($list_category) ? [0] : ArrayHelper::getColumn($list_category, 'brand_id');
                    $cond_brand['name'] = $product['brand_name'];
                    $item_brand = $brand_model->getInfo($cond_brand, true, 'id', 'id desc');
                    if (!empty($item_brand)) {
                        $product['brand_id'] = $item_brand['id'];
                    } else {
                        $brand_list['name'] = $product['brand_name'];
                        $brand_list['status'] = 2;
                        $brand_list['description'] = $product['brand_name'];
                        $brand_list['sort'] = 99;
                        $brand_list['add_time'] = date('Y-m-d H:i:s');
                        $product['brand_id'] = $brand_model->getInsert($brand_list);
                        if ($product['brand_id'] != false) {
                            //日志
                            $content = "管理员：".\Yii::$app->user->identity->username.",增加了品牌id为:".$product['brand_id'].",品牌名称为:".$brand_list['name'];
                            $log_model = new Log();
                            $log_model->recordLog($content);
                            $cate['brand_id'] = $product['brand_id'];
                            $cate['category_id'] = $product['cate_first_id'];
                            $cate['add_time'] = date('Y-m-d H:i:s');
                            $brand_cate->insertInfo($cate);
                        }
                    }
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
                    ArrayHelper::remove($product, 'brand_name');
                    //毛利率
                    if ($product['origin_price']>0) {
                        $product['sale_profit_margin'] = round(($product['origin_price'] - $product['sale_price']) / $product['origin_price'] * 100, 2) . '%';
                        $product['shop_profit_margin'] = round(($product['origin_price'] - $product['shop_price']) / $product['origin_price'] * 100, 2) . '%';
                    } else {
                        $product['sale_profit_margin'] = '0%';
                        $product['shop_profit_margin'] = '0%';
                    }
                    $list = $model->getInsert($product);
                    if ($list > 0) {
                        //记录日志
                        $content = "管理员：".\Yii::$app->user->identity->username.",添加了一个商品id为:".$list.",商品名称为:".$product['name'].' 的商品';
                        $log_model = new Log();
                        $log_model->recordLog($content);
                        $img_model = new ProductImage();
                        //新增商品实时同步到sphinx
                        $url = $this->channel_url.'/sync/sync-goods?goods_id='.$list;
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
                    $this->redirect('/goods/productpre');
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
            'product'=>$product,
            'products'=>$products
        );
        return $this->render('add', $param);
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
                 $cate_list[0]['name'] = '选择顶级分类';
                 $cate_list[$k+1] = $v;


            }
        }
        //商品二级分类
        $cate_second_cond['level'] = 2;
        $cate_second_cond['status'] = 2;
        $cate_second_cond['type'] = 0;
        $cate_second_cond['parent_id'] = $item['cate_first_id'];
        $cate_second_data = $cate_model->getList($cate_second_cond, 'id,name', 'id desc');
        if ($cate_second_data) {
            foreach ($cate_second_data as $k => $v) {
                $cate_second_data[0]['id'] = '';
                $cate_second_data[0]['name'] = '选择二级分类';
                $cate_second_data[$k+1] = $v;


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
            $brand = $brand_model->getList($cond, 'id,name', 'id desc');
            $brand_list = ArrayHelper::getColumn($brand, 'name');
        }
        $brand_data = $brand_model->getInfo(['id'=>$item['brand_id']]);
        $item['brand_name'] = empty($brand_data) ? '' : $brand_data['name'];
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
        $model['cate_second_id'] = $item['cate_second_id'];
        $product = RequestHelper::post('Product');
        $log_model = new Log();
        $img_model = new ProductImage();
        $products = RequestHelper::post('Products');
        if (!empty($product)) {
            $file = $_FILES['image'];
            $attr_result = $this->attrValue($products, $id);
            $model->attributes = $product;
            if ($product['cate_first_id']=="") {
                $model->addError('cate_first_id', '商品分类 不能为空');
            } elseif ($product['brand_name']=="") {
                \Yii::$app->getSession()->setFlash('brand_name', '商品品牌 不能为空');
            } elseif ($attr_result['code']!=200) {
                \Yii::$app->getSession()->setFlash('attr_value', $attr_result['msg']);
            } elseif (count($file['name']) - count(array_filter($file['name'])) > 1) {
                \Yii::$app->getSession()->setFlash('attr_value', '主图 不能为空');
            } elseif (empty($product['description'])) {
                \Yii::$app->getSession()->setFlash('description', '商品详情 不能为空');
            } else {
                $cond_brand['name'] = $product['brand_name'];
                foreach ($products['attr_value'] as $k => $v) {
                    $list_category = $category_model->getList(['category_id'=>$product['cate_first_id']], 'brand_id');
                    $cond_brand['id'] = empty($list_category) ? [0] : ArrayHelper::getColumn($list_category, 'brand_id');
                    $item_brand = $brand_model->getInfo($cond_brand, true, 'id', 'id desc');
                    if (!empty($item_brand)) {
                        $product['brand_id'] = $item_brand['id'];
                    } else {
                        $list_brand['name'] = $product['brand_name'];
                        $list_brand['status'] = 2;
                        $list_brand['description'] = $product['brand_name'];
                        $list_brand['sort'] = 99;
                        $list_brand['add_time'] = date('Y-m-d H:i:s');
                        $product['brand_id'] = $brand_model->getInsert($list_brand);
                        if ($product['brand_id'] != false) {
                            //日志
                            $content = "管理员：".\Yii::$app->user->identity->username.",增加了品牌id为:".$product['brand_id'].",品牌名称为:".$list_brand['name'];
                            $log_model = new Log();
                            $log_model->recordLog($content);
                            $cate['brand_id'] = $product['brand_id'];
                            $cate['category_id'] = $product['cate_first_id'];
                            $cate['add_time'] = date('Y-m-d H:i:s');
                            $category_model->insertInfo($cate);
                        }
                    }
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
                    ArrayHelper::remove($product, 'brand_name');
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
                        //毛利率
                        if ($product['origin_price']>0) {
                            $product['sale_profit_margin'] = round(($product['origin_price'] - $product['sale_price']) / $product['origin_price'] * 100, 2) . '%';
                            $product['shop_profit_margin'] = round(($product['origin_price'] - $product['shop_price']) / $product['origin_price'] * 100, 2) . '%';
                        } else {
                            $product['sale_profit_margin'] = '0%';
                            $product['shop_profit_margin'] = '0%';
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
                            $url = $this->channel_url . '/sync/sync-goods?goods_id=' . $id;
                            CurlHelper::get($url, 'channel');
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
                        $product['single'] = 2;
                        $product['create_time'] = date("Y-m-d H:i:s");
                        //毛利率
                        $product['sale_profit_margin'] = round(($product['origin_price']-$product['sale_price'])/$product['origin_price']*100, 2).'%';
                        $product['shop_profit_margin'] = round(($product['origin_price']-$product['shop_price'])/$product['origin_price']*100, 2).'%';
                        $result = $model->getInsert($product);
                        if ($result > 0) {
                            //记录日志
                            $content = "管理员：".\Yii::$app->user->identity->username.",添加了一个商品id为:".$result.",商品名称为:".$product['name'].' 的商品';
                            $log_model->recordLog($content);
                            //新增商品实时同步到sphinx
                            $url = $this->channel_url.'/sync/sync-goods?goods_id='.$result;
                            CurlHelper::get($url, 'channel');
                            //主图添加
                            $img_data = array();
                            $img_data['image'] = $product['image'];
                            $img_data['product_id'] = $result;
                            $img_data['status'] = 2;
                            $img_data['sort'] = 99;
                            $img_data['create_time'] = time();
                            $img_data['type'] = 1;
                            $img_model->getBulkInsert($img_data);
                        }
                    }
                    if ($result>0) {
                        $this->redirect('/goods/productpre');
                    } else {
                        $this->redirect('/goods/productpre');
                    }
                }
            }
        }
        $list = array(
            'model'=>$model,
            'item'=>$item,
            'cate_list'=>$cate_list,
            'brand_list'=>json_encode($brand_list),
            'city_list'=>$city_data,
            'bc_id' =>$bc_id,
            'branch_id'=>$branch_id,
            'cate_second_list' => $cate_second_data,
            'products' => $products
        );
        return $this->render('edit', $list);
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
            $cate_model = new Category();
            if ($list['cate_first_id']) {

                $cate_cond['id'] = $list['cate_first_id'];
                $cate_list = $cate_model->getInfo($cate_cond, 'name');
                $list['cate_name'] = empty($cate_list) ? '' : $cate_list['name'];
            }
            //二级分类名称
            $list['cate_second_name'] = '';
            if ($list['cate_second_id']) {
                $cate_second_cond['id'] = $list['cate_second_id'];
                $cate_second_list = $cate_model->getInfo($cate_second_cond, 'name');
                $list['cate_second_name'] = empty($cate_second_list) ? '' : $cate_second_list['name'];
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
                        $this->redirect('/goods/productpre/list?id=' . $id);
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
                $url = $this->channel_url.'/sync/sync-goods?goods_id='.$id;
                CurlHelper::get($url, 'channel');
            }
        }
        return $code;
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
            $cond['single'] = 2;
            $number = $model->getCount($cond);
            if ($number==0) {
                $array = ['code'=>102, 'msg'=>'产品不存在'];
            } else {
                $data['single'] = 1;
                $data['status'] = 1;
                $result = $model->updateInfo($data, $cond);
                if ($result==true) {
                    //修改商品实时同步到sphinx
                    $url = $this->channel_url.'/sync/batch-goods?goods_id='.$id.'&type=3';
                    CurlHelper::get($url, 'channel');
                    $array = ['code'=>200, 'msg'=>'发布成功'];
                } else {
                    $array = ['code'=>103, 'msg'=>'系统繁忙'];
                }
            }
        }
        return json_encode($array);
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
        $total = [];
        foreach ($data['total'] as $value) {
            if ($value!="") {
                $total[] = $value;
            }
        }
        $bar_code = array_filter($data['bar_code']);
        if (empty($attr_value) or count($data['attr_value'])!=count($attr_value)) {
            $result = ['code'=>101, 'msg'=>'属性 不能为空'];
        } elseif (empty($origin_price) or count($data['origin_price'])!=count($origin_price)) {
            $result = ['code'=>101, 'msg'=>'建议售价 不能为空'];
        } elseif (empty($sale_price) or count($data['sale_price'])!=count($sale_price)) {
            $result = ['code'=>101, 'msg'=>'进货价 不能为空'];
        } elseif (empty($shop_price) or count($data['shop_price'])!=count($shop_price)) {
            $result = ['code'=>101, 'msg'=>'铺货价 不能为空'];
        } elseif (count($data['total'])!=count($total)) {
            echo count($data['total']);
            echo 123;
            echo count($total);
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
                    if (!is_numeric($data['bar_code'][$k]) or mb_strlen($data['bar_code'][$k], 'utf8')<13) {
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
}

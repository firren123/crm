<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  ContractController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/24 下午1:34
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\modules\iyangpin\controllers;
use backend\controllers\BaseController;
use backend\models\i500m\UploadFrom;
use backend\models\i500m\YpContract;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * Class ContractController
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ContractController extends BaseController
{
    //行业
    public $industry_id_data = [
        0 => '旅游',
        1 => '美食',
        2 => '服饰',
        3 => '特产礼品',
        4 => '食品快消',
        5 => '数码',
        6 => '休闲娱乐',
        7 => '日化',
        8 => '母婴',
        9 => '美容健身',
        10=> '酒店',
        11=> '其他'
    ];
    public $qualification_data = [
        0 => '营业执照（生产厂家和经销商)',
        1 => '卫生许可证',
        2 => '生产许可证',
        3 => '生产委托书',
        4 => '食品流通许可证',
        5 => '产品检测报告',
        6 => '商品流通许可证',
        7 => '特许经营许可证',
        8 => '合格证',
        9 => '法人身份证',
        10 => '权威认证'

    ];
    /**
     * 简介：合同列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $model = new YpContract();
        $page = RequestHelper::get('page', 1, 'intval');
        $shop_name = RequestHelper::get('shop_name');
        $where = array();
        
        $where['status'] = array(0, 1, 2);

        $count = $model->getListCount($where);
        $list = $model->getPageList($where, "*", 'id desc ', $page, $this->size);
        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        return $this->render(
            'index',
            [
                'pages' => $pages,
                'title' => '添加合同',
                'list' => $list,
                'shop_name' => $shop_name,
                'industry_id_data'=>$this->industry_id_data,
                'qualification_data'=>$this->qualification_data
            ]
        );
    }

    /**
     * 简介：合同列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAdd()
    {
        $model = new YpContract();
        $post = RequestHelper::post('YpContract', array());
        if ($post) {
            $post['qualification'] = implode(',', $post['qualification']);
            $uploadFrom = new UploadFrom();
            $uploadFrom->product_img = UploadedFile::getInstances($model, 'product_img');
            $uploadFrom->product_logo_img = UploadedFile::getInstances($model, 'product_logo_img');
            $uploadFrom->shop_logo_img = UploadedFile::getInstances($model, 'shop_logo_img');
            $uploadFrom->brand_logo = UploadedFile::getInstances($model, 'brand_logo');
            $product_img = $uploadFrom->upload('product_img');
            if (!$product_img) {
                $model->addError('product_img', '商品图片上传失败');
            }
            $post['product_img'] = $product_img;
            $product_logo_img = $uploadFrom->upload('product_logo_img');
            if (!$model->upload('product_logo_img')) {
                $model->addError('product_logo_img', '图片上传失败');
            }
            $post['product_logo_img'] = $product_logo_img;
            $hop_logo_img = $uploadFrom->upload('product_img');
            if (!$model->upload('shop_logo_img')) {
                $model->addError('shop_logo_img', '图片上传失败');
            }
            $post['shop_logo_img'] = $hop_logo_img;
            $brand_logo = $uploadFrom->upload('brand_logo');
            if (!$model->upload('brand_logo')) {
                $model->addError('brand_logo', '图片上传失败');
            }
            $post['brand_logo'] = $brand_logo;
            $post['time'] = date('Y-m-d H:i:s');
            $model->attributes = $post;
            if ($model->save()) {
                return $this->success('添加成功');
            } else {
                return $this->error('添加失败');
            }
        }
        return $this->render(
            'add',
            [
                'title' => '添加合同',
                'model' => $model,
                'industry_id_data'=>$this->industry_id_data,
                'qualification_data'=>$this->qualification_data
            ]
        );
    }

    /**
     * 简介：合同列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEdit()
    {
        $model2 = new YpContract();
        $post = RequestHelper::post('YpContract', array());
        $id = RequestHelper::get('id', 0, 'intval');
        $model = $model2->findOne($id);
        $model->qualification = explode(',', $model->qualification);
        if ($post) {
            $post['qualification'] = implode(',', $post['qualification']);
            $uploadFrom = new UploadFrom();
            $uploadFrom->product_img = UploadedFile::getInstances($model, 'product_img');
            $uploadFrom->product_logo_img = UploadedFile::getInstances($model, 'product_logo_img');
            $uploadFrom->shop_logo_img = UploadedFile::getInstances($model, 'shop_logo_img');
            $uploadFrom->brand_logo = UploadedFile::getInstances($model, 'brand_logo');
            $product_img = $uploadFrom->upload('product_img');
            if (!$product_img) {
                $model->addError('product_img', '商品图片上传失败');
            }
            $post['product_img'] = $product_img;
            $product_logo_img = $uploadFrom->upload('product_logo_img');
            if (!$model->upload('product_logo_img')) {
                $model->addError('product_logo_img', '图片上传失败');
            }
            $post['product_logo_img'] = $product_logo_img;
            $hop_logo_img = $uploadFrom->upload('product_img');
            if (!$model->upload('shop_logo_img')) {
                $model->addError('shop_logo_img', '图片上传失败');
            }
            $post['shop_logo_img'] = $hop_logo_img;
            $brand_logo = $uploadFrom->upload('brand_logo');
            if (!$model->upload('brand_logo')) {
                $model->addError('brand_logo', '图片上传失败');
            }
            $post['brand_logo'] = $brand_logo;
            $model->attributes = $post;
            if ($model->save()) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
        return $this->render(
            'add',
            [
                'title' => '修改合同',
                'model' => $model,
                'industry_id_data'=>$this->industry_id_data,
                'qualification_data'=>$this->qualification_data
            ]
        );
    }

    public function actionView()
    {
        $model2 = new YpContract();
        $post = RequestHelper::post('YpContract', array());
        $id = RequestHelper::get('id', 0, 'intval');
        $model = $model2->findOne($id);
        $model->qualification = explode(',', $model->qualification);
        return $this->render(
            'view',
            [
                'title' => '查看合同',
                'model' => $model,
                'industry_id_data'=>$this->industry_id_data,
                'qualification_data'=>$this->qualification_data
            ]
        );
    }
    /**
     * 简介：合同列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionDel()
    {

    }
}
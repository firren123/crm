<?php
/**
 *
 * @category  CRM
 * @package   LinkController
 * @author    youyong <youyong@iyangpin.com>
 * @time      2015/4/17 18:01
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://admin.com
 * @link      youyong@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\models\i500m\Branch;
use backend\models\i500m\City;
use backend\models\i500m\Link;
use backend\models\i500m\Province;
use backend\models\shop\ShopConfig;
use common\helpers\FastDFSHelper;
use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * Class LinkController
 * @category  PHP
 * @package   LinkController
 * @author    youyong <youyong@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class LinkController extends BaseController
{
    /**
     * 意见反馈列表
     *
     * Author youyoing@iyangpin.com
     *
     * @param： int $page     页码
     *
     * @return int 返回值说明
     */
    public function actionIndex()
    {
        $cond['status'] = [0, 2];
        $title = RequestHelper::get('title');
        $and_where = [];
        if (!empty($title)) {
            //$cond .= " and title LIKE '%" . $title . "%' ";
            $and_where = ['like', 'title', $title];
        }

        $order = 'id desc';
        $page = RequestHelper::get('page', 1);
        $pageSize = $this->size;
        $model = new Link();
        $list = $model->getPageList($cond, '*', $order, $page, $pageSize, $and_where);
        $data['is_number'] = 1;
        $total = $model->getCount($cond, $and_where);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        return $this->render('index', ['list' => $list, 'pages' => $pages, 'title' => $title]);
    }


    /**
     * 友情链接添加
     *
     * @return string
     */
    public function actionAdd()
    {
        $model = new Link();
        $model->status = 2;
        $link = RequestHelper::post('Link');
        if (!empty($link)) {
            $model->attributes = $link;
            $list = $model->getDetailsByName($link['title']);
            //var_dump($list);exit;
            $file = UploadedFile::getInstance($model, 'images');
            $result = 0;
            if (!empty($list)) {
                $model->addError('title', '站点名称 不能重复');
            } else {
                if ($file) {
                    $file_size = $file->size;//大小
                    $file_type = $file->type;//类型
                    $size = 1024 * 1024 * 1;
                    if ($file_type != 'image/jpeg') {
                        $model->addError('images', '品牌图片 仅支持JPG/PNG格式.');
                    } elseif ($file_size > $size) {
                        $model->addError('images', '品牌图片 不能大于1m.');
                    } else {
                        //上传图片
                        $fdfs = new FastDFSHelper();
                        $ret = $fdfs->fdfs_upload_name_size($file->tempName, $file->name);
                        $link['images'] = '/' . $ret['group_name'] . '/' . $ret['filename'];
                        $link['create_time'] = time();
                        $result = $model->getInsert($link);
                    }
                }
                if ($result != false) {
                    $this->redirect('/admin/link');
                }
            }
        }
        return $this->render('add', ['model' => $model]);
    }


    /**
     * 友情链接修改
     *
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Link();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, true, '*');
        $model->attributes = $item;
        $link = RequestHelper::post('Link');
        if (!empty($link)) {
            $model->attributes = $link;
            $list = $model->getDetailsByName($link['title'], $id);
            $fdfs = new FastDFSHelper();
            $image = $fdfs->fdfs_uplthis->oad("images");
            $image_url = '/' . $image['group_name'] . '/' . $image['filename'];
            //var_dump($image_url);exit;
            // var_dump($_FILES);exit;
            if (empty($list)) {
                $link['images'] = $image_url;
                if (!$_FILES['images']['tmp_name']) {
                    $link['images'] = $item['images'];
                }
                $link['update_time'] = time();
                //var_dump($link);exit;
                $result = $model->updateInfo($link, $cond);
                if ($result == true) {
                    $this->redirect('/admin/link');
                }
            } else {
                $model->addError('title', '站点名称 不能重复');
            }
        }
        return $this->render('edit', ['model' => $model]);
    }

    /**
     * 简介：友情链接删除
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $model = new Link();
            $result = $model->getDelete($id);
            if ($result == 200) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }

    /**
     * 简介：
     * @return int
     */
    public function actionAjaxDelete()
    {
        $code = 0;
        $ids = RequestHelper::post('ids');
        $model = new Link();
        $result = $model->getBatchDelete($ids);
        if ($result == 200) {
            $code = 1;
        }
        return $code;
    }

    /**
     * 增添/修改网站配置页面
     *
     * Author songjiankang@iyangpin.com
     *
     * @return string 返回值说明
     */
    public function actionWeb()
    {
        $type = RequestHelper::get('type');
        $id = RequestHelper::get('id', 0, 'intval');
        $model_branch = new Branch();
        $city = $model_branch->getPageList(array('status' => 1));
        if ($id) {
            $cond = array('id' => $id);
            $model = new ShopConfig();
            if (1 == $type) {
                $one_info = $model->getInfo(array('id' => $id), true, 'bc_id');
                $all_info = $model_branch->getList(array('status' => 1));
                foreach ($all_info as $key => $value) {
                    $city_id_arr = explode(',', $value['city_id_arr']);
                    if (in_array($one_info['bc_id'], $city_id_arr)) {
                        $branch = $value;
                        break;
                    }
                }
                $model_city = new City();
                $city_name = $model_city->city_one($one_info['bc_id']);
                $city_arr = explode(',', $branch['city_id_arr']);
                $city_name_arr = array();
                foreach ($city_arr as $value) {
                    $city_info = $model_city->city_one($value);
                    array_push($city_name_arr, $city_info);
                }
                $info = $model->getInfo($cond);

                return $this->render('web', ['info' => $info, 'city' => $city, 'city_name' => $city_name, 'branch' => $branch, 'city_name_arr' => $city_name_arr, 'type' => $type]);
            } else {

                $info = $model->getInfo($cond);
                return $this->render('web', ['info' => $info, 'city' => $city, 'city_name' => '', 'branch' => '', 'city_name_arr' => '', 'type' => $type]);

            }

        } else {
            $info = array(
                'id' => 0,
                'free_shipping' => '',
                'send_price' => '',
                'freight' => '',
                'bc_id' => 0,
                'community_num' => '',
                'price_limit' => 1
            );
            $branch = array('id' => 0);
            $city_name = array('id' => 0, 'name' => '请选择');
            return $this->render('web', ['info' => $info, 'city' => $city, 'city_name' => $city_name, 'branch' => $branch, 'city_name_arr' => array('id' => 0), 'type' => $type]);
        }
    }

    /**
     * 增添/修改网站配置功能
     *
     * Author songjiankang@iyangpin.com
     *
     * @return null
     */
    public function actionAddweb()
    {
        $info = RequestHelper::post('info');
        $type = RequestHelper::post('type');
        $model = new ShopConfig();
        if ($info['id']) {
            $where_arr = array(
                'id' => $info['id']
            );
            $set_arr = $info;
            $result = $model->updateOneRecord($where_arr, '', $set_arr);
            if (1 == $result['result']) {
                if ($type == 2) {
                    return $this->redirect('userweb');
                } else {
                    return $this->redirect('indexweb');
                }

            } else {
                return $this->error('修改失败', '/admin/link/indexweb');
            }
        } else {
            if (2 == $type) {
                $info = array(
                    'free_shipping' => $info['free_shipping'],
                    'send_price' => $info['send_price'],
                    'freight' => $info['freight'],
                    'bc_id' => 0,
                    'community_num' => 0,
                    'price_limit' => 0,
                    'type' => $type
                );
                $model->add($info);
                return $this->redirect('userweb');
            }
            $info['type'] = $type;
            $model->add($info);
            return $this->redirect('indexweb');
        }
    }

    /**
     * 商户网站配置首页
     *
     * Author songjiankang@iyangpin.com
     *
     * @return string 返回值说明
     */
    public function actionIndexweb()
    {

        $title = RequestHelper::get('title');
        $model = new ShopConfig();
        $city_model = new City();
        $city_info = $city_model->all_city();
        $city_list = array();
        $page = RequestHelper::get('page', 1);
        $order = 'id desc';
        $pageSize = $this->size;
        foreach ($city_info as $key => $value) {
            $city_list[$value['id']] = $value['name'];
        }
        $cond = '';
        $conf = 'TYPE = 1';
        if (!empty($title)) {
            $pos = (strpos($title, "'"));
            if (false === $pos) {
                $cond .= "name LIKE '%" . $title . "%' ";
                $city_all = $city_model->getList($cond);
                if (!empty($city_all)) {
                    $city_all_str = '';
                    foreach ($city_all as $key => $value) {
                        $city_all_str .= $value['id'] . ',';
                    }
                    $city_all_str = rtrim($city_all_str, ',');
                    $conf .= " and bc_id in ($city_all_str)";
                    $info = $model->getPageList($conf, '*', $order, $page, $pageSize);
                    if (!empty($info)) {
                        $conf = "type=1 and bc_id in ($city_all_str)";
                    } else {
                        $conf = '1=2';
                    }
                } else {
                    $conf = '1=2';
                }
            } else {
                $conf = '1=2';
            }
        } else {
            $conf .= ' and 1=1';
        }
        $info = $model->getPageList($conf, '*', $order, $page, $pageSize);
        $total = $model->getCount($conf);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        return $this->render('indexweb', ['info' => $info, 'pages' => $pages, 'city_list' => $city_list, 'type' => 1]);
    }

    /**
     * Ajax获取分公司对应的城市
     * Author songjiankang@iyangpin.com
     * @return json 返回值说明
     */
    public function actionAjax()
    {
        $id = RequestHelper::post('id');
        $model_branch = new Branch();
        $data = $model_branch->getInfo(array('status' => 1, 'id' => $id), true, 'city_id_arr');
        $city = $data['city_id_arr'];
        $city_name_arr = array();
        if (!empty($city)) {
            $city_arr = explode(',', $city);
            foreach ($city_arr as $value) {
                $model_city = new City();
                $city_name = $model_city->city_one($value);
                $city_name_arr[$city_name['id']] = $city_name['name'];
            }
            echo json_encode($city_name_arr);
        } else {
            echo json_encode($city_name_arr);
        }
    }

    /**
     * 删除网站配置
     *
     * Author songjiankang@iyangpin.com
     *
     * @return string 返回值说明
     */
    public function actionDelweb()
    {
        $id = RequestHelper::post('id');
        $arr_id = explode(',', $id);
        foreach ($arr_id as $value) {
            $model = new ShopConfig();
            $where_arr = array('id' => $value);
            $result = $model->delOneRecord($where_arr);
        }
        echo json_encode(array('status' => 1));
    }

    /**
     * 简介：
     * @return string 返回值说明
     */
    public function actionCheck()
    {
        $bc_id = RequestHelper::post('bc_id');
        $id = RequestHelper::post('id');
        $model_config = new ShopConfig();
        $list = $model_config->check($bc_id, $id);
        if (!empty($list)) {
            echo json_encode(array('status' => 0, 'message' => '配置已存在'));
        } else {
            echo json_encode(array('status' => 1));
        }

    }

    /**
     * 用户网站配置首页
     *
     * Author songjiankang@iyangpin.com
     *
     * @return string 返回值说明
     */
    public function actionUserweb()
    {
        $cond = 'type = 2';
        $model = new ShopConfig();
        $info = $model->getPageList($cond);
        $count = $model->getCount($cond);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        return $this->render('indexweb', ['info' => $info, 'pages' => $pages, 'type' => 2, 'city_list' => array('0' => '')]);
    }
}


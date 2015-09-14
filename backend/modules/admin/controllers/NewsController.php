<?php
/**
 * 新500m后台-资讯管理
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   NEWS
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015-04-17 11:38
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace backend\modules\admin\controllers;

use Yii;
use common\helpers\CommonHelper;
use common\helpers\RequestHelper;
use backend\models\i500m\News;
use backend\models\i500m\NewsCategory;
use backend\models\i500m\CategoryTree;
use backend\controllers\BaseController;
use yii\data\Pagination;

/**
 * NEWS
 *
 * @category ADMIN
 * @package  NEWS
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class NewsController extends BaseController
{
    public $news_model;
    public $news_category_model;
    public $PAGE_SIZE = '10';
    /**
     * 初始化对象
     * @return object
     */
    public function init()
    {
        parent::init();
        $this->news_model          = new News();
        $this->news_category_model = new NewsCategory();
    }
    /**
     * 资讯列表
     * @return string
     */
    public function actionIndex()
    {
        $data  = [];
        $where = [];
        $where['is_deleted'] = '1';
        $title = RequestHelper::get('title', '', 'htmlspecialchars');
        if (!empty($title)) {
            $and_where = ['like', 'title', $title];
            $data['title']  = $title;
        } else {
            $and_where = '';
        }
        $category_id = RequestHelper::get('category_id', '', 'intval');
        if (!empty($category_id)) {
            $where['category_id'] = $category_id;
            $data['category_id']  = $category_id;
        }
        // 分公司ID
        if ($this->is_head_company!=1) {  //不是总公司
            if (!empty($this->bc_id)) {
                $where['bc_id'] = $this->bc_id;
            } else {
                $where['bc_id'] = 0;
            }
        }

        $field = 'id,title,author,category_id,create_time,status';
        $order = 'id DESC';
        $page  = RequestHelper::get('page', 1, 'intval');
        $data['list'] = $this->news_model->getNewsList($where, $field, $order, $page, $this->PAGE_SIZE, $and_where);
        if (!empty($data['list'])) {
            foreach ($data['list'] as $k => $v) {
                $data['list'][$k]['category_name'] = $this->news_category_model->getCategoryName($v['category_id']);
            }
        }
        $total_count = $this->news_model->getCount($where, $and_where);
        $pages = new Pagination(['totalCount' =>$total_count, 'pageSize' => $this->PAGE_SIZE]);
        return $this->render('index', ['data'=>$data,'pages'=>$pages]);
    }

    /**
     * 添加资讯
     * @return string
     */
    public function actionAdd()
    {
        return $this->render('add');
    }

    /**
     * 编辑资讯
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id', '0', 'intval');
        $info = $this->news_model->getDetails($id);
        $info['content'] = htmlspecialchars_decode($info['content']);
        return $this->render('edit', ['info'=>$info]);
    }

    /**
     * 执行添加和编辑的方法
     * @return string
     */
    public function actionNewsOperation()
    {
        $act = RequestHelper::post('act', '', 'htmlspecialchars');
        if ($act != 'add' && $act != 'edit') {
            CommonHelper::ajaxReturn('no', '非法请求', []);
        }
        $data = [];
        $data['bc_id'] = RequestHelper::post('bc_id', '0', 'intval');
        if (empty($data['bc_id'])) {
            CommonHelper::ajaxReturn('no', '请选择分公司', []);
        }
        $data['title'] = RequestHelper::post('title', '', 'htmlspecialchars');
        if (empty($data['title'])) {
            CommonHelper::ajaxReturn('no', '请输入标题', []);
        }
        $data['category_id'] = RequestHelper::post('category_id', '0', 'intval');
        if (empty($data['category_id'])) {
            CommonHelper::ajaxReturn('no', '请选择分类', []);
        }
        $data['author'] = RequestHelper::post('author', '', 'htmlspecialchars');
        if (empty($data['author'])) {
            CommonHelper::ajaxReturn('no', '请输入作者', []);
        }
        $data['content'] = RequestHelper::post('content', '', 'htmlspecialchars');
        if (empty($data['content'])) {
            CommonHelper::ajaxReturn('no', '请输入内容', []);
        }
        $data['seo_title'] = RequestHelper::post('seo_title', '', 'htmlspecialchars');
        if (empty($data['seo_title'])) {
            CommonHelper::ajaxReturn('no', '请输入SEO标题', []);
        }
        $data['seo_keywords'] = RequestHelper::post('seo_keywords', '', 'htmlspecialchars');
        if (empty($data['seo_keywords'])) {
            CommonHelper::ajaxReturn('no', '请输入SEO关键字', []);
        }
        $data['seo_description'] = RequestHelper::post('seo_description', '', 'htmlspecialchars');
        if (empty($data['seo_description'])) {
            CommonHelper::ajaxReturn('no', '请输入SEO描述', []);
        }
        if ($act=='add') {
            $rs = $this->news_model->add($data);
        } elseif ($act=='edit') {
            $data['id']     = RequestHelper::post('id', '0', 'intval');
            if (empty($data['id'])) {
                CommonHelper::ajaxReturn('no', '请传递合法的ID', []);
            }
            $data['status'] = RequestHelper::post('status', '0', 'intval');
            if (empty($data['status'])) {
                CommonHelper::ajaxReturn('no', '请传递合法的状态值', []);
            }
            $rs = $this->news_model->edit($data);
        }
        if ($rs) {
            CommonHelper::ajaxReturn('ok', 'success', []);
        } else {
            CommonHelper::ajaxReturn('no', '服务繁忙，请稍后', []);
        }
    }
    /**
     * 删除资讯
     * @return string
     */
    public function actionDel()
    {
        $id = RequestHelper::get('id', '0', 'intval');
        $rs = $this->news_model->del($id);
        if ($rs) {
            CommonHelper::ajaxReturn('ok', 'success', []);
        } else {
            CommonHelper::ajaxReturn('no', '服务繁忙，请重试', []);
        }

    }

    /**
     * 资讯分类列表
     * @return string
     */
    public function actionCategory()
    {
        $data  = [];
        $where = ['is_deleted'=>'1'];
        $fields = 'id,parent_id,name,create_time';
        $lists = $this->news_category_model->getCategoryList($where, $fields);
        $category_tree = new CategoryTree($lists, array('id', 'parent_id', 'name', 'fullname'));
        $data['list'] =  $category_tree->getList();
        return $this->render('category', ['data'=>$data]);
    }

    /**
     * 添加资讯分类
     * @return string
     */
    public function actionAddCategory()
    {
        $data = [];
        $data['name'] = RequestHelper::post('cate_name', '', 'htmlspecialchars');
        if (empty($data['name'])) {
            CommonHelper::ajaxReturn('no', '请输入分类名称', []);
        }
        $data['parent_id'] = RequestHelper::post('parent_id', '', 'intval');
        $rs = $this->news_category_model->add($data);
        if ($rs) {
            CommonHelper::ajaxReturn('ok', 'success', []);
        } else {
            CommonHelper::ajaxReturn('no', '服务繁忙，请重试', []);
        }
    }

    /**
     * 编辑资讯分类
     * @return string
     */
    public function actionEditCategory()
    {
        $id = RequestHelper::get('id', '0', 'intval');
        $info = $this->news_category_model->getDetails($id);
        return $this->render('edit_category', ['info'=>$info]);
    }

    /**
     * 执行编辑资讯分类的方法
     * @return array
     */
    public function actionDoEditCategory()
    {
        $data = [];
        $data['id']  = RequestHelper::post('id', '0', 'intval');
        $data['name'] = RequestHelper::post('name', '', 'htmlspecialchars');
        if (empty($data['name'])) {
            CommonHelper::ajaxReturn('no', '请输入名称', []);
        }
        $data['parent_id']   = RequestHelper::post('parent_id', '0', 'intval');
        $data['description'] = RequestHelper::post('description', '0', 'htmlspecialchars');
        $data['sort']        = RequestHelper::post('sort', '0', 'intval');
        $rs = $this->news_category_model->edit($data);
        if ($rs) {
            CommonHelper::ajaxReturn('ok', 'success', []);
        } else {
            CommonHelper::ajaxReturn('no', '服务繁忙，请重试', []);
        }
    }

    /**
     * 删除资讯分类
     * @return string
     */
    public function actionDelCategory()
    {
        //todo 删除时判断下面是否有文章
        $id = RequestHelper::get('id', '0', 'intval');
        $check_rs = $this->news_model->checkCategory($id);
        if (!empty($check_rs)) {
            CommonHelper::ajaxReturn('no', '该分类下有资讯，不能删除', []);
        } else {
            $rs = $this->news_category_model->del($id);
            if ($rs) {
                CommonHelper::ajaxReturn('ok', 'success', []);
            } else {
                CommonHelper::ajaxReturn('no', '服务繁忙，请重试', []);
            }
        }
    }

    /**
     * 获取分类
     * @return array
     */
    public function actionGetCategory()
    {
        $where = ['is_deleted'=>'1'];
        $fields = 'id,parent_id,name';
        $lists = $this->news_category_model->getCategoryList($where, $fields);
        if (!empty($lists)) {
            $category_tree = new CategoryTree($lists, array('id', 'parent_id', 'name', 'fullname'));
            $rs =  $category_tree->getList();
            CommonHelper::ajaxReturn('ok', 'success', $rs);
        } else {
            CommonHelper::ajaxReturn('no', 'success', []);
        }
    }

    /**
     * 获取分公司
     * @return array
     */
    public function actionGetBranchCompany()
    {
        $bc = [];
        $bc = $this->getCommonBC();
        CommonHelper::ajaxReturn('ok', 'success', $bc);
    }
}

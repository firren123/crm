<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  PostController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 上午11:41
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Log;
use backend\models\social\Comments;
use backend\models\social\Content;
use backend\models\social\Forum;
use backend\models\social\ForumOther;
use backend\models\social\Post;
use common\helpers\CommonHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;


/**
 * Class PostController
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class PostController extends BaseController
{
    /**
     * 简介：帖子列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $forum_id = RequestHelper::get('f_id', 0, 'intval');
        $start_time = RequestHelper::get('start_time');
        $end_time = RequestHelper::get('end_time');
        $keyword = RequestHelper::get('keyword');
        $mobile = RequestHelper::get('mobile');
        $PostModel = new Post();
        $cond = [];
        $and_where = [];
        $and_where1 = [];
        $and_where2 = [];
        if (!empty($keyword)) {
            $and_where = ['like', 'title', $keyword];
        }
        if (!empty($start_time)) {
            $and_where1 = ['>', 'create_time', $start_time];
        }
        if (!empty($end_time)) {
            $and_where2 = ['<', 'create_time', $end_time];
        }

        if ($mobile != '') {
            $cond['mobile'] = $mobile;
        }

        $order = "id desc";
        $page = RequestHelper::get('page', 1);
        $forumModel = new Forum();
        $forum_list = $forumModel->getList(1, "id,pid");
        $forum_ids = CommonHelper::getChildCateId($forum_list, $forum_id);
        $forum_ids = array_merge($forum_ids,array($forum_id));
        if ($forum_id != 0) {
            $cond['forum_id'] = $forum_ids;
        }
        if ($cond == []) {
            $cond = 1;
        }
        $count = $PostModel->find()->where($cond)->andWhere($and_where)->andWhere($and_where1)->andWhere($and_where2)->count();
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        $page_new = ($page - 1) * $this->size;
        $list = $PostModel->find()->where($cond)->andWhere($and_where)->andWhere($and_where1)->andWhere($and_where2)->offset($page_new)->limit($this->size)->orderBy($order)->all();
        $commentsModel = new Comments();
        foreach ($list as $k => &$v) {
            $v['comment_num'] = $commentsModel->getCount(['post_id' => $v['id']]);
        }
        $forum_list = $this->_forumList();
        $forum_list1 = $this->_forumList(1);
        $data = [
            'pages' => $pages,
            'list' => $list,
            'mobile' => $mobile,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'forum_id' => $forum_id,
            'forum_list'=>$forum_list,
            'keyword'=>$keyword,
            'forum_list1'=>$forum_list1
        ];

        return $this->render('index', $data);
    }

    /**
     * 简介：帖子添加
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAdd()
    {
        $model = new Post();
        $contentModel = new Content();
        $Post = RequestHelper::post('Post');
        $forum_list = $this->_forumList(1);
        $content = [];
        if (!empty($Post)) {
            $content['content'] = $Post['content'];
            $Post['thumbs']=RequestHelper::post('Post[thumbs]', 0, 'intval');
            $Post['views']=RequestHelper::post('Post[views]', 0, 'intval');
            if (empty($Post['post_img'])) {
                return $this->error('图片不能为空');
            } else {
                $Post['post_img'] = implode('###', $Post['post_img']);
            }
            $model->attributes = $Post;
            unset($Post['content']);
            $result = $model->insertOneRecord($Post);
            if ($result['result'] == 1) {
                $content['post_id'] = $result['data']['new_id'];
                $contentModel->insertInfo($content);
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '添加了帖子' . $result['data']['new_id'];
                $log->recordLog($log_info, 12);

                $this->redirect('/social/post/index');
            } else {
                \Yii::$app->getSession()->setFlash('error', '添加失败.');
            }
        }
        return $this->render('add', ['model' => $model, 'forum_list' => $forum_list]);
    }

    /**
     * 简介：帖子修改
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Post();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, false, '*');
        $item->post_img = explode("###",$item->post_img);
        $contentModel = new Content();
        $con = $contentModel->getInfo(['post_id' => $id]);
        $item['content'] = $con['content'];
        $Post = RequestHelper::post('Post');
        if (!empty($Post)) {
            if (empty($Post['post_img'])) {
                return $this->error('图片不能为空');
            } else {
                $Post['post_img'] = implode('###', $Post['post_img']);
            }
            $content = $Post['content'];
            unset($Post['content']);
            $model->attributes = $Post;
            $result = $model->updateInfo($Post, $cond);
            if ($result == true) {
                $contentModel->updateInfo(['content' => $content], ['post_id' => $id]);
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '修改了帖子' . $id;
                $log->recordLog($log_info, 12);
                $this->redirect('/social/post/index');
            }
        }
        $forum_list = $this->_forumList(1);
        return $this->render('add', ['model' => $item, 'forum_list' => $forum_list]);
    }

    /**
     * 简介：显示详情
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionView()
    {
        $id = RequestHelper::get('id');
        $model = new Post();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond);
        $item['post_img'] = explode("###",$item['post_img']);
        $contentModel = new Content();
        $con = $contentModel->getInfo(['post_id' => $id]);
        $item['content'] = $con['content'];
        $forum_list = $this->_forumList();
        return $this->render('view', ['item' => $item,'forum_list'=>$forum_list]);
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionDel()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $code = 0;
        if (!empty($id)) {
            $model = new Post();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了id为' . $id . '的帖子';
                $log->recordLog($log_info, 12);
                $code = 1;
            }
        }
        echo $code;
    }

    /**
     * 简介：批量删除帖子
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionAllDel()
    {
        $id = RequestHelper::get('ids');
        $ids = explode(',', $id);
        $code = 0;
        if (!empty($ids)) {
            $model = new Post();
            $ret = $model->deleteAll(array('id' => $ids));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了id为' . $id . '的帖子';
                $log->recordLog($log_info, 12);
                $code = 1;
            }
        }
        echo $code;
    }

    /**
     * 简介：转移帖子
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionRemove()
    {
        $id = RequestHelper::post('ids');
        $forum_id = RequestHelper::post('forum_id');
        $ids = explode(',', $id);
        $code = 0;
        if (!empty($ids)) {
            $model = new Post();
            $ret = $model->updateInfo(['forum_id'=>$forum_id], ['id'=>$ids]);
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '转移' . $id . '的帖子';
                $log->recordLog($log_info, 12);
                $code = 1;
            }
        }
        echo $code;
    }
    /**
     * 简介：评论列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionCommentsList()
    {
        $post_id = RequestHelper::get('post_id', 0, 'intval');
        $mobile = RequestHelper::get('mobile');
        $cond = [];
        if ($mobile != '') {
            $cond['mobile'] = $mobile;
        }
        $CommentModel = new Comments();
        $cond['post_id'] = $post_id;
        $order = "id desc";
        $page = RequestHelper::get('page', 1);
        $count = $CommentModel->getCount($cond);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $this->size]);
        $list = $CommentModel->getPageList($cond, "*", $order, $page, $this->size);
        $data = ['pages' => $pages, 'list' => $list, 'mobile' => $mobile, 'post_id' => $post_id];
        return $this->render('comment_list', $data);
    }

    /**
     * 简介：删除评论
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionCommentsDel()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $code = 0;
        if (!empty($id)) {
            $model = new Comments();
            $ret = $model->deleteAll(array('id' => $id));
            if ($ret) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了id为' . $id . '的评论';
                $log->recordLog($log_info, 12);
                $code = 1;
            }
        }
        echo $code;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionForumList()
    {

        $ForumModel = new Forum();
        $where['pid'] = 0;
        $module_list = $ForumModel->getList($where, "*", 'sort asc');
        foreach ($module_list as $k => $v) {
            $where = [];
            $where['pid'] = $v['id'];
            $controll_list = $ForumModel->getList($where, "*", 'sort asc');
            $module_list[$k][$v['id']] = $controll_list;
            foreach ($controll_list as $kk => $vv) {
                $where = [];
                $where['pid'] = $vv['id'];
                $module_list[$k][$v['id']][$kk][$vv['id']] = $ForumModel->getList($where, "*", 'sort asc');
            }
        }
        $data = ['list' => $module_list];
        return $this->render('forum_list', $data);
    }

    /**
     * 简介：编辑板块
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionForumEdit()
    {
        $id = RequestHelper::get('id');
        $model = new Forum();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, false, '*');
        $Forum = RequestHelper::post('Forum');

        if (!empty($Forum)) {
            $result = $model->updateInfo($Forum, $cond);
            if ($result == true) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '修改了帖子板块' . $id;
                $log->recordLog($log_info, 12);
                $this->redirect('/social/post/forum-list');
            }
        }
        return $this->render('forum_add', ['model' => $item]);
    }

    /**
     * 简介：http://admin.500mi.local.com/admin/post/forum-add?p_id=4
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionForumAdd()
    {
        $pid = RequestHelper::get('p_id', 0, 'intval');
        $model = new Forum();
        $model->pid = $pid;
        $Forum = RequestHelper::post('Forum');
        if (!empty($Forum)) {
            $Forum['sort'] = RequestHelper::post('Forum[sort]', 999, 'intval');
            $result = $model->insertInfo($Forum);
            if ($result == true) {
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '添加帖子板块' . $Forum['title'];
                $log->recordLog($log_info, 12);
                \Yii::$app->getSession()->setFlash('success', '添加成功');
                return $this->redirect('/social/post/forum-list');
            }
        }
        return $this->render('forum_add', ['model' => $model, 'pid' => $pid]);
    }

    /**
     * 简介：删除评论
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionForumDel()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $code = 0;
        if (!empty($id)) {
            $model = new Forum();
            $child = $model->getCount(['pid' => $id]);
            if ($child != 0) {
                $code = 2;
            } else {
                $ret = $model->deleteAll(array('id' => $id));
                $forumOtherModel = new ForumOther();
                $forumOtherModel->deleteAll(array('forum_id' => $id));
                if ($ret) {
                    $log = new Log();
                    $log_info = '管理员 ' . \Yii::$app->user->identity->username . '删除了id为' . $id . '的评论';
                    $log->recordLog($log_info, 12);
                    $code = 1;
                }
            }
        }
        echo $code;
        exit;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionForumView()
    {
        $id = RequestHelper::get('f_id');
        $model = new Forum();
        $cond = 'id=' . $id;
        $item = $model->getInfo($cond, true, '*');
        $forumOtherModel = new ForumOther();
        $info = $forumOtherModel->getInfo(['forum_id' => $id]);
        $item['thumbs'] = $info['thumbs'];
        $item['views'] = $info['views'];
        $item['forum_number'] = $info['forum_number'];
        return $this->render('forum_view', ['item' => $item]);
    }
    //===================================================私有方法==============================================================//

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param int $num 类型
     * @return array
     */
    private function _forumList($num = 0)
    {
        $model = new Forum();
        $cate_list2 = [];
        $cate = $model->getList(1, 'id,pid,title');
        $cate_list = array();
        if ($num == 0) {
            $cate_list = RequestHelper::unlimitedForLevel($cate, '');
        }
        if ($num == 1) {
            $cate_list = RequestHelper::unlimitedForLevel($cate, '|-----');
        }
        foreach ($cate_list as $k => &$v) {
            $cate_list2[$v['id']] = $v['html'] . $v['title'];
        }
        return $cate_list2;
    }
}

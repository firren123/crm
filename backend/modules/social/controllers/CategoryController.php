<?php
/**
 * 服务类型管理
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   CategoryController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/9/14 0014 上午 11:55
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Log;
use backend\models\social\ServiceCategory;
use common\helpers\CommonHelper;
use common\helpers\RequestHelper;

/**
 * CategoryController
 *
 * @category Admin
 * @package  CategoryController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class CategoryController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new ServiceCategory();
        $search = RequestHelper::get('Search', '');
        $and_where = [];
        if (!empty($search['name'])) {
            $and_where = ['like', 'name', $search['name']];
        }
        $cond['pid'] = 0;
        $list = $model->getList($cond, '*', 'sort asc', $and_where);
        foreach ($list as $k => $v) {
            $where = [];
            $where['pid'] = $v['id'];
            $category_list = $model->getList($where, "*", 'sort asc');
            $list[$k][$v['id']] = $category_list;
            foreach ($category_list as $kk => $vv) {
                $where = [];
                $where['pid'] = $vv['id'];
                $list[$k][$v['id']][$kk][$vv['id']] = $model->getList($where, "*", 'sort asc');
            }
        }
        $param = [
            'list' => $list,
            'search' => $search
        ];
        return $this->render('index', $param);
    }

    /**
     * 添加类型
     *
     * @return string
     */
    public function actionAdd()
    {
        $model = new ServiceCategory();
        $model['status'] = 1;
        $model['sort'] = 99;
        $model['is_deleted'] = 2;
        $model['pid'] = RequestHelper::get('p_id', 0);
        $post = RequestHelper::post('ServiceCategory', '');
        if (!empty($post)) {
            $model->attributes = $post;
            $model->image = $post['image'];
            $post['name'] = CommonHelper::semiangle($post['name']);
            $post['sort'] = CommonHelper::semiangle($post['sort']);
            $post['description'] = CommonHelper::semiangle($post['description']);
            $name = empty($post['name']) ? '' : $post['name'];
            $name_length = mb_strlen($name, 'utf8');
            if ($name_length==0 or $name_length>4) {
                $model->addError('name', '类型名称 最小一位最大四位');
            } elseif (empty($post['sort'])) {
                $model->addError('sort', '排序 不能为空');
            } elseif (empty($post['description'])) {
                $model->addError('description', '类型描述 不能为空');
            } else {
                $post['sort'] = is_numeric($post['sort']) ? $post['sort'] : 99;
                $post['is_deleted'] = 2;
                $post['create_time'] = date("Y-m-d H:i:s");
                $result = $model->getInsert($post);
                if ($result>0) {
                    $log = new Log();
                    $log_info = '管理员 ' . \Yii::$app->user->identity->username.'添加了id为'.$result.'的 服务类型';
                    $log->recordLog($log_info, 13);
                    $this->redirect("/social/category");
                }
            }
        }
        $param = [
            'model' => $model
        ];
        return $this->render('add', $param);
    }
    /**
     * 编辑类型
     *
     * @return string
     */
    public function actionEdit()
    {
        $model = new ServiceCategory();
        $cond['id'] = RequestHelper::get('id', 0);
        $item = $model->getInfo($cond, false, '*');
        $detail = $model->getInfo($cond, true, '*');
        $post = RequestHelper::post('ServiceCategory', '');
        if (!empty($post)) {
            $post['name'] = CommonHelper::semiangle($post['name']);
            $post['sort'] = CommonHelper::semiangle($post['sort']);
            $post['description'] = CommonHelper::semiangle($post['description']);
            $name = empty($post['name']) ? '' : $post['name'];
            $name_length = mb_strlen($name, 'utf8');
            if ($name_length==0 or $name_length>4) {
                $model->addError('name', '类型名称 最少一位最大四位');
            } elseif (empty($post['sort'])) {
                $model->addError('sort', '排序 不能为空');
            } elseif (empty($post['description'])) {
                $model->addError('description', '类型描述 不能为空');
            } else {
                $post['sort'] = is_numeric($post['sort']) ? $post['sort'] : 99;
                $post['update_time'] = date('Y-m-d H:i:s');
                $result = $model->updateInfo($post, $cond);

                if ($result==true) {
                    $array = array_diff($post, $detail);
                    $content = "管理员：" . \Yii::$app->user->identity->username . ",把服务类型id为:" . $cond['id'] . ",服务类型名称为:" . $detail['name'] . ' 的';
                    if ($detail['status']!=$post['status']) {
                        $status = $post['status'] ==1 ? "可用" : "不可用";
                        $content .= " 服务类型是否可用修改成".$status;
                    }
                    if ($detail['is_deleted']!=$post['is_deleted']) {
                        $is_deleted = $post['is_deleted'] ==1 ? "不显示" : "显示";
                        $content .= " 服务类型是否显示修改成".$is_deleted;
                    }
                    if ($array) {
                        $log = new Log();
                        if (!empty($array['name'])) {
                            $content .= " 服务类型名称修改成".$array['name'];
                        }
                        if (!empty($array['image'])) {
                            $content .= " 服务类型图片修改成".$array['image'];
                        }
                        if (!empty($array['sort'])) {
                            $content .= " 服务类型排序修改成".$array['sort'];
                        }
                        if (!empty($array['description'])) {
                            $content .= " 服务类型描述修改成".$array['description'];
                        }
                        if (!empty($array['update_time'])) {
                            $content .= " 服务类型修改时间修改成".$array['update_time'];
                        }
                        $log->recordLog($content, 13);
                    }
                    $this->redirect("/social/category");
                }
            }
        }
        $param = [
            'model' => $item
        ];
        return $this->render('add', $param);
    }
    /**
     * 类型详情
     *
     * @return string
     */
    public function actionView()
    {
        $model = new ServiceCategory();
        $cond['id'] = RequestHelper::get('id', 0);
        $item = $model->getInfo($cond);
        if ($item) {
            $detail_cond['id'] = $item['pid'];
            $detail = $model->getInfo($detail_cond, true, 'name');
            $item['pid_name'] = empty($detail) ? '顶级分类' : $detail['name'];
        }
        $param = [
            'item' => $item
        ];
        return $this->render('view', $param);
    }

    /**
     * 删除
     *
     * @return array
     */
    public function actionDelete()
    {
        $array = ['code'=>'101', 'msg'=>'缺少参数'];
        $model = new ServiceCategory();
        $id = RequestHelper::get('id', 0);
        if ($id>0) {
            $cond['pid'] = $id;
            $number = $model->getCount($cond);
            if ($number==0) {
                $result = $model->delOneRecord(['id'=>$id]);
                if ($result==true) {
                    $log = new Log();
                    $log_info = '管理员 ' . \Yii::$app->user->identity->username.'删除了id为'.$id.'的 服务类型';
                    $log->recordLog($log_info, 13);
                    $array = ['code'=>'200', 'msg'=>'删除成功'];
                } else {
                    $array = ['code'=>'103', 'msg'=>'系统繁忙'];
                }
            } else {
                $array = ['code'=>'102', 'msg'=>'无法删除'];
            }
        }
        return json_encode($array);
    }
}

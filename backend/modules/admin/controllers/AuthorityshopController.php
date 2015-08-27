<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  Authorityshop.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 下午1:50
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\AuthorityShop;
use common\helpers\RequestHelper;
use yii\data\Pagination;


/**
 * Class AuthorityshopController
 * @category  PHP
 * @package   Admin
 * @filename  Authorityshop.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 下午1:50
 * @link      http://www.i500m.com/
 */
class AuthorityshopController extends BaseController
{
    /**
     * 简介：商家黑名单列表
     * @author  weitonghe@iyangpin.com
     * @return string
     */
    public function actionIndex()
    {
        $page = RequestHelper::get('page', 1, 'intval');

        $AuthorityShopModel = new AuthorityShop();
        $count = $AuthorityShopModel->getListCount('');
        $list = $AuthorityShopModel->getPageList(true, "*", 'id desc ', $page, $this->size);
        $pages = new Pagination(['totalCount' => $count['num'], 'pageSize' => $this->size]);
        $data = [
            'list' => $list,
            'pages' => $pages
        ];
        return $this->render('index', $data);
    }


    /**
     * 简介：商家黑名单添加
     * @author  weitonghe@iyangpin.com
     * @return string
     */
    public function actionAdd()
    {
        $AuthorityShopModel = new AuthorityShop();
        $AuthorityShop = RequestHelper::post('AuthorityShop');
        if (!empty($AuthorityShop)) {
            $where = array('shop_id' => $AuthorityShop['shop_id']);
            $count = $AuthorityShopModel->getCount($where);
            if ($count == 0) {
                $AuthorityShop['create_time'] = date("Y-m-d H:i:s");
                $result = $AuthorityShopModel->insertInfo($AuthorityShop);
                if ($result == true) {
                    return $this->success('添加成功', '/admin/authorityshop/index');
                }
            } else {
                return $this->success('添加失败,已存在的商家ID!', '/admin/authorityshop/add');
            }
        }
        return $this->render('add', ['model' => $AuthorityShopModel]);

    }

    /**
     * 简介：商家黑名单修改
     * @author  weitonghe@iyangpin.com
     * @return string
     */
    public function actionEdit()
    {
        $id = RequestHelper::get('id');
        $AuthorityShopModel = new AuthorityShop();
        $AuthorityShop = RequestHelper::post('AuthorityShop');
        if (!empty($AuthorityShop)) {
            $where = array('shop_id' => $AuthorityShop['shop_id']);
            $count = $AuthorityShopModel->getCount($where);
            if ($count == 0) {
                $cond = 'id=' . $id;
                $item = $AuthorityShopModel->getInfo($cond, false, '*');
                if (!empty($AuthorityShop)) {
                    $AuthorityShopModel->attributes = $AuthorityShop;
                    $result = $AuthorityShopModel->updateInfo($AuthorityShop, $cond);
                    if ($result == true) {
                        return $this->success('修改成功', '/admin/authorityshop/index');
                    }
                }
                return $this->render('add', ['model' => $item]);
            } else {
                return $this->success('修改失败,已存在的商家ID!', '/admin/authorityshop/edit?id='.$id);
            }
        }
        $cond = 'id=' . $id;
        $item = $AuthorityShopModel->getInfo($cond, false, '*');
        return $this->render('add', ['model' => $item]);
    }

    /**
     * 简介：商家黑名单删除
     * @author  weitonghe@iyangpin.com
     * @return int
     */
    public function actionDelete()
    {
        $code = 0;
        $id = RequestHelper::get('id');
        if (!empty($id)) {
            $AuthorityShopModel = new AuthorityShop();
            $ret = $AuthorityShopModel->deleteAll(array('id' => $id));
            if ($ret) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }

    /**
     * 简介：AJAX提交
     * @author  weitonghe@iyangpin.com
     * @return int
     */
    public function actionAjaxDelete()
    {
        $code = 0;
        $id = RequestHelper::post('ids');
        if (!empty($id)) {
            $AuthorityShopModel = new AuthorityShop();
            $ret = $AuthorityShopModel->deleteAll(array('id' => $id));
            if ($ret) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        return $code;
    }
}

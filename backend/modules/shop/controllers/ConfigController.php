<?php
/**
 * 商家多少钱起送及多少钱配送
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ConfigController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/5/15 0015 上午 10:01
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */
namespace backend\modules\shop\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\CrmBranch;
use backend\models\shop\ShopConfig;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * ConfigController
 *
 * @category CRM
 * @package  ADDRESS
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ConfigController extends BaseController
{
    /**
     * 商家起送列表
     *
     * @return string
     */
    public function actionList()
    {
        $size = $this->size;
        $page = RequestHelper::get('page', 1);
        $model = new ShopConfig();
        $branch = new CrmBranch();
        $cond = '1=1';
        $list = $model->getPageList($cond, '*', 'id desc', $page, $size);
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $branch_conf['id'] = $value['bc_id'];
                $branch_list = $branch->getInfo($branch_conf);
                $list[$key]['branch_name'] = empty($branch_list) ? '--' : $branch_list['name'];
            }
        }
        $total = $model->getCount($cond);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $size]);

        $param = [
            'list' => $list,
            'pages' => $pages,
        ];

        return $this->render('list', $param);
    }

    /**
     * 添加起送费
     *
     * @return string
     */
    public function actionAdd()
    {
        $model = new ShopConfig();
        $model->community_num = 0;
        $model->price_limit = 0;
        $bc_id = \Yii::$app->user->identity->bc_id;//分公司id
        $branch_model = new CrmBranch();
        $data_cond['name'] = '总公司';
        $branch_item = $branch_model->getInfo($data_cond);
        $branch_id = empty($branch_item['id']) ? 0 : $branch_item['id'];//总公司id
        //分公司对应的省id集合
        $crm_branch_conf['status'] = 1;
        if ($bc_id != $branch_id) {
            $crm_branch_conf['id'] = array($bc_id);
        }
        $branch_data = $branch_model->getList($crm_branch_conf, '*');
        $ShopConfig = RequestHelper::post('ShopConfig');
        if (!empty($ShopConfig)) {
            $model->attributes = $ShopConfig;
            $free_shipping = $ShopConfig['free_shipping'];//免运费金额
            $send_price = $ShopConfig['send_price'];//起送费
            $freight = $ShopConfig['freight'];//运费
            $bc_id = $ShopConfig['bc_id'];//限定城市id
            $city_data = 0;
            if ($bc_id) {
                $city_data = $model->getCount(['bc_id' => $bc_id]);
            }

            if (!is_numeric($free_shipping)) {
                $model->addError('free_shipping', '免运费金额 必须是数字');
            } elseif ($free_shipping < 0) {
                $model->addError('free_shipping', '免运费金额 必须是大于等于0的数字');
            } elseif (!is_numeric($send_price)) {
                $model->addError('send_price', '起送费 必须是数字');
            } elseif ($send_price < 0) {
                $model->addError('send_price', '起送费 必须是大于等于0的数字');
            } elseif (!is_numeric($freight)) {
                $model->addError('freight', '运费 必须是数字');
            } elseif ($freight < 0) {
                $model->addError('send_price', '运费 必须是大于等于0的数字');
            } elseif (empty($bc_id)) {
                \Yii::$app->getSession()->setFlash('error', '限定区域 不能为空');
            } elseif ($city_data > 0) {
                \Yii::$app->getSession()->setFlash('error', '限定区域 已经添加');
            } else {
                $config_add = $model->insertInfo($ShopConfig);
                if ($config_add == true) {
                    return $this->success('添加成功!', '/shop/config/list');
                }
            }
        }
        return $this->render('add', ['model' => $model, 'branch_data' => $branch_data]);
    }

}

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
use backend\models\i500m\YpContract;
use common\helpers\RequestHelper;

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
        0 => '营业执照（生产厂家和经销商',
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
        echo 123;
    }

    /**
     * 简介：合同列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionAdd()
    {
        $model = new YpContract();
        $post = RequestHelper::post('YpContract',array());
        if ($post) {
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
        $model = new YpContract();
        $post = RequestHelper::post('YpContract',array());
        var_dump($_POST);
        var_dump($post);exit;
        if ($post) {
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
                'title' => '修改合同',
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
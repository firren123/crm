<?php
/**
 * Xxx
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   Admin
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @time      2015/3/31 12:27
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhoujun@iyangpin.com
 */
namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Plot;
use backend\models\i500m\Province;
use backend\models\i500m\City;
use backend\models\i500m\OpenCity;
use backend\models\i500m\ShopCommunity;
use common\helpers\CurlHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use backend\models\i500m\Log;

/**
 * Class PlotController
 * @category  PHP
 * @package   PlotController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class PlotController extends BaseController
{
    public $size = 10;
    public $setSuffix = "beijing";

    /**
     * 小区分页显示
     * @name: actionIndex
     * @return string
     */
    public function actionIndex()
    {
        $open_city = new Opencity();
        $model = new Plot();

        $area = RequestHelper::get('area');
        $data = array();
        $data['page'] = RequestHelper::get('page', 1);
        $data['size'] = RequestHelper::get('per-page', $this->size);

        if ($data['page'] == 1) {
            $offset = 0;
        } else {
            $offset = ($data['page'] - 1) * $data['size'];
        }
        $city_id = RequestHelper::get('city_name', 0, 'intval');   //全国不选择城市时默认读取北京的

        if (!in_array($this->quanguo_city_id, $this->city_id)) {
            $city_id = $city_id ?: $this->city_id[0];
            $city_all = $open_city->getList(['city' => $this->city_id], 'city, city_name');
        } else {
            $city_id = $city_id ?: 1;
            $city_all = $open_city->getList(['status' => 1], 'city, city_name');
            foreach ($city_all as $k => $v) {
                if ('全国' == $v['city_name']) {
                    unset($city_all[$k]);
                }
            }
        }
        $city = $open_city->getInfo(['city' => $city_id], true, 'pinyin');
        $table = $city['pinyin'];

        $table_name = '_' . strstr($table, substr($table, strpos($table, 'shi'), 3), true);
        $model->setSuffix1($table_name);

        $shop_community_model = new ShopCommunity();
        $list = $model->show($data, $offset, $area);
        foreach ($list as $k => $v) {
            $shop_info = $shop_community_model->shop_community($v['id'], $v['city']); //$v['id']小区ID
            $list[$k]['shop_community'] = $shop_info;
        }
        $total = $model->total($area);
        $pages = new Pagination(['totalCount' => $total, 'pageSize' => $this->size]);
        return $this->render('index', ['list' => $list, 'pages' => $pages, 'total' => $total, 'city' => $city_all, 'city_id' => $city_id]);
    }

    /**
     * 简介：
     * @return string
     */
    public function actionAdd()
    {
        $open_city = new Opencity();
        $province_model = new Province();
        $model = new Plot();
        $city_info_id = RequestHelper::get('city_id'); //999
        if ($_POST) {
            $data = RequestHelper::post('plot', ''); //一维数组
            if ($data['average'] == '') {
                $data['average'] = 0;
            }
            if ($data['total_area'] == '') {
                $data['total_area'] = 0;
            }
            if ($data['total_number'] == '') {
                $data['total_number'] = 0;
            }
            if ($data['buildings'] == '') {
                $data['buildings'] = 0;
            }
            if ($data['developer'] == '') {
                $data['developer'] = '暂无数据';
            }
            if ($data['volume_rate'] == '') {
                $data['volume_rate'] = 0;
            }
            if ($data['property'] == '') {
                $data['property'] = '暂无数据';
            }
            if ($data['letting_rate'] == '') {
                $data['letting_rate'] = 0;
            }
            if ($data['property_type'] == '') {
                $data['property_type'] = 0;
            }
            if ($data['parking'] == '') {
                $data['parking'] = 0;
            }
            if ($data['property_fee'] == '') {
                $data['property_fee'] = 0;
            }
            if ($data['greening_rate'] == '') {
                $data['greening_rate'] = 0;
            }
            $city_id = $data['city'];
            $city_one_info = $open_city->getInfo(['city' => $city_id], true, 'pinyin'); //根据选取的城市ID获取该城市中文名称

            if (empty($city_one_info)) {
                return $this->error('该城市没有开通', '/admin/plot/index?city_name=' . $city_id);
            }
            $table = $city_one_info['pinyin'];
            $table_name = '_' . strstr($table, substr($table, strpos($table, 'shi'), 3), true);

            $model->setSuffix1($table_name);
            //$tablename = 'community'.$table_name;
            //$tablename_is = $model->ckeck_table_is($tablename);

            $verify = $model->verifyDredgeCity($data['name']);
            if ($verify) {
                return $this->error('此小区已添加过', '/admin/plot/add');
            } else {
                $coordinate = explode(',', $data['coordinate']);
                $num = count($coordinate);  //数组$coordinate中有几个键值对
                if ($num == 2) {
                    $data['lng'] = $coordinate[0];
                    $data['lat'] = $coordinate[1];
                } elseif ($num == 1) {
                    $data['lng'] = $coordinate[0];
                    $data['lat'] = '';
                } else {
                    $data['lng'] = '';
                    $data['lat'] = '';
                }
                if ($data['lng'] && empty($data['lat'])) {
                    $is_exist = 1;
                } elseif ($data['lng'] && $data['lat']) {
                    $is_exist = 2;
                } else {
                    $is_exist = 0;
                }
                $data['create_time'] = date('Y-m-d H:i:s', time());
                $community_id = $model->add_info($data, $is_exist);
                if ($community_id) {
                    CurlHelper::get('shopsync/edit-community?community_id=' . $community_id . '&city=' . $data['city'], 'server');
                    return $this->success('添加成功', '/admin/plot/index?city_name=' . $city_id);
                } else {
                    return $this->success('添加失败', '/admin/plot/index?city_name=' . $city_id);
                }
            }

        } else {
            $province_list = [];
            $city = [];
            if (!in_array($this->quanguo_city_id, $this->city_id)) {
                $m_city = new City();
                $city = $m_city->getList(['id' => $this->city_id], 'id, name, province_id');
                $province_id = isset($city[0]['province_id']) ? $city[0]['province_id'] : 0;
                if ($province_id) {
                    $province_list = $province_model->getList(['id' => $province_id], 'id, name');
                }
            }

            if (!$province_list) {
                $province_list = $province_model->getAllProvince();
                $arr = array('id' => '0', 'name' => '请选择');
                array_unshift($province_list, $arr);
            }
            return $this->render('add', ['model' => $model, 'info' => $province_list, 'city_info_id' => $city_info_id, 'city' => $city]);
        }
    }

    /**
     * 查询出对应省级下面的所有市
     * @name: actionCity
     * @return string
     */
    public function actionCity()
    {
        $id = RequestHelper::get('pid', 1, 'intval');
        $model = new City();
        $info = $model->city($id);
        echo json_encode($info);
    }

    /**
     * 简介：
     * @return string
     */
    public function actionDelete()
    {
        $open_city = new Opencity();
        $model = new Plot();
        $id = RequestHelper::get('id');
        $city_id = RequestHelper::get('city_id');
        $city_one_info = $open_city->getInfo(['city' => $city_id], true, 'pinyin'); //根据选取的城市ID获取该城市中文名称

        $table = $city_one_info['pinyin'];
        $table_name = '_' . strstr($table, substr($table, strpos($table, 'shi'), 3), true);
        $model->setSuffix1($table_name);
        $info = $model->del($id);
        if ($info) {
            //日志
            $account_time = date("Y-m-d H:i:s", time());
            $log = new Log();
            $log_info = '管理员 ' . \Yii::$app->user->identity->username . '在【小区管理】中删除了id为' . $id . '的小区' . $account_time;
            $log->recordLog($log_info, 2);

            return $this->success('删除成功', '/admin/plot/index?city_name=' . $city_id);
        } else {
            return $this->error('删除失败', '/admin/plot/index?city_name=' . $city_id);
        }
    }

    /**
     * 简介：
     * @return string
     */
    public function actionView()
    {
        $open_city = new Opencity();
        $province_model = new Province();
        $model = new Plot();
        $id = RequestHelper::get('id');
        $city_info_id = RequestHelper::get('city_id');
        if ($_POST) {
            $city_info_id = RequestHelper::post('city_info_id');
            $info = RequestHelper::post('plot');
            $ids = $info['id'];
            $city_one_info = $open_city->getInfo(['city' => $city_info_id], true, 'pinyin'); //根据选取的城市ID获取该城市中文名称
            if (empty($city_one_info)) {
                return $this->error('该城市没有开通', '/admin/plot/index?city_name=' . $city_info_id);
            }
            $table = $city_one_info['pinyin'];
            $table_name = '_' . strstr($table, substr($table, strpos($table, 'shi'), 3), true);

            $model->setSuffix1($table_name);
            if (!empty($info['coordinate'])) {
                $coordinate = $info['coordinate'];
                $x_y = explode(',', $coordinate);
                $info['lng'] = $x_y[0];
                $info['lat'] = $x_y[1];
                unset($info['coordinate']);
            } else {
                $info['lng'] = '';
                $info['lat'] = '';
                unset($info['coordinate']);
            }
            $model->attributes = $info;
            $cond = 'id=' . $info['id'];
            $result = $model->updateInfo($info, $cond);
            if ($result == true) {
                if ($city_info_id == '999') {
                    $city_info_id = 1;
                }
                CurlHelper::get('shopsync/edit-community?community_id=' . $info['id'] . '&city=' . $city_info_id, 'server');

                //日志
                $account_time = date("Y-m-d H:i:s", time());
                $log = new Log();
                $log_info = '管理员 ' . \Yii::$app->user->identity->username . '在【小区管理】中修改了id为' . $ids . '的小区' . $account_time;
                $log->recordLog($log_info, 2);


                return $this->success('编辑成功', '/admin/plot/index?city_name=' . $city_info_id);
            } else {
                return $this->error('编辑失败', '/admin/plot/index?city_name=' . $city_info_id);
            }
        } else {
            if ($city_info_id == '999') {
                $city_info_id = 1;
            }
            $city_arr = $open_city->getInfo(['city' => $city_info_id], true, 'pinyin'); //根据选取的城市ID获取该城市中文名称
            $table = $city_arr['pinyin'];
            $table_name = '_' . strstr($table, substr($table, strpos($table, 'shi'), 3), true);

            $model->setSuffix1($table_name);
            $Province_info = $province_model->getAllProvince();
            $info = $model->display_back($id);
            $lng = $info['lng'];
            $lat = $info['lat'];
            $arr = array();
            $arr[] = $lng;
            $arr[] = $lat;
            $info['coordinate'] = implode(',', $arr);
            return $this->render('edit', ['model' => $model, 'info' => $info, 'arr' => $Province_info, 'id' => $id, 'city_info_id' => $city_info_id]);
        }
    }

    /**
     * 简介：
     * @return string
     */
    public function actionLook()
    {
        $id = RequestHelper::get('id');
        $open_city = new Opencity();
        $model = new Plot();
        $city_id = RequestHelper::get('city_id');
        if ($city_id == 999) {
            $city_id = 1;
        }
        $city_one_info = $open_city->getInfo(['city' => $city_id], true, 'pinyin'); //根据选取的城市ID获取该城市中文名称
        $table = $city_one_info['pinyin'];
        $table_name = '_' . strstr($table, substr($table, strpos($table, 'shi'), 3), true);

        $model->setSuffix1($table_name);
        $list = $model->look($id);
        return $this->render('details', ['model' => $model, 'list' => $list, 'city_id' => $city_id]);
    }
}

<?php
/**
 * 小区相关功能控制器
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/20 16:41
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


namespace backend\modules\admin\controllers;


use yii;
use common\helpers\RequestHelper;
use backend\controllers\BaseController;
use backend\models\i500m\Community;
use backend\models\i500m\Province;
//use backend\models\i500m\Shop;
//use yii\data\Pagination;
use alexgx\phpexcel\PhpExcel;

/**
 * 小区相关功能控制器
 *
 * @category ADMIN
 * @package  CONTROLLER
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class CommunityController extends BaseController
{
    //private $_default_page_size = 10;

    private $_default_str_andwhere = '';
    //private $_default_arr_order = array();
    //private $_default_str_field = '*';
    private $_default_int_offset = -1;
    private $_default_int_limit = -1;

    private $_tmp_city = array(
        1 => array('name' => '北京', 'pinyin' => 'beijing'),
        258 => array('name' => '成都', 'pinyin' => 'chengdu'),
        261 => array('name' => '泸州', 'pinyin' => 'luzhou'),
        2 => array('name' => '天津', 'pinyin' => 'tianjin'),
        257 => array('name' => '重庆', 'pinyin' => 'chongqing'),
        87 => array('name' => '杭州', 'pinyin' => 'hangzhou'),
        3 => array('name' => '石家庄', 'pinyin' => 'shijiazhuang'),
        7 => array('name' => '邢台', 'pinyin' => 'xingtai'),
        74 => array('name' => '南京', 'pinyin' => 'nanjing'),
        135 => array('name' => '济南', 'pinyin' => 'jinan'),
        140 => array('name' => '烟台', 'pinyin' => 'yantai'),
        300 => array('name' => '大理', 'pinyin' => 'dali'),
        288 => array('name' => '昆明', 'pinyin' => 'kunming'),
        223 => array('name' => '柳州', 'pinyin' => 'liuzhou'),
        222 => array('name' => '南宁', 'pinyin' => 'nanning'),
        230 => array('name' => '玉林', 'pinyin' => 'yulin'),
    );


    /**
     * Action之前的处理
     *
     * //z20150424 关闭csrf
     *
     * Author zhengyu@iyangpin.com
     *
     * @param \yii\base\Action $action action
     *
     * @return bool
     *
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    /**
     * 导入页面
     *
     * Author zhengyu@iyangpin.com
     *
     * @return void
     */
    public function actionImport()
    {
        //echo "actionImport";exit;

        $act = RequestHelper::post('act', '', 'trim');

        if ($act == 'import_excel') {
            //$this->_outputUploadResult(0, 'ztest');return;

            $province_id = RequestHelper::post('province_id', 0, 'intval');
            $city_id = RequestHelper::post('city_id', 0, 'intval');
            if ($province_id <= 0) {
                $this->_outputUploadResult(0, '导入失败：参数错误(error_code=admin_community_C_import_1)');
                return;
            }
            if ($city_id <= 0) {
                $this->_outputUploadResult(0, '导入失败：参数错误(error_code=admin_community_C_import_2)');
                return;
            }

            //ztodo 临时限制2个城市
            $arr_allow_city = array_keys($this->_tmp_city);
            if (!in_array($city_id, $arr_allow_city)) {
                $this->_outputUploadResult(0, '导入失败：此城市尚不可导入小区');
                return;
            }

            $arr_ex_data = array(
                'province_id' => $province_id,
                'city_id' => $city_id,
            );

            $this->_importExcel($arr_ex_data);
            return;
        }

        //$model_shop = new Shop();
        $model_province = new Province();
        //  获取全部省
        //$arr_where = array('status' => 1);
        $arr_where = array();
        $str_field = 'id,name';
        $arr_order = array('sort' => SORT_ASC);
        $arr_select_province = $model_province->getList2(
            $arr_where,
            $this->_default_str_andwhere,
            $arr_order,
            $str_field,
            $this->_default_int_offset,
            $this->_default_int_limit
        );
        //$arr_select_province = $model_shop->getOpenProvince();
        //echo "<pre>";print_r($arr_select_province);echo "</pre>";exit;

        $arr_view_data = array(
            'arr_select_province' => $arr_select_province,
        );
        echo $this->render('import', $arr_view_data);
        return;
    }

    /**
     * 上传返回
     *
     * Author zhengyu@iyangpin.com
     *
     * @param int    $type 类型
     * @param string $msg  数据
     *
     * @return void
     */
    private function _outputUploadResult($type = 0, $msg = '')
    {
        $str_return = "<script"
            ." type='text/javascript'>"
            . "parent.upload_return(" . $type . ",'" . $msg . "');"
            . '</script>';
        //return $str_return;
        echo  $str_return;
        return;
    }

    /**
     * 导入excel
     *
     * Author zhengyu@iyangpin.com
     *
     * @param array $arr_ex_data 导入时所需的额外数据
     *
     * @return void
     */
    private function _importExcel($arr_ex_data)
    {
        //echo "<pre>";print_r($_FILES);echo "</pre>";exit;
        //$this->_outputUploadResult(1,'test');
        set_time_limit(0);
        ini_set('memory_limit', '256M');

        $map_upload_error = array(
            1 => '上传文件的大小超过了php.ini中upload_max_filesize选项设定的值',
            2 => '上传文件的大小超过了浏览器 MAX_FILE_SIZE选项设定的值',
            3 => '文件只有部分被上传',
            4 => '没有文件被上传'
        );
        $file_size_1_m = 1024 * 1024;
        $max_file_size = 2 * $file_size_1_m;//上传文件体积限制


        if ($_FILES['z_input_file']['error'] > 0) {
            //由文件上传导致的错误代码
            $this->_outputUploadResult(0, '上传文件失败：' . $map_upload_error[$_FILES['z_input_file']['error']]);
            return;
        }

        $upload_file_name = $_FILES['z_input_file']['name'];
        //$arr_split_file_name = explode('.', $upload_file_name);
        //$sizeArrSplitFileName = sizeof($arr_split_file_name);
        //$file_type = strtolower($arr_split_file_name[$sizeArrSplitFileName - 1]);
        $file_type = pathinfo($upload_file_name, PATHINFO_EXTENSION);
        //上传文件的扩展名//need
        $type_is_allow = in_array($file_type, array('xlsx', 'xls'));
        if ($type_is_allow === false) {
            //类型不对，结束//扩展名不对
            $this->_outputUploadResult(0, '上传文件失败：上传的文件类型不正确');
            return;
        }

        //检测文件大小
        $upload_file_size = $_FILES['z_input_file']['size'];
        if ($upload_file_size > $max_file_size) {
            //大小不对，结束
            $this->_outputUploadResult(0, '上传文件失败：上传的文件体积过大(大于' . $max_file_size / $file_size_1_m . 'M)');
            return;
        }

        $excel_path = $_FILES['z_input_file']['tmp_name'];

        $arr_data = $this->_getDataFromExcel($excel_path);//结果数组 key 1起
        //echo "<pre>";print_r($arr_data);echo "</pre>";exit;

        $arr_import_error = $this->_importData($arr_data, $arr_ex_data);
        //echo "<pre>";print_r($arr_import_error);echo "</pre>";exit;

        $this->_outputUploadResult(1, json_encode($arr_import_error));
        return;
    }

    /**
     * 获取excel中的数据存入数组
     *
     * Author zhengyu@iyangpin.com
     *
     * @param string $excel_path excel路径
     *
     * @return array
     */
    private function _getDataFromExcel($excel_path)
    {
        $obj = new PhpExcel();
        //var_dump($obj);exit;
        //$obj_phpexcel = \PHPExcel_IOFactory::load($excel_path);
        //$obj_phpexcel = $obj->load("/data/www/excel-test/asdf.xls");
        $obj_phpexcel = $obj->load($excel_path);
        //var_dump($obj_phpexcel);exit;

        $arr_data = $obj_phpexcel->getActiveSheet()->toArray(null, true, true, true);
        //var_dump($arr_data);
        //echo "<pre>";print_r($arr_data);echo "</pre>";
        $obj_phpexcel->disconnectWorksheets();
        unset($obj_phpexcel);

        return $arr_data;
    }

    /**
     * 导入数据到表里
     *
     * 返回值数组格式
     * array(
     *   'all_num' =>
     *   'succ_num' =>
     *   'fail_num' =>
     *   'error_info' => array(array('name'=>小区名,'info'=>错误信息),...)
     * )
     *
     * @param array $arr_data    分析excel得到的数组
     * @param array $arr_ex_data 额外数据
     *
     * @return array 导入过程记录到的错误信息 array(array('name'=>小区名,'info'=>错误信息),...)
     */
    private function _importData($arr_data, $arr_ex_data)
    {
        //$model = new Community();
        //$model->isNewRecord = true;
        //$model->beforeSave(true);
        //$model->setSuffix("_beijing");//ztodo

        $arr_import_error = array();//array(array('ticket'=>券号,'info'=>错误信息),...)
        $import_all_num = sizeof($arr_data) - 1;//导入总记录数
        $import_succ_num = 0;//成功记录数
        $import_fail_num = 0;//失败记录数
        $create_time = date("Y-m-d H:i:s", time());
        foreach ($arr_data as $key => $value) {
            if ($key == 1) {
                continue;
            }
            //$ctrip_ticket = trim($value['A']);
            //$ctrip_trade_no = trim($value['G']);
            //$consume_time = date("Y-m-d H:i:s", strtotime(trim($value['K'])));
            //$activity_id = intval($value['I']);
            //$selling_price = number_format($value['L'], 2, '.', '');
            //$commission = number_format($value['N'], 2, '.', '');
            //$settlement_price = number_format($value['M'], 2, '.', '');

            $id = trim($value['A']);
            $name = trim($value['B']);
            $lat = floatval($value['C']);//须数字
            $lng = floatval($value['D']);//须数字
            $average = intval($value['E']);//均价  数字
            $total_number = intval($value['H']);//总户数  数字
            //以下字符串格式
            $total_area = trim($value['F']);//总面积
            $area = trim($value['G']);//所在版块
            $area = preg_replace('/\s+/', ' ', $area);
            $address = trim($value['I']);//地址
            $buildings = trim($value['J']);//建造年代
            $developer = trim($value['K']);//开发商
            $volume_rate = trim($value['L']);//容积率
            $property = trim($value['M']);//物业公司
            $letting_rate = trim($value['N']);//出租率
            $property_type = trim($value['O']);//物业类型
            $parking = trim($value['P']);//停车位
            $property_fee = trim($value['Q']);//物业费用
            $greening_rate = trim($value['R']);//绿化率

            //z20150430a
            unset($arr_data[$key]);

            $arr_field_value = array(
                'name'=> $name,
                'lat'=> $lat,
                'lng'=> $lng,
                'average'=> $average,
                'total_area'=> $total_area,
                'area'=> $area,
                'total_number'=> $total_number,
                'address'=> $address,
                'buildings'=> $buildings,
                'developer'=> $developer,
                'volume_rate'=> $volume_rate,
                'property'=> $property,
                'letting_rate'=> $letting_rate,
                'property_type'=> $property_type,
                'parking'=> $parking,
                'property_fee'=> $property_fee,
                'greening_rate'=> $greening_rate,
                'province'=> $arr_ex_data['province_id'],
                'city'=> $arr_ex_data['city_id'],
                'district'=> 0,
                'create_time'=> $create_time,
                'status'=> 1,
            );

            //ztodo 检查是否已存在

            $model = new Community();
            $table_suffix = $this->_tmp_city[$arr_ex_data['city_id']]['pinyin'];//不含下划线
            $model->setSuffix("_" . $table_suffix);//ztodo
            //$model->beforeSave(true);
            //$model->afterSave(true, array());
            //$model->oldAttributes = array();
            //$model->attributes = array();
            //echo "<pre>0000 arr_field_value=";print_r($arr_field_value);echo "</pre>";
            $arr_result = $model->insertOneRecord($arr_field_value);
            if (1 == $arr_result['result']) {
                $import_succ_num++;
            } else {
                $arr_import_error[] = array('id' => $id, 'name' => $name, 'info' => $arr_result['msg']);
                //$arr_import_error[] = array('id' => $id, 'name' => $name, 'info' => "此记录导入失败");
                $import_fail_num++;
                continue;
            }
        }//foreach end

        //$arr_import_error[] = array('id' => 111, 'name' => '222', 'info' => '333');
        $arr_return = array(
            'all_num' => $import_all_num,
            'succ_num' => $import_succ_num,
            'fail_num' => $import_fail_num,
            'error_info' => $arr_import_error
        );
        return $arr_return;
    }


}

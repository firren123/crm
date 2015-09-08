<?php
/**
 * 简介
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   SHOP
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @time      2015/3/12 15:51
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhoujun@iyangpin.com
 */

namespace backend\modules\admin\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\Branch;
use backend\models\i500m\City;
use backend\models\i500m\Log;
use backend\models\i500m\Shop;
use backend\models\shop\Settlement;
use backend\models\shop\ShopAccount;
use common\helpers\CurlHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
/**
 * Shop
 *
 * @category SHOP
 * @package  Shop
 * @author   zhoujun <zhoujun@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhoujun@iyangpin.com
 */
class ShopController extends BaseController
{
    public $size = 10;

    /**
     * 商家带结算列表
     * @return string
     */
    public function actionIndex()
    {
        //实例化
        $city_model  = new City();
        $branch_model = new Branch();
        $shop_model = new Settlement();
        $model = new Shop();

        //分页
        $data = array();
        $data['page'] = RequestHelper::get('page', 1);
        $data['size'] = RequestHelper::get('per-page', $this->size);
        $offset = ($data['page'] - 1) * $data['size'];

        //获取参数
        $city_id = RequestHelper::get('city_id', 0);  //获取城市ID
        $branch_id = RequestHelper::get('branch_id', 0);  //获取分公司ID
        $settle_status = RequestHelper::get('settle_status', -1, 'intval');
        $shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $positive_minus = RequestHelper::get('positive_minus');

        //根据分公司id取得其下的所有城市
        if ($branch_id) {
            $info = $branch_model->city_all($branch_id);
            $list = explode(',', $info['city_id_arr']);
            $arr = array();
            foreach ($list as $k=>$v) {
                $city_all = $city_model->city_all($v);
                $arr[] = $city_all[0];
            }
            $city_arr = array('id'=>'0', 'name'=>'请选择');
            array_unshift($arr, $city_arr);
        } else {
            $arr = '';
        }

        //验证收索的商家ID，判断是否有此商家，防止收索没有的商家时报错
        if ($shop_id) {
            $verify = $shop_model->all_shop($shop_id);
            if (empty($verify)) {
                return $this->error('没有此商家', '/admin/shop/index');
            }
        }

        //获取全部分公司（放入INDEX页面搜索分公司下拉）
        $branch_all = $branch_model->branch_info();

        //拼装sql语句中的where条件
        $where = array();
        if ($branch_id) {
            $where[] = 'branch_id ='.$branch_id;
        }

        if ($city_id) {
            $where[] = 'city_id ='.$city_id;
        }

        if (in_array($settle_status, array(0, 1, 2))) {
            $where[] = "status =" . $settle_status;
        }

        if ($positive_minus) {
            if ($positive_minus == 1) {
                $where[] = "money < 0";
            } elseif ($positive_minus == 2) {
                $where[] = "money >= 0";
            }
        }

        if ($shop_id) {
            $where[] = "shop_id =" . $shop_id;
        }

        $where = empty($where) ? '' : implode(' and ', $where);

        //根据where条件以分页形式查询出数据
        $list = $shop_model->show($data, $offset, $where);

        //从shop表中查询出商家名称赋予到$list数组里，拼装一下时间
        $number_all = 0;
        foreach ($list as $k=>$v) {
            $number_all += $v['money'];
            $info_shop = $model->shop_info($v['shop_id']);
            $list[$k]['shop_name'] = $info_shop['shop_name'];
            $list[$k]['settle_time'] = substr($list[$k]['start_time'], 0, 10).'--'.substr($list[$k]['end_time'], 0, 10);
            unset($list[$k]['start_time']);
            unset($list[$k]['end_time']);
        }

        $total = $shop_model->total($where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        $params = [
            'list'=>$list,
            'pages'=>$pages,
            'total'=>$total,
            'number_all'=>$number_all,
            'branch_all'=>$branch_all,
            'branch_id'=>$branch_id,
            'city_id'=>$city_id,
            'settle_status'=>$settle_status,
            'arr'=>$arr,
            'positive_minus'=>$positive_minus
        ];
        return $this->render('index', $params);
    }


    /**
     * 商家带结算冻结OR解除冻结OR结算
     * @return string
     */
    public function actionFreeze()
    {
        $shop_model = new Settlement();
        $is_freeze = RequestHelper::get('is_freeze', 0);
        $id = RequestHelper::get('id', 0);
        $shop_id = RequestHelper::get('shop_id');
        $account_time = RequestHelper::post('account_time');
        $info = $shop_model->freeze($id, $is_freeze);
        if ($info) {
            $bark = RequestHelper::post('info');
            $log = new Log();
            $str = '';
            if ($is_freeze == 0) {
                $str = '冻结了';
            } elseif ($is_freeze == 1) {
                $str = '结算了';
            } elseif ($is_freeze == 2) {
                $str = '解除冻结了';
            }
            $log_info = '管理员 '.\Yii::$app->user->identity->username . $str.'商家id为'.$shop_id.'的账期'.$account_time;
            $log_info .=' 备注：'.$bark;
            $log->recordLog($log_info, 2);
            return $this->success('状态修改成功', '/admin/shop/index');
        } else {
            return $this->error('状态修改失败', '/admin/shop/index');
        }
    }

    /**
     * 根据所选分公司查询出对应的城市
     * @return string
     */
    public function actionCity()
    {
        $city_model  = new City();
        $model = new Branch();
        $bid = RequestHelper::get('bid');
        $info = $model->city_all($bid);
        $list = explode(',', $info['city_id_arr']);
        $arr = array();
        foreach ($list as $k=>$v) {
            $city_all = $city_model->city_all($v);
            $arr[] = $city_all[0];
        }
        $city_arr = array('id'=>'0', 'name'=>'请选择');
        array_unshift($arr, $city_arr);
        echo json_encode($arr);
    }

    /**
     * 商家待结算流水详情页
     * @return string
     */
    public function actionDetails()
    {
        $model = new Shop();
        $account_id = RequestHelper::get('account_id');
        $shop_id = RequestHelper::get('shop_id');
        $page = RequestHelper::get('page', 1, 'intval');
        //取出商家名称shop_name
        $shop_one = $model->shop_info($shop_id);
        $shop_name = $shop_one['shop_name'];
        //调用接口返回该商家的订单信息
        $info = $model->details_all($shop_id, $account_id, $page);
        $total = ArrayHelper::getValue($info, 'data.count', 0);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => 20]);
        //结算状态
        $status = $info['data']['status'];

        //拼凑时间
        $ship_merge = $info['data']['account_start_time'].'--'.$info['data']['account_end_time'];
        $arr = $info['data']['data'];
        $params = [
            'arr'=>$arr,
            'pages'=>$pages,
            'shop_name'=>$shop_name,
            'ship_merge'=>$ship_merge,
            'status'=>$status,
            'info'=>$info,
            'shop_id'=>$shop_id,
            'account_id'=>$account_id
        ];
        return $this->render('details', $params);
    }

    /**
     * 商家待结算流水订单详细
     * @return string
     */
    public function actionParticulars()
    {
        $model = new Shop();
        $account_id = RequestHelper::get('account_id');
        $order_sn = RequestHelper::get('order_sn');
        $shop_id = RequestHelper::get('shop_id');
        $list = $model->details_other($shop_id, $order_sn);
        $data = ArrayHelper::getValue($list, 'data.detail', []);
        $coupons = ArrayHelper::getValue($list, 'data.coupons', []);
        $freight = ArrayHelper::getValue($list, 'data.freight', []);
        $params = [
            'list'=>$data,
            'account_id'=>$account_id,
            'coupons'=>$coupons,
            'freight'=>$freight,
            'info'=>ArrayHelper::getValue($list, 'data.info', []),
            'shop_id'=>$shop_id
        ];
        return $this->render('particulars', $params);
    }

    /**
     * 导出账期中待结算金额大于0的订单
     *
     * @author liuwei <liuwei@iyangpin.com>
     * @return array
     */
    public function actionExport()
    {
        $account_id = RequestHelper::get('account_id', 0);
        if ($account_id>0) {
            $account_model = new ShopAccount();
            $account_item = $account_model->getInfo(['id'=>$account_id]);
            if (empty($account_item)) {
                echo $this->error('账期不存在');
            } else {
                $shop_model = new Shop();
                $shop_item = $shop_model->getInfo(['id'=>$account_item['shop_id']]);
                $shop_name = empty($shop_item) ? '' : $shop_item['shop_name'];
                $url = "shop/account/order-list?shop_id=".$account_item['shop_id'].'&account_id='.$account_id;
                $result = CurlHelper::get($url, 'server');
                if ($result['code']==200) {
                    if (empty($result['data'])) {
                        echo $this->error('账期中没有待结算金额大于0的订单');
                    } else {
                        $this->write($result['data'], $shop_name);
                    }
                }
            }
        } else {
            echo $this->error('账期id不能为空');
        }
    }

    /**
     * 导出表格
     *
     * @param array  $list      数组
     * @param string $shop_name 商家id
     *
     * @author liuwei <liuwei@iyangpin.com>
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @return array
     */
    public  function write($list,$shop_name)
    {
        $name    = $shop_name.'商家的待结算订单列表'.date("Y-m-d H:i:s");//文件名
        error_reporting(E_ALL);
        //date_default_timezone_set('Europe/London');
        $objPHPExcel = new \PHPExcel();
        $header = array(
            'A' => $shop_name.'商家的待结算订单列表',
            'B' => '',
            'C' => '',
            'D' => '',
            'E' => '',
            'F' => '',
            'G' => '',
        );
        foreach ($header as $key => $value) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($key . '1', $value);
            $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(12);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '订单编号')
            ->setCellValue('B2', '交易日期')
            ->setCellValue('C2', '支付类型')
            ->setCellValue('D2', '交易金额（元）')
            ->setCellValue('E2', '已结算（元）')
            ->setCellValue('F2', '待结算（元）');
        //设置样式
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(19);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        foreach ($list as $k => $v) {
            $num=$k+3;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，account_bank是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, '`'.$v['order_sn'].'`')
                ->setCellValue('B'.$num, $v['ship_status_time'])
                ->setCellValueExplicit('C'.$num, $v['pay_method'])
                ->setCellValue('D'.$num, sprintf("%0.2f", $v['total']))
                ->setCellValue('E'.$num, $v['settled'])
                ->setCellValue('F'.$num, $v['unsettled']);
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}

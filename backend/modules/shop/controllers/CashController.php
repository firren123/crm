<?php
/**
 * 商家提现申请
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   CashController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/5/28 0028 下午 5:00
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\modules\shop\controllers;
use backend\controllers\BaseController;
use backend\models\i500m\AuthorityShop;
use backend\models\i500m\Log;
use backend\models\shop\ShopAccount;
use backend\models\shop\ShopCash;
use backend\models\pay\PayCash;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * CashController
 *
 * @category CRM
 * @package  CashController
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class CashController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new ShopCash();
        $search = RequestHelper::get('Search');
        $cond = [];
        $and_where = "1=1";
        if (!empty($search['shop_id'])) {
            $cond['shop_id'] = $search['shop_id'];
        }
        if (!empty($search['status'])) {
            $cond['status'] = $search['status']==2 ? 0 : $search['status'];
        }
        $page = RequestHelper::get('page', 1);
        $list = $model->getPageList($cond, '*', 'id desc', $page, $this->size, $and_where);
        $total_price = 0;
        if ($list) {
            foreach ($list as $v) {
                $total_price += $v['status']==1 ? $v['money'] : 0;
            }
        }
        $total = $model->getCount($cond, $and_where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        return $this->render('index', ['list'=>$list,'pages'=>$pages,'total_price'=>number_format($total_price, 2, '.', ''),'search'=>$search, 'total'=>$total]);
    }

    /**
     * 详情页
     *
     * @return string
     */
    public function actionInfo()
    {
        $data['id'] = RequestHelper::get('id');
        $model = new ShopCash();
        $item = $model->getInfo($data);
        $id = [];
        if ($item) {
            $id = explode(',', $item['account_id']);
        }
        $account_model = new ShopAccount();
        $data = $account_model->getList(['id'=>$id]);
        return $this->render('info', ['item'=>$item,'data'=>$data]);
    }

    /**
     * 审核操作
     *
     * @return string
     */
    public function actionUpdateStatus()
    {
        $id = RequestHelper::get('id');
        $status = RequestHelper::get('status');
        $list = [];
        $list['code'] = 0;
        $list['message'] = '参数错误';
        if ($id and $status>=0) {

            $model = new ShopCash();
            $account_model = new ShopAccount();
            $item = $model->getInfo(['id'=>$id]);
            if (empty($item)) {
                $list['code'] = 2;
                $list['message'] = '暂无数据';
            } else {
                $data['status'] = $status;
                $data['handle_time'] = date('Y-m-d H:i:s');
                $result = $model->updateInfo($data, ['id'=>$id]);
                //日志记录
                $log = new Log();
                $status_name = $status==1 ? "审核通过" : "审核中";
                $cash_content = "管理员：".\Yii::$app->user->identity->username.",修改了商家:".$item['shop_name'].",提现申请账期为:".$item['account_period']."的审核状态，修改成了".$status_name;
                $log->recordLog($cash_content, 2);
                if ($result==true) {
                    $list['code'] = 1;
                    $list['message'] = '操作成功';
                    $cash_model = new PayCash();
                    //付款中的详情
                    $cash_item = $cash_model->getInfo(['shop_id'=>$item['shop_id'],'status'=>0], true);
                    $cash_id_data = explode(",", $cash_item['cash_id']);
                    $cash_result = in_array($id, $cash_id_data);
                    if ($status==1) {
                        $account_id = $item['account_id'];
                        $account_model->updateInfo(['is_apply'=>2], ['id'=>$account_id]);
                        //最后一个支付失败的详情
                        $item_cash = $cash_model->getInfo(['shop_id'=>$item['shop_id'],'status'=>1], true, 'cash_id', 'id desc');
                        $cash_id = empty($item_cash) ? '' : $item_cash['cash_id'];
                        //付款中的是否存在
                        $list_cash = $cash_model->getOneRecord(['shop_id'=>$item['shop_id'],'status'=>0], ['like', 'cash_id', $cash_id]);
                        $pay_cash_id = !empty($list_cash) ? '' : $cash_id;
                        if ($cash_item) {
                            if ($cash_result==false) {
                                $cash_data['cash_id'] = $cash_item['cash_id'].$pay_cash_id.$id.',';
                                $cash_data['money'] = $cash_item['money'] + $item['money'];
                                $cash_model->updateInfo($cash_data, ['shop_id' => $item['shop_id'],'status'=>0]);
                            }
                        } else {
                            $cash_data['shop_id'] = $item['shop_id'];
                            $cash_data['account_bank'] = $item['account_bank'];
                            $cash_data['account_name'] = $item['account_name'];
                            $cash_data['account_card'] = $item['account_card'];
                            $cash_data['create_time'] = date('Y-m-d H:i:s');
                            $cash_data['money'] = $item['money'];
                            $cash_data['cash_id'] = $pay_cash_id.','.$item['id'].',';
                            $cash_model->insertinfo($cash_data);
                        }
                    }
                    if ($status==0) {
                        if ($cash_item) {
                            if ($cash_result==true) {
                                $array[] = $id;
                                $array1 = array_diff($cash_id_data, $array);
                                $cash_id = implode($array1, ',');
                                if ($cash_id=="") {
                                    $cash_model->deleteAll(['shop_id' => $item['shop_id']]);
                                } else {
                                    $cash_data['cash_id'] = $cash_id;
                                    $cash_data['money'] = $cash_item['money'] - $item['money'];
                                    $cash_model->updateInfo($cash_data, ['shop_id' => $item['shop_id'],'status'=>0]);
                                }

                            }
                        }
                        $account_id = $item['account_id'];
                        $account_model->updateInfo(['is_apply'=>1], ['id'=>$account_id]);
                    }
                }
            }
        }
        return json_encode($list);
    }

    /**
     * 检查数据是否可以导出到Excel
     * @author   weitonghe <weitonghe@iyangpin.com>
     * @return string
     */
    public function actionExport()
    {
        $model = new ShopCash();
        $cond    ['status']        = 1;
        $and_cond['export_status'] = 1;
        $field                     = 'id,shop_id,account_bank,account_name,account_card,money';
        $order                     = '';
        $model_result = $model->getList($cond, $field, $order, $and_cond);
        if (empty($model_result)) {
            echo $this->renderPartial(
                '/../../../views/layouts/success',
                [
                    'message' => 0,
                    'error'   => '没有可以导出的数据',
                    'jumpUrl' => '/shop/cash/index',
                    'waitSecond' => 3,
                ]
            );
            return;
        }
        //判断shop_id 是否存在于商家黑名单列表中 并且status=1  过滤掉已经成为黑名单的商家
        unset($cond);
        $AuthorityShop_model = new AuthorityShop();
        $cond['status'] = 1 ;    //状态为禁用
        $AuthorityShop_model_result = $AuthorityShop_model->getList($cond, 'shop_id');
        if (!empty($AuthorityShop_model_result)) {
            foreach ($model_result as $k=>$v) {
                foreach ($AuthorityShop_model_result as $kk=>$vv) {
                    if ($v['shop_id']==$vv['shop_id']) {
                        unset($model_result[$k]);
                    }
                }
            }
            $i = 0 ;
            $model_results = [];
            foreach ($model_result as $k=>$v) {
                if ($v) {
                    $model_results[$i] = $model_result[$k];
                    $i++;
                }
            }
        } else {
            $model_results = $model_result;
        }
        if (empty($model_results)) {
            echo $this->renderPartial(
                '/../../../views/layouts/success',
                [
                    'message' => 0,
                    'error'   => '没有可以导出的数据',
                    'jumpUrl' => '/shop/cash/index',
                    'waitSecond' => 3,
                ]
            );
            return;
        }
        //生成pay_cash表里的数据
        unset($id);
        $t            = time();
        $current_time = date("YmdHis", $t);                                //批号
        $create_time  = date("Y-m-d H:i:s", $t);                           //create_time
        $list         = [];
        foreach ($model_results as $k=>$v) {
            $id[$k]                   = $v['id'];
            $list[$k]['cash_id']      = $v['id'];
            $list[$k]['shop_id']      = $v['shop_id'];
            $list[$k]['export_num']   = $current_time;
            $list[$k]['account_bank'] = $v['account_bank'];
            $list[$k]['account_name'] = $v['account_name'];
            $list[$k]['account_card'] = $v['account_card'];
            $list[$k]['money']        = $v['money'];
            $list[$k]['create_time']  = $create_time;
            $list[$k]['status']       = 0;
        }
        //把$list数据写入pay_cash表里
        $pay_cash_model = new PayCash();
        foreach ($list as $k=>$v) {
            $pay_cash_model_result = $pay_cash_model->insertinfo($v);
            if (!$pay_cash_model_result) {
                echo $this->renderPartial('/../../../views/layouts/success', ['message' => 0, 'error'   => '导出数据出错 ，请重试!', 'jumpUrl' => '/shop/cash/index', 'waitSecond' => 3,]);
                return;
            }
        }
        //更改shop_cash表里export_status的状态 export_status=>2
        foreach ($id as $k=>$v) {
            $result = $model->status($v);
            if (!$result) {
                echo $this->renderPartial('/../../../views/layouts/success', ['message' => 0, 'error'   => '导出数据出错 ，请重试!', 'jumpUrl' => '/shop/cash/index', 'waitSecond' => 3,]);
                return;
            }
        }
        //生成Excel文件
        $this->_Write($list, $current_time);
    }

    /**
     * 执行数据导出到Excel操作
     *
     * @param array  $list         数据
     * @param string $current_time 批号
     *
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @return boolean
     */
    public static function _Write($list, $current_time)
    {
        $name    = $current_time.\Yii::$app->user->identity->username;//文件名
        error_reporting(E_ALL);
        //date_default_timezone_set('Europe/London');
        $objPHPExcel = new \PHPExcel();
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', '请填写业务参考号：')
            ->setCellValueExplicit("C1", strval($current_time), \PHPExcel_Cell_DataType::TYPE_STRING)//设置单元格格式问文本类型
            ->setCellValue('D1', '填写模板须知：
1、此参考号要求每批唯一，作为查询和上传的标识!注意不能超过30位!
2、模板中的黄色区域需要填写，白色区域为选填。行数可以根据需要自行增减。
3、模板中的“银行名称”一列，请填写例如“中国工商银行”等大行名称，无需细化到支行、分行，如匹配不到您填写的银行名称，将转换为城市商业银行进行付款。
4、从单位银行结算账户向个人银行结算账户支付款项单笔超过5万元人民币时，请务必在模板中的备注栏注明事由，并对支付款项事由的真实性、合法性负责。')
            ->setCellValue('A2', '银行名称')
            ->setCellValue('B2', '收款方姓名')
            ->setCellValue('C2', '收款方银行账号')
            ->setCellValue('D2', '金额')
            ->setCellValue('E2', '备注（50字以内）')
            ->setCellValue('F2', '商家订单号');
        //图片
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath('./images/kuaiqian.png');
        $objDrawing->setHeight(70);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setOffsetX(10);
        $objDrawing->setOffsetY(10);
        $objDrawing->setRotation(25);
        $objDrawing->getShadow()->setVisible(true);
        $objDrawing->getShadow()->setDirection(45);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        //设置样式
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->getStartColor()->setARGB('010010255');
        $objPHPExcel->getActiveSheet()->getStyle('B1:C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFill()->getStartColor()->setARGB('#FF9FF9');
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(70);
        $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true)->setSize(13)->getColor()->setARGB('#FFFFFFF');
        $objPHPExcel->getActiveSheet()->mergeCells('D1:F1');
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true)->setSize(7)->getColor()->setARGB('0199140');
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(19);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $num = 0;
        foreach ($list as $k => $v) {
            $num=$k+3;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，account_bank是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $v['account_bank'])
                ->setCellValue('B'.$num, $v['account_name'])
                //->setCellValue('C'.$num, $v['account_card'])
                ->setCellValueExplicit('C'.$num, $v['account_card'], \PHPExcel_Cell_DataType::TYPE_STRING)//设置单元格格式问文本类型
                ->setCellValue('D'.$num, $v['money']);
            //->setCellValue('E'.$num, $v['e']);

        }
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getTop()->getColor()->setARGB('000000000');
        $objPHPExcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getBottom()->getColor()->setARGB('000000000');
        $objPHPExcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getLeft()->getColor()->setARGB('000000000');
        $objPHPExcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2:A3')->getBorders()->getRight()->getColor()->setARGB('000000000');
        //$objPHPExcel->getActiveSheet()->getStyle('A1');
        $objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A3'), 'A3:F'.$num);//复制单元格样式
        $objPHPExcel->getActiveSheet()->getStyle('A3:D'.$num)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A3:D'.$num)->getFill()->getStartColor()->setARGB('#FFFF99');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}

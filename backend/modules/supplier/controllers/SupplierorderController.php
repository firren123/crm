<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 供应商下单
 *
 * @category  PHP
 * @package   Admin
 * @filename  SupplierorderController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/26 下午2:34
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\supplier\controllers;


use backend\controllers\BaseController;
use backend\models\i500m\Branch;
use backend\models\i500m\Category;
use backend\models\i500m\PaySite;
use backend\models\i500m\Supplier;
use backend\models\i500m\SupplierGoods;
use backend\models\i500m\SupplierOrder;
use backend\models\i500m\SupplierOrderDetails;
use backend\models\i500m\Warehouse;
use common\helpers\RequestHelper;
use yii\data\Pagination;

/**
 * Class SupplierorderController
 * @category  PHP
 * @package   SupplierorderController
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SupplierorderController extends BaseController
{

    public $pay_site_id_data = [
        //1=>'银行付款',
        //2=>'货到付款',
        //3=>'账期付款'

    ];

    public $pay_status_data = [
        0 => '未付款',
        1 => '已付款',
    ];

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function init()
    {
        parent::init();
        //获取支付类型
        $pay_site = new PaySite();
        $pay_site_arr = $pay_site->getList(1, 'id,name');
        $this->pay_site_id_data[0] = '--';
        foreach ($pay_site_arr as $k => $v) {
            $this->pay_site_id_data[$v['id']] = $v['name'];
        }
    }

    /**
     * 简介：订单列表
     * @author  sunsongsong@iyangpin.com。
     * @return null
     */
    public function actionOrderList()
    {

        $name = RequestHelper::get('name', '');
        $page = RequestHelper::get('page', 1, 'intval');
        $pay_time_one = RequestHelper::get('pay_time_one');
        $pay_time_two = RequestHelper::get('pay_time_two');
        $pay_status = RequestHelper::get('pay_status', 999, 'intval');
        $order_sn = RequestHelper::get('order_sn');
        $supplier_name = RequestHelper::get('supplier_name', '');
        $receive_time_one = RequestHelper::get('arrange_receive_time_one');
        $receive_time_two = RequestHelper::get('arrange_receive_time_two');
        $where = " 1 ";
        if (999 != $pay_status) {
            $where .= " and pay_status =" . $pay_status;
        }

        if (!empty($pay_time_one)) {
            $where .= " and create_time >= '{$pay_time_one} 00:00:00'";
        }

        if (!empty($pay_time_two)) {
            $where .= " and create_time <= '{$pay_time_two} 23:59:59'";
        }

        if (!empty($receive_time_one)) {
            $where .= " and arrange_receive_time >= '{$receive_time_one} 00:00:00'";
        }

        if (!empty($receive_time_two)) {
            $where .= " and arrange_receive_time <= '{$receive_time_two} 23:59:59'";
        }

        if (!empty($order_sn)) {
            $order_sn = htmlspecialchars($order_sn, ENT_QUOTES, "UTF-8");
            $where .= " and order_sn = '{$order_sn}'";
        }
        $and_where = array();
        if (!empty($supplier_name)) {
            $and_where = ['like', 'supplier_name', $supplier_name];
        }

        $search = new SupplierOrder();
        $filed = 'id,order_sn,total,supplier_name,pay_status,remark,create_time,arrange_receive_time';
        $count = $search->getCount($where, $and_where);

        $search_list = $search->getPageList($where, $filed, "id desc", $page, 10, $and_where);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => 10]);

        return $this->render(
            'orderlist',
            [
                'name' => $name,
                'count' => $count,
                'pages' => $pages,
                'list' => $search_list,
                'order_sn' => $order_sn,
                'pay_status' => $pay_status,
                'pay_time_one' => $pay_time_one,
                'pay_time_two' => $pay_time_two,
                'supplier_name' => $supplier_name,
                'receive_time_one' => $receive_time_one,
                'receive_time_two' => $receive_time_two,
                'pay_status_data' => $this->pay_status_data
            ]
        );
    }

    /**
     * 简介：下单
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionOrder()
    {
        $branch_m = new Branch();
        $branch_list = $branch_m->getList(['status' => 1], 'id,name');
        return $this->render(
            'order',
            [
                'pay_site_id_data' => $this->pay_site_id_data,
                'branch_list' => $branch_list
            ]
        );
    }

    /**
     * 简介：下单提交页面
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function actionAddOrder()
    {
        $company_name = RequestHelper::post('company_name', '');
        $sp_id = RequestHelper::post('sp_id', 0, 'intval');
        $goods = RequestHelper::post('goods');
        $pay_site_id = RequestHelper::post('pay_site_id', 0, 'intval');
        $arrange_receive_time = RequestHelper::post('arrange_receive_time');
        $ware_house_id = RequestHelper::post('ware_house_id', 0, 'intval');
        $remark = RequestHelper::post('remark');
        if ($sp_id == 0) {
            return $this->error('请选择分公司');
        }
        if ($ware_house_id == 0) {
            return $this->error('请选择仓库');
        }
        if ($pay_site_id == 0) {
            return $this->error('请选择支付方式');
        }
        if (empty($arrange_receive_time)) {
            return $this->error('请选择交货日期');
        }
        if (empty($goods)) {
            return $this->error('商品不能为空');
        }
        $order_sn = $this->getOrderSn();  //订单号
        //获取产品信息
        $all_total = 0;
        $all_freight = 0;
        $supplier_goods_model = new SupplierGoods();
        foreach ($goods as $k => $v) {
            //邮费
            $supplier_goods_info = $supplier_goods_model->getInfo(['id' => $k]);
            $goods[$k]['image'] = $supplier_goods_info['image'];
            $goods[$k]['name'] = $supplier_goods_info['title'];
            $goods[$k]['supplier_id'] = $sp_id;
            $goods[$k]['order_sn'] = $order_sn;
            $goods[$k]['goods_id'] = $k;
            $goods[$k]['attr_value'] = $supplier_goods_info['attr_value'];
            $goods[$k]['attribute_str'] = $supplier_goods_info['unit'];
            $goods[$k]['info'] = $v['remark'];
            $goods[$k]['total'] = $v['num'] * ($v['price'] + $supplier_goods_info['logistic']);
            $goods[$k]['freight'] = $v['num'] * $supplier_goods_info['logistic'];
            $goods[$k]['bar_code'] = $supplier_goods_info['bar_code'];
            unset($goods[$k]['remark']);
            unset($goods[$k]['id']);
            $all_total += $goods[$k]['total'];
            $all_freight += $goods[$k]['freight'];
        }

        //获取仓库信息
        $ware_model = new Warehouse();
        $ware_info = $ware_model->getInfo(['id' => $ware_house_id], "*");
        //插入订单表
        $order_data = [];
        $order_data['order_sn'] = $order_sn; //订单号
        $order_data['supplier_id'] = $sp_id;  //供应商ID
        $order_data['supplier_name'] = $company_name; //供应商名称
        $order_data['total'] = $all_total;  //订单总价,含运费
        $order_data['freight'] = $all_freight;  //运费
        $order_data['remark'] = $remark;  //备注
        $order_data['status'] = 0;  //订单状态(0未确认 1已确认 2取消  -1删除)
        $order_data['pay_status'] = 0;  //支付状态 0=未付款 1=已付款
        $order_data['ship_status'] = 0;  //配送状态,0=待发货,1=待收货,2=已完成,3=有退货
        $order_data['create_time'] = date('Y-m-d H:i:s');  //创建时间
        $order_data['consignee'] = $ware_info['username'];  //收货人
        $order_data['address'] = $ware_info['address'];  //详细地址
        $order_data['province'] = $ware_info['province_id'];  //省
        $order_data['city'] = $ware_info['city_id'];   //市
        $order_data['district'] = $ware_info['district_id']; //区县
        $order_data['mobile'] = $ware_info['phone'];   //电话
        $order_data['postcode'] = $ware_info['postcode'];   //邮编
        $order_data['pay_site_id'] = $pay_site_id;  //支付类型
        $order_data['arrange_receive_time'] = $arrange_receive_time;  //约定交货日期
        $order_data['storage_id'] = $ware_house_id;  //库房ID
        $supplier_order_model = new SupplierOrder();
        $ret = $supplier_order_model->insertOneRecord($order_data);
        if ($ret['result'] == 0) {
            return $this->error('下单失败');
        }
        $order_id = $ret['data']['new_id'];
        //插入订单详情
        $order_detail_model = new SupplierOrderDetails();
        foreach ($goods as $k => $v) {
            $v['order_id'] = $order_id;
            $order_detail_model->insertInfo($v);
        }
        return $this->success('添加成功', '/supplier/supplierorder/order-list');
    }

    /**
     * 简介：获取供应商列表
     * param array $arr x
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionSupplierList()
    {
        $this->layout = 'dialog';
        $page = RequestHelper::get('page', 1, 'intval');
        $id = RequestHelper::get('id', 0, 'intval');
        $company_name = RequestHelper::get('company_name', '');

        $supplier_m = new Supplier();
        $where = " `status` != 2 ";
        if ($id) {
            $where .= " and id =" . $id;
        }
        $and_where = array();
        if ($company_name) {
            $and_where = ['like', 'company_name', $company_name];
        }
        $count = $supplier_m->getCount($where, $and_where);
        $list = $supplier_m->getPageList($where, "*", 'id desc', $page, 10, $and_where);
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => 10]);
        $id = $id ? $id : '';
        return $this->render(
            'supplierlist',
            [
                'pages' => $pages,
                'list' => $list,
                'id' => $id,
                'company_name' => $company_name,

            ]
        );

    }

    /**
     * 简介：
     * @return string
     */
    public function actionGoodsList()
    {
        $this->layout = 'dialog';
        $sp_id = RequestHelper::get('sp_id', 0, 'intval');
        $page = RequestHelper::get('page', 1, 'intval');
        $cate_id = RequestHelper::get('cate_id', -1, 'intval');
        $goods_name = RequestHelper::get('goods_name', '');
        if ($sp_id) {
            $supplier_goods_m = new SupplierGoods();
            $category_m = new Category();
            $where = ['status' => 2, 'level' => 1];
            $cate_l = $category_m->getList($where, 'id,name');
            $cate_list = [];
            foreach ($cate_l as $k => $v) {
                $cate_list[$v['id']] = $v['name'];
            }
            $swhere = " supplier_id=" . $sp_id;
            $swhere .= " and status =5 ";
            $and_where = array();
            if ($goods_name) {
                $and_where = ['like', 'title', $goods_name];
            }
            if ($cate_id != -1) {
                $swhere .= " and category_id =" . $cate_id;
            }
            $count = $supplier_goods_m->getCount($swhere, $and_where);
            $goods_list = $supplier_goods_m->getPageList($swhere, "*", "id desc", $page, 10, $and_where);

            $pages = new Pagination(['totalCount' => $count, 'pageSize' => 10]);
            return $this->render(
                'goodslist',
                [
                    'pages' => $pages,
                    'list' => $goods_list,
                    'sp_id' => $sp_id,
                    'cate_list' => $cate_list,
                    'cate_id' => $cate_id,
                    'goods_name' => $goods_name
                ]
            );

        } else {
            echo '先选择供应商';
        }
    }

    /**
     * 简介：获取订单号
     * param array $arr x
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    protected function getOrderSn()
    {
        $time = time();
        $rand4 = mt_rand(1000, 9999);
        return 'CG-' . $time . '-' . $rand4;

    }

    /**
     * 简介：获取一条商品
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionGetGoods()
    {
        $supplier_goods_m = new SupplierGoods();
        $id = RequestHelper::get('id', 0, 'intval');
        $where = ['id' => $id];
        $supp_info = $supplier_goods_m->getInfo($where, true, "*");
        echo json_encode($supp_info);
    }

    /**
     * 简介：获取商家信息
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionGetSupplierInfo()
    {
        $supplier_m = new Supplier();
        $id = RequestHelper::get('id', 0, 'intval');
        $where = ['id' => $id];
        $supp_info = $supplier_m->getInfo($where, true, "id,company_name,contact,email,mobile,phone");
        echo json_encode($supp_info);

    }

    /**
     * 简介：获取仓库列表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionGetWareHouse()
    {
        $bc_id = RequestHelper::get('bc_id');
        $ware_m = new Warehouse();
        $ware_list = $ware_m->getList(['bc_id' => $bc_id], "id,name", "id desc");
        echo json_encode($ware_list);
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionOrderDetail()
    {
        $order_sn = RequestHelper::get('order_sn');
        $order_detail_model = new SupplierOrderDetails();
        $field = "id,name,attr_value,price,num,total,info,attribute_str";
        $detail_list = $order_detail_model->getList(['order_sn' => $order_sn], $field, "id desc");
        echo json_encode($detail_list);

    }
}

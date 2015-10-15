<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  setting_detail.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 上午11:02
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
?>
<?= $this->registerCssFile("@web/css/globalm.css"); ?>
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '服务订单详情';

?>
<div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a
        href="/social/service/order">服务订单</a><span>&gt;</span><span>服务订单详情</span>
</div>
<table class="table table-bordered table-hover">

    <!--        订单状态、服务信息（名称，价格，预约时间，预约地点）、支付信息（支付方式，支付状态）联系信息（体验方名称、手机，服务方名称、手机）-->

    <tr>
        <th colspan="30">服务信息</th>
    </tr>
    <tr>
        <th>名称：</th>
        <td><?= $list['service_info_title'];?></td>
        <th>价格：</th>
        <td><?= $list['total'];?></td>
        <th>预约时间：</th>
        <td><?= $list['appointment_service_time'];?></td>
        <th>预约地点：</th>
        <td><?= $list['appointment_service_address'];?></td>
    </tr>
    <tr>
        <th colspan="30">联系信息</th>
    </tr>
    <tr>
        <th>体验方名称：</th>
        <td><?= $list['uid_name'];?></td>
        <th>手机：</th>
        <td><?= $list['service_mobile'];?></td>
        <th>服务方名称：</th>
        <td><?= $list['service_name'];?></td>
        <th>手机：</th>
        <td><?= $list['service_mobile'];?></td>
    </tr>
    <tr>
        <th colspan="30">订单信息</th>
    </tr>

    <tr>
        <th>订单状态：</th>
        <td><?= $order_status_data[$list['status']]; ?></td>
        <th>订单支付状态：</th>
        <td><?= $order_pay_status_data[$list['pay_status']]; ?></td>
        <th>创建时间：</th>
        <td><?= $list['create_time']; ?></td>
        <td></td>
        <td></td>
    </tr>




    <tr>
        <td colspan="10">
            <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
        </td>
    </tr>

</table>



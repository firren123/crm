<?= $this->registerCssFile("@web/css/globalm.css"); ?>
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '订单详情';
?>
<div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a href="/social/orderchinaepay/index">服务</a><span>&gt;</span><span>服务详情</span>
</div>
<table class="table table-bordered table-hover">
<tr>
    <th>手机号：</th>
    <td><?=$list['mobile'];?></td>
</tr>


    <tr>
        <th>订单号：</th>
        <td><?=$list['order_sn'];?></td>
    </tr>

    <tr>
        <th>业务唯一编号/编码：</th>
        <td><?=$list['business_code'];?></td>
    </tr>
    <tr>
        <th>订单金额：</th>
        <td><?=$list['total'];?></td>
    </tr>
    <tr>
        <th>购买数量：</th>
        <td><?=$list['total_number'];?></td>
    </tr>
    <tr>
        <th>订单状态：</th>
        <td><?=$status_data[$list['status']];?></td>
    </tr>
    <tr>
        <th>支付状态：</th>
        <td><?=$pay_status_data[$list['pay_status']];?></td>
    </tr>
    <tr>
        <th>支付方式：</th>
        <td><?php if(isset($pay_site_id_data[$list['pay_site_id']])){echo $pay_site_id_data[$list['pay_site_id']];};?></td>
    </tr>
    <tr>
        <th>创建时间：</th>
        <td><?=$list['create_time'];?></td>
    </tr>
    <tr>
        <th>支付时间：</th>
        <td><?=$list['pay_time'];?></td>
    </tr>
    <tr>
        <th>支付返回的交易流水号：</th>
        <td><?=$list['unionpay_tn'];?></td>
    </tr>
    <tr>
        <th>业务类型：</th>
        <td><?=$business_type_data[$list['business_type']];?></td>
    </tr>
    <tr>
        <th>订单处理状态：</th>
        <td><?=$handle_status_data[$list['handle_status']];?></td>
    </tr>
    <tr>
        <th>订单处理时间：</th>
        <td><?=$list['handle_time'];?></td>
    </tr>
    <tr>
        <th>来源：</th>
        <td><?=$source_type_data[$list['source_type']];?></td>
    </tr>
    <tr>
        <th>小区城市：</th>
        <td><?=$list['community_city_id'];?></td>
    </tr>
    <tr>
        <th>小区：</th>
        <td><?=$list['community_id'];?></td>
    </tr>
    <tr>
        <th>订单备注：</th>
        <td><?=$list['remark'];?></td>
    </tr>
    <tr>
        <td colspan="10">
            <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
        </td>
    </tr>
</table>







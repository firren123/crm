<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>
<style>
    table td, table th {
        padding: 9px 10px;
        text-align: left;
    }
</style>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class="active"><a href="/couponstype"></a> 优惠券类别管理</a></li>
        <li class="active">查看优惠券分类</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
            <table>
                <tbody>
                <tr>
                    <td>名称：</td>
                    <td><?= $item['type_name'];?></td>
                </tr>
                <tr>
                    <td>发放类型：</td>
                    <td><?= $item['send_type']!=1 ? '按用户发放' : '线下发放';?></td>
                </tr>
                <tr>
                    <td>现金券面值：</td>
                    <td><?= $item['par_value'];?></td>
                </tr><tr>
                    <td>最小订单金额：</td>
                    <td><?= $item['min_amount'];?></td>
                </tr>
                <tr>
                    <td>缩略图：</td>
                    <td><img src="<?= $item['coupon_thumb'];?>" style="max-height: 100px; max-height: 100px"/></td>
                </tr>
                <tr>
                    <td>消费积分：</td>
                    <td><?= $item['consumer_points'];?></td>
                </tr>
                <tr>
                    <td>添加时间：</td>
                    <td><?= $item['add_time'];?></td>
                </tr>
                <tr>
                    <td>开始时间：</td>
                    <td><?= $item['start_time'];?></td>
                </tr>
                <tr>
                    <td>过期时间：</td>
                    <td><?= $item['expired_time'];?></td>
                </tr>
                <tr>
                    <td>是否可用：</td>
                    <td><?= $item['used_status']!=0 ? '不可使用' : '可以使用';?></td>
                </tr><tr>
                    <td>发放数量：</td>
                    <td><?= $item['number'];?></td>
                </tr>
                <tr>
                    <td>用户最多使用数量：</td>
                    <td><?= $item['limit_num'];?></td>
                </tr>
                <tr>
                    <td>现金券来源：</td>
                    <td><?= $item['source'];?></td>
                </tr>
                <tr>
                    <td>是否在前台显示：</td>
                    <td><?= $item['status']!=1 ? '不显示' : '显示';?></td>
                </tr>
                <tr>
                    <td>类型：</td>
                    <td><?= $item['coupon_type']!=1 ? '普通类型' : '注册类型';?></td>
                </tr>


                </tbody>
                </table>
            <a id="yw1" class="btn btn-primary"  href="javascript:history.go(-1);"> 返回</a>
        </div>
    </div>

</legends>
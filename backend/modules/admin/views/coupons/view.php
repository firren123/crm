<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '优惠券详情';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/admin/coupons">优惠券管理</a></li>
        <li class="active">优惠券详情</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover" style="width: 60%;text-align: center;">
            <tbody>
            <?php if (empty($item)) { ?>
                <tr>
                    <td colspan="2">暂无数据</td>
                </tr>
            <?php }else{ ?>
                <tr>
                    <td style="width: 20%">优惠券名称</td>
                    <td><?= $item['type_name'];?></td>
                </tr>
                <tr>
                    <td>优惠券分类</td>
                    <td><?= $item['type_name'];?></td>
                </tr>
                <tr>
                    <td>用户名称</td>
                    <td><?= empty($item['mobile']) ? '--' : $item['mobile'];?></td>
                </tr>
                <tr>
                    <td>最小订单金额</td>
                    <td><?= $item['min_amount'];?></td>
                </tr>
                <tr>
                    <td>现金券面值</td>
                    <td><?= $item['par_value'];?></td>
                </tr>
                <tr>
                    <td>消费积分</td>
                    <td><?= $item['consumer_points'];?></td>
                </tr>

                <tr>
                    <td>订单号</td>
                    <td><?= empty($item['order_sn']) ? '--' : $item['order_sn'];?></td>
                </tr>
                <tr>
                    <td>现金券序列号</td>
                    <td><?= $item['serial_number'];?></td>
                </tr>
                <tr>
                    <td>现金券来源</td>
                    <td><?= $item['source'];?></td>
                </tr>
                <tr>
                    <td>获取时间</td>
                    <td><?= $item['get_time'];?></td>
                </tr>
                <tr>
                    <td>过期时间</td>
                    <td><?= $item['expired_time'];?></td>
                </tr>
                <tr>
                    <td>使用时间</td>
                    <td><?= $item['used_time'];?></td>
                </tr>
                <tr>
                    <td>状态</td>
                    <td><?= empty($item['status_name']) ? '--' : $item['status_name'];?></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td><?= $item['remark'];?></td>
                </tr>
                <tr>
                    <td>是否被领取</td>
                    <td><?= $item['is_geted']==0 ? '未领取' : '已领取';?></td>
                </tr>

            <?php } ?>
            <tr>
                <td colspan="2"><a class="btn cancelBtn" href="/admin/coupons">返回</a></td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
<?php
$this->title = '订单分发优惠券规则';
?>
<script type="text/javascript" src="/js/goods/product.js"></script>
<legends  style="fond-size:12px;">
    <legend>订单分发优惠券规则</legend>
</legends>
<?php
if (!empty($item)) {
    ?>
    <div class="tab-content">
        <table class="table table-bordered table-hover" style="width: 60%">
            <tbody>
            <tr>
                <td style="width:30%;">上限值：</td>
                <td><?= $item['max']?></td>
            </tr>
            <tr>
                <td style="width:20%;">下限值：</td>
                <td><?= $item['min']?></td>
            </tr>
            <tr>
                <td style="width:20%;">最多领取数量：</td>
                <td><?= $item['num'].'个'?></td>
            </tr>
            <tr>
                <td style="width:20%;">有效期：</td>
                <td><?= $item['validity'].'天'?></td>
            </tr>
            <tr>
                <td style="width:20%;">修改时间：</td>
                <td><?= $item['update_time']?></td>
            </tr>
            <tr>
                <td style="width:20%;">状态：</td>
                <td><?= $item['status']==1 ? '开启' : '关闭'?></td>
            </tr>
            <tr>
                <td style="width:20%;">操作：</td>
                <td><a href="/user/ordersendcoupons/edit">编辑</a></td>
            </tr>
            </tbody>
        </table>
    </div>
<?php
} else {
    ?>
    <a id="yw0" class="btn btn-primary" href="/user/ordersendcoupons/add" style="margin-bottom:10px;">增加规则</a>
<?php
}
?>
<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<script>
    clickCheckbox();

</script>
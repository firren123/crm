<?php
use yii\widgets\LinkPager;
$this->title = '退换货订单详情';
?>
<legends  style="fond-size:12px;">
    <legend>退换货订单详情</legend>
</legends>

<div class="tab-content">

    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>订单号</th>
                <th>商家名称</th>
                <th>商品图片</th>
                <th>原因</th>
            </tr>
            </tbody>
            <tfoot>
            <?php
            if (empty($data)) {
                echo '<tr><td colspan="10">暂无记录</td></tr>';
            } else {
                    ?>
                    <tr>
                        <td><?=$data['id']?></td>
                        <td><?=$data['order_sn']?></td>
                        <td><?=$data['shop_name']?></td>
                        <td>
                            <?php if (empty($data['image'])) {
                            echo '暂无图片';
                            }else{
                                foreach($data['image'] as $v):
                             ?>
                            <div style='width: 60px;'>
                                <div style='float: left;margin: 10px 10px 10px 10px'><img width='30' height='20' src="<?=$img_url.$v?>"/></div>
                            </div>
                            <?php endforeach; }?>
                        </td>
                        <td><?=$data['remark']?></td>
                    </tr>
            <?php
            }
            ?>
            </tfoot>
        </table>
    </div>
</div>
<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<script type="text/javascript">

</script>

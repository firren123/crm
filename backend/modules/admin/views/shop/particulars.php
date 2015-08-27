<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
?>
<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>
<legends  style="fond-size:12px;">
    <legend>订单明细</legend>
    <div class="tab-content">
        <div class="row-fluid">
            <div><p>订单编号：<?php echo $info['order_sn']?></p></div>
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">商家名称</th>
                    <th colspan="2">规格</th>
                    <th colspan="2">商品类型</th>
                    <th colspan="2">单价（元）</th>
                    <th colspan="2">数量（件）</th>
                    <th colspan="2">已结算（元）</th>
                    <th colspan="2">待结算（元）</th>
                </tr>
                <?php if(empty($list)) {
                    echo '<tr><td colspan="14" style="text-align:center;">暂无记录</td></tr>';
                }else{?>
                    <?php foreach($list as $k=>$v):?>
                        <tr>
                            <td colspan="2"><?= $v['name'];?></td>
                            <td colspan="2"><?= $v['attribute_str'];?></td>
                            <td colspan="2"><?= ($v['goods_type']==3)?'铺货':($v['goods_type']==1)?'自营':'特供';?></td>
                            <td colspan="2"><?= $v['price'];?></td>
                            <td colspan="2"><?= $v['num'];?></td>
                            <td colspan="2"><?= number_format($v['settled'],2);?></td>
                            <?php if($v['unsettled']>=0){?>
                            <td colspan="2" style="color: red;"><?= $v['unsettled'];?></td>
                            <?php }else{?>
                            <td colspan="2" style="color: #008000;"><?= $v['unsettled'];?></td>
                            <?php }?>
                        </tr>
                        <?php endforeach;?>
                    <?php if(!empty($coupons)){?>
                            <tr>
                                <td colspan="2" class="detailsTabT"><p><?=$coupons['name']; ?></p></td>
                                <td colspan="2">--</td>
                                <td colspan="2"><?$coupons['name']; ?></td>
                                <td colspan="2"><?=$coupons['money']; ?></td>
                                <td colspan="2"><?=$coupons['number']; ?></td>
                                <td colspan="2"><?=$coupons['settled']; ?></td>
                                <td colspan="2"><?=$coupons['unsettled']; ?></td>
                            </tr>
                    <?php }?>
                    <?php if(!empty($freight)){?>
                        <tr>
                            <td colspan="2" class="detailsTabT"><p><?=$freight['name']; ?></p></td>
                            <td colspan="2">--</td>
                            <td colspan="2"><?=$freight['name']; ?></td>
                            <td colspan="2"><?=$freight['money']; ?></td>
                            <td colspan="2"><?=$freight['number']; ?></td>
                            <td colspan="2"><?=$freight['settled']; ?></td>
                            <td colspan="2"><?=$freight['unsettled']; ?></td>
                        </tr>
                    <?php }?>
                    <?php } ?>
                </tbody>
            </table>
            <a href="<?= '/admin/shop/details?shop_id='.$shop_id.'&account_id='.$account_id;?>" class="btn btn-primary" style="margin: 20px 0px 0px 400px;">返回</a>
        </div>
    </div>
</legends>


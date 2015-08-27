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
    <legend>优惠券类别管理</legend>
    <a id="yw0" class="btn btn-primary" href="/admin/couponstype/add" style="margin-bottom:20px;">添加优惠券类别</a>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">类别ID</th>
                    <th colspan="2" style="width: 20%">类别名称</th>
                    <th colspan="2">发送方式</th>
                    <th colspan="2">现金劵面额</th>
                    <th colspan="2">最小订单金额</th>
                    <th colspan="2">消费积分</th>
                    <th colspan="2">是否可用</th>
                    <th colspan="2">发放数量</th>
                    <th colspan="2">系统</th>
                    <th colspan="2">限定区域</th>
                    <th colspan="2">过期时间</th>
                    <th colspan="2">类型</th>
                    <th colspan="4" style="width: 5%">操作</th>
                </tr>
                <?php if(empty($data)) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                    }else{
                    foreach($data as $item){
                ?>
                        <tr>
                            <td colspan="2"><?= $item['type_id'];?></td>
                            <td colspan="2"><?= $item['type_name'];?></td>
                            <td colspan="2"><?= $item['send_type']!=1 ? '按用户发放' : '线下发放';?></td>
                            <td colspan="2"><?= $item['par_value'];?></td>
                            <td colspan="2"><?= $item['min_amount'];?></td>
                            <td colspan="2"><?= $item['consumer_points'];?></td>
                            <td colspan="2"><?= $item['used_status']!=0 ? '不可' : '可用';?></td>
                            <td colspan="2"><?= $item['number'];?></td>
                            <td colspan="2"><?= $item['use_system']==1 ? 'i500' : '社交平台系统';?></td>
                            <td colspan="2"><?= $item['area_name'];?></td>
                            <td colspan="2"><?= $item['expired_time'];?></td>
                            <td colspan="2"><?= empty($item['coupon_name']) ? '--':$item['coupon_name'];?></td>
                            <td colspan="4"> <a href="<?= '/admin/couponstype/view?id='.$item['type_id'];?>">详情</a> <br> <a href="<?= '/admin/coupons/index?type_id='.$item['type_id'].'&use_system='.$item['use_system'];?>">查看</a>
                                <?php if($item['coupon_type']!=2 and $item['coupon_number']==0) :?>
                                    <br> <a href="<?= '/admin/couponstype/delete?id='.$item['type_id'];?>" onclick="return confirm('确定要删除该分类吗？')">删除</a>
                                <?php endif;?>
                                <?php if($item['used_status']==0) :?>
                                <?php if($item['send_type']==1) {?>
                                        <br> <a href="<?= '/admin/coupons/add?type_id='.$item['type_id'];?>">生成</a>
                            <?php }else {?>
                                        <br> <a href="<?= '/admin/coupons/addbyuser?id='.$item['type_id'];?>" >分发</a>
                            <?php }?>
                            <?php endif;?>
                            </td>
                        </tr>
                <?php } }?>

                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>

</legends>
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
    <legend>结算管理</legend>
    <form action="/admin/shop/index" method="get">
        分公司:<select name="branch_id" id="branch">
            <option value="">请选择</option>
            <?php foreach($branch_all as $k=>$v):?>
                <option value="<?php echo $v['id'];?>" <?php if($branch_id == $v['id']){echo "selected";}?>><?php echo $v['name'];?></option>
            <?php endforeach;?>
        </select>
        城市：<select name="city_id" id="city">
            <option value="">请选择</option>
            <?php if($arr){?>
            <?php foreach($arr as $k=>$v):?>
                <option value="<?php echo $v['id'];?>" <?php if($v['id']==$city_id){echo "selected";}?>><?php echo $v['name'];?></option>
            <?php endforeach;?>
            <?php }?>
        </select>
        结算状态：<select name="settle_status" id="name">
            <option value="-1" <?php if($settle_status == -1){echo "selected";}?>>请选择</option>
            <option value="0" <?php if($settle_status == 0){echo "selected";}?>>未结算</option>
            <option value="1" <?php if($settle_status == 1){echo "selected";}?>>已结算</option>
            <option value="2" <?php if($settle_status == 2){echo "selected";}?>>冻结</option>
        </select>
        待结算金额：<select name="positive_minus" id="minus">
            <option value="">请选择</option>
            <option value="1" <?php if($positive_minus == 1){echo "selected";}?>>待收款</option>
            <option value="2" <?php if($positive_minus == 2){echo "selected";}?>>待付款</option>
        </select>
       商家ID：<input type="text" name="shop_id">
        <input type="submit"  class="btn btn-primary" value="搜索">
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
    </form>
    <div class="tab-content">
        <div class="row-fluid">
            <div style="padding: 0px  0px  0px  830px;color: red;"><p>共<?php echo $total;?>条记录</p></div>
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">商家ID</th>
                    <th colspan="2">商家名称</th>
                    <th colspan="2">账期时间</th>
                    <th colspan="2">结算状态</th>
                    <th colspan="2">待结算金额</th>
                    <th colspan="4">操作</th>    <!--22-->
                </tr>
                <?php if(empty($list)) {
                    echo '<tr><td colspan="14" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($list as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['shop_id'];?></td>
                            <td colspan="2"><?= $item['shop_name'];?></td>
                            <td colspan="2"><?= $item['settle_time'];?></td>                       <!--area-->
                            <td colspan="2"><?= ($item['status'] != 2)?(($item['status']=='1') ? '已结算' : '未结算'):'冻结' ;?></td>
                            <?php if($item['money']>=0){?>
                            <td colspan="2" style="color: red;"><?= $item['money'];?></td>
                            <?php }else{?>
                            <td colspan="2" style="color: green;"><?= $item['money'];?></td>
                            <?php }?>
                            <td colspan="4"><a href="<?= '/admin/shop/details?shop_id='.$item['shop_id'].'&account_id='.$item['id'];?>">详情</a></td>
                        </tr>
                    <?php } }?>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2"></td>
                    <td colspan="2"></td>
                    <td colspan="2">小计</td>
                    <td colspan="2"><?php echo $number_all;?></td>
                    <td colspan="4"></td>
                </tr>
                </tbody>
            </table>
            <?php echo LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</legends>
<input type="hidden" id="pro_id" value="<?= $branch_id?>"/>
<input type="hidden" id="city_id" value="<?= $city_id?>"/>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>
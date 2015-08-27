<?= $this->registerCssFile("@web/css/globalm.css");?>
<link rel="stylesheet" href="css/ui-dialog.css">
<script src="dist/dialog-min.js"></script>
<script>
    var dialog = require('./src/dialog');
    function al(){
        var d = dialog({
            title: '欢迎',
            content: '欢迎使用 artDialog 对话框组件！'
        });
        d.show();
    }
    $("#sss").click(function(){
        al();
    })
</script>

<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '商家订单详情';
?>
<div class="content fr">
    <div class="breadcrumbs">您现在的位置：<a href="/shop/shoporder/index">首页</a><span>&gt;</span><a href="/shop/shoporder/index">订单管理</a><span>&gt;</span><span class="current">订货详情</span></div>
    <div class="currrnttitle">交易详情</div>
    <div class="indentbox">
        <div class="indenttitle">收货人信息</div>
        <ul>
            <li>
                <dl>
                    <dt>商户名称：</dt>
                    <dd><?php echo $shop_name;?></dd>
                </dl>
                <dl>
                    <dt>收货人：</dt>
                    <dd><?php echo $order_info['consignee']; ?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>电话：</dt>
                    <dd><?php echo $order_info['mobile']; ?></dd>
                </dl>
                <dl>
                    <dt>收货地址：</dt>
                    <dd><?php echo $order_info['address']; ?></dd>
                </dl>
            </li>
        </ul>
    </div>
    <div class="indentbox">
        <div class="indenttitle">订单信息</div>
        <ul>
            <li>
                <dl>
                    <dt>订单编号：</dt>
                    <dd><?php echo $order_info['order_sn']; ?></dd>
                </dl>
                <dl>
                    <dt>下单时间：</dt>
                    <dd><?php echo $order_info['create_time']; ?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>订单收款：</dt>
                    <dd><?php echo $order_info['total']; ?></dd>
                </dl>
                <dl>
                    <dt>支付状态:</dt>
                    <dd><?= $pay_status_type[$order_info['pay_status']]?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>物流状态：</dt>
                    <dd><?= $ship_status_type[$order_info['ship_status']];?></dd>
                </dl>
                <dl>
                    <dt>确认状态：</dt>
                    <dd><?= $status_type[$order_info['status']]?></dd>
                </dl>
            </li>
        </ul>
    </div>
    <div class="indentbox nobor">
        <div class="indenttitle">支付及配送</div>
        <ul>
            <li>
                <dl>
                    <dt>支付方式：</dt>
                    <dd><?php echo $pay_type_data[$order_info['pay_type']]; ?></dd>
                </dl>
                <dl>
                    <dt>发货日期：</dt>
                    <dd><?php echo $order_info['delivery_time'];?></dd>
                </dl>
            </li>

        </ul>
    </div>
    <table class="waitlist">
        <tbody>
        <tr class="waittitle">
            <th class="merchandisebox">宝贝</th>
            <th>属性</th>
            <th>状态</th>
            <th>单价</th>
            <th>数量</th>
            <th>商品总价</th>
        </tr>

        <?php foreach($order_info['detail'] as $a_product_info){ ?>
            <tr>
                <td class="tdbox">
                    <dl class="commoditybox">
                        <dt><a href="#"><img src="<?= Yii::$app->params['imgHost'].$a_product_info['image']?>"></a></dt>
                        <dd>
                            <a href="#"><?php echo $a_product_info['name']; ?></a>
                        </dd>
                    </dl>
                </td>
                <td>
                    <?php echo str_replace("_","  ",$a_product_info['attribute_str']);?>
                </td>
                <td><span><?php echo $ship_status_type[$order_info['ship_status']]; ?></span></td>
                <td><?php echo $a_product_info['price']; ?></td>
                <td><?php echo $a_product_info['num']; ?></td>
                <td><?php echo $a_product_info['total']; ?></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tr>
                    <th>操作</th>
                    <th>备注</th>
                    <th>管理员</th>
                    <th>时间</th>
                </tr>
                <?php if($log_list){
                    foreach($log_list as $k => $v){
                        ?>
                        <tr>
                            <td><?= $v['status_log'];?></td>
                            <td><?= $v['oper']; ?></td>
                            <td><?= $v['name'];?></td>
                            <td><?= $v['add_time'];?></td>
                        </tr>
                    <?php   }}else{ ?>
                    <tr>
                        <td colspan="4">暂无记录</td>
                    </tr>
                <?php }
                ?>

            </table>
            <table class="table table-bordered table-hover">
                <tbody>
                <?php
                if($order_info['status']!=2){


                if($order_info['status'] ==0){ ?>
                <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/shop/shoporder/edits',]); ?>
                <tr>
                    <th colspan="4">订单确认/取消</th>
                    <th colspan="1">

                        <input type="radio" class="valStatus" name="status" value="1"/>确认
                        <input type="radio" class="valStatus" name="status" value="2"/>取消
                    </th>

                </tr>
                <tr>
                    <td colspan="8">备注：
                        <input type="hidden" name="order_sn" value="<?php echo $order_info['order_sn']; ?>"/>
                        <textarea rows="6" cols="50" name="remark" placeholder="请填写备注信息" class="error"></textarea>
                        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>

                    </td>


                </tr>
                <?php ActiveForm::end(); ?>
                <?php } if($order_info['ship_status'] <3 && $order_info['status']==1){  ?>

                <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/shop/shoporder/edits',]); ?>
                <tr>
                    <th colspan="4">订单发货</th>
                    <th colspan="1">
                        <?php if($order_info['ship_status'] == 0){?>
                        <input type="checkbox" class="valStatus" name="ship_status" value="1"/>发货
                        <?php } if($order_info['ship_status']==1){ ?>
                        <input type="checkbox" class="valStatus" name="ship_status" value="2"/>收货
                        <?php } if($order_info['ship_status']==2){ ?>
                        <input type="checkbox" class="valStatus" name="ship_status" value="3"/>有退货
                        <?php } ?>
                    </th>

                </tr>
                <tr>
                    <td colspan="8">备注：
                        <input type="hidden" name="order_sn" value="<?php echo $order_info['order_sn']; ?>"/>
                        <textarea rows="6" cols="50" name="remark" placeholder="请填写备注信息" class="error"></textarea>
                        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>

                    </td>


                </tr>
                <?php ActiveForm::end(); ?>
                <?php } if($order_info['pay_status'] ==0 && $order_info['ship_status'] > 1){ ?>
                <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/shop/shoporder/edits',]); ?>
                <tr>
                    <th colspan="4">订单收款</th>
                    <th colspan="1">

                        <input type="checkbox" class="valStatus" name="pay_status" value="1"/>确认收款
                    </th>

                </tr>
                <tr>
                    <td colspan="8">备注：
                        <input type="hidden" name="order_sn" value="<?php echo $order_info['order_sn']; ?>"/>
                        <textarea rows="6" cols="50" name="remark" placeholder="请填写备注信息"  class="error"></textarea>
                        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>

                    </td>


                </tr>
                <?php ActiveForm::end(); } } ?>
                </tbody>
            </table>

        </div>
    </div>
    <!--确认收货 去付款-->
    <a href="/shop/shoporder/index" class="btn waitbtn btn-primary">取&nbsp;&nbsp;&nbsp;&nbsp;消</a>
</div>
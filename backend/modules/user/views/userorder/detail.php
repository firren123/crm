<?= $this->registerCssFile("@web/css/globalm.css");?>
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '用户订单详情';

?>
<div class="content fr">
    <div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a href="/user/userorder/index">用户订单</a><span>&gt;</span><span class="current">订单详情</span></div>
    <div class="currrnttitle">交易详情 </div>
    <div class="indentbox">
        <div class="indenttitle">收货人信息
            <span id="editUserInfo" class="userInfoBut " style="float: right;cursor: pointer;color: red;">修改</span>
            <span id="saveUserInfo" class="userInfoBut " style="float: right;cursor: pointer;display: none;color: red;">保存</span>
        </div>
        <ul>
            <li>
                <dl>
                    <dt>收货人：</dt>
                    <dd><span class="user_value" id="val_consignee"><?php echo $order_info['consignee']; ?></span>

                        <p class="user_input" style="display:none;">
                            <input type="text" id="input_consignee" class="input_user_value form-control" size="20" value="<?php if(isset($order_info['consignee'])){echo $order_info['consignee'];} ?>" />
                        </p>
                    </dd>
                </dl>
                <dl>
                    <dt>电话：</dt>
                    <dd>
                        <span class="user_value" id="val_mobile"><?php echo $order_info['mobile']; ?></span>
                        <p class="user_input" style="display:none;">
                            <input type="text" id="input_mobile" class="input_user_value form-control" size="20" value="<?php if(isset($order_info['mobile'])){echo $order_info['mobile'];} ?>" />
                        </p>
                    </dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>收货地址：</dt>
                    <dd>
                        <span class="user_value" id="val_address"><?php echo $order_info['address']; ?></span>
                        <p class="user_input" style="display:none;">
                            <input type="text" id="input_address" class="input_user_value form-control" size="60" value="<?php if(isset($order_info['address'])){echo $order_info['address'];} ?>" />
                        </p>
                    </dd>
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
                    <dd id="order_sn"><?php echo $order_info['order_sn']; ?></dd>
                </dl>
                <dl>
                    <dt>下单时间：</dt>
                    <dd><?php echo $order_info['create_time']; ?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>商品金额：</dt>
                    <dd><?php echo sprintf("%.2f", ($order_info['total'] - $order_info['freight'] + $order_info['dis_amount'])); ?></dd>
                </dl>
                <dl>
                    <dt>优惠金额：</dt>
                    <dd>
                         <?php   echo  $order_info['dis_amount'];
                        ?>
                    </dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>订单收款：</dt>
                    <dd><?php echo $order_info['total']; ?></dd>
                </dl>
                <dl>
                    <dt>支付状态：</dt>
                    <dd>
                        <?php if(isset($order_info['pay_status'])){
                            echo  isset($pay_status_type[$order_info['pay_status']]) ? $pay_status_type[$order_info['pay_status']] : '';
                        }
                        ?>
                    </dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>物流状态：</dt>
                    <dd><?php
                        if(isset($order_info['ship_status'])){
                            echo  $ship_status_data[$order_info['ship_status']];
                        }
                        ?>
                        <input type="hidden" value="<?= $order_info['ship_status']; ?>" id="order_info_ship_status"/></dd>
                </dl>
                <dl>
                    <dt>确认状态：</dt>
                    <dd><?php
                        if(isset($order_info['status'])){
                            echo  $status_data[$order_info['status']];
                        }
                        ?>
                        <input type="hidden" value="<?= $order_info['status']; ?>" id="order_info_status"/></dd>
                </dl>
            </li>
            </ul>
        </div>
            <div class="indentbox">
                <div class="indenttitle">商家信息
                    <span id="editShop" class="editShopBut" style="float: right;cursor: pointer;color: red;">修改</span>
                </div>
                <ul>
            <li>
                <dl>
                    <dt>店铺名称：</dt>
                    <dd>
                        <?= $order_info['shop_info']['shop_name']; ?>
                    </dd>
                </dl>
                <dl>
                    <dt>联系人：</dt>
                    <dd><?= $order_info['shop_info']['contact_name']; ?>
                    </dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>邮箱：</dt>
                    <dd>
                        <?= $order_info['shop_info']['email']; ?>
                    </dd>
                </dl>
                <dl>
                    <dt>手机号：</dt>
                    <dd><?= $order_info['shop_info']['mobile']; ?>
                    </dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>营业时间：</dt>
                    <dd>
                        <?= $order_info['shop_info']['hours']; ?>
                    </dd>
                </dl>
                <dl>
                    <dt>营业状态：</dt>
                    <dd><?php if($order_info['shop_info']['business_status']==1){
                            echo "营业中";
                        }else{
                            echo "打烊";
                        }; ?>
                    </dd>
                </dl>
            </li>
                    <li>
                        <dl>
                            <dt>店铺地址：</dt>
                            <dd>
                                <?= $order_info['shop_info']['address']; ?>
                            </dd>
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
                    <dd><?php
                        if($order_info['pay_site_id']){
                            echo  isset($pay_type_data[$order_info['pay_site_id']]) ? $pay_type_data[$order_info['pay_site_id']] : '其他';
                        }
                         ?></dd>
                </dl>
                <dl>
                    <dt>发货日期：</dt>
                    <dd><?php echo  $order_info['delivery_time'] ?></dd>
                </dl>
            </li>

        </ul>
    </div>
    <table class="waitlist table table-bordered table-hover">
        <tbody>
        <tr class="waittitle">
            <th class="merchandisebox">宝贝</th>
            <th>属性</th>
            <th>状态</th>
            <th>单价</th>
            <th>数量</th>
            <th>商品总价</th>
        </tr>

        <?php foreach($order_info['goods_list'] as $a_product_info){ ?>
            <tr>
                <td class="tdbox">
                    <dl class="commoditybox">
                        <dt><a href="<?= Yii::$app->params['pc_mall'].'/goods/details/'.$order_info['shop_id'].'/'.$a_product_info['p_id'];?>"
                                ><img src="<?= Yii::$app->params['imgHost'].$a_product_info['image']?>"></a></dt>
                        <dd>
                            <a href="<?= Yii::$app->params['pc_mall'].'/goods/details/'.$order_info['shop_id'].'/'.$a_product_info['p_id'];?>"><?php echo $a_product_info['name']; ?></a>
                        </dd>
                    </dl>
                </td>
                <td>
                    <?php echo str_replace("_","  ",$a_product_info['attribute_str']);?></td>
                <td><span><?php
                        if(isset($order_info['ship_status'])){
                            echo  $ship_status_data[$order_info['ship_status']];
                        }
                         ?>
                    </span></td>
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
                    <th>信息</th>
                    <th>管理员</th>
                    <th>时间</th>
                </tr>
                <?php if($log_list){
                    foreach($log_list as $k => $v){
                    ?>
                    <tr>
                        <td><?= $v['status_log'];?></td>
                        <td><?= $v['oper']; ?></td>
                        <td><?= $v['log_info'];?></td>
                        <td><?= $v['name'];?></td>
                        <td><?= $v['add_time'];?></td>
                    </tr>
                <?php   }}else{ ?>
                    <tr>
                        <td colspan="5">暂无记录</td>
                    </tr>
                <?php }
                ?>

            </table>
            <table class="table table-bordered table-hover">
                <tbody>
                <?php if($order_info['status'] ==0){ ?>
                <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/user/userorder/edits',]); ?>
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
                        <input type="hidden" name="o_id" value="<?php echo $order_info['id']; ?>"/>
                        <textarea rows="6" cols="50" name="remark"  class="error" placeholder="请填写备注信息"></textarea>
                        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>

                    </td>


                </tr>
                <?php ActiveForm::end(); ?>
                <?php } ?>
                <?php  if($order_info['ship_status'] < 5 && $order_info['status'] == 1){  ?>

                <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/user/userorder/edits',]); ?>
                <tr>
                    <th colspan="4">订单发货</th>
                    <th colspan="1">
                        <?php if($order_info['ship_status'] == 0){?>
                        <input type="checkbox" class="valStatus" name="ship_status" value="4"/>发货
                        <?php } if($order_info['ship_status']==4){ ?>
                        <input type="checkbox" class="valStatus" name="ship_status" value="5"/>收货
                        <?php }?>
                    </th>

                </tr>
                <tr>
                    <td colspan="8">备注：
                        <input type="hidden" name="order_sn" value="<?php echo $order_info['order_sn']; ?>"/>
                        <input type="hidden" name="o_id" value="<?php echo $order_info['id']; ?>"/>
                        <textarea rows="6" cols="50" name="remark" placeholder="请填写备注信息"  class="error"></textarea>
                        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>

                    </td>

                </tr>
                <?php ActiveForm::end(); ?>
                <?php } ?>

                </tbody>
            </table>

        </div>
    </div>
    <!--确认收货 去付款-->
    <a href="/user/userorder/index" class="btn waitbtn btn-primary">取&nbsp;&nbsp;&nbsp;&nbsp;消</a>
    <a href="/financial/userorder/index?order_sn=<?= $order_info['order_sn']; ?>" class="btn waitbtn btn-primary">点击退货</a>
</div>
<input type="hidden" id="_csrf" name="YII_CSRF_TOKEN" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />

<script style="text/javascript" src="/js/userorder/userOrder.js"></script>
<script>
    $(function(){
       $(document).on("click",".userInfoBut",function(){
            var id = $(this).attr("id");
           if(id == "editUserInfo"){
               userOrder.editBut(this);
           }else if(id == "saveUserInfo"){
               userOrder.saveBut(this);
           }
       });
        $(document).on("click",".editShopBut",function(){
            var s = $("#order_info_status").val();
            var p = $("#order_info_ship_status").val();
            if(s == 1){
                if(p ==0){
                    userOrder.editShopBut();
                }else{
                    alert('商家已确认订单，不能再转移订单');
                    return false;
                }

            }else{
                alert('请先确认订单，再转移订单');
            }

        });
    });
</script>

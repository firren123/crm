<<<<<<< HEAD
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
    <div class="tab-content">
        <div class="row-fluid">
            <div>
                <span style="color:#0088cc;">商家名称：<?php echo $shop_name;?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="color:#0088cc;">账期：<?php echo $ship_merge;?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="color:#0088cc;">结算状态：<?php echo ($status!=2)?(($status==1)?'已结算':'待结算'):'冻结';?></span>
            </div>
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">订单编号</th>
                    <th colspan="2">交易日期</th>
                    <th colspan="2">支付类型</th>
                    <th colspan="2">交易金额（元）</th>
                    <th colspan="2">已结算（元）</th>
                    <th colspan="4">待结算（元）</th>    <!--22-->
                </tr>
                <?php if(empty($arr)) {
                    echo '<tr><td colspan="14" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($arr as $item){
                        ?>
                        <tr>
                            <td colspan="2"><a href="<?= '/admin/shop/particulars?shop_id='.$shop_id.'&order_sn='.$item['order_sn'].'&account_id='.$account_id;?>"><?php echo $item['order_sn'];?></a></td>
                            <td colspan="2"><?php echo $item['ship_status_time'];?></td>
                            <td colspan="2"><?php echo $item['pay_method'];?></td>                       <!--area-->
                            <td colspan="2"><?php echo $item['total'];?></td>
                            <td colspan="2"><?php echo number_format($item['settled'],2);?></td>
                            <?php if($item['unsettled']>=0){?>
                                <td colspan="2" style="color: red;"><?php echo number_format($item['unsettled'],2);?></td>
                            <?php }else{?>
                                <td colspan="2" style="color: green;"><?php echo number_format($item['unsettled'],2);?></td>
                            <?php }?>
                        </tr>
                    <?php }}?>
                <tr>
                    <td colspan="6"><b>待结算总金额</b></td>
                    <td colspan="6"><b><?php echo $info['data']['total'];?></b></td>
                </tr>
                </tbody>
            </table>
            <?php echo LinkPager::widget(['pagination' => $pages]); ?>
            <br><br>
            <div style="padding: auto;">
                <form action="/admin/shop/freeze" method="post">
                    <?php if($status == 0){?>  <!--未结算-->
                        <div style="margin: 0px 0px 0px 115px;">
                            <span style="color: red;">*</span><span>备注：</span>
                            <textarea cols="80" rows="5" name="intro" id="intro"></textarea><span id="area" style="color: red;"></span><br><br><br>
                        </div>
                        <div style="padding: 0px 0px 0px 240px;">
                            <input type="button" class="btn btn-primary" id="jiesuan" style="margin: 0px 45px 0px 0px;"  value="确认结算">
                            <input type="button" class="btn btn-primary" id="dongjie"  value="异常冻结">
                            <a href="/admin/shop/export?account_id=<?= $_GET['account_id']?>" style="margin: 0px 0px 0px 45px;" class="btn btn-primary">导出</a>
                            <a href="/admin/shop/index" style="margin: 0px 0px 0px 45px;" class="btn btn-primary">返回</a>
                        </div>
                    <?php }elseif($status == 1){?>  <!--已结算-->
                        <a href="/admin/shop/index" class="btn btn-primary" style="margin: 0px 0px 0px 460px;">返回</a>
                    <?php }else{?>  <!--冻结-->
                        <div style="margin: 0px 0px 0px 115px;">
                            <span style="color: red;">* 备注：</span>
                            <textarea cols="80" rows="5" name="intro" id="intro"></textarea><span id="area" style="color: red;"></span><br><br><br>
                        </div>

                        <input type="button" class="btn btn-primary" id="jiechu" style="margin: 0px 0px 0px 260px;" value="解除冻结">
                        <a href="/admin/shop/index" class="btn btn-primary" style="margin: 0px 0px 0px 90px;">返回</a>

                    <?php }?>
                </form>
            </div>
            <br><br><br>
        </div>
    </div>
</legends>
<input type="hidden" id="pro_id" value="<?= $shop_id?>"/>
<input type="hidden" id="city_id" value="<?= $account_id?>"/>
<input type="hidden" id="account_time" value="<?= $ship_merge?>"/>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>
<?= $this->registerJsFile("@web/js/layer/layer.js");?>
<script>
    $(function(){
        var checkSubmitFlg = false;
        $("#jiesuan").click(function(){
            var shop_id = $("#pro_id").val();
            var account_id = $("#city_id").val();
            var info = $.trim($("#intro").val());
            var account_time = $("#account_time").val();

            if(info){
                $("#area").text('');
            }else{
                $("#area").text('备注必须填写');
                return false;
            }


            if (!checkSubmitFlg) {
                checkSubmitFlg = true;
                $.ajax({
                    type: "POST",
                    url: '/admin/shop/freeze?id='+account_id+'&is_freeze=1'+'&shop_id='+shop_id,
                    data: {
                        "info":info,
                        "account_time":account_time
                    },
                    success: function(){
                        layer.msg('修改成功！');
                        window.location.href = '/admin/shop/index';
                    }
                });
            } else {
                layer.msg('请勿重复提交！');
                return false;
            }
        })

        $("#dongjie").click(function(){
            var shop_id = $("#pro_id").val();
            var account_id = $("#city_id").val();
            var info = $.trim($("#intro").val());
            var account_time = $("#account_time").val();

            if(info){
                $("#area").text('');
            }else{
                $("#area").text('备注必须填写');
                return false;
            }

            if (!checkSubmitFlg) {
                checkSubmitFlg = true;
                $.ajax({
                    type: "POST",
                    url: '/admin/shop/freeze?id='+account_id+'&is_freeze=0'+'&shop_id='+shop_id,
                    data: {
                        "info":info,
                        "account_time":account_time
                    },
                    success: function(){
                        layer.msg('修改成功！');
                        window.location.href = '/admin/shop/index';
                    }
                });
            } else {
                layer.msg('请勿重复提交！');
                return false;
            }
        })


        $("#jiechu").click(function(){
            var shop_id = $("#pro_id").val();
            var account_id = $("#city_id").val();
            var info = $.trim($("#intro").val());
            var account_time = $("#account_time").val();

            if(info){
                $("#area").text('');
//                        window.location.href = '/admin/shop/index';
            }else{
                $("#area").text('备注必须填写');
                return false;
            }

            if (!checkSubmitFlg) {
                checkSubmitFlg = true;
                $.ajax({
                    type: "POST",
                    url: '/admin/shop/freeze?id='+account_id+'&is_freeze=2'+'&shop_id='+shop_id,
                    data: {
                        "info":info,
                        "account_time":account_time
                    },
                    success: function(){
                        layer.msg('修改成功！');
                        window.location.href = '/admin/shop/index';
                    }
                });
            } else {
                layer.msg('请勿重复提交！');
                return false;
            }
        })
    })

</script>
=======
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
    <div class="tab-content">
        <div class="row-fluid">
            <div>
                <span style="color:#0088cc;">商家名称：<?php echo $shop_name;?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="color:#0088cc;">账期：<?php echo $ship_merge;?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="color:#0088cc;">结算状态：<?php echo ($status!=2)?(($status==1)?'已结算':'待结算'):'冻结';?></span>
            </div>
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">订单编号</th>
                    <th colspan="2">交易日期</th>
                    <th colspan="2">支付类型</th>
                    <th colspan="2">交易金额（元）</th>
                    <th colspan="2">已结算（元）</th>
                    <th colspan="4">待结算（元）</th>    <!--22-->
                </tr>
                <?php if(empty($arr)) {
                    echo '<tr><td colspan="14" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($arr as $item){
                        ?>
                        <tr>
                            <td colspan="2"><a href="<?= '/admin/shop/particulars?shop_id='.$shop_id.'&order_sn='.$item['order_sn'].'&account_id='.$account_id;?>"><?php echo $item['order_sn'];?></a></td>
                            <td colspan="2"><?php echo $item['ship_status_time'];?></td>
                            <td colspan="2"><?php echo $item['pay_method'];?></td>                       <!--area-->
                            <td colspan="2"><?php echo $item['total'];?></td>
                            <td colspan="2"><?php echo number_format($item['settled'],2);?></td>
                            <?php if($item['unsettled']>=0){?>
                                <td colspan="2" style="color: red;"><?php echo number_format($item['unsettled'],2);?></td>
                            <?php }else{?>
                                <td colspan="2" style="color: green;"><?php echo number_format($item['unsettled'],2);?></td>
                            <?php }?>
                        </tr>
                        <?php }}?>
                        <tr>
                            <td colspan="6"><b>待结算总金额</b></td>
                            <td colspan="6"><b><?php echo $info['data']['total'];?></b></td>
                        </tr>
                </tbody>
            </table>
            <?php echo LinkPager::widget(['pagination' => $pages]); ?>
            <br><br>
            <div style="padding: auto;">
            <form action="/admin/shop/freeze" method="post">
                <?php if($status == 0){?>  <!--未结算-->
                    <div style="margin: 0px 0px 0px 115px;">
                    <span style="color: red;">*</span><span>备注：</span>
                    <textarea cols="80" rows="5" name="intro" id="intro"></textarea><span id="area" style="color: red;"></span><br><br><br>
                    </div>
                <div style="padding: 0px 0px 0px 240px;">
                    <input type="button" class="btn btn-primary" id="jiesuan" style="margin: 0px 45px 0px 0px;"  value="确认结算">
                <input type="button" class="btn btn-primary" id="dongjie"  value="异常冻结">
                    <a href="/admin/shop/export?account_id=<?= $_GET['account_id']?>" style="margin: 0px 0px 0px 45px;" class="btn btn-primary">导出</a>
                <a href="/admin/shop/index" style="margin: 0px 0px 0px 45px;" class="btn btn-primary">返回</a>
                </div>
                <?php }elseif($status == 1){?>  <!--已结算-->
                    <a href="/admin/shop/index" class="btn btn-primary" style="margin: 0px 0px 0px 460px;">返回</a>
                <?php }else{?>  <!--冻结-->
                <div style="margin: 0px 0px 0px 115px;">
                    <span style="color: red;">* 备注：</span>
                    <textarea cols="80" rows="5" name="intro" id="intro"></textarea><span id="area" style="color: red;"></span><br><br><br>
                </div>

                    <input type="button" class="btn btn-primary" id="jiechu" style="margin: 0px 0px 0px 260px;" value="解除冻结">
                    <a href="/admin/shop/index" class="btn btn-primary" style="margin: 0px 0px 0px 90px;">返回</a>

                <?php }?>
            </form>
                </div>
            <br><br><br>
        </div>
    </div>
</legends>
<input type="hidden" id="pro_id" value="<?= $shop_id?>"/>
<input type="hidden" id="city_id" value="<?= $account_id?>"/>
<input type="hidden" id="account_time" value="<?= $ship_merge?>"/>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>
<?= $this->registerJsFile("@web/js/layer/layer.js");?>
<script>
    $(function(){
        var checkSubmitFlg = false;
        $("#jiesuan").click(function(){
            var shop_id = $("#pro_id").val();
            var account_id = $("#city_id").val();
            var info = $.trim($("#intro").val());
            var account_time = $("#account_time").val();

            if(info){
                $("#area").text('');
            }else{
                $("#area").text('备注必须填写');
                return false;
            }


            if (!checkSubmitFlg) {
                checkSubmitFlg = true;
                $.ajax({
                    type: "POST",
                    url: '/admin/shop/freeze?id='+account_id+'&is_freeze=1'+'&shop_id='+shop_id,
                    data: {
                        "info":info,
                        "account_time":account_time
                    },
                    success: function(){
                        layer.msg('修改成功！');
                        window.location.href = '/admin/shop/index';
                    }
                });
            } else {
                layer.msg('请勿重复提交！');
                return false;
            }
        })

        $("#dongjie").click(function(){
            var shop_id = $("#pro_id").val();
            var account_id = $("#city_id").val();
            var info = $.trim($("#intro").val());
            var account_time = $("#account_time").val();

            if(info){
                $("#area").text('');
            }else{
                $("#area").text('备注必须填写');
                return false;
            }

            if (!checkSubmitFlg) {
                checkSubmitFlg = true;
                $.ajax({
                    type: "POST",
                    url: '/admin/shop/freeze?id='+account_id+'&is_freeze=0'+'&shop_id='+shop_id,
                    data: {
                        "info":info,
                        "account_time":account_time
                    },
                    success: function(){
                        layer.msg('修改成功！');
                        window.location.href = '/admin/shop/index';
                    }
                });
            } else {
                layer.msg('请勿重复提交！');
                return false;
            }
        })


        $("#jiechu").click(function(){
            var shop_id = $("#pro_id").val();
            var account_id = $("#city_id").val();
            var info = $.trim($("#intro").val());
            var account_time = $("#account_time").val();

            if(info){
                $("#area").text('');
//                        window.location.href = '/admin/shop/index';
            }else{
                $("#area").text('备注必须填写');
                return false;
            }

            if (!checkSubmitFlg) {
                checkSubmitFlg = true;
            $.ajax({
                type: "POST",
                url: '/admin/shop/freeze?id='+account_id+'&is_freeze=2'+'&shop_id='+shop_id,
                data: {
                    "info":info,
                    "account_time":account_time
                },
                success: function(){
                    layer.msg('修改成功！');
                    window.location.href = '/admin/shop/index';
                }
            });
            } else {
                layer.msg('请勿重复提交！');
                return false;
            }
        })
    })

</script>
>>>>>>> dev

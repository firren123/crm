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
<?php $this->title='用户订单日志';?>
<legends  style="fond-size:12px;">
    <legend>用户订单日志</legend>
    <form action="/admin/productlog/index" method="get">
        订单号：<input type="text" name="order_sn" class="dingdan">
        类型:<select name="log_type">
            <option value="" style="width: 150px;">请选择</option>
            <option value="1" style="width: 150px;" <?php if($log_type == 1){echo 'selected';}?>>用户</option>
            <option value="2" style="width: 150px;" <?php if($log_type == 2){echo 'selected';}?>>商家</option>
            <option value="3" style="width: 150px;" <?php if($log_type == 3){echo 'selected';}?>>管理员</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"  class="btn btn-primary" value="搜索" id="sousuo">
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
    </form>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th width="300px">订单号</th>
                    <th>日志内容</th>
                    <th>类型</th>
                    <th>添加时间</th>
                    <th>ip地址</th>
                    <th>浏览器</th>
                </tr>
                <?php if(empty($shop_info)) {
                    echo '<tr><td colspan="14" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($shop_info as $item){
                        ?>
                        <tr>
                            <td><?= $item['id'];?></td>
                            <td><p style="width: 300px;"><?= $item['order_sn'];?></p></td>
                            <td><?= $item['log_info'];?></td>
                            <td><?= ($item['type'] == 3)?'管理员':(($item['type'] == 2)?'商家':'用户');?></td>
                            <td><?= $item['add_time'];?></td>
                            <td><?= $item['ip_address'];?></td>
                            <td><?= $item['browser'];?></td>

                        </tr>
                    <?php } }?>
                </tbody>
            </table>
            <?php echo LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/layer/layer.js");?>

<script>
    $(function(){
        $("#sousuo").click(function(){
           var info = $(".dingdan").val();
            if(!(/^[0-9]*$/.test(info))){
                layer.msg('订单号格式不正确，请输入正确订单号！');
                return false;
            }
        })
    })
</script>

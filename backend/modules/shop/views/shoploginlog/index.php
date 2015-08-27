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
<?php $this->title='商家登录日志';?>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/shop/shoploginlog" method="get">
        <label for="name">商品名称：</label>
        <input id="name" type="text" name="shop_name"  value="<?= empty($shop_name) ? '' : $shop_name?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
    <div class="tab-content">
        <div class="summary pull-right" >共 <span style="color: red"><?= $total?></span> 个登录日志</div>
        <br>
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th width="300px">商家名称</th>
                    <th>终端系统</th>
                    <th>浏览器</th>
                    <th>ip地址</th>
                    <th>登录时间</th>
                </tr>
                <?php if(empty($data)) {
                    echo '<tr><td colspan="14" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($data as $item){
                        ?>
                        <tr>
                            <td><?= $item['id'];?></td>
                            <td><p style="width: 300px;"><a href="/shop/shop/detail?id=<?= $item['shop_id'];?>" target="_blank"><?= $item['shop_name'];?></a></p></td>
                            <td><?= $item['os'];?></td>
                            <td><?= $item['browser'];?></td>
                            <td><?= $item['ip_address'];?></td>
                            <td><?= $item['login_time'];?></td>

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


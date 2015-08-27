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
    <legend>选择商户</legend>
    <form action="/goods/activity/shop-list" method="get">
        商家ID：<input type="text" name="shop_id">
        商家类型:<select name="type_id">
            <option value="" style="width: 150px;">请选择</option>
            <?php foreach($type_info as $k=>$v):?>
                <option value="<?php echo $v['id'];?>" <?php if($type_id == $v['id']){echo "selected";}?>><?php echo $v['name'];?></option>
            <?php endforeach;?>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"  class="btn btn-primary" value="搜索">
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <input type="hidden" value="<?php if(isset($_GET['district'])){echo $_GET['district'];}?>" name="district"/>
    </form>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">商家ID</th>
                    <th colspan="2">商家名称</th>
                    <th colspan="2">商家类型</th>
                    <th colspan="4">操作</th>    <!--22-->
                </tr>
                <?php if(empty($shop_info)) {
                    echo '<tr><td colspan="10" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($shop_info as $item){
                        ?>
                        <tr>
                            <td colspan="2" class="shop_id"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['shop_name'];?></td>
                            <td colspan="2"><?= $item['manage_name'];?></td>
                            <td colspan="4"><a href="#" class="add_shop">选择</a></td>
                        </tr>
                    <?php } }?>
                </tbody>
            </table>
            <?php echo LinkPager::widget(['pagination' => $pages]); ?>
        </div>

    </div>
</legends>
<script type="text/javascript" src="/js/goods/activity.js?_<?= Yii::$app->params['jsVersion'];?>"></script>
<script>
    $(".add_shop").click(function(){
        var shop_id = $(this).parent().parent().find(".shop_id").html();
        activity.childAddShop(shop_id);
    });

</script>


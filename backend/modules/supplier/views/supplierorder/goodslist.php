<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<script type="text/javascript" src="/js/supplier/order.js"></script>
<div class="wide form">
    <?php
    $form = ActiveForm::begin([
        'id' => "login-form",
        'layout' => 'horizontal',
        'method'=>'get',
        'action'=>'/supplier/supplierorder/goods-list',
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data'],
    ]);
    ?>
    <label for="id">分类：</label>
    <select name="cate_id" id="">
        <option value="-1">全部</option>
        <?php foreach($cate_list as $k=>$v){ ?>
            <option value="<?= $k;?>" if($k == $cate_id){ echo 'selected';}><?= $v;?></option>
        <?php } ?>
    </select>
    <label for="name">商品名称：</label>
    <input id="name" type="text" name="goods_name" size="15" value="<?= $goods_name;?>" class="form-inline">
    <input type="hidden" name="sp_id" value="<?php if(isset($_GET['sp_id'])){ echo $_GET['sp_id'];}?>"/>
    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
</div>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>商品名称</th>
                <th>商品规格</th>
                <th>商品类型</th>
                <th>进货价</th>
                <th>库存</th>
                <th>操作</th>

            </tr>
            <?php if(empty($list)) {
                echo '<tr><td colspan="7" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>

                        <td><span class="ids"><?= $item['id'];?></span></td>
                        <td><?= $item['title'];?></td>
                        <td><?= $item['attr_value'];?></td>
                        <td><?php if(isset($cate_list[$item['category_id']])){echo $cate_list[$item['category_id']];};?></td>
                        <td><?= $item['supply_price'];?></td>
                        <td><?= $item['stock'];?></td>
                        <td><a style="cursor:pointer" class="addGoods">添加</a>

                        </td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
        <div id="zcss_page" class="pages">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<script>
    $(".addGoods").click(function(){
       var id = $(this).closest("tr").find(".ids").text();
        order.childAddGoods(id);
    });
</script>
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
        'action'=>'/supplier/supplierorder/supplier-list',
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data'],
    ]);
    ?>
        <label for="id">供应商ID：</label>
        <input id="id" type="text" name="id" size="10" value="<?= $id;?>" class="form-inline">
        <label for="name">供应商名称：</label>
        <input id="name" type="text" name="company_name" size="15" value="<?= $company_name;?>" class="form-inline">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
</div>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>公司名称</th>
                <th>联系人</th>
                <th>手机</th>
                <th>操作</th>

            </tr>
            <?php if(empty($list)) {
                echo '<tr><td colspan="5" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>

                        <td><span class="ids"><?= $item['id'];?></span></td>
                        <td><?= $item['company_name'];?></td>
                        <td><?= $item['contact'];?></td>
                        <td><?= $item['mobile'];?></td>
                        <td><a style="cursor:pointer" class="addCom">添加</a>  </td>
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
    $(".addCom").click(function(){
        var id = $(this).closest("tr").find(".ids").text();
        order.childAddCom(id);
    });
</script>
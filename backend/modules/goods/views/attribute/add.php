<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/attribute/index">属性管理</a></li>
        <li class="active">添加属性</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
            <?php $form = ActiveForm::begin(['id' => 'login-form','layout' => 'horizontal',]); ?>
            <?= $form->field($model, 'admin_name') ?>
            <?= $form->field($model, 'attr_name') ?>

            <?= $form->field($model, 'weight')->textInput(['style'=>'width:50px','value'=>99]) ?>
            <?= $form->field($model, 'is_search')->radioList(['0'=>'否','1'=>'是']) ?>
            <div class="form-group field-attributevalue-attr_value">
                <label class="control-label col-sm-3" for="attributevalue-attr_value">属性值列表</label>

                <table>
                    <thead>
                    <tr>

                        <th>属性值</th>
                        <th>属性值权重</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>

                        <td><input type="text" id="attributevalue-attr_value" class="form-control" name="AttributeValue[attr_value][]" maxlength="5" style="width:100px"></td>
                        <td><input type="text" id="attributevalue-weight" class="form-control" name="AttributeValue[weight][]" value="99" maxlength="2" style="width:50px"></td>

                    </tr>



                    </tbody>
                </table>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3"><?= Html::button('添加', ['class' => 'btn', 'id'=>'add_attr_value']) ?></label>


            </div>





            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>

</legends>
<script>
    $("#add_attr_value").click(function(){
        //gf.confirm('','gf.abc()');
        var html = $('table tbody tr:first-child').html();
        $('table tbody').append('<tr>'+html+'</tr>');
    })
</script>
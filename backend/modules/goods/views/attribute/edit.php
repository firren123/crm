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
        <li class="active">修改属性</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">

            <?php $form = ActiveForm::begin(['id' => 'login-form','layout' => 'horizontal']); ?>
            <?= $form->field($model, 'admin_name') ?>
            <?= $form->field($model, 'attr_name') ?>

            <?= $form->field($model, 'weight')->textInput(['style'=>'width:50px']) ?>
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
                    <?php if(!empty($value_list)):?>
                        <?php foreach($value_list as $k=>$v):?>
                            <tr>
                                <td><input value="<?=$v['attr_value']?>" type="text" id="attributevalue-attr_value" class="form-control" name="AttributeValue[<?=$v['id']?>][]" maxlength="5" style="width:100px"></td>
                                <td><input value="<?=$v['weight']?>" type="text" id="attributevalue-weight" class="form-control" name="AttributeValue[<?=$v['id']?>][]" value="99" maxlength="2" style="width:50px"></td>
                                <td><a href="javascript:void(0)" onclick="del_value(this,<?=$v['id'];?>)">删除</a> </td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    <tr>

                        <td><input type="text" id="attributevalue-attr_value" class="form-control" name="NewValue[attr_value][]" maxlength="5" style="width:100px"></td>
                        <td><input type="text" id="attributevalue-weight" class="form-control" name="NewValue[weight][]" value="99" maxlength="2" style="width:50px"></td>

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
//        gf.confirm('111','gf.abc()');
        var html = $('table tbody tr:last-child').html();
        $('table tbody').append('<tr>'+html+'</tr>');
    })
    function del_value(obj,id){
        $.get("/goods/attribute/del-value",{"id":id},function(result){
            if(result.code == 200){
                $(obj).parents('tr').remove();
            }else {
                gf.alert('删除失败');
            }
        },'json')
    }

</script>
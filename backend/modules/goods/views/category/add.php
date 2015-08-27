<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加/修改 商品分类';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class="active"><a href="/goods/category">商品分类管理</a></li>
        <li class="active">增加/修改 商品分类</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
            </div>
        </div>
</legends>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<?= $form->field($model, 'name')->label('分类名称')->input('text',['style'=>'width:200px']) ; ?>
    <div class="form-group field-brand-sort">
        <label class="control-label col-sm-3" for="brand-sort">属性</label>
        <div class="col-sm-6" style="line-height: 35px;">
            <?php if (!empty($attribute_list)) :?>
                <?php foreach($attribute_list as $list):?>
                    <input type="checkbox" name="attribute[]" <?php if (!empty($list['checked']) and $list['checked']==1):?>checked<?php endif;?> value="<?= $list['id']?>"> <?= $list['admin_name']?>
                <?php endforeach;?>
            <?php endif;?>
            <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('error'); ?> </div>
        </div>
    </div>
<?= $form->field($model, 'img')->fileInput()->label('分类图片') ; ?>
<?= $form->field($model, 'sort')->textInput(['style'=>'width:200px'])->label('排序'); ?>
<?= $form->field($model, 'status')->radioList(['1'=>'隐藏','2'=>'显示'])->label('是否显示'); ?>
<div class="form-actions">
<?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    <a class="btn cancelBtn" href="/goods/category">返回</a>
</div>
<?php ActiveForm::end(); ?>
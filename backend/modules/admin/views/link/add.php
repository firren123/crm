<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加友情链接';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/link"></a> 友情链接管理</a></li>
        <li class="active">增加/修改 友情链接</li>
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
<?= $form->field($model, 'title')->label('站点名称')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'url')->label('站点地址')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'images')->fileInput()->label('logo图片') ; ?>
<?= $form->field($model, 'sort')->textInput(['value'=>999,'style'=>'width:200px'])->label('排序'); ?>
<?= $form->field($model, 'status')->radioList(['0'=>'不显示','2'=>'显示'])->label('是否显示'); ?>
<div class="form-actions">
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
<?php ActiveForm::end(); ?>
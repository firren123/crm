<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '增加/修改模块';
?>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/admin/module/index"> 模块管理</a></li>
            <li class="active">增加/修改 模块</li>
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

<?= $form->field($model, 'name')->label('模块名称')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'status')->radioList(['0'=>'不显示','1'=>'显示'])->label('是否显示'); ?>
<?= $form->field($model, 'sort')->label('排序')->input('text',['style'=>'width:200px','placeholder'=>99]) ; ?>

    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    </div>
<?php ActiveForm::end(); ?>
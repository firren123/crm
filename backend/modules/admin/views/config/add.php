<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '修改 网站配置';
?>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li class="active">修改 网站配置</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid">
            </div>
        </div>
    </legends>
<h2>字段名称不能修改</h2>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>

<?= $form->field($model, 'title')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'value')->textarea() ; ?>
    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    </div>
<?php ActiveForm::end(); ?>
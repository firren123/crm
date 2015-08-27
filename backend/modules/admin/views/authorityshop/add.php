<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '增加/修改 商家黑名单';
?>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li class="active">增加/修改 商家黑名单</li>
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

<?= $form->field($model, 'shop_id')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'shop_name')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'status')->radioList(['1'=>'禁用','2'=>'正常']); ?>
    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    </div>
<?php ActiveForm::end(); ?>
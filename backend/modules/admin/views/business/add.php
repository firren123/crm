<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加业务员';
?>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/admin/business/index">业务员管理</a></li>
            <li class="active">增加/修改 业务员员</li>
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
<?= $form->field($model, 'name')->label('姓名')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'mobile')->label('手机')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'email')->label('邮箱')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'status')->radioList(['0'=>'禁用','1'=>'正常'])->label('是否禁用'); ?>

<?= $form->field($model, 'bc_id')->label('分公司')->dropDownList($branch_arr); ?>

    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    </div>
<?php ActiveForm::end(); ?>
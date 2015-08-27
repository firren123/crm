<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加管理员';
?>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/admin/admin/index">管理员管理</a></li>
            <li class="active">增加/修改 管理员</li>
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

<?= $form->field($model, 'username')->label('账号')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'password')->label('密码')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'name')->label('真实姓名')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'email')->label('邮箱')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'status')->radioList(['1'=>'禁用','2'=>'正常'])->label('是否禁用'); ?>

<?= $form->field($model, 'bc_id')->label('分公司')->dropDownList($branch); ?>
<?= $form->field($model, 'role_id')->label('职位')->dropDownList($role); ?>
<?= $form->field($model, 'ip_access')->radioList(['1'=>'不容许','2'=>'容许'])->label('是否外网访问'); ?>

    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    </div>
<?php ActiveForm::end(); ?>
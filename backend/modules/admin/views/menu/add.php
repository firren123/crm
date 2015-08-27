<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加菜单';
?>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/admin/menu/index">菜单管理</a></li>
            <li class="active">增加/修改 菜单</li>
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

<?= $form->field($model, 'name')->label('节点名称')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'title')->label('菜单名称')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'description')->label('菜单描述')->input('text',['style'=>'width:200px']) ; ?>

<?php if($act){ ?>
<?= $form->field($model, 'nav_id')->label('所属导航')->dropDownList($module_list) ; ?>
<?= $form->field($model, 'level')->label('导航级别')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'display')->radioList(['0'=>'不显示','1'=>'显示'])->label('导航显示'); ?>
    <?php } ?>
<?= $form->field($model, 'status')->radioList(['1'=>'激活','0'=>'冻结'])->label('激活状态'); ?>
<?= $form->field($model, 'sort')->label('排序')->input('text',['style'=>'width:200px','placeholder'=>999]) ; ?>
<?= $form->field($model, 'p_id')->hiddenInput(array('value'=>$p_id)); ?>
    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    </div>
<?php ActiveForm::end(); ?>
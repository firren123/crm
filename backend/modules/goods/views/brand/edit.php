<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '修改商品品牌';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/brand">商品品牌管理</a></li>
        <li class="active">增加/修改 商品品牌</li>
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
<?= $form->field($model, 'name')->label('品牌名称')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'img')->fileInput()->label('品牌图片') ; ?>
<?= $form->field($model, 'description')->textarea(['row'=>3])->label('品牌描述')?>
    <div class="form-group field-brand-sort required">
        <label class="control-label col-sm-3" for="brand-sort">所属分类</label>
        <div class="col-sm-6" style="line-height: 35px;">
            <?php if (!empty($cate_list)) :?>
                <?php foreach($cate_list as $list):?>
                    <input type="checkbox" name="cate[]" <?php if (!empty($list['checked']) and $list['checked']==1):?>checked<?php endif;?> value="<?= $list['id']?>"> <?= $list['name']?>
                <?php endforeach;?>
            <?php endif;?>
            <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('error'); ?> </div>
        </div>
    </div>
<?= $form->field($model, 'sort')->textInput(['style'=>'width:200px'])->label('排序'); ?>
<?= $form->field($model, 'status')->radioList(['1'=>'无效','2'=>'有效'])->label('状态'); ?>
<div class="form-actions">
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    <a class="btn cancelBtn" href="/goods/brand">返回</a>
</div>
<?php ActiveForm::end(); ?>
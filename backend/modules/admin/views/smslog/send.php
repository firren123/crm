<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li class="active">短信发送</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
            <?php $form = ActiveForm::begin(['id' => 'login-form','layout' => 'horizontal',]); ?>
            <?= $form->field($model, 'mobile') ?>
            <?= $form->field($model, 'content')->textarea(['size'=>60,'maxlength'=>128])?>
            <div class="form-actions">
                <?= Html::submitButton('发送', ['class' => 'btn btn-primary']) ?>
                <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</legends>
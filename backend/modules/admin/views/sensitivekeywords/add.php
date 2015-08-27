<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>

    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li class="active"><a href="/admin/sensitivekeywords/index">敏感词管理</a></li>
            <li class="active">增加敏感词</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid">
                <?php $form = ActiveForm::begin(['id' => 'login-form','layout' => 'horizontal',]); ?>
                <?= $form->field($model, 'keyword'); ?>
                <?= $form->field($model, 'status')->radioList(['0'=>'启用','1'=>'禁用']) ?>
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>
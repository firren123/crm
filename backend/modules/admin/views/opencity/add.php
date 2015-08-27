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
        <li class="active"><a href="/admin/opencity/index" style="color: #286090;">开通城市管理</a></li>
        <li class="active">增加开通城市</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
            <?php $form = ActiveForm::begin(['id' => 'login-form','layout' => 'horizontal',]); ?>
            <?= $form->field($model, 'province')->dropDownList($arr,['prompt'=>'选择省份']); ?>
            <?= $form->field($model, 'city')->dropDownList($city_list); ?>
            <?= $form->field($model, 'status')->radioList(['1'=>'启用','2'=>'禁用']) ?>
            <?= $form->field($model, 'display')->radioList(['1'=>'显示','0'=>'不显示']) ?>
            <a href="/admin/opencity/index" class="btn btn-primary" style="margin: 10px 195px;">返回</a>
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>
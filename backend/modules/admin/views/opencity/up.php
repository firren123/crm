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
            <li class="active"><a href="/opencity"></a> 开通城市管理</li>
            <li class="active">修改开通城市</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid">
                <?php $form = ActiveForm::begin(['id' => 'login-form','layout' => 'horizontal',]); ?>
                <?= $form->field($model, 'province')->dropDownList([$info['province']]); ?>
                <?= $form->field($model, 'city')->dropDownList([$info['city_name']]); ?>
                <?= $form->field($model, 'status')->radioList(['1'=>'启用','2'=>'禁用']) ?>
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>
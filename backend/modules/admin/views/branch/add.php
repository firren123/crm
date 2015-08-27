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
            <li class="active"><a href="/admin/branch/index">分公司管理</a></li>
            <li class="active">增加分公司</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid">
                <?php $form = ActiveForm::begin(['id' => 'login-form','layout' => 'horizontal']); ?>
                <?= $form->field($model,'name')->textInput(); ?>
                <?= $form->field($model, 'province_id')->dropDownList($arr,['prompt'=>'请选择省份']); ?>  <!--province_id 要对应数据库中该表的字段，这个就要对应crm_branch表-->
                <?= $form->field($model, 'city_id_arr')->dropDownList($city_list,['multiple'=>'multiple', 'size'=>10]); ?>
                <?= $form->field($model, 'status')->radioList(['1'=>'启用','0'=>'禁用']) ?>
                <?= $form->field($model, 'sort')->textInput(); ?>
                <a class="btn cancelBtn" href="/admin/branch/index" style="margin: 50px 80px;">返回</a>
                <?= Html::Button('提交', ['class' => 'btn btn-primary','id'=>'sub']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </legends>

<input type="hidden" value="<?php echo Yii::$app->session['name'] ?>" id="name" />

<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>


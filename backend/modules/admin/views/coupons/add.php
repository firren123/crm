<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '批量生成优惠券';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/admin/coupons">优惠券管理</a></li>
        <li class="active">批量生成优惠券</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover" style="width: 60%;text-align: center;">
            <tbody>
            <tr>
                <td colspan="2"><?= $type_list['type_name']?>优惠券 -- 批量生成</td>
            </tr>
            <?php
            $form = ActiveForm::begin([
                'id' => "login-form",
                'layout' => 'horizontal',
                'enableAjaxValidation' => false,
                'options' => ['enctype' => 'multipart/form-data'],
            ]);
            ?>
            <tr>
                <td>生成数量</td>
                <td><input type="text" name="number"></td>
            </tr>
            <tr>
                <td colspan="2"><?= Html::submitButton('生成', ['class' => 'btn btn-primary']) ?></td>
            </tr>
            <?php ActiveForm::end(); ?>
            </tbody>
            </table>
        </div>
    </div>
<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  add.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/24 下午2:58
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = $title;
?>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/admin/admin/index">合同管理</a></li>
            <li class="active"><?= $title; ?></li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid">
            </div>
        </div>
    </legends>
<?php
$form = ActiveForm::begin([
    'id' => "contract-form",
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<?= $form->field($model, 'contract_code')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'company_people')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'customer_people')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'contact')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'shop_name')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'shop_phone')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'brand_name')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'brand_info')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'contract_valid_start_time')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'contract_valid_end_time')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'online_time')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'store_event')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'store_info')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'per_consumption')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'get_time_limit')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'is_chain')->radioList(['1' => '是', '0' => '否']); ?>
<?= $form->field($model, 'is_scan_code')->radioList(['1' => '是', '0' => '否']); ?>
<?= $form->field($model, 'is_appointment')->radioList(['1' => '是', '0' => '否']); ?>
<?= $form->field($model, 'traffic_routes')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'industry_id')->dropDownList($industry_id_data,['prompt'=>'请选择']); ?>
<?= $form->field($model, 'release_form')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'qualification')->checkboxList($qualification_data) ?>
<?= $form->field($model, 'product_img')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'product_logo_img')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'shop_logo_img')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'brand_logo')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'code_validity')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'special_requirements')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'product_info')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'remark')->input('text', ['style' => 'width:200px']); ?>
    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    </div>
<?php ActiveForm::end(); ?>
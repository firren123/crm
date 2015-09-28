<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  view.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/28 上午10:59
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = $title;
?>
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
<?= $form->field($model, 'contract_valid_start_time')->input('text', ['style' => 'width:200px','onFocus'=>"WdatePicker({isShowClear:true,readOnly:false,dateFmt:'yyyy-MM-dd HH:mm:ss'})"]); ?>
<?= $form->field($model, 'contract_valid_end_time')->input('text', ['style' => 'width:200px','onFocus'=>"WdatePicker({isShowClear:true,readOnly:false,dateFmt:'yyyy-MM-dd HH:mm:ss'})"]); ?>
<?= $form->field($model, 'online_time')->input('text', ['style' => 'width:200px','onFocus'=>"WdatePicker({isShowClear:true,readOnly:false,dateFmt:'yyyy-MM-dd HH:mm:ss'})"]); ?>
<?= $form->field($model, 'store_event')->input('number', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'store_info')->textarea(['rows'=>3]) ?>
<?= $form->field($model, 'per_consumption')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'get_time_limit')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'is_chain')->radioList(['1' => '是', '0' => '否']); ?>
<?= $form->field($model, 'is_scan_code')->radioList(['1' => '是', '0' => '否']); ?>
<?= $form->field($model, 'is_appointment')->radioList(['1' => '是', '0' => '否']); ?>
<?= $form->field($model, 'traffic_routes')->textarea(['rows'=>3]) ?>
<?= $form->field($model, 'industry_id')->dropDownList($industry_id_data,['prompt'=>'请选择']); ?>
<?= $form->field($model, 'release_form')->input('text', ['style' => 'width:200px']); ?>
<?= $form->field($model, 'qualification')->checkboxList($qualification_data) ?>
<div class="form-group field-ypcontract-product_img required">
    <label class="control-label col-sm-3" for="ypcontract-product_img">产品图片</label>
    <div class="col-sm-6">
        <?php if(!empty($model->product_img)){
            $imgs = explode("###",$model->product_img);
            foreach($imgs as $val){ ?>
                <img src="<?= \Yii::$app->params['imgHost'].$val;?>" style="width:90px;height:90px;">
                <?php }
          } ?>
    </div>

</div>
    <div class="form-group field-ypcontract-product_logo_img required">
        <label class="control-label col-sm-3" for="ypcontract-product_logo_img">产品LOGO图片</label>
        <div class="col-sm-6">
            <?php if(!empty($model->product_logo_img)){
                $imgs = explode("###",$model->product_logo_img);
                foreach($imgs as $val){ ?>
                    <img src="<?= \Yii::$app->params['imgHost'].$val;?>" style="width:90px;height:90px;">
                <?php }
            } ?>
        </div>

    </div>
    <div class="form-group field-ypcontract-shop_logo_img required">
        <label class="control-label col-sm-3" for="ypcontract-shop_logo_img">商家LOGO图片</label>
        <div class="col-sm-6">
            <?php if(!empty($model->shop_logo_img)){
                $imgs = explode("###",$model->shop_logo_img);
                foreach($imgs as $val){ ?>
                    <img src="<?= \Yii::$app->params['imgHost'].$val;?>" style="width:90px;height:90px;">
                <?php }
            } ?>
        </div>

    </div>
    <div class="form-group field-ypcontract-brand_logo required">
        <label class="control-label col-sm-3" for="ypcontract-brand_logo">品牌LOGO</label>
        <div class="col-sm-6">
            <?php
            if(!empty($model->brand_logo)) {
                $imgs = explode("###",$model->brand_logo);
                foreach($imgs as $val){ ?>
                    <img src="<?= \Yii::$app->params['imgHost'].$val;?>" style="width:90px;height:90px;">
                <?php }
            }
            ?>
        </div>

    </div>
<?= $form->field($model, 'code_validity')->input('number', ['style' => 'width:200px', 'value' => 15]); ?>
<?= $form->field($model, 'special_requirements')->textarea(['rows'=>3]) ?>
<?= $form->field($model, 'product_info')->textarea(['rows'=>3]) ?>
<?= $form->field($model, 'remark')->textarea(['rows'=>3]) ?>
<div class="form-actions">
    <a class="btn cancelBtn" href="javascript:history.go(-1);">返回</a>
</div>
<?php ActiveForm::end(); ?>
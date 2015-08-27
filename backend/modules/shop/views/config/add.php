<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加/修改 分公司起送费';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/shop/config"> 起送费列表</a></li>
        <li class="active">添加/修改 分公司起送费</li>
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
<?= $form->field($model, 'free_shipping')->label('免运费金额')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'send_price')->label('起送费')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'freight')->label('运费')->input('text',['style'=>'width:200px']) ; ?>
    <div class="form-group field-coupontype-used_status required">
    <label class="control-label col-sm-3" for="coupontype-city_id">限定区域</label>
        <div class="col-sm-6">
            <label style="float: left;height: 34px ;line-height: 34px">分公司:   </label>
            <select id="bc_id" class="form-control" name="bc_id" style="width: 150px;float: left;">
                <option value="" >选择分公司</option>
                <?php foreach ($branch_data as $data) :?>
                    <option value="<?= $data['province_id'];?>"><?= $data['name'];?></option>
                <?php endforeach?>
            </select>
            <label style="float: left;height: 34px ;line-height: 34px">城市:   </label>
            <select id="city_id" class="form-control" name="ShopConfig[bc_id]" style="width: 150px;float: left;">
                <option value="">选择城市</option>
            </select>

        </div>
        <div class="help-block help-block-error " style="color: red;"><?php echo \Yii::$app->getSession()->getFlash('error');?></div>
    </div>
<?= $form->field($model, 'community_num')->radioList(['0'=>'不限制','1'=>'限制'])->label('是否设置最大值'); ?>
<?= $form->field($model, 'price_limit')->radioList(['0'=>'不限制','1'=>'限制'])->label('是否限制售价'); ?>
    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="/shop/config/list">返回</a>
    </div>
<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function(){
        $("#bc_id").change(function()
        {
            var bc_id=$(this).val();
            $.get
            (
                "/admin/couponstype/city?bc_id="+bc_id,
                function(str_json)
                {
                    obj=JSON.parse(str_json);

                    var html_option="<option value=''>选择城市</option>";
                    var len=obj.length;
                    for(var i=0;i<len;i++)
                    {
                        html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                    }
                    $("#city_id").html('全部城市');
                    $("#city_id").append(html_option);
                }
            );
        });
    });
</script>
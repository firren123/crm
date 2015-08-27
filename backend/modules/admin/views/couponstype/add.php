<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class="active"><a href="/admin/couponstype">优惠券类别管理</a></li>
        <li class="active">增加优惠券类别</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
            <?php $form = ActiveForm::begin(['id' => 'login-form','layout' => 'horizontal',]); ?>
            <?= $form->field($model, 'type_name') ?>
            <?= $form->field($model, 'send_type')->radioList(['1'=>'线下发放','2'=>'按用户发放']) ?>

            <?= $form->field($model, 'par_value') ?>
            <?= $form->field($model, 'min_amount') ?>
<!--            <?/*= $form->field($model, 'coupon_thumb')->fileInput()*/ ?>-->

<!--            'consumer_points' =>'消费积分',-->
            <?= $form->field($model, 'consumer_points') ?>
<!--            'add_time' =>'添加时间',-->
            <div class="form-group field-coupontype-send_type required">
                <label class="control-label col-sm-3" for="coupontype-start_time">开始时间</label>
                <div class="col-sm-6">
                    <input type="text" id="coupontype-start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" style="float:left;" class="form-control" name="CouponType[start_time]" value="<?= $model->start_time;?>">
                    <div class="help-block help-block-error " style="color: #a94442"><?= \Yii::$app->getSession()->getFlash('start_time'); ?></div>
                </div>

            </div>
<!--            'expired_time' =>'过期时间',-->
            <div class="form-group field-coupontype-send_type required">
                <label class="control-label col-sm-3" for="coupontype-expired_time">过期时间</label>
                <div class="col-sm-6">
                    <input type="text" id="coupontype-expired_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" style="float:left;" class="form-control" name="CouponType[expired_time]" value="<?= $model->expired_time;?>">
                    <div class="help-block help-block-error " style="color: #a94442"><?= \Yii::$app->getSession()->getFlash('expired_time'); ?></div>
                </div>

            </div>
<!--            'used_status'=>'是否可用',-->
            <?= $form->field($model, 'used_status')->radioList(['0'=>'可用']) ?>
<!--            'number' => '数量',-->
            <?= $form->field($model, 'number') ?>
<!--            'limit_num' => '用户最多兑换张数',-->
            <?= $form->field($model, 'limit_num') ?>
<!--            'source' => '券来源',-->
            <?= $form->field($model, 'source') ?>
<!--            'is_all' => '全场通用',-->
            <?= $form->field($model, 'is_all')->checkbox(['1'=>'全场通用'],false) ?>
<!--            'coupon_type' => '券类型',-->
            <?= $form->field($model, 'coupon_type')->radioList(['0'=>'普通券','1'=>'注册送券','2'=>'系统券']) ?>
<!--            'status' => '状态',-->
            <?= $form->field($model, 'status')->radioList(['1'=>'显示','2'=>'禁用']) ?>
            <?= $form->field($model, 'use_system')->radioList(['1'=>'i500m','2'=>'社交平台'])->label('系统') ?>
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
                    <select id="city_id" class="form-control" name="CouponType[city_id]" style="width: 150px;float: left;">
                        <option value="">选择城市</option>
                    </select>

                </div>
                <div class="help-block help-block-error " style="color: red;"><?php echo \Yii::$app->getSession()->getFlash('error');?></div>
            </div>
<!--           <?///*= $form->field($model, 'cate_id') */?>-->
<!--            <?/*= $form->field($model, 'product_id') */?>-->
<!--            <?/*= $form->field($model, 'brand_id') */?>-->
            <?= $form->field($model, 'remark')->textarea(['size'=>60,'maxlength'=>128])?>
            <div class="form-actions">
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
                <a class="btn cancelBtn" href="/admin/couponstype">返回</a>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>

</legends>
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
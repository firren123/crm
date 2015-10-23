<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  add_set.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/20 下午3:55
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加/修改服务';
?>
<script type="text/javascript" src="/js/webuploader//webuploader.js"></script>
<style>
    .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
        text-align: left;
    }
    .compileForm .imgListForm{padding-top:10px; height:117px;}
    ul li{
        float: left;

    }
    ul{list-style: none;}
    .imgListForm li{width:92px;min-height:117px; border:none; background:#fff;}
    .imgListForm a,.imgListForm span{display:block;}
    .imgListForm a{width:90px; height:90px; text-align:center; border:1px solid #dfdfdf; background:#f5f5f5;}
    .imgListForm span{color:#666; line-height:25px; cursor:pointer;text-align: center;}
</style>
<link rel="stylesheet" type="text/css" href="/js/webuploader/webuploader.css">
<legends  style="fond-size:12px;">
    <legend>添加/修改服务</legend>
</legends>

<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/service/setting">服务管理</a></li>
        <li class="active">增加/修改 服务</li>
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
<?= $form->field($model, 'mobile')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'category_id')->dropDownList($category_id_data,['prompt'=>'请选择分类']); ?>
<?= $form->field($model, 'son_category_id')->dropDownList([],['prompt'=>'请选择子分类']); ?>
<div class="form-group field-service-image required">
    <label class="control-label col-sm-3" for="service-image">服务图片</label>
    <div class="col-sm-6">
        <ul class="imgList imgListForm">
            <li>
                <a href="javascript:;" id="filePicker1_img">
                    <?php if (!empty($model->image)) { ?>
                        <img src="<?= \Yii::$app->params['imgHost'] . $model->image; ?>"
                             style="width:90px;height:90px;">
                    <?php } ?>
                </a>
                <span class="txt" id="filePicker1">上传</span>
                <input type="hidden" value="<?php if (isset($model->image)) {
                    echo $model->image;
                } ?>" name="Service[image]" id="images1"/>
            </li>
        </ul>
        <div class="help-block help-block-error "></div>
    </div>
</div>
<?= $form->field($model, 'title')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'price')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'unit')->radioList($unit_data); ?>
<?= $form->field($model, 'service_way')->radioList($service_way_data); ?>
<?= $form->field($model, 'description')->textarea() ; ?>
<?= $form->field($model, 'community_city_id')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'community_id')->input('text',['style'=>'width:200px']) ; ?>

<div class="form-actions">
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    <input type="hidden" value="<?= $model->son_category_id;?>" id="son_id"/>
</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    $(function(){
        var son_id = $("#son_id").val();
        var p_id = $("#service-category_id").val();
        if(son_id){
            $.get(
                '/social/service/get-son-cate?id='+p_id,
                function(data){
                    var html ='';
                    for(i in data){
                        if(i==son_id){
                            html += "<option selected value='"+i+"'>"+data[i]+"</option>";
                        }else{
                            html += "<option value='"+i+"'>"+data[i]+"</option>";
                        }
                    }
                    $("#service-son_category_id").html('<option value="">请选择子分类</option>');
                    $("#service-son_category_id").append(html);
                },
                'json'
            );
        }
        console.log(son_id);
        $("#service-category_id").change(function(){
            var pid = $(this).val();
            $.get(
                '/social/service/get-son-cate?id='+pid,
                function(data){
                    var html ='';
                    for(i in data){
                        html += "<option value='"+i+"'>"+data[i]+"</option>";
                    }
                    $("#service-son_category_id").html('<option value="">请选择子分类</option>');
                    $("#service-son_category_id").append(html);
                },
                'json'
            );
        });


    })
</script>
<script type="text/javascript" src="/js/goods/Upload.js?_<?= Yii::$app->params['jsVersion'];?>"></script>

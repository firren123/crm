<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  forum_add.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 下午4:57
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '增加/修改 服务类型';
?>
<script type="text/javascript" src="/js/webuploader/webuploader.js"></script>
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
    .breadcrumb{height: 35px}
</style>
<link rel="stylesheet" type="text/css" href="/js/webuploader/webuploader.css">
<legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/social/category">服务类型管理</a></li>
            <li class="active">增加/修改 服务类型</li>
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
<?= $form->field($model, 'name')->input('text',['style'=>'width:200px'])->label('类型名称') ; ?>

<div class="form-group field-forum-pid">
    <label class="control-label col-sm-3" for="forum-pid"><span class="red">*</span>类型图片</label>
    <div class="col-sm-6">
        <ul class="imgList imgListForm">
            <!--当上传图片后<span class="txt">上传</span>去掉-->
            <li>
                <a href="javascript:;" id="filePicker1_img">
                    <?php if(!empty($model->image)){ ?>
                        <img src="<?= \Yii::$app->params['imgHost'].$model->image;?>" style="width:90px;height:90px;">
                    <?php } ?>
                </a>
                <span class="txt" id="filePicker1">上传</span>
                <input type="hidden" value="<?= $model->image;?>" name="ServiceCategory[image]" id="images1" />
            </li>

        </ul>
        <div class="help-block help-block-error "></div>
    </div>

</div>
<?= $form->field($model, 'sort')->input('text',['style'=>'width:200px'])->label('排序名称') ; ?>
<?= $form->field($model, 'status')->radioList(['1'=>'可用','2'=>'禁用'])->label('是否可用'); ?>
<?= $form->field($model, 'is_deleted')->radioList(['1'=>'不显示','2'=>'显示'])->label('显示'); ?>
<?= $form->field($model, 'description')->textarea()->label('类型描述') ; ?>
<?= $form->field($model, 'pid')->label('')->hiddenInput(); ?>
    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">返回</a>
    </div>
<?php ActiveForm::end(); ?>
<input type="hidden" id="img_url" value="<?= \Yii::$app->params['imgHost']; ?>"/>
<script type="text/javascript">
    $(function(){
        $(".btn-primary").click(function(){
            var img = $("#images1").val();
            if(img.length ==0){
                gf.alert('类型图片不能为空');
                return false;
            }
        });

        $("#filePicker1").click(function(){
            var f_id = $("#forum-pid").val();
            var d = dialog({
                url:'/public/phone?f_id='+f_id,
                title: '选择图片',

            });
            d.showModal();
            return false;
        });
    });
    function addImg(data){
        var imgUrl = $("#img_url").val();
        $("#images1").val(data);
        $('#filePicker1_img').html('<img src="'+imgUrl+data+'" style="width:90px;height:90px;" />');
    }


</script>
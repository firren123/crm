<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '修改帖子';
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
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/social/post/index">帖子管理</a></li>
            <li class="active">增加/修改 帖子</li>
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
<?= $form->field($model, 'forum_id')->dropDownList($forum_list,['prompt'=>'请选择']) ; ?>
<?= $form->field($model, 'title')->input('text',['style'=>'width:200px']) ; ?>
    <div class="form-group field-forum-post_img">
        <label class="control-label col-sm-3" for="forum-post_img"><span class="red">*</span>帖子图片</label>
        <div class="col-sm-6">
            <ul class="imgList imgListForm">
                <li>
                    <a href="javascript:;" id="filePicker1_img">
                        <?php if(!empty($model->post_img)){ ?>
                            <img src="<?= \Yii::$app->params['imgHost'].$model->post_img;?>" style="width:90px;height:90px;">
                        <?php } ?>
                    </a>
                    <span class="txt" id="filePicker1">上传</span>
                    <input type="hidden" value="<?= $model->post_img;?>" name="Post[post_img]" id="images1" />
                </li>
            </ul>
            <div class="help-block help-block-error "></div>
        </div>

    </div>
<?= $form->field($model, 'thumbs')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'views')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'top')->radioList(['1'=>'是','2'=>'否']); ?>
<?= $form->field($model, 'status')->radioList(['1'=>'禁用','2'=>'可用']); ?>
<?= $form->field($model, 'content')->textarea(); ?>
<?php // $form->field($model, 'is_deleted')->radioList(['1'=>'已删除','2'=>'未删除']); ?>

    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
    </div>
<?php ActiveForm::end(); ?>
<script type="text/javascript" src="/js/goods/Upload.js?_<?= Yii::$app->params['jsVersion'];?>"></script>
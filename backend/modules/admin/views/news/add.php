<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "添加资讯";
?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/news.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.config.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.all.min.js");?>

<div class="tab-content">

    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">公司</span>
        <select style="width: 150px;" id="bc_id" class="form-control bc_option">
<!--            <option value="0">请选择分公司</option>-->
        </select>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">标题</span>
        <input type="text" id="title" class="form-control" placeholder="资讯标题">
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">类别</span>
        <select style="width: 150px;" id="category_id" class="form-control category_option">
            <option value="0">请选择分类</option>
        </select>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">作者</span>
        <input type="text" class="form-control" id="author" placeholder="资讯作者">
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">内容</span>
        <script id="editor" type="text/plain" style="width:100%;height:300px;"></script>
    </div>

    <div class="input-group" style="margin-top: 5px;">
        <input type="text" class="form-control" id="seo_title" placeholder="输入SEO页面标题">
        <span class="input-group-addon" id="basic-addon2">SEO页面标题</span>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <input type="text" class="form-control" id="seo_keywords" placeholder="输入SEO页面关键字">
        <span class="input-group-addon" id="basic-addon2">SEO页面关键字</span>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <input type="text" class="form-control" id="seo_description" placeholder="输入SEO页面描述">
        <span class="input-group-addon" id="basic-addon2">SEO页面描述</span>
    </div>

    <div style="margin-top: 5px;float: right;">
        <a class="btn btn-primary submit-news option_news" onclick="news.submitNews();" href="javascript:;">发布文章</a>
        <a class="btn btn-primary submit-news sub_loading" style="display: none;" href="javascript:;">提交中...</a>
    </div>

    <input type="hidden" id="token" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
    <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
    <input type="hidden" id="act" value="add">

</div>
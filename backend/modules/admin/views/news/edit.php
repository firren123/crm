<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "编辑资讯";
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
        <input type="text" id="title" value="<?php echo !empty($info['title']) ? $info['title'] : '' ?>" class="form-control" placeholder="资讯标题">
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">类别</span>
        <select style="width: 150px;" id="category_id" class="form-control category_option">
            <option value="0">请选择分类</option>
        </select>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">作者</span>
        <input type="text" class="form-control" value="<?php echo !empty($info['author']) ? $info['author'] : '' ?>" id="author" placeholder="资讯作者">
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">内容</span>
        <script id="editor" type="text/plain" style="width:100%;height:300px;"><?php echo !empty($info['content']) ? $info['content'] : '' ?></script>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">状态</span>
        <select style="width: 120px;" id="status" class="form-control">
            <?php if(!empty($info['status'])) {
               ?>
                <option <?php if($info['status']=='1') echo "selected='selected'";?> value="1">可用</option>
                <option <?php if($info['status']=='2') echo "selected='selected'";?> value="2">禁用</option>
               <?php
            }?>
        </select>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <input type="text" class="form-control" value="<?php echo !empty($info['seo_title']) ? $info['seo_title'] : '' ?>" id="seo_title" placeholder="输入SEO页面标题">
        <span class="input-group-addon" id="basic-addon2">SEO页面标题</span>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <input type="text" class="form-control" value="<?php echo !empty($info['seo_keywords']) ? $info['seo_keywords'] : '' ?>" id="seo_keywords" placeholder="输入SEO页面关键字">
        <span class="input-group-addon" id="basic-addon2">SEO页面关键字</span>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <input type="text" class="form-control" value="<?php echo !empty($info['seo_description']) ? $info['seo_description'] : '' ?>" id="seo_description" placeholder="输入SEO页面描述">
        <span class="input-group-addon" id="basic-addon2">SEO页面描述</span>
    </div>

    <div style="margin-top: 5px;float: right;">
        <a class="btn btn-primary submit-news option_news" onclick="news.submitNews();" href="javascript:;">编辑文章</a>
        <a class="btn btn-primary submit-news sub_loading" style="display: none;" href="javascript:;">提交中...</a>
    </div>
    <input type="hidden" id="info_category_id" value="<?php echo !empty($info['category_id']) ? $info['category_id'] : 0 ;?>"/>
    <input type="hidden" id="info_bc_id" value="<?php echo !empty($info['bc_id']) ? $info['bc_id'] : 0 ;?>"/>
    <input type="hidden" id="token" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
    <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
    <input type="hidden" id="news_id" value="<?php echo !empty($info['id']) ? $info['id'] : '0' ?>">
    <input type="hidden" id="act" value="edit">

</div>
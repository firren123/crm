<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "编辑资讯分类";
?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/news.js");?>

<div class="tab-content">

    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">名称</span>
        <input type="text" id="name" value="<?php echo !empty($info['name']) ? $info['name'] : '' ?>" class="form-control" placeholder="请输入分类名称">
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">类别</span>
        <select style="width: 150px;" id="parent_id" class="form-control category_option">
            <option value="0">顶级分类</option>
        </select>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">描述</span>
        <textarea type="text" class="form-control" id="description" placeholder="请输入分类描述"><?php echo !empty($info['description']) ? $info['description'] : '' ?></textarea>
    </div>
    <div class="input-group" style="margin-top: 5px;">
        <span class="input-group-addon" id="sizing-addon1">排序</span>
        <input type="text" style="width:80px;" value="<?php echo !empty($info['sort']) ? $info['sort'] : '' ?>" class="form-control" id="sort" placeholder="排序">
    </div>

    <div style="margin-top: 5px;float: right;">
        <a class="btn btn-primary submit-news sub_category" href="javascript:;">编辑分类</a>
        <a class="btn btn-primary submit-news sub_loading" style="display: none;" href="javascript:;">提交中...</a>
    </div>
    <input type="hidden" id="info_category_id" value="<?php echo !empty($info['parent_id']) ? $info['parent_id'] : 0 ;?>"/>
    <input type="hidden" id="token" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
    <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
    <input type="hidden" id="act" value="edit_category">
    <input type="hidden" id="id" value="<?php echo !empty($info['id']) ? $info['id'] : '0' ?>">

</div>
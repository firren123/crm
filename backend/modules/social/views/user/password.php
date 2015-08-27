<?php
$this->title = '用户管理-修改密码';
use yii\bootstrap\ActiveForm;
?>
<script type="text/javascript" src="/js/social/user.js"></script>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/user">用户管理</a></li>
        <li class="active">用户管理-修改密码</li>
    </ul>
    <?php
            if (empty($item)) {
                echo '<tr><td colspan="15">暂无记录</td></tr>';
            } else {
    ?>
    <div class="tab-content">
        <div class="form-horizontal">
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">新密码:</label>

                <div class="col-sm-6">
                    <input id="password" class="form-control" type="password" style="width:200px">

                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">重复密码:</label>

                <div class="col-sm-6">
                    <input id="re_password" class="form-control" type="password" style="width:200px">

                    <div class="help-block help-block-error "></div>
                </div>
            </div>
        </div>

    </div>
    <div class="form-actions">
        <input type="hidden" id="csrf" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>"/>
        <input type="button" value="提交" onclick="user.updatePassword(<?= $item['mobile']?>)" class='btn btn-primary'>
        <a class="btn cancelBtn" href="/social/user">返回</a>
    </div>
</legends>
<?php
}?>
<?php
$this->title = '用户实名验证';
use yii\bootstrap\ActiveForm;
?>
<script type="text/javascript" src="/js/social/user.js"></script>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/user">用户管理</a></li>
        <li class="active">用户实名验证</li>
    </ul>
    <table class="table table-bordered table-hover" style="width: 60%">
        <tbody>
        <tr>
            <td colspan="2">审核情况</td>
        </tr>
        <tr>
            <td style="width:20%;">审核状态：</td>
            <td><?php
                if ($item['card_audit_status']) {
                    switch ($item['card_audit_status']) {
                        case 0;
                            echo "未审核";
                            break;
                        case 1;
                            echo "审核中";
                            break;
                        case 2;
                            echo "审核通过";
                            break;
                        case 3;
                            echo "审核失败";
                            break;
                    }
                } else {
                    echo '--';
                }
                ?></td>
        </tr>
        </tbody>
    </table>
    <?php
    $form = ActiveForm::begin([
        'id' => "login-form",
        'layout' => 'horizontal',
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data'],
    ]);
    ?>
    <div class="tab-content">
        <div class="form-horizontal">
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">真实姓名:</label>
                <div class="col-sm-6">
                    <input id="realname" class="form-control" type="text" style="width:200px;" name="User[mobile]" value="<?= $item['realname']?>">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">身份证号:</label>
                <div class="col-sm-6">
                    <input id="user_card" class="form-control" type="text" style="width:200px" name="User[nickname]" value="<?= $item['user_card']?>">
                </div>
            </div>


    </div>
    <div class="form-actions">
        <input type="hidden" id="csrf" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <a class="btn btn-primary" href="javascript:void(0)" onclick="user.examine(<?php echo $_GET['mobile']?>)">提交</a>
        <a class="btn cancelBtn" href="/social/user">返回</a>
    </div>
    <?php ActiveForm::end(); ?>
</legends>
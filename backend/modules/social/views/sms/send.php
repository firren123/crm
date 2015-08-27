<?php
$this->title = '短信发送';
use yii\bootstrap\ActiveForm;
?>
<script type="text/javascript" src="/js/social/sms.js"></script>
<legends  style="fond-size:12px;">
    <legend>短信发送</legend>
</legends>
    <div class="tab-content">
        <div class="form-horizontal">
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">手机号:</label>
                <div class="col-sm-6">
                    <input id="mobile" class="form-control" type="text" style="width:200px;" name="User[mobile]">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">发送内容:</label>
                <div class="col-sm-6">
                    <textarea id="content" rows="8" cols="100" class="form-control"></textarea>
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
    </div>
        </div>
    <div class="form-actions">
        <input type="hidden" id="csrf" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <input type="button" value="发送" onclick="sms.sendSms()" class = 'btn btn-primary'>
    </div>
</legends>
<script>
    $(function () {
        ms_DatePicker({
            YearSelector: ".sel_year",
            MonthSelector: ".sel_month",
            DaySelector: ".sel_day"
        });
        ms_DatePicker();
    });
</script>
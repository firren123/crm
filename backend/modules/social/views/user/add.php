<?php
$this->title = '用户管理-添加';
use yii\bootstrap\ActiveForm;
?>
<script type="text/javascript" src="/js/social/user.js"></script>
<script type="text/javascript" src="/js/social/birthday.js"></script>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/user">用户管理</a></li>
        <li class="active">用户管理-添加</li>
    </ul>
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
                <label class="control-label col-sm-3" for="brand-name">手机号:</label>
                <div class="col-sm-6">
                    <input id="mobile" class="form-control" type="text" style="width:200px;" name="User[mobile]">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">密码:</label>
                <div class="col-sm-6">
                    <input id="password" class="form-control" type="password" style="width:200px" name="User[password]">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name">
                <label class="control-label col-sm-3" for="brand-name">昵称:</label>
                <div class="col-sm-6">
                    <input id="nickname" class="form-control" type="text" style="width:200px" name="User[nickname]">
                </div>
            </div>
            <div class="form-group field-brand-name">
                <label class="control-label col-sm-3" for="brand-name">真实姓名:</label>
                <div class="col-sm-6">
                    <input id="realname" class="form-control" type="text" style="width:200px" name="User[realname]">
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">性别:</label>
                <div class="col-sm-6">
                    <select style="width:100px;height: 30px" id="sex" name="User[sex]">
                        <option value="1" selected="selected">男士</option>
                        <option value="2">女士</option>
                    </select>
                </div>
            </div>
            <div class="form-group field-brand-name">
                <label class="control-label col-sm-3" for="brand-name">头像:</label>
                <div class="col-sm-6">
                    <input type="file" id="productimage-image" name="User[avatar]">
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">城市:</label>
                <div class="col-sm-6">
                    <select id="province_id" class="zjs_select_province" style="height: 30px;width: 100px" name="User[province_id]">
                        <option value="0">省市</option>
                        <?php if(!empty($province_list)){
                            ?>
                            <?php foreach($province_list as $a_provice){ ?>
                                <option value="<?php echo $a_provice['id']; ?>"><?php echo $a_provice['name']; ?></option>
                            <?php } ?>
                        <?php
                        }?>
                    </select>
                    <select id="city_id" class="zjs_select_city" style="height: 30px;width: 100px" name="User[city_id]">
                        <option value="0" class="zjs_default_v">市区</option>
                    </select>
                    <select id="district_id" class="zjs_select_district" style="height: 30px;width: 100px" name="User[district_id]">
                        <option value="0" class="zjs_default_v">县乡</option>
                    </select>
                    </div>
                </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">小区名称:</label>
                <div class="col-sm-6">
                    <input id="community_name" class="form-control" type="text" style="width:200px" name="User[community_name]">
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">生日:</label>
                <div class="col-sm-6">
                    <select class="sel_year" rel="1990" style="height: 30px;width: 100px" name="User[year]"></select>年
                    <select class="sel_month" rel="1" style="height: 30px;width: 100px" name="User[month]"></select>月
                    <select class="sel_day" rel="1" style="height: 30px;width: 100px" name="User[day]"></select>日
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name">
                <label class="control-label col-sm-3" for="brand-name">个性签名:</label>
                <div class="col-sm-6">
                    <input id="personal_sign" class="form-control" type="text" style="width:200px" name="User[personal_sign]">
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">是否禁用:</label>
                <div class="col-sm-6">
                    <select style="width:100px;height: 30px" id="status" name="User[status]">
                        <option selected="selected" value="2">否</option>
                        <option value="1">是</option>
                    </select>
                </div>
            </div>
        </div>

    </div>
    <div class="form-actions">
        <input type="hidden" id="csrf" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <input type="button" value="提交" onclick="user.submit()" class = 'btn btn-primary'>
        <a class="btn cancelBtn" href="/social/user">返回</a>
    </div>
    <?php ActiveForm::end(); ?>
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
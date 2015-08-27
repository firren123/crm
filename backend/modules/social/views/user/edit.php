<?php
$this->title = '用户管理-编辑';
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
        <li class="active">用户管理-编辑</li>
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
                    <input id="mobile" class="form-control" type="text" style="width:200px;" name="User[mobile]" value="<?= $item['mobile']?>"  disabled="true"　readOnly="true">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name">
                <label class="control-label col-sm-3" for="brand-name">昵称:</label>
                <div class="col-sm-6">
                    <input id="nickname" class="form-control" type="text" style="width:200px" name="User[nickname]" value="<?= $item['nickname']?>">
                </div>
            </div>
            <div class="form-group field-brand-name">
                <label class="control-label col-sm-3" for="brand-name">真实姓名:</label>
                <div class="col-sm-6">
                    <input id="realname" class="form-control" type="text" style="width:200px" name="User[realname]" value="<?= $item['realname']?>">
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">性别:</label>
                <div class="col-sm-6">
                    <select style="width:100px;height: 30px" id="sex" name="User[sex]">
                        <option value="1" <?php if(!empty($item['sex']) and $item['sex']==1):?>selected="selected"<?php endif;?>>男士</option>
                        <option value="2" <?php if(!empty($item['sex']) and $item['sex']==2):?>selected="selected"<?php endif;?>>女士</option>
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
                                <option value="<?php echo $a_provice['id']; ?>" <?php if(!empty($item['province_id']) and $item['province_id']==$a_provice['id']):?>selected="selected"<?php endif;?>><?php echo $a_provice['name']; ?></option>
                            <?php } ?>
                        <?php
                        }?>
                    </select>
                    <select id="city_id" class="zjs_select_city" style="height: 30px;width: 100px" name="User[city_id]">
                        <option value="0" class="zjs_default_v">市区</option>
                        <?php if(!empty($city_list)){
                            ?>
                            <?php foreach($city_list as $a_city){
                                $selected = '';
                                if(!empty($city_list) && $item['city_id'] == $a_city['id']) {
                                    $selected = ' selected';
                                }

                                echo '<option value="'.$a_city['id'].'"'.$selected.'>'.$a_city['name'].'</option>';
                            } ?>
                        <?php
                        }?>
                    </select>
                    <select id="district_id" class="zjs_select_district" style="height: 30px;width: 100px" name="User[district_id]">
                        <option value="0" class="zjs_default_v">县乡</option>
                        <?php if(!empty($district_list)){
                            ?>
                            <?php foreach($district_list as $a_district){
                                $selected = '';
                                if(!empty($district_list) && $item['district_id'] == $a_district['id']) {
                                    $selected = ' selected';
                                }

                                echo '<option value="'.$a_district['id'].'"'.$selected.'>'.$a_district['name'].'</option>';
                            } ?>
                        <?php
                        }?>
                    </select>
                    </div>
                </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">小区名称:</label>
                <div class="col-sm-6">
                    <input id="community_name" class="form-control" type="text" style="width:200px" name="User[community_name]" value="<?= $item['community_name']?>">
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">生日:</label>
                <div class="col-sm-6">
                    <select class="sel_year" rel="<?= empty($year_data) ? '1990' : $year_data[0]?>" style="height: 30px;width: 100px" name="User[year]"></select>年
                    <select class="sel_month" rel="<?= empty($year_data) ? '1' : $year_data[1]?>" style="height: 30px;width: 100px" name="User[month]"></select>月
                    <select class="sel_day" rel="<?= empty($year_data) ? '1' : $year_data[2]?>" style="height: 30px;width: 100px" name="User[day]"></select>日
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name">
                <label class="control-label col-sm-3" for="brand-name">个性签名:</label>
                <div class="col-sm-6">
                    <input id="personal_sign" class="form-control" type="text" style="width:200px" name="User[personal_sign]" value="<?= $item['personal_sign']?>">
                </div>
            </div>
        </div>

    </div>
    <div class="form-actions">
        <input type="hidden" id="csrf" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <input type="button" value="提交" onclick="user.update()" class = 'btn btn-primary'>
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
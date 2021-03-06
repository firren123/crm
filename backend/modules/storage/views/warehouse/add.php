<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加库房';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/storage/warehouse/index">库房管理</a></li>
        <li class="active">添加库房</li>
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
<?= $form->field($model, 'sn')->label('库房编号')->input('text',['style'=>'width:200px','maxlength'=>20,'class'=>'form-control sn_num']) ; ?>
<?= $form->field($model, 'name')->label('库房名称')->input('text',['style'=>'width:200px','maxlength'=>14]) ; ?>
<?= $form->field($model, 'username')->label('库房联系人')->input('text',['style'=>'width:200px','maxlength'=>10]) ; ?>
<?= $form->field($model, 'phone')->label('手机号')->input('text',['style'=>'width:200px']) ; ?>

<div class="form-group field-warehouse-bc_id required">
    <label class="control-label col-sm-3" for="warehouse-bc_id">分公司</label>
    <div class="col-sm-6">
        <select id="warehouse-bc_id" class="form-control bc_id" name="Warehouse[bc_id]" style="width:200px">
            <option value="0" class="form-control bc_id">请选择分公司</option>

            <?php if(!empty($branch_arr)) :?>
                <?php foreach($branch_arr as $data) :?>
                    <option value="<?= $data['id'];?>" <?php if(!empty($bc_id) and $data['id']==$bc_id):?>selected="selected"<?php endif;?>><?= $data['name']; ?></option>
                <?php endforeach;?>
            <?php endif;?>
        </select>
        <div class="help-block help-block-error "></div>
    </div>

</div>

<div class="form-group field-warehouse-province_id required">
    <label class="control-label col-sm-3" for="warehouse-province_id">入库地址</label>
    <div class="col-sm-6" style="width:80%;height:30px;">
        <select id="warehouse-province_id" class="form-control province_id" name="Warehouse[province_id]" style="width:150px;float:left;">
            <option>请选择省</option>
        </select>
        <select id="warehouse-city_id" class="form-control city_id" name="Warehouse[city_id]" style="width:150px;float:left; margin:0 10px;">
            <option value='0'>请选择市</option>
        </select>
        <select id="warehouse-district_id" class="form-control district_id" name="Warehouse[district_id]" style="width:150px;float:left;">
            <option value='0'>全部区/县</option>
        </select>
    </div>
</div>

<?= $form->field($model, 'address')->label('详细地址')->input('text',['style'=>'width:445px']) ; ?>
<?= $form->field($model, 'remarks')->textarea(['size'=>60,'maxlength'=>128])?>
<?= $form->field($model, 'status')->radioList(['1'=>'禁用','2'=>'正常'])->label('有效性'); ?>

<div class="form-actions">
    <a href="javascript:;" class='btn btn-primary save'>保存</a>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
    $(function()
    {
        $(".bc_id").change(function()
        {
            var bc_id=$(".bc_id").val();
            $.get
            (
                "/storage/warehouse/province-city?bc_id="+bc_id,
                function(obj)
                {
                    var obj_p=obj.pName;
                    var html_option='';
                    html_option+='<option value="'+obj_p.pid+'">'+obj_p.name+'</option>';
                    $(".province_id").html(html_option);

                    var obj_c=obj.cName;
                    var len=obj_c.length;
                    var html_option='';
                    html_option+="<option value='0'>请选择市</option>";
                    for(var i=0;i<len;i++){
                        html_option+='<option value="'+obj_c[i].id+'">'+obj_c[i].name+'</option>';
                    }
                    $(".city_id").html(html_option);
                },
                'json'
            );
        });
    });

    $(function()
    {
        $(".city_id").change(function()
        {
            var city_id=$(".city_id").val();
            $.get
            (
                "/storage/warehouse/district?city_id="+city_id,
                function(obj)
                {
                    var obj_d=obj;
                    var len=obj_d.length;
                    var html_option='';
                    if(city_id == 999 || city_id == 0){
                        html_option+="<option value='10000'>全部区/县</option>";
                    }else {
                        for (var i = 0; i < len; i++) {
                            html_option += '<option value="' + obj_d[i].id + '">' + obj_d[i].name + '</option>';
                        }
                    }
                    $(".district_id").html(html_option);
                },
                'json'
            );
        });
        /*//验证库房编号是否存在
        $(".sn_num").keyup(function(){
            var sn = $.trim($(this).closest("div").find(".sn_num").val());
            if(sn != '') {
                $.getJSON('/storage/warehouse/check', {'sn': sn}, function (data) {
                    if (data.status == 1) {
                        var d = dialog({
                            title: '提示',
                            content: '库房编号已经存在！！！',
                            okValue: '确定',
                            ok: function () {
                            }
                        })
                        d.showModal();
                        return false;
                    }
                });
            }
        })*/

        //点击提交
        $(".save").click(function(){
            var city = $("#warehouse-city_id option:selected").val();
            var dist = $("#warehouse-district_id option:selected").val();
            var sn = $.trim($(".sn_num").val());
            if(city == '' || city == 0){
                var d = dialog({
                    title: '提示',
                    content: '选择城市不能为空！！！',
                    okValue: '确定',
                    ok: function () {
                    }
                })
                d.showModal();
                return false;
            }


            $.getJSON('/storage/warehouse/check', {'sn': sn}, function (data) {
                if (data.status == 1) {
                    var d = dialog({
                        title: '提示',
                        content: '库房编号已经存在！！！',
                        okValue: '确定',
                        ok: function () {
                        }
                    })
                    d.showModal();
                    return false;
                }else{
                    $("#login-form").submit();
                }
            });

        })
    });

</script>
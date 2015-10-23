<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  add_set.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/20 下午3:55
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加/修改店铺';
?>
<style type="text/css">
    .item{
        padding:3px 5px;
        cursor:pointer;
        height: 30px;
        line-height: 30px;
    }
    .addbg{
        background:#ccc;
    }
    .first{
        border:solid #87A900 2px;
        width:300px;
    }
    #append{
        border:1px solid #ccc;
        border-top:0;
        display:none;
        width: 445px;
        overflow: hidden;
        position: absolute;
        z-index: 2;
        background: #ffffff;
    }
</style>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/news.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.config.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.all.min.js");?>
<?= $this->registerJsFile("@web/js/goods/product.js");?>
<legends  style="fond-size:12px;">
    <legend>添加/修改店铺</legend>
</legends>

<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/service/setting">店铺管理</a></li>
        <li class="active">增加/修改 店铺</li>
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
<?= $form->field($model, 'name')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'description')->textarea() ; ?>
<?= $form->field($model, 'province_id')->dropDownList($province_list,['prompt'=>'请选择','style'=>'width:200px']) ; ?>
<div class="form-group field-servicesetting-search_address required">
    <label class="control-label col-sm-3" for="kw">检索出的详细地址</label>
    <div class="col-sm-6">
        <input onKeyup="getContent(this);" type="text" id="kw" class="form-control" name="ServiceSetting[search_address]" value="<?= isset($model['search_address']) ? $model['search_address'] : '' ?>"/>
        <div id="append"></div>
        <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('search_address'); ?></div>
        <div class="help-block help-block-error "></div>
    </div>
</div>

<?= $form->field($model, 'details_address')->input('text') ; ?>
<?= $form->field($model, 'status')->radioList(['1'=>'禁用','2'=>'可用']); ?>
<?= $form->field($model, 'lng')->input('text',['style'=>'width:200px']) ; ?>
<?= $form->field($model, 'lat')->input('text',['style'=>'width:200px']) ; ?>

<div class="form-group field-servicesetting-lat">
    <label class="control-label col-sm-3" for="servicesetting-lat">坐标</label>
    <div class="col-sm-9">
        <!--获取坐标 start-->
        <style type="text/css">
            #z_id_div_map{height:600px;width:100%;border:1px solid #bcbcbc;}
        </style>
        <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
        <div>
            <p>请点击位置获取坐标。若找不到，可到 <a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">百度拾取</a> 获取坐标，并把坐标复制粘贴到上面的文本框中</p>
            <div id="z_id_div_map"></div>
        </div>
        <script type="text/javascript">
            var x=116.417321;
            var y=39.887979;
            // 百度地图API功能
            var map = new BMap.Map("z_id_div_map");//创建Map实例
            var point = new BMap.Point(x, y);
            map.centerAndZoom(point, 18);
            var mk = new BMap.Marker(point);
            map.addOverlay(mk);//加标记
            mk.enableDragging();//marker可拖拽
            mk.addEventListener("dragend", function(e){
                $("#servicesetting-lng").val(e.point.lng);
                $("#servicesetting-lat").val(e.point.lat);
            });
            map.enableScrollWheelZoom();
            map.addEventListener("click",function(e){
                $("#servicesetting-lng").val(e.point.lng);
                $("#servicesetting-lat").val(e.point.lat);
            });
        </script>
        <!--获取坐标 end-->
    </div>

</div>


<div class="form-actions">
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
<?php ActiveForm::end(); ?>
<script>
    var data= ["查询中。。"];
    var pro_id =1;
    $(document).ready(function(){
        $("#servicesetting-province_id").change(function()
        {
            pro_id=$(this).find("option:selected").text();
        });
    });
    $(document).ready(function(){
        $(document).keydown(function(e){
            e = e || window.event;
            var keycode = e.which ? e.which : e.keyCode;
            if(keycode == 38){
                if(jQuery.trim($("#append").html())==""){
                    return;
                }
                movePrev();
            }else if(keycode == 40){
                if(jQuery.trim($("#append").html())==""){
                    return;
                }
                $("#kw").blur();
                if($(".item").hasClass("addbg")){
                    moveNext();
                }else{
                    $(".item").removeClass('addbg').eq(0).addClass('addbg');
                }

            }else if(keycode == 13){
                dojob();
            }
        });

        var movePrev = function(){
            $("#kw").blur();
            var index = $(".addbg").prevAll().length;
            if(index == 0){
                $(".item").removeClass('addbg').eq($(".item").length-1).addClass('addbg');
            }else{
                $(".item").removeClass('addbg').eq(index-1).addClass('addbg');
            }
        }

        var moveNext = function(){
            var index = $(".addbg").prevAll().length;
            if(index == $(".item").length-1){
                $(".item").removeClass('addbg').eq(0).addClass('addbg');
            }else{
                $(".item").removeClass('addbg').eq(index+1).addClass('addbg');
            }

        }
        var dojob = function(){
            $("#kw").blur();
            var value = $(".addbg").text();
            $("#kw").val(value);
            $("#append").hide().html("");
        }
    });
    function getContent(obj){
        var kw = jQuery.trim($(obj).val());
        if(kw == ""){
            $("#append").hide().html("");
            return false;
        }
        var html = "";
        html = html + "<div class='item' onmouseenter='getFocus(this)' onClick='getCon(this);'>" + data[0] + "</div>";
        $("#append").show().html(html);
        $.get('/social/service/search-add?keyword='+kw+'&province='+pro_id,function(data2){
            console.log(data2.code);
            console.log(data2);
            if(data2.code ==200){
                console.log(data);
                var html2 = '';
                for(i =0 ;i<data2.data.length;i++){
                    html2 = html2 + "<div class='item' onmouseenter='getFocus(this)' onClick='getCon(this);'>" + data2.data[i].address + "</div>"
                    if(html2 != ""){
                        $("#append").show().html(html2);
                    }else{
                        $("#append").hide().html("");
                    }
                }
            }
        },"json");
    }
    function getFocus(obj){
        $(".item").removeClass("addbg");
        $(obj).addClass("addbg");
    }
    function getCon(obj){
        var value = $(obj).text();
        $("#kw").val(value);
        $("#append").hide().html("");
    }
</script>
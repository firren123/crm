<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>

<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class="active"><a href="/admin/opencity/index" style="color: #286090;">开通城市管理</a></li>
        <li class="active">编辑已开通城市</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
           <form action="/admin/opencity/edit" method="post">
               省份： &nbsp;&nbsp;&nbsp;<select name="open[province]" style="width:300px;" id="province">
                    <?php foreach($province as $k=>$v):?>
                    <option value="<?php echo $v['id'];?>" <?php if($v['id'] == $list[0]['province']){echo "selected";}?>><?php echo $v['name'];?></option>
                    <?php endforeach;?>
               </select>
               <br><br><br>
               城市： &nbsp;&nbsp;&nbsp;<select class="city" name="open[city]" id="city" style="width:300px;"></select><br><br><br>
               状态： &nbsp;&nbsp;&nbsp;<input type="radio" name="open[status]" ckecked="ckecked" value="1">启用
               &nbsp;&nbsp;&nbsp;<input type="radio" name="open[status]" value="2">禁用
               <br><br><br>
               <input type="hidden" id="pro_id" value="<?= $list[0]['province']?>"/>
               <input type="hidden" id="city_id" value="<?= $list[0]['city']?>"/>
               <input type="hidden" name="open[id]" value="<?= $id;?>"/>
               <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
               <a href="<?= '/admin/opencity/index';?>" class="btn btn-primary" style="margin: 0px 20px">返回列表</a>
               <input type="submit" class="btn btn-primary" value="确认修改" />
        </div>
    </div>
</legends>
</form>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>
<script>
$(function(){
    var proid = $("#pro_id").val();
    var cityid = $("#city_id").val();
    if(cityid>0){
    $.ajax({
    type: "GET",
    url: "/admin/opencity/city",
    data: "pid="+proid,
    success: function(data){
    var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
    var len=data2.length;
    $("#city").empty();
    var html_option="";
    for(var i=0;i<len;i++)
    {
    if(cityid == data2[i]['id']){
    html_option+='<option selected value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
    }else{
    html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
    }
    console.log(cityid);
    console.log(data2[i]['id']);
    }
    $("#city").append(html_option);
    }
    });
    }
})
</script>
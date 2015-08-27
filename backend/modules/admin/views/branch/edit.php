<?php
/**
 * 修改-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   BACKEND
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      2015-04-01
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */
$this->title = "分公司信息修改";
?>

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/zcommon.js"></script>

<style type="text/css">
    .zcss_table1 th{font-weight:bold;}
    .zcss_table1 th,.zcss_table1 td{border:1px solid #000000;padding:5px;}

    .zcss_td_left{width:120px;}
    .zcss_td_right{width:400px;}

    .zcss_class0401{margin:30px 0;}
    .zcss_class0401 a{padding:0 20px;}
</style>



<div>
    <form action="/admin/branch/up" method="post">
        分公司名：<input type="text" name="branch[name]" value="<?php echo $info['name']?>"><br/><br/><br/>
        省名：<select name="branch[province_id]" id="branch-province_id">
            <?php foreach($arr as $k=>$v):?>
            <option value="<?php echo $v['id'];?>" <?php if($v['id'] == $info['province_id']){echo "selected";}?>><?php echo $v['name']?></option>
            <?php endforeach;?>
            <br/><br/><br/>
        </select>
        市名：<select name="branch[city_id][]" id="branch-city_id" multiple='multiple'></select>
        <br/><br/><br/>
        状态：<input type="radio" name="branch[status]" checked="checked" value="1" />启用
        <input type="radio" name="branch[status]" value="0" />禁止
        <br/><br/><br/>
        排序：<input type="text" name="branch[sort]" value="<?php echo $info['sort']?>">
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <br/><br/><br/>
        <input type="hidden" name="branch[id]" value="<?php echo $id; ?>">
        <a href="/admin/branch/index">返回列表</a>
        <input type="submit" class="zjs_btn_submit" value="确认修改" />
    </form>

</div>
<input type="hidden" id="pro_id" value="<?= $info['province_id']?>"/>
<input type="hidden" id="city_id" value="<?= $info['city_id_arr']?>"/>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<script type="text/javascript">
    $(function()
    {
        var proid = $("#pro_id").val();
        var cityid = $("#city_id").val();
        if(cityid){
            $.ajax({
                type: "GET",
                url: "/admin/branch/city",
                data: "pid="+proid,
                success: function(data){
                    var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
                    var len=data2.length;
                    $("#branch-city_id").empty();
                    var html_option="";
                    for(var i=0;i<len;i++)
                    {
                        if(cityid.indexOf(data2[i]['id']) != -1){
                            html_option+='<option selected value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                        }else{
                            html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                        }
                        console.log(cityid);
                        console.log(data2[i]['id']);
                    }
                    $("#branch-city_id").append(html_option);
                }
            });
        }

        $("#branch-province_id").change(function(){
            var info = $(this).val();
            $.ajax({
                type: "GET",
                url: "/admin/branch/city",
                data: "pid="+info,
                success: function(data){
                    var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
                    var len=data2.length;
                    $("#branch-city_id").empty();
                    var html_option="";
                    for(var i=0;i<len;i++)
                    {
                        html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                        console.log(data2.i);
                    }
                    $("#branch-city_id").append(html_option);
                }
            });

        });

        $(".zjs_btn_submit").click(function()
        {
            edit();
        });

        $(".zjs_select_province").change(function()
        {
            var province_id=$(this).val();
            var csrf=$(".zjs_csrf").val();
            //console.log(csrf);return;

            $(".zjs_select_city option").not(".zjs_default_v").remove();
            $(".zjs_select_district option").not(".zjs_default_v").remove();
            $(".zjs_select_city .zjs_default_v").html("加载中");
            $(".zjs_select_district .zjs_default_v").html("请先选择城市");
            if(province_id==0){
                return;
            } else {
                $.post
                (
                    "/community/p2c",
                    {
                        "_csrf":csrf,
                        "pid":province_id
                        //"YII_CSRF_TOKEN":csrf
                    },
                    function(str_json)
                    {
                        //console.log(str_json);
                        obj=JSON.parse(str_json);
                        //console.log(obj);

                        var html_option="";
                        var len=obj.length;
                        for(var i=0;i<len;i++)
                        {
                            html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                        }
                        $(".zjs_select_city .zjs_default_v").html("请选择城市");
                        $(".zjs_select_city").append(html_option);
                    }
                );
            }
        });
        $(".zjs_select_city").change(function()
        {
            var city_id=$(this).val();
            var csrf=$(".zjs_csrf").val();
            //console.log(csrf);return;
            $(".zjs_select_district option").not(".zjs_default_v").remove();
            $(".zjs_select_district .zjs_default_v").html("加载中");
            if(city_id==0){
                return;
            } else {
                $.post
                (
                    "/community/c2d",
                    {
                        "_csrf":csrf,
                        "cid":city_id
                        //"YII_CSRF_TOKEN":csrf
                    },
                    function(str_json)
                    {
                        //console.log(str_json);
                        obj=JSON.parse(str_json);
                        //console.log(obj);

                        var html_option="";
                        var len=obj.length;
                        for(var i=0;i<len;i++)
                        {
                            html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                        }
                        $(".zjs_select_district .zjs_default_v").html("全部区/县");
                        $(".zjs_select_district").append(html_option);
                    }
                );
            }
        });
    });

    function edit()
    {
        var id=$(".zjs_id").html();
        var name=$(".zjs_name").val();
        var province=$(".zjs_select_province").val();
        var city=$(".zjs_select_city").val();
        var district=$(".zjs_select_district").val();
        var area=$(".zjs_area").val();
        var address=$(".zjs_address").val();
        var lng=$(".zjs_lng").val();
        var lat=$(".zjs_lat").val();
        var status=$(".zjs_status").val();

        var csrf=$(".zjs_csrf").html();

        if(isNaN(id)===true){z_log("id not num");return;}
        if(isNaN(province)===true){z_log("province not num");return;}
        if(isNaN(city)===true){z_log("city not num");return;}
        if(isNaN(district)===true){z_log("district not num");return;}
        if(isNaN(lng)===true){z_log("lng not num");return;}
        if(isNaN(lat)===true){z_log("lat not num");return;}
        if(isNaN(status)===true){z_log("status not num");return;}

        $.post
        (
            "/community/ajax-post",
            {
                "act":"edit",
                "_csrf":csrf,
                "id":id,
                "name":name,
                "province":province,
                "city":city,
                "district":district,
                "area":area,
                "address":address,
                "lng":lng,
                "lat":lat,
                "status":status
            },
            function(str)
            {
                if(str=='1'){
                    z_tip("修改成功",4000);
                    window.location.href="/community/list";
                }else{
                    alert("修改失败");
                }
            }
        );
    }

</script>

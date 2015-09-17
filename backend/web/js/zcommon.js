//author zy
//z20150410 create

//require jquery
//require json2

//update 20150422 0938
//update 20150917 1411



var z_is_btn_valid = 1;
function check_btn()
{
    if(typeof z_is_btn_valid!=='undefined' && (z_is_btn_valid==1 || z_is_btn_valid==0)){
        return z_is_btn_valid;
    }else{
        return 1;
    }
}
function disable_btn()
{
    z_is_btn_valid=0;
}
function enable_btn()
{
    z_is_btn_valid=1;
}


$(function()
{
    $(".zjs_select_province").change(function()
    {
        var province_id=$(this).val();

        $(".zjs_select_city option").not(".zjs_default_v").remove();
        $(".zjs_select_district option").not(".zjs_default_v").remove();
        $(".zjs_select_city .zjs_default_v").html("加载中");
        $(".zjs_select_district .zjs_default_v").html("全部区/县");
        if(province_id==0){
            $(".zjs_select_city .zjs_default_v").html("全部市");
            $(".zjs_select_district .zjs_default_v").html("全部区/县");
            return;
        }else{
            $.get
            (
                "/shop/shop/p2c?pid="+province_id,
                function(str_json)
                {
                    //console.log(str_json);
                    var obj=JSON.parse(str_json);
                    //console.log(obj);
                    var html_option="";
                    var len=obj.length;
                    for(var i=0;i<len;i++){
                        html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                    }
                    $(".zjs_select_city .zjs_default_v").html("全部市");
                    $(".zjs_select_city").append(html_option);
                }
            );
        }
    });

    $(".zjs_select_province_open").change(function()
    {
        var province_id=$(this).val();

        $(".zjs_select_city option").not(".zjs_default_v").remove();
        $(".zjs_select_district option").not(".zjs_default_v").remove();
        $(".zjs_select_city .zjs_default_v").html("加载中");
        $(".zjs_select_district .zjs_default_v").html("全部区/县");
        if(province_id==0){
            $(".zjs_select_city .zjs_default_v").html("全部市");
            $(".zjs_select_district .zjs_default_v").html("全部区/县");
            return;
        }else{
            $.get
            (
                "/shop/shop/p2c?pid="+province_id+"&open=1",
                function(str_json)
                {
                    //console.log(str_json);
                    var obj=JSON.parse(str_json);
                    //console.log(obj);
                    var html_option="";
                    var len=obj.length;
                    for(var i=0;i<len;i++){
                        html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                    }
                    $(".zjs_select_city .zjs_default_v").html("全部市");
                    $(".zjs_select_city").append(html_option);
                }
            );
        }
    });

    $(".zjs_select_city").change(function()
    {
        var city_id=$(this).val();
        $(".zjs_select_district option").not(".zjs_default_v").remove();
        $(".zjs_select_district .zjs_default_v").html("加载中");
        if(city_id==0){
            $(".zjs_select_district .zjs_default_v").html("全部区/县");
            return;
        }else{
            $.get
            (
                "/shop/shop/c2d?cid="+city_id,
                function(str_json)
                {
                    //console.log(str_json);
                    var obj=JSON.parse(str_json);
                    //console.log(obj);
                    var html_option="";
                    var len=obj.length;
                    for(var i=0;i<len;i++){
                        html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                    }
                    $(".zjs_select_district .zjs_default_v").html("全部区/县");
                    $(".zjs_select_district").append(html_option);
                }
            );
        }
    });


    $(document).on("change",".zjs_select_branch_open_v4",function()
    {
        var value=$(this).val();
        var obj={};
        obj.id=value;
        obj.open=1;
        obj.p_class='zjs_select_province_open_v4';
        obj.c_class='zjs_select_city_v4';
        obj.d_class='zjs_select_district_v4';
        select_branch_open_v4(obj);
    });
    $(document).on("change",".zjs_select_province_open_v4",function()
    {
        var value=$(this).val();
        var obj={};
        obj.id=value;
        obj.open=1;
        obj.c_class='zjs_select_city_v4';
        obj.d_class='zjs_select_district_v4';
        select_province_open_v4(obj);
    });
    $(document).on("change",".zjs_select_city_v4",function()
    {
        var value=$(this).val();
        var obj={};
        obj.id=value;
        obj.d_class='zjs_select_district_v4';
        select_city_v4(obj);
    });



});



function z_check_json(str)
{
    try {
        var obj=JSON.parse(str);
    } catch (e) {
        return 0;
    }
    return 1;
}

function z_check_mobile_num(str)
{
    if (!(/^1[3|4|5|7|8]\d{9}$/.test(str))) {
        return 0;
    }
    return 1;
}

function z_check_password(str)
{
    if(str.length<6){
        return 0;
    }
    if (!(/^[0-9A-Za-z]+$/.test(str))) {
        return 0;
    }
    return 1;
}

function z_check_num(str)
{
    if (isNaN(str) === true) {
        return 0;
    } else {
        return 1;
    }
}

function z_check_email(str)
{
    var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if (!(regex.test(str))) {
        return 0;
    }
    return 1;
}

function z_log(str)
{
    try {
        console.log(str);
    } catch (e) {
        //
    }
}

function z_tip(str,time,z_tip_callback)
{
    time=time||1000;
    z_tip_callback=z_tip_callback||function(){};

    var popup_html='';
    popup_html+='<div class="zjs_zcommon_popup_tip" style="width:100%;height:100%;position:fixed;top:0;bottom:0;z-index:999;display:none;">';
    popup_html+='<div style="width:400px;height:50px;margin:-25px 0 0 -200px;position:absolute;top:50%;left:50%;z-index:999;padding:10px;text-align:center;">';
    popup_html+="<span style=\"font-size:18px;font-family:'Microsoft YaHei',menlo,'Courier New',Monospace;background-color: #f5e79e;padding:20px 50px;border-radius:20px;\">";
    popup_html+=str;
    popup_html+='</span>';
    popup_html+='</div>';
    popup_html+='</div>';

    $("body").append(popup_html);

    $(".zjs_zcommon_popup_tip").show().fadeOut(time,z_tip_callback);

}


function z_check_money(str)
{
    if (!(/^(([1-9]\d*)|0)(\.\d{1,2})?$/.test(str))) {
        return 0;
    }
    return 1;
}




function select_branch_open_v4(obj_param)
{
    var bid=obj_param.id;
    obj_param.p_class='zjs_select_province_open_v4';
    obj_param.c_class='zjs_select_city_v4';
    obj_param.d_class='zjs_select_district_v4';

    $("."+obj_param.p_class).html("<option>加载中</option>");
    $("."+obj_param.c_class).html("<option>加载中</option>");
    $("."+obj_param.d_class).html("<option>加载中</option>");
    if(bid==0){
        $("."+obj_param.p_class).html('<option class="zjs_default_v" value="0">全部省</option>');
        $("."+obj_param.c_class).html('<option class="zjs_default_v" value="0">全部市</option>');
        $("."+obj_param.d_class).html('<option class="zjs_default_v" value="0">全部区/县</option>');
        return;
    }else{
        $.ajax({
            "type":"post",
            "url": "/shop/shop/ajax",
            "dataType":"json",
            "data":{
                "act":"select_branch",
                "bid":bid,
                "open":1
            },
            "success":function(obj_return){
                //z_log("success");
                var obj_p={};
                obj_p.p_class=obj_param.p_class;
                obj_p.arr_p_info=obj_return.arr_p_info;
                rebuild_select_province_v4(obj_p);
                var obj_c={};
                obj_c.c_class=obj_param.c_class;
                obj_c.arr_c_list=obj_return.arr_c_list;
                rebuild_select_city_v4(obj_c);
                if(obj_return.arr_c_list.length==1){
                    var obj_d={};
                    obj_d.d_class=obj_param.d_class;
                    obj_d.arr_d_list=obj_return.arr_d_list;
                    rebuild_select_district_v4(obj_d);
                }else{
                    $("."+obj_param.d_class).html('<option class="zjs_default_v" value="0">全部区/县</option>');
                }
            },
            "error":function(){
                //z_log("error");
                $("."+obj_param.p_class).html("<option>获取失败</option>");
                $("."+obj_param.c_class).html("<option>获取失败</option>");
                $("."+obj_param.d_class).html("<option>获取失败</option>");
            },
            "complete":function(){
                //z_log("complete");
            }
        });
    }
}

function select_province_open_v4(obj_param)
{
    var pid=obj_param.id;
    obj_param.c_class='zjs_select_city_v4';
    obj_param.d_class='zjs_select_district_v4';

    $("."+obj_param.c_class).html("<option>加载中</option>");
    $("."+obj_param.d_class).html("<option>加载中</option>");
    if(pid==0){
        $("."+obj_param.c_class).html('<option class="zjs_default_v" value="0">全部市</option>');
        $("."+obj_param.d_class).html('<option class="zjs_default_v" value="0">全部区/县</option>');
        return;
    }else{
        $.ajax({
            "type":"post",
            "url": "/shop/shop/ajax",
            "dataType":"json",
            "data":{
                "act":"select_province",
                "pid":pid,
                "open":1
            },
            "success":function(obj_return){
                //z_log("success");
                var obj_c={};
                obj_c.c_class=obj_param.c_class;
                obj_c.arr_c_list=obj_return.arr_c_list;
                rebuild_select_city_v4(obj_c);
                if(obj_return.arr_c_list.length==1){
                    var obj_d={};
                    obj_d.d_class=obj_param.d_class;
                    obj_d.arr_d_list=obj_return.arr_d_list;
                    rebuild_select_district_v4(obj_d);
                }else{
                    $("."+obj_param.d_class).html('<option class="zjs_default_v" value="0">全部区/县</option>');
                }
            },
            "error":function(){
                //z_log("error");
                $("."+obj_param.c_class).html("<option>获取失败</option>");
                $("."+obj_param.d_class).html("<option>获取失败</option>");
            },
            "complete":function(){
                //z_log("complete");
            }
        });
    }
}

function select_city_v4(obj_param)
{
    var cid=obj_param.id;
    obj_param.d_class='zjs_select_district_v4';

    $("."+obj_param.d_class).html("<option>加载中</option>");
    if(cid==0){
        $("."+obj_param.d_class).html('<option class="zjs_default_v" value="0">全部区/县</option>');
        return;
    }else{
        $.ajax({
            "type":"post",
            "url": "/shop/shop/ajax",
            "dataType":"json",
            "data":{
                "act":"select_city",
                "cid":cid
            },
            "success":function(obj_return){
                //z_log("success");
                var obj_d={};
                obj_d.d_class=obj_param.d_class;
                obj_d.arr_d_list=obj_return.arr_d_list;
                rebuild_select_district_v4(obj_d);
            },
            "error":function(){
                //z_log("error");
                $("."+obj_param.d_class).html("<option>获取失败</option>");
            },
            "complete":function(){
                //z_log("complete");
            }
        });
    }
}

function rebuild_select_province_v4(obj)
{
    //z_log(obj);
    if(obj.p_class===undefined || obj.arr_p_info===undefined){
        return;
    }
    var html='';
    html+='<option value="'+obj.arr_p_info.id+'">'+obj.arr_p_info.name+'</option>';
    $("."+obj.p_class).html(html);
}

function rebuild_select_city_v4(obj)
{
    if(obj.c_class===undefined || obj.arr_c_list===undefined){
        return;
    }
    var html='';
    var len=obj.arr_c_list.length;
    if(len==0){
        html+='<option class="zjs_default_v" value="0">无数据</option>';
    }
    if(len>1){
        html+='<option class="zjs_default_v" value="0">选择市</option>';
    }
    for(var i=0;i<len;i++){
        html+='<option value="'+obj.arr_c_list[i].id+'">'+obj.arr_c_list[i].name+'</option>';
    }
    $("."+obj.c_class).html(html);
}

function rebuild_select_district_v4(obj)
{
    if(obj.d_class===undefined || obj.arr_d_list===undefined){
        return;
    }
    var html='';
    var len=obj.arr_d_list.length;
    if(len==0){
        html+='<option class="zjs_default_v" value="0">无数据</option>';
    }
    if(len>1){
        html+='<option class="zjs_default_v" value="0">选择区/县</option>';
    }
    for(var i=0;i<len;i++){
        html+='<option value="'+obj.arr_d_list[i].id+'">'+obj.arr_d_list[i].name+'</option>';
    }
    $("."+obj.d_class).html(html);
}

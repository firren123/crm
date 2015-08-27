//z2050426

//require js
//require json2
// //require zcommon.js


$(function()
{
    $(document).on("change",".zjs_hours_start",function()
    {
        rebuilt_hours_end();
    });


});


function check_lng(num)
{
    if(isNaN(num)===true){
        return 0;
    }
    num=parseFloat(num);
    if(num>180 || num<-180){
        return 0;
    }
    return 1;
}

function check_lat(num)
{
    if(isNaN(num)===true){
        return 0;
    }
    num=parseFloat(num);
    if(num>90 || num<-90){
        return 0;
    }
    return 1;
}

function get_salesman_list()
{
    var kw=$(".zjs_input_salesman_kw").val();

    $(".zjs_btn_query_salesman").val("查询中...");
    $.post
    (
        "/shop/shop/ajax",
        {
            "act":"getsalesman",
            "kw":kw
        },
        function(json)
        {
            $(".zjs_btn_query_salesman").val("查询");
            var obj=JSON.parse(json);
            //console.log(obj);
            if(obj.code=='200'){
                rebuild_select_salesman(obj.data);
            }else{
                //
            }
        }
    );

}

function rebuild_select_salesman(obj_data)
{
    if(obj_data.data===undefined || obj_data.data.length==0){
        return;
    }

    var html=''
    var len=obj_data.data.length;
    var obj2=obj_data.data;
    for(var i=0;i<len;i++){
        html+='<option value="'+obj2[i].id+'">'+obj2[i].name+'</option>';
    }
    $(".zjs_select_salesman").html(html);
}

function reset_hours_start()
{
    var cur=parseInt($(".zjs_hours_start").val());
    if(cur<0){
        cur=0;
    }
    var html='';
    for(var i=0;i<=23;i++){
        if(i==cur){
            html+='<option value="'+i+'" selected="selected">'+i+'</option>';
        }else{
            html+='<option value="'+i+'">'+i+'</option>';
        }

    }
    $(".zjs_hours_start").html(html);
}

function reset_hours_end()
{
    var cur=parseInt($(".zjs_hours_end").val());
    if(cur<0){
        cur=0;
    }
    var html='';
    for(var i=0;i<=23;i++){
        if(i==cur){
            html+='<option value="'+i+'" selected="selected">'+i+'</option>';
        }else{
            html+='<option value="'+i+'">'+i+'</option>';
        }

    }
    $(".zjs_hours_end").html(html);
}

function rebuilt_hours_end()
{
    var cur=parseInt($(".zjs_hours_end").val());
    if(cur<0){
        cur=0;
    }

    var start=parseInt($(".zjs_hours_start").val());
    if(start>=0 && start<=23){
        var html='';
        for(var i=start;i<=23;i++){
            if(i==cur){
                html+='<option value="'+i+'" selected="selected">'+i+'</option>';
            }else{
                html+='<option value="'+i+'">'+i+'</option>';
            }
        }
        $(".zjs_hours_end").html(html);
    }
}

function check_hours()
{
    var start=parseInt($(".zjs_hours_start").val());
    var end=parseInt($(".zjs_hours_end").val());
    if(start>end){
        return 0;
    }
    return 1;
}

function check_htnumber(str)
{
    if(str.length<8){
        return 0;
    }
    if (!(/^BJ[0-9]{6}$/.test(str))) {
        return 0;
    }
    return 1;
}

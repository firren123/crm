/**
 * Created by Administrator on 2015/4/1.
 */

$(function(){

//        var info = $("#opencity-province").val();
    $("#opencity-province").change(function(){
        var info = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/opencity/city",
            data: "pid="+info,
            success: function(data){
                var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
                var len=data2.length;
                $("#opencity-city").empty();
                var html_option="";
                for(var i=0;i<len;i++)
                {
                    html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                    console.log(data2.i);
                }
                $("#opencity-city").append(html_option);
            }
        });
    });

    $("#branch").change(function(){
        var info = $(this).val();
        var proid = $("#pro_id").val();
        var cityid = $("#city_id").val();
        $.ajax({
            type: "GET",
            url: "/admin/shop/city",
            data: "bid="+info,
            success: function(data){
                var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
                var len=data2.length;
                $("#city").empty();
                var html_option="";
                for(var i=0;i<len;i++)
                {
                    html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                    console.log(data2.i);
                }
                $("#city").append(html_option);
            }
        });
    });

    $("#plot-province").change(function(){
        var info = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/plot/city",
            data: "pid="+info,
            success: function(data){
                var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
                var len=data2.length;
                $("#plot-city").empty();
                var html_option="";
                for(var i=0;i<len;i++)
                {
                    html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                    console.log(data2.i);
                }
                $("#plot-city").append(html_option);
            }
        });
    });


    $("#province").change(function(){
        var info = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/plot/city",
            data: "pid="+info,
            success: function(data){
                var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
                var len=data2.length;
                $("#city").empty();
                var html_option = '';
                for(var i=0;i<len;i++)
                {
                    html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                    console.log(data2.i);
                }
                $("#city").append(html_option);
            }
        });
    });

    $('#sub').click(function(){
        var branch_name = $.trim($('#branch-name').val());
        var srf = $('[name="_csrf"]').val();
        if(($('#name').val()==branch_name)){
            $('#login-form').submit();

       }else{
            $.ajax({
                url : "/admin/branch/check",
                type : 'POST',
                data : {'_csrf':srf,'branch_name':branch_name},
                dataType : 'JSON',
                success : function($result){
                    if($result.status==0){
                        alert($result.message);
                        window.location.reload();
                    }else{
                        $('#login-form').submit();
                    }
                }
            })
        }
    })

    $("#branch-province_id").change(function(){
        var info = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/branch/city",
            data: "pid="+info,
            success: function(data){
                var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
                var len=data2.length;
                $("#branch-city_id_arr").empty();
                var html_option = '';
                for(var i=0;i<len;i++)
                {
                    html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                    console.log(data2.i);
                }
                $("#branch-city_id_arr").append(html_option);
            }
        });
    });

    function cityOrProvince(info){
        $.ajax({
            type: "GET",
            url: "i500/provinces/city",
            data:"pid="+info,
            dataType:"json",
            success:function(msg){

            }
        });
        return false;
    }


})
function updateStatus(id){
    $.ajax({
        type: "GET",
        url: "/admin/opencity/up",
        data: "id=" + id,
        success: function (data) {
            if(data == 1) {
                alert('成功');
                window.location.reload();
            }else{
                alert('失败');
            }
        }
    });
}

function upStatus(id){
    var token = $("#token").val();
    $.ajax({
        type: "GET",
        url: "/admin/sensitivekeywords/up",
        data: "id=" + id+"&token="+token,
        success: function (data) {
            if(data == 1) {
                alert('成功');
                window.location.reload();
            }else{
                alert('失败');
            }
        }
    });
}
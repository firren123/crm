/**
 * Created by Administrator on 2015/8/13 0013.
 */
var user= {
  submit:function(){
      var mobile = $("#mobile").val();
      var password = $("#password").val();
      var province_id = $("#province_id").val();
      var city_id = $("#city_id").val();
      var district_id = $("#district_id").val();
      if(mobile.length==0)
      {
          gf.alert('请输入手机号');
          return false;
      }
      if(mobile.length!=11)
      {
          gf.alert('请输入有效的手机号码');
          return false;
      }
      var myreg = /^(((1[34578]{1}))+\d{9})$/;
      if(!myreg.test(mobile))
      {
          gf.alert('请输入有效的手机号码');
          return false;
      }
      if(password.length<6)
      {
          gf.alert('密码长度不能少于6位');
          return false;
      }
      if(password.length>12)
      {
          gf.alert('密码长度不能大于12位');
          return false;
      }
      if (province_id==0) {
          gf.alert('请选择省');
          return false;
      }
      if (city_id==0) {
          gf.alert('请选择市');
          return false;
      }
      if (district_id==0) {
          gf.alert('请选择县');
          return false;
      }
      $("#login-form").submit();
  },
    //修改
    update:function(){
        var province_id = $("#province_id").val();
        var city_id = $("#city_id").val();
        var district_id = $("#district_id").val();
        if (province_id==0) {
            gf.alert('请选择省');
            return false;
        }
        if (city_id==0) {
            gf.alert('请选择市');
            return false;
        }
        if (district_id==0) {
            gf.alert('请选择县');
            return false;
        }
        $("#login-form").submit();
    },
    /* 状态修改*/
    updateStatus:function(status,mobile){
        var url= "/social/user/status";
        var content = "<div style='width: 200px;text-align: center'>" +
            "<label><input type='radio' value='1' name='status' checked>更改帖子状态</label>" +
            "<label><input type='radio' value='2' name='status'>不更改帖子状态</label>" +
            "</div>";

        var d = dialog({
            title: "是否更改用户帖子状态",
            content: content,
            ok: function () {
                var type = $('input:radio:checked').val();
                $.ajax(
                    {
                        type: "GET",
                        url: url,
                        data:{'status':status,'type':type,'mobile':mobile},
                        asynic:false,
                        dataType:"json",
                        beforeSend:function(){
                        },
                        success: function(result)
                        {

                            if(result['code']==='200'){
                                gf.alert(result['msg']);
                                history.go(0);
                            }else{
                                gf.alert(result['msg']);
                            }
                        }

                    });
            }
        });
        d.showModal();

    },
    //修改密码
    updatePassword:function(mobile){
        var password = $("#password").val();
        var re_password = $("#re_password").val();
        var _csrf = $("#csrf").val();
        if (password.length<6) {
            gf.alert('密码长度不能少于6位');
            return false;
        }
        if (password.length>12) {
            gf.alert('密码长度不能大于12位');
            return false;
        }
        if (password!=re_password) {
            gf.alert('密码和重复密码不一致');
            return false;
        }
        $.ajax(
            {
                type: "POST",
                url: "/social/user/uppassword",
                data:{'password':password,'re_password':re_password,'mobile':mobile,'_csrf':_csrf},
                asynic:false,
                dataType:"json",
                beforeSend:function(){
                },
                success: function(result)
                {

                    if(result['code']==='200'){
                        gf.alert(result['msg']);
                        window.location="/social/user";
                    }else{
                        gf.alert(result['msg']);
                    }
                }

            });
    },
    //身份证审核
    'cardStatus':function(mobile){
        var status = $("input[name=status]:checked").val();
        $.ajax(
            {
                type: "GET",
                url: "/social/user/update-card-status",
                data:{'status':status,'mobile':mobile},
                asynic:false,
                dataType:"json",
                beforeSend:function(){
                },
                success: function(result)
                {
                    if(result['code']==='200'){
                        gf.alert(result['msg']);
                        window.location="/social/user";
                    }else{
                        gf.alert(result['msg']);
                    }
                }

        });
    },
    //身份证审核
    'examine':function(mobile){
        var real_name = $("#realname").val();
        var user_card = $("#user_card").val();
        if (real_name.length<2) {
            gf.alert("真实姓名 必须大于等于两位数");
            return;
        }
        if (isNaN(user_card)) {
            gf.alert("身份证号 必须是18位数字");
            return;
        } else {
            if (user_card.length!=18) {
                gf.alert("身份证号 必须是18位数字");
                return;
            }
        }
        $.ajax(
            {
                type: "GET",
                url: "/social/user/update-info",
                data:{'real_name':real_name,'user_card':user_card,'mobile':mobile},
                asynic:false,
                dataType:"json",
                beforeSend:function(){
                },
                success: function(result)
                {
                    if(result['code']==='200'){
                        gf.alert(result['msg']);
                        window.location="/social/user";
                    }else{
                        gf.alert(result['msg']);
                    }
                }

            });
    }
};
$(document).ready(function(){
    $(".zjs_select_province").change(function()
    {
        var province_id=$(this).val();
        $(".zjs_select_city option").not(".zjs_default_v").remove();
        $(".zjs_select_district option").not(".zjs_default_v").remove();
        $(".zjs_select_city .zjs_default_v").html("选择省市");
        $(".zjs_select_district .zjs_default_v").html("选择城市");
        if(province_id==0){
            return;
        }
        else{
            $.get
            (
                "/social/user/get-city?pid="+province_id,
                function(str_json)
                {
                    obj=JSON.parse(str_json);

                    var html_option="";
                    var len=obj.length;
                    for(var i=0;i<len;i++)
                    {
                        html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                    }
                    $(".zjs_select_city .zjs_default_v").html("全部城市");
                    $(".zjs_select_city").append(html_option);
                }
            );
        }
    });
    $(".zjs_select_city").change(function()
    {
        var city_id=$(this).val();
        $(".zjs_select_district option").not(".zjs_default_v").remove();
        $(".zjs_select_district .zjs_default_v").html("选择城市");
        if(city_id==0){
            return;
        }
        else{
            $.get
            (
                "/social/user/get-district?cid="+city_id,
                function(str_json)
                {
                    obj=JSON.parse(str_json);
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
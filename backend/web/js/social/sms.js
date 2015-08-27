/**
 * Created by Administrator on 2015/8/18 0018.
 */
var sms={
  sendSms:function(){
      var mobile = $("#mobile").val();
      var content = $("#content").val();
      var _csrf = $("#csrf").val();
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
      var reg = /^(((1[34578]{1}))+\d{9})$/;
      if(!reg.test(mobile))
      {
          gf.alert('请输入有效的手机号码');
          return false;
      }
      if(content.length<10)
      {
          gf.alert('短信内容必须不能少于10个字');
          return false;
      }
      $.ajax(
          {
              type: "POST",
              url: "/social/sms/add",
              data:{'content':content,'mobile':mobile,'csrf':_csrf},
              asynic:false,
              dataType:"json",
              beforeSend:function(){
              },
              success: function(result)
              {

                  if(result['code']==='200'){
                      gf.alert(result['msg']);
                      history.back();
                  }else{
                      gf.alert(result['msg']);
                      history.back();
                  }
              }

          });
  }
};
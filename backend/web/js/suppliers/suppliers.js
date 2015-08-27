
var dataid = new Array();
var dataname = new Array();
var datastand = new Array();
var datapric = new Array();
var datanum = new Array();
var datasum = new Array();
var databarcode = new Array();
var dataremark = new Array();
 var suppliers = {

     goods:function(data,k){
         parent.suppliers.addgood(data,k);
     },
     addgood:function(d,k){
         var html = '';
         html += "<tr>";
         html += "<td class='add_id' style='text-align:center;'>"+d.id+"</td>>";
         html += "<td style='text-align:center;' class='goodname'>"+d.name+"</td>>";
         html += "<td style='text-align:center;' class='stand'>"+d.stand+"</td>>";
         html += "<td style='text-align:center;'>件</td>>";
         html += "<td style='text-align:center;' class='pric'>"+d.pric+"元</td>";
         html += "<td style='text-align:center;' class='num'>"+d.num+"</td>>";
         html += "<td style='text-align:center;' class='pric_all'>"+d.sum+"元</td>>";
         html += "<td style='text-align:center;' class='remark'>"+d.remark+"</td>";
         html += "<td class='bar_code' style='text-align:center;'>"+d.bar_code+"</td>";
         html += "<td style='text-align:center;'><a href='javascript:;' onclick='del(this)'>删除</a></td>>";
         /*html += "<td id='data' style='display: none'>"
         + "<input type='hidden' value="+d.id+">"
         + "<input type='hidden' value="+d.name+">"
         + "<input type='hidden' value="+d.stand+">"
         + "<input type='hidden' value="+d.pric+">"
         + "<input type='hidden' value="+d.num+">"
         + "<input type='hidden' value="+d.sum+">"
         + "<input type='hidden' value="+d.remark+">"
         + "</td>";*/
         html += "</tr>";

         var checkid = $("#info_id").val().split (",");
         var checkname = $("#info_name").val().split (",");
         var checkpric = $("#info_pric").val().split (",");
         var checkattr = $("#info_attr_value").val().split (",");
         var checknum = $("#info_num").val().split (",");
         var checksum = $("#info_sum").val().split (",");
         var checkbarcode = $("#info_bar_code").val().split (",");
         var checkremark = $("#info_remark").val().split (",");

         if($.inArray(d.id,checkid) >= 0){

             var de = dialog({
                 title: '提示',
                 content: '商品已经添加！！！',
                 okValue: '确定',
                 ok: function () {
                 }
             })
             $(k).parent().closest("tr").find(".goodname").text(checkname[$.inArray(d.id,checkid)]);
             $(k).parent().closest("tr").find(".stand").text(checkattr[$.inArray(d.id,checkid)]);
             $(k).parent().closest("tr").find(".pric").val(checkpric[$.inArray(d.id,checkid)]);
             $(k).parent().closest("tr").find(".number").val(checknum[$.inArray(d.id,checkid)]);
             $(k).parent().closest("tr").find(".sum").text(checksum[$.inArray(d.id,checkid)]);
             $(k).parent().closest("tr").find(".bar_code").text(checkbarcode[$.inArray(d.id,checkid)]);
             $(k).parent().closest("tr").find(".remark").val(checkremark[$.inArray(d.id,checkid)]);
             de.show();
             return false;
         }

         dataid.push(d.id);
         dataname.push(d.name);
         datastand.push(d.stand);
         datapric.push(d.pric);
         datanum.push(d.num);
         datasum.push(d.sum);
         databarcode.push(d.bar_code);
         dataremark.push(d.remark);

         //填充数据
         $("#info_id").val(dataid);
         $("#info_name").val(dataname);
         $("#info_attr_value").val(datastand);
         $("#info_pric").val(datapric);
         $("#info_num").val(datanum);
         $("#info_sum").val(datasum);
         $("#info_bar_code").val(databarcode);
         $("#info_remark").val(dataremark);


         $("#goods").append(html);
         $("#div").css("display","block");
     }
 }
 $(function(){
     $("#sub").click(function(){

         var name_one = $.trim($("#name_one").val());
         var name = $.trim($("#name").val());
         var mobile = $.trim($("#mobile").val());
         var phone = $.trim($("#phone").val());
         var code = $.trim($("#code").val());//区号
         var tel = $.trim($("#tel").val());
         var email = $.trim($("#email").val());
         var depots = $.trim($("#depots option:selected").val());
         var dep_name = $.trim($("#dep_name").val());//仓库名称
         var depot = $.trim($("#depot").val());//仓库地址
         var explain = $.trim($("#explain").val());
         var info_id = $.trim($("#info_id").val());

         var d = dialog({title:"提示",
             okValue: '确定',
             ok: function () {}
         });
         if(name_one == ''){
             content = "供货方不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }
         if(name == ''){
             content = "联系人不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }
         var reg =/^1[3|4|5|7|8]\d{9}$/;
         if(!reg.test(mobile))
         {
             content = "您的手机号码不正确，请重新输入";
             d.content(content);
             d.show();
             return false;
         }

         if(phone != '' || code != '') {
             var phone_num = code + '-' + phone;
             var p = /^0\d{2,3}\-\d{7,8}$/;
             if (!p.test(phone_num)) {
                 content = "联系电话格式书写错误！！！";
                 d.content(content);
                 d.showModal();
                 return false;
             }
         }
         var email_reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
         if(!email_reg.test(email)){
             content = "邮箱地址格式不正确！！！";
             d.content(content);
             d.showModal();
             return false;
         }
         if(email == ''){
             content = "邮箱地址不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }
         if(depots == ''){
             content = "选择仓库不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }
         if(dep_name == ''){
             content = "仓库名称不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }
         if(depot == ''){
             content = "仓库地址不能为空！！！";
             d.content(content);
             d.showModal();
         }
         if(explain == ''){
             content = "入库说明不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }
         if(info_id == ''){
             content = "添加商品不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }

         /*if(code == ''){
             content = "电话区号不能为空！！！";
             d.content(content);
             d.showModal();
         }
         if(tel == ''){
             content = "电话不能为空！！！";
             d.content(content);
             d.showModal();
         }*/

         $("#login-form").submit();
         $('#sub').unbind("click");

     })

     $("#add_good").click(function(){

         var d = dialog({
             url:'/storage/suppliers/good-add',
             title: '添加商品',
             width: '70em',
             okValue: '确定',
             lock: true,
             ok: function () {}
         })
         d.showModal();
     })
 })

 function ware(d){
     if($.trim(d) != '') {
         $.getJSON('/storage/suppliers/warehouse', {'sn': d}, function (data) {
             if(data.status == 1){
                 $("#dep_name").val(data.info.name);
                 $("#depot").val(data.info.dizhi);
             }else{
                 var d = dialog({
                     title:"提示",
                     content: data.info,
                     okValue: '确定',
                     ok: function () {}
                 });
                 d.showModal();
                 return false;
             }
         });
     }else{
         $("#dep_name").val("");
         $("#depot").val("");
     }
 }

 function del(obj){

     var id = $.trim($(obj).parent().parent().closest("tr").find(".add_id").text());
     var d = dialog({
         title: '提示',
         content: '确定要删除吗！！！',
         okValue: '确定',
         ok: function () {
             $(obj).parent().parent().remove();

             var checkid = $("#info_id").val().split (",");
             var checkname = $("#info_name").val().split (",");
             var checkattr = $("#info_attr_value").val().split (",");
             var checkpric = $("#info_pric").val().split (",");
             var checknum = $("#info_num").val().split (",");
             var checksum = $("#info_sum").val().split (",");
             var checkbarcode = $("#info_bar_code").val().split (",");
             var checkremark = $("#info_remark").val().split (",");

             dataid.splice($.inArray(id,checkid),1);
             dataname.splice($.inArray(id,checkid),1);
             datastand.splice($.inArray(id,checkid),1);
             datapric.splice($.inArray(id,checkid),1);
             datanum.splice($.inArray(id,checkid),1);
             datasum.splice($.inArray(id,checkid),1);
             databarcode.splice($.inArray(id,checkid),1);
             dataremark.splice($.inArray(id,checkid),1);

             $("#info_id").val(dataid);
             $("#info_name").val(dataname);
             $("#info_attr_value").val(datastand);
             $("#info_pric").val(datapric);
             $("#info_num").val(datanum);
             $("#info_sum").val(datasum);
             $("#info_bar_code").val(databarcode);
             $("#info_remark").val(dataremark);

             if($('#goods').find('tbody').find('tr').length == 2){
                 $('#div').css("display","none");
             }
         },
         cancelValue: '取消',
         cancel: function () {}
     })
     d.showModal();
 }

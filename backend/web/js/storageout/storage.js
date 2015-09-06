
var dataid = new Array();
var dataname = new Array();
var datastand = new Array();
var datapric = new Array();
var datanum = new Array();
var datasum = new Array();
var databarcode = new Array();
var datasn = new Array();
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
         html += "<td style='display:none;text-align:center;' class='bar_code'>"+d.bar_code+"</td>>";
         html += "<td style='display:none;text-align:center;' class='sn'>"+d.storage_sn+"</td>>";
         html += "<td style='text-align:center;' class='remark'>"+d.remark+"</td>";
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
         var checksn = $("#info_storage_sn").val().split (",");
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
             $(k).parent().closest("tr").find(".sn").text(checksn[$.inArray(d.id,checkid)]);
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
         datasn.push(d.storage_sn);
         dataremark.push(d.remark);

         //填充数据
         $("#info_id").val(dataid);
         $("#info_name").val(dataname);
         $("#info_attr_value").val(datastand);
         $("#info_pric").val(datapric);
         $("#info_num").val(datanum);
         $("#info_sum").val(datasum);
         $("#info_bar_code").val(databarcode);
         $("#info_storage_sn").val(datasn);
         $("#info_remark").val(dataremark);


         $("#goods").append(html);
         $("#div").css("display","block");
     }
 }
 $(function(){
     $("#sub").click(function(){

         var depots = $.trim($("#depots option:selected").val());
         var reason = $.trim($("#reason option:selected").val());
         var info_id = $.trim($("#info_id").val());

         var d = dialog({title:"提示",
             okValue: '确定',
             ok: function () {}
         });
         if(reason == ''){
             content = "出库说明不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }

         if(depots == ''){
             content = "请选择仓库！！！";
             d.content(content);
             d.showModal();
             return false;
         }

         if(info_id == ''){
             content = "出库商品不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }

         $("#login-form").submit();
         $('#sub').unbind("click");

     })

     $("#add_good").click(function(){

         var depots = $.trim($('select[name="depots"]').val());
         var d = dialog({title:"提示",
             okValue: '确定',
             ok: function () {}
         });

         if(depots == ''){
             content = "请选择仓库！！！";
             d.content(content);
             d.showModal();
             return false;
         }


         var d = dialog({
             url:'/storage/storage-out/good-add?sn='+depots,
             title: '添加商品',
             width: '70em',
             okValue: '确定',
             lock: true,
             ok: function () {}
         })
         d.showModal();
     })
 })

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
             var checksn = $("#info_storage_sn").val().split (",");
             var checkremark = $("#info_remark").val().split (",");

             dataid.splice($.inArray(id,checkid),1);
             dataname.splice($.inArray(id,checkid),1);
             datastand.splice($.inArray(id,checkid),1);
             datapric.splice($.inArray(id,checkid),1);
             datanum.splice($.inArray(id,checkid),1);
             datasum.splice($.inArray(id,checkid),1);
             databarcode.splice($.inArray(id,checkid),1);
             datasn.splice($.inArray(id,checkid),1);
             dataremark.splice($.inArray(id,checkid),1);

             $("#info_id").val(dataid);
             $("#info_name").val(dataname);
             $("#info_attr_value").val(datastand);
             $("#info_pric").val(datapric);
             $("#info_num").val(datanum);
             $("#info_sum").val(datasum);
             $("#info_bar_code").val(databarcode);
             $("#info_storage_sn").val(datasn);
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

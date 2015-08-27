<?php
$this->title = "业务员信息";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>业务员分组</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/admin/businessinfo/group" method="get">
        <?php if($admin_bc_id == '1'){?>
        <label for="name">分公司：</label>
        <select id="bc_id" name="bc_id">
            <?php if(!empty($branch_arr)){  foreach ($branch_arr as $k=>$item):?>
                <option value="<?=$k?>"><?=$item?></option>
            <?php endforeach;}?>
        </select>
        <?php }?>
        <label for="name">分组名称：</label>
        <input id="g_name" type="text" name="g_name" value="<?=$name?>" class="form-control">
        <!--<a href="javascript:;" id="but" class="btn btn-primary">添加</a>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>-->
        <button id="yw3" class="btn btn-primary" type="submit">搜索</button>
    </form>
</div>
<a id="groupadd" class="btn btn-primary" href="javascript:;" style="margin-bottom:20px;">添加分组</a>
<div class="wide form" id="gadd" style="display: none">
    <form id="search-form" class="well form-inline" action="/admin/businessinfo/groupadd" method="get">
        <?php if($admin_bc_id == '1'){?>
        <label for="name">分公司：</label>
        <select id="bcid" name="bcid">
            <?php if(!empty($branch_arr)){  foreach ($branch_arr as $k=>$item):?>
                <option value="<?=$k?>"><?=$item?></option>
            <?php endforeach;}?>
        </select>
        <?php }?>
        <label for="name">分组名称：</label>
        <input id="name" type="text" name="name" value="" class="form-control">
        <input id="id" type="hidden" value="">
        <input id="stat" type="hidden" value="add">
        <a href="javascript:;" id="but" class="btn btn-primary">添加</a>
        <a class="btn cancelBtn" id="off" href="javascript:;">取消</a>
    </form>
</div>

<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>分公司</th>
                <th>分组名</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <?php if(empty($data)) {
                echo '<tr><td colspan="11" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($data as $item):
                    ?>
                    <tr>
                        <td><?= $item['id'];?></td>
                        <td><?= $item['branch'];?></td>
                        <td><?= $item['name'];?></td>
                        <td><?= $item['status']==1?'正常':'禁用';?></td>
                        <td><a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a> | <a style="cursor:pointer" onclick="edits(<?= $item['id'];?>)" id="edit" href="javascript:;">修改</a> </td>
                    </tr>
                <?php endforeach;
            }
            ?>
        </tbody>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
       $("#groupadd").click(function(){
           $("#id").val("");
           $("#name").val("");
           $("#bcid").val("0");
           $("#stat").val("add");
           $("#but").html("添加");
           $("#gadd").slideToggle();
       });

        $("#off").click(function(){
            $("#id").val("");
            $("#bcid").val("0");
            $("#stat").val("add");
            $("#name").val("");
            $("#gadd").slideToggle();
        });

        $("#but").click(function(){
            var name = $("#name").val();
            var bcid= $("#bcid").val();
            var id   = $("#id").val();
            var stat  = $("#stat").val();
            $.getJSON('/admin/businessinfo/groupadd',{'id':id,'name':name,'bcid':bcid,'stat':stat},function(data){
                var d = dialog({
                    title: '提示',
                    content: data.msg,
                    okValue: '确定',
                    ok: function () {
                        window.location.reload();
                    }
                });
                d.show();
            });
        });
    });

    function edits(id)
    {
        if(id != '' || id != '0') {
            var name = $("#name").val();

            $.getJSON('/admin/businessinfo/groupedit', {'id': id}, function (data) {
                if(data.msg != '')
                {
                    var d = dialog({
                        title: '提示',
                        content: data.msg,
                        okValue: '确定',
                        ok: function () {}
                    });
                    d.showModal();
                    return false;
                }

                $("#name").val(data.data.name);
                $("#bcid").val(data.data.branch_id);
                $("#stat").val("updata");
                $("#id").val(id);
                $("#but").html("修改");
                $("#gadd").slideDown();
            });

        }
    }

    function Delete(id){
        var conter = "您真的确定要删除吗？";
        var d = dialog({
            title: '提示',
            content:conter,
            okValue: '确定',
            lock: true,
            ok: function () {
                $.getJSON('/admin/businessinfo/groupdel',{'id':id},function(data){
                    var d = dialog({
                        title: '提示',
                        content: data.msg,
                        okValue: '确定',
                        ok: function () {
                            window.location.reload();
                        }
                    });
                    d.show();
                });
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.show();
    }


</script>
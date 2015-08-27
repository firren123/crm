<?php
$this->title = "业务员信息";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>业务员信息</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/admin/businessinfo/index" method="get">
        <label for="name">姓名：</label>
        <input id="name" type="text" name="name" value="<?=$name?>" class="form-control">
        <label for="name">手机号：</label>
        <input id="mobile" type="text" name="mobile" value="<?=$mobile?>" class="form-control">
        <label for="name">分公司：</label>
        <select id="bc" name="bc">
            <?php if(!empty($branch_arr)){  foreach ($branch_arr as $k=>$item):?>
                <option value="<?=$k?>"><?=$item?></option>
            <?php endforeach;}?>
        </select>
        <!--<label for="name">状态：</label>
        <select id="status" name="status">
            <option value="0" <?php /*if($status == 0){echo "selected";}*/?>>禁用</option>
            <option value="1" <?php /*if($status == 1){echo "selected";}*/?>>正常</option>
        </select>-->
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<a id="yw0" class="btn btn-primary" href="/admin/businessinfo/add" style="margin-bottom:20px;">添加业务员</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>姓名</th>
                <th>电话</th>
                <th>分公司</th>
                <th>职务</th>
                <th>状态</th>
                <th>当日拜访目标</th>
                <th>当月开店数</th>
                <th>当月销售总额</th>
                <th>当月开店数</th>
                <th>实际销售金额</th>
                <th>操作</th>
            </tr>
            <?php if(empty($list)) {
                echo '<tr><td colspan="12" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td class="ywy_id look"><?= $item['id'];?></td>
                        <td class="look"><?= $item['name'];?></td>
                        <td class="look"><?= $item['mobile'];?></td>
                        <td class="look"><?php if(isset($branch_arr[$item['bc_id']])){ echo $branch_arr[$item['bc_id']];}else{ echo '没有分配分公司';};?></td>
                        <td class="look"><?= $item['duty'];?></td>
                        <td class="look"><?= $item['status']==1?'正常':'禁用';?></td>
                        <td name="day" class="look"><?= $item['day_total'];?></td>
                        <td name="openshop" class="look"><?= $item['openshop_total'];?></td>
                        <td name="sales" class="look"><?= floor($item['sales_total']);?></td>
                        <td name="shop_num" class="look"><?= $item['shop_num'];?></td>
                        <td name="fact_total" class="look"><?= $item['fact_total'];?></td>
                        <td>
                            <a style="cursor:pointer" id="set" onclick="set(<?= $item['id'];?>)">
                                <?php if(empty($item['day_total']) &&
                                empty($item['openshop_total']) && $item['sales_total'] <= 0){
                                    echo "设置目标";
                                }else{
                                    echo "修改目标";
                                }?>
                            </a> |
                            <a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a> |
                            <a style="cursor:pointer" href="/admin/businessinfo/edit?id=<?= $item['id'];?>">修改</a> |
                            <a style="cursor:pointer" href="/admin/businesssubinfo/index?id=<?= $item['id'];?>" class="view">详情</a>
                        </td>
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
       var bc_id = "<?=$bc?>";
        if(bc_id != '')
        {
            $("#bc").val(bc_id);
        }
        $(".look").click(function(){
            var id = $(this).parent().closest("tr").find(".ywy_id").text();
            var d = dialog({
                url:'/admin/businesstrack/index?id='+id,
                title: '业务员轨迹',
                width: '50em',
                okValue: '确定',
                lock: true,
                ok: function () {
                }
            })
            d.showModal();
        })

        /*$(".view").click(function(){
            var id = $(this).parent().closest("tr").find(".ywy_id").text();
            var d = dialog({
                url:'/admin/businesssubinfo/index?id='+id,
                title: '业务员详情',
                width: '60em',
                okValue: '确定',
                lock: true,
                ok: function () {
                }
            })
            d.showModal();
        })*/
    });

    function set(id)
    {
        var html = "";
        html += "<form id='form' method='get'><table class='table table-bordered table-hover'>" +
        "<tr><th><span class='red'>*</span>当日拜访目标</th><td><input type='text' maxlength='4' id='day_total' name='day_total' class='day_total' value=''/></td></tr>" +
        "<tr><th><span class='red'>*</span>当月开店</th><td><input type='text' maxlength='4' id='openshop_total' name='openshop_total' class='openshop_total' value=''/></td></tr>" +
        "<tr><th><span class='red'>*</span>当月销售目标</th><td><input type='text' maxlength='6' id='sales_total' name='sales_total' class='sales_total' value=''/></td></tr>" +
        "</table></form>";


        var del = dialog({
            //url:'/admin/businessinfo/target?id='+id,
            title: '业务员目标',
            width:'40em',
            content:html,
            okValue: '确定',
            lock: true,
            ok: function () {
                var content     = "";
                var day_total   = $("#day_total").val();
                var openshop_total = $("#openshop_total").val();
                var sales_total    = $("#sales_total").val();
                var d = dialog({title:"提示",
                    okValue: '确定',
                    ok: function () {}
                });

                if(day_total == ''){
                    content = "拜访目标不能为空！！！";
                    form.day_total.focus();
                    d.content(content);
                    d.show();
                    return false;
                }

                if(openshop_total == ''){
                    content = "当月开店数不能为空！！！";
                    form.openshop_total.focus();
                    d.content(content);
                    d.show();
                    return false;
                }

                if(openshop_total <= day_total){
                    content = "当月开店数不能小于或等于当日拜访目标！！！";
                    form.openshop_total.focus();
                    d.content(content);
                    d.show();
                    return false;
                }

                if(sales_total <= openshop_total){
                    content = "销售目标不能小于或等于当月开店数！！！";
                    form.sales_total.focus();
                    d.content(content);
                    d.show();
                    return false;
                }

                $.getJSON('/admin/businessinfo/target',{'id':id,'day_total':day_total,'openshop_total':openshop_total,'sales_total':sales_total},
                    function(data){
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

                //$('#form').submit();
            },
            cancelValue: '取消',
            cancel: function () {}
        });

        $(".day_total").blur(function(){
            var dt = $.trim($(".day_total").val());
            if(isNaN(dt) || dt == '' || dt == ' '){
                $(".day_total").val("0");
            }
        })
        $(".openshop_total").blur(function(){
            var ot = $.trim($(".openshop_total").val());
            if(isNaN(ot) || ot == '' || ot == ' '){
                $(".openshop_total").val("0");
            }
        })
        $(".sales_total").blur(function(){
            var st = $.trim($(".sales_total").val());
            if(isNaN(st) || st == '' || st == ' '){
                $(".sales_total").val("0");
            }
            if(st.indexOf('.') != -1){
                $(".sales_total").val("0");
            }
        })
        del.showModal();
        $.getJSON('/admin/businessinfo/groupinfo',{'id':id},function(data){
            $('#day_total').val(data.info.day_total);
            $('#openshop_total').val(data.info.openshop_total);
            $('#sales_total').val(data.info.sales_total);
        });

    }

    function Delete(id){
        var conter = "您真的确定要删除吗？";
        var d = dialog({
            title: '提示',
            content:conter,
            okValue: '确定',
            lock: true,
            ok: function () {
                $.getJSON('/admin/businessinfo/delete',{'id':id},function(data){
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
<?php
$this->title = "业务员信息";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>业务员汇报信息</legend>
</legends>
<!--<div class="wide form">
    <form id="search-form" class="well form-inline" action="/admin/businesssubinfo/index" method="get">
        <label for="name">业务员ID：</label>
        <input id="id" type="text" name="id" value="<?/*=$business_id*/?>" class="form-control">
        <button class="btn btn-primary" type="submit">搜索</button>
    </form>
</div>-->
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>业务员ID</th>
                <!--<th>今日拜访数</th>
                <th>本月目前商家订单数</th>
                <th>本月目前商家订单总额</th>-->
                <th>总结/备注</th>
                <th>图片</th>
            </tr>
            </tbody>
            <?php if (empty($data)){
                echo '<tr><td colspan="8"><div>暂无记录</div></td></tr>';
            } else {
                foreach ($data as $item):
            ?>
            <tr>
                <td class="id"><?=$item['id']?></td>
                <td class="bid"><?=$item['business_id']?></td>
                <!--<td><?/*=$item['visit_total']*/?></td>
                <td><?/*=$item['order_total']*/?></td>
                <td><?/*=$item['money_total']*/?></td>-->
                <td><?=$item['remark']?></td>
                <td><a class="btn btn-primary look" href="JavaScript:;" >查看图片</a></td>
            </tr>
            <?php endforeach; }?>

        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $(".look").click(function(){
            var bid = $(this).parent().closest("tr").find(".bid").html();
            var id  = $(this).parent().closest("tr").find(".id").html();
            $.getJSON('/admin/businesssubinfo/look', {
                'id': id,
                'bid': bid
            }, function (data) {

                var html = '';
                html += "<div style='width: 700px;'>";
                $.each(data.msg,function(){
                    html += "<div style='float: left;margin: 10px 10px 10px 10px'><img width='320' height='200' src='"+this+"'/></div>";
                });
                html  += "</div>";
                //alert(JSON.stringify(data.msg));
                var d = dialog({
                    title: '图片展示',
                    //modal:true,
                    content: html,
                    okValue: '关闭',
                    ok: function () {

                    }
                });
                d.showModal();

            });

        })
    })

</script>

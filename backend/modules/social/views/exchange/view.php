<?php
use yii\widgets\LinkPager;
$this->title = '退换货订单详情';
?>
<ul class="breadcrumb">
    <li>
        <a href="/">首页</a>
    </li>
    <li><a href="/social/exchange/index">退换货列表</a></li>
    <li class="active">详情</li>
</ul>

<div class="tab-content">

    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>订单号</th>
                <th>商家名称</th>
                <th>商品图片</th>
                <th>原因</th>
            </tr>
            </tbody>
            <tfoot>
            <?php
            if (empty($data)) {
                echo '<tr><td colspan="10">暂无记录</td></tr>';
            } else {
                    ?>
                    <tr>
                        <td><?=$data['id']?></td>
                        <td><?=$data['order_sn']?></td>
                        <td><?=$data['shop_name']?></td>
                        <td><div style='width: 140px;'>
                            <?php if (empty($data['image'])) {
                            echo '暂无图片';
                            }else{
                                foreach($data['image'] as $k=>$v):
                             ?>

                                <div class="img_v" id="img_<?=$k?>"  style='float: left;margin: 5px'><img width='60' height='60' src="<?=$img_url.$v?>"/></div>

                            <?php endforeach; }?>
                            </div>
                        </td>
                        <td><?=$data['remark']?></td>
                    </tr>
            <?php
            }
            ?>
            </tfoot>
        </table>
    </div>
</div>
<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<script type="text/javascript">
$(function(){

    $(".img_v").click(function(){
        var str = $(this).attr("id");
        var img_url = $("#"+str+" img").attr("src");
        var html = "";
        html += "<div><img src="+img_url+"></div>";
        var d = dialog({title:"提示",
            okValue: '确定',
            ok: function () {}
        });
        d.content(html);
        d.show();
    });
})
</script>

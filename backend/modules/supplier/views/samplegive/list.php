<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = "样品领取列表";

?>

<div class="wide form">
    <form id="search-form" class="well form-inline" action="/shop/shoporder/index" method="get">


    </form>
</div>
<div class="tab-content">

    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tr>
                <th>样品名称</th>
                <th>领取用户</th>
                <th>领取状态</th>
                <th>申请时间</th>
                <th>领取时间</th>
            </tr>
            <?php if(empty($data)) {
                echo '<tr><td colspan="5" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($data as $item):
                    ?>
                    <tr>
                        <td><?=$item['sample_name']?></td>
                        <td><?=$item['phone']?></td>
                        <td><?= ($item['is_get']==0)?'未领取':'已领取'?></td>
                        <td><?=$item['create_time']?></td>
                        <td><?=$item['get_time']?></td>
                    </tr>
                <?php endforeach;
            }
            ?>
        </table>
    </div>
    <!--<div class="pages">
        <?/*= LinkPager::widget(['pagination' => $pages]) */?>
    </div>-->
</div>

<script type="text/javascript">
    function ex(id, status)
    {
        $.ajax({
            type: "GET",
            url: "/supplier/samplegive/examine",
            data: {"id":id, "status":status},
            dataType:"json",
            success: function(msg){
                var d = dialog({title:"提示",
                    okValue: '确定',
                    ok: function () {}
                });
                if(msg.code==200){
                    content = "操作成功！！！";
                    d.content(content);
                    d.show();
                    $("#td_"+id).text('已审核');
                    $("#span_"+id).remove();
                }else{
                    content = "操作失败！！！";
                    d.content(content);
                    d.show();
                }
            }
        });
    }
</script>
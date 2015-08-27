<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = "供应商样品管理";

?>



<div class="wide form">
    <form id="search-form" class="well form-inline" action="/shop/shoporder/index" method="get">


    </form>
</div>
<div class="tab-content">

    <div class="row-fluid">
        <table class="table table-bordered table-hover">

            <tr>
                <!--                <th><input type="checkbox" id="all"/>全选</th>-->
                <th>样品名称</th>
                <th>审核状态</th>
                <th>操作</th>

            </tr>
            <?php if (!empty($list) || $list != null) { ?>
                <!--                <form action="/shop/shoporder/alledit" method="post">-->
                <?php foreach ($list as $k => $v) { ?>
                    <tr >
                        <td><?= $v['title']; ?></td>
                        <td id="td_<?= html::encode($v['id']); ?>">
                            <?php if ($v['status'] == 1) { ?>
                                待审核
                            <?php } elseif ($v['status'] == 2) { ?>
                                审核通过
                            <?php } elseif ($v['status'] == 3) { ?>
                                审核驳回
                            <?php } ?>
                        </td>
                        <td>
                            <a href="/supplier/sample/detail?id=<?= html::encode($v['id']); ?>">查看详情</a>
                            <?php if($v['status'] == 1){ ?><span id="span_<?= html::encode($v['id']); ?>"><a href="javascript:void(0)" onclick="ex(<?= html::encode($v['id']); ?>, 2)">审核通过</a></span><?php } ?>
                            <?php if($v['status'] == 1){ ?><span id="span_<?= html::encode($v['id']); ?>"><a href="javascript:void(0)" onclick="ex(<?= html::encode($v['id']); ?>, 3)">审核驳回</a></span><?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>
    </div>
    <div class="pages">
        <?= LinkPager::widget(['pagination' => $pages]) ?>
    </div>
</div>

<script type="text/javascript">
    function ex(id, status)
    {
        $.ajax({
            type: "GET",
            url: "/supplier/sample/examine",
            data: {"id":id, "status":status},
            dataType:"json",
            success: function(msg){
                if(msg.code==200){
                    alert('操作成功');
                    $("#td_"+id).text('已审核');
                    $("#span_"+id).remove();
                }else{
                    alert('确认失败');
                }
            }
        });
    }
</script>
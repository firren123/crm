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
                <th>样品名称</th>
                <th>审核状态</th>
                <th>操作</th>

            </tr>
            <?php if (empty($list)) { ?>
                <tr><td colspan="3">无记录</td></tr>
                <?php }else{ foreach ($list as $k => $v) { ?>
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
                            <!--<a href="/supplier/sample/detail?id=<?/*= html::encode($v['id']); */?>">查看详情</a>-->
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
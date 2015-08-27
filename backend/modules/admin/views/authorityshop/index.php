<?php
$this->title = "商家黑名单列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>商家黑名单列表</legend>
</legends>

<a id="yw0" class="btn btn-primary" href="/admin/authorityshop/add" style="margin-bottom:20px;">商家黑名单添加</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>商家id</th>
                <th>商家名称</th>
                <th>添加时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <?php if(empty($list)) {
                echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td><?= $item['id'];?></td>
                        <td><?= $item['shop_id'];?></td>
                        <td><?= $item['shop_name'];?></td>
                        <td><?= $item['create_time'];?></td>
                        <td><?= $item['status']==1?'禁用':'正常'?></td>
                        <td><a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a>
                            <br/>
                            <a style="cursor:pointer" href="/admin/authorityshop/edit?id=<?= $item['id'];?>">修改</a> </td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<script>
    function Delete(id){
        var msg = "您真的确定要删除吗？";
        if (confirm(msg)==true){
            $.ajax(
                {
                    type: "GET",
                    url: '/admin/authorityshop/delete',
                    data: {'id':id},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==1){
                            window.location.reload()
                        }
                    }
                }
            );

        }else{
            return false;
        }

    }
</script>
<?php
$this->title = "经营种类列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>经营种类管理</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/goods/managetype" method="get">
        <label for="name">种类名称：</label>
        <input id="name" type="text" name="name" value="<?= $name;?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<a id="yw0" class="btn btn-primary" href="/goods/managetype/add" style="margin-bottom:20px;">添加经营种类</a>
<h5 style="float:right;">共计&nbsp;<span style="color: red;"><?php echo $total;?></span>&nbsp;条记录</h5>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>种类名称</th>
                <th>排序</th>
                <th>状态</th>
                <th>店铺数量</th>
                <th>操作</th>
            </tr>
            <?php if(empty($data)) {
                echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($data as $item):
                    ?>
                    <tr>
                        <td><?= $item['id'];?></td>
                        <td><?= $item['name'];?></td>
                        <td><?= $item['sort'];?></td>
                        <td><?= $item['status']==1 ? '不显示' : '显示';?></td>
                        <td id="n_<?php echo $item['id'];?>"><?php if(isset($item['num'])){echo $item['num'];} ?></td>
                        <td><a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a> | <a style="cursor:pointer" href="/goods/managetype/edit?id=<?= $item['id'];?>">修改</a> </td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
        </table>
        <div style="float:left;">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<script>
    function Delete(id){
        var msg = "您真的确定要删除吗？";
        var n = document.getElementById('n_'+id).innerHTML;
        if(parseInt(n)==0){
            if (confirm(msg)==true){
                $.ajax(
                    {
                        type: "GET",
                        url: '/goods/managetype/delete',
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
        }else{
            alert('店铺数量不为0,不能删除');
        }


    }

    clickCheckbox();

</script>
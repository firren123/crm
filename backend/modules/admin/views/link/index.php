<?php
$this->title = "友情链接列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>友情链接管理</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/admin/link" method="get">
        <label for="name">名称：</label>
        <input id="name" type="text" name="title" value="<?= $title;?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<a id="yw0" class="btn btn-primary" href="/admin/link/add" style="margin-bottom:20px;">添加友情链接</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />全选
                </th>
                <th>ID</th>
                <th>站点名称</th>
                <th>站点地址</th>
                <th>logo图片</th>
                <th>排序</th>
                <th>有效性</th>
                <th>操作</th>
            </tr>
            <?php if(empty($list)) {
                echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="ids[]" value="<?php echo $item['id'] ?>" id="brandid" class="check"/>
                        </td>
                        <td><?= $item['id'];?></td>
                        <td><?= $item['title'];?></td>
                        <td><?= $item['url'];?></td>
                        <td><img src="<?= \Yii::$app->params['imgHost'] .$item['images'];?>" style="max-width: 150px;max-height: 150px;"></td>
                        <td><?= $item['sort'];?></td>
                        <td><?= $item['status']==0 ? '不显示' : '显示';?></td>
                        <td><a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a> | <a style="cursor:pointer" href="/admin/link/edit?id=<?= $item['id'];?>">修改</a> </td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="8">
                    <div id="egw0" class="pull-right" style="position:relative">
                        <input type="hidden" id="token" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
                        <button id="delete_all" class="bulk-actions-btn btn btn-danger btn-small active" type="button" name="yt1" onclick="checkSelectd()">删除所选</button>
                        <div class="bulk-actions-blocker" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; display: none;"></div>
                    </div>
                </td>
            </tr>
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
                    url: '/admin/link/delete',
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

    clickCheckbox();
    /**
     * 全选
     * @return ''
     */
    function clickCheckbox(){
        $(".chooseAll").click(function(){
            var status=$(this).prop('checked');
            $(".check").prop("checked",status);
            //$(".chooseAll").prop("checked",status);
        });
    }
    /**
     * 判断是否选中
     * @returns {boolean}
     */
    function checkSelectd(){
            var falg = 0;
            $("input[name='ids[]']:checkbox").each(function () {
                if ($(this).prop("checked")==true) {
                    falg += 1;
                }
            })
            if (falg > 0){
                if(confirm('确定要删除勾选的吗')) {
                    var token = $('#token').val();
                    var ids = $("input[name='ids[]']:checkbox").valueOf();
                    var ids = $('input[id="brandid"]:checked').map(function () {
                        return this.value
                    }).get().join();
                    $.ajax(
                        {
                            type: "POST",
                            url: "/admin/link/ajax-delete",
                            data: {'ids': ids, '_csrf': token},
                            asynic: false,
                            dataType: "json",
                            beforeSend: function () {
                            },
                            success: function (result) {
                                if (result == 1) {
                                    window.location.reload()
                                }
                            }
                        });
                }
            }else{
                alert('请选择要删除项');
                return false;
            }

    }
</script>
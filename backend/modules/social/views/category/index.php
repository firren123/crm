<?php
use yii\widgets\LinkPager;
$this->title = '服务类型管理';
?>
<legends  style="fond-size:12px;">
    <legend>服务类型管理</legend>
</legends>
<?php
echo $this->render('_search', ['search'=>$search]);
?>
<div class="tab-content">
    <div class="row-fluid">
        [<a href="/social/category/add?p_id=0">添加类型</a>]
        <table class="table table-bordered table-hover">
            <tbody>
            <?php if (empty($list)) {
            } else {
                foreach ($list as $key => $item):
                    ?>
                    <tr id="tr_<?= $item['id']; ?>">
                        <td style="text-align: left;">
                            <?= $item['name']; ?>(<?= $item['description']; ?>)　
                            [<a href="/social/category/add?p_id=<?= $item['id']; ?> ">添加子类型</a>]　
                            [<a href="/social/category/edit?id=<?= $item['id']; ?> ">编辑</a>]
                            [<a href="/social/category/view?id=<?= $item['id']; ?>">查看</a>]
                            <?php if (empty($list[$key][$item['id']])) :
                                ?>
                                [<a onclick="Delete(<?= $item['id']; ?>)" href="javascript:void(0)">删除</a>]
                            <?php endif?>
                        </td>
                    </tr>
                    <?php if (empty($list[$key][$item['id']])) {
                } else {
                    foreach ($list[$key][$item['id']] as $key1 => $item1):
                        ?>
                        <tr id="tr_<?= $item1['id']; ?>">
                            <td style="text-align: left;">　　
                                |----<?= $item1['name']; ?>(<?= $item1['description']; ?>)　
<!--                                [<a href="/social/category/add?p_id=--><?//= $item1['id']; ?><!-- ">添加子类型</a>]-->
                                [<a href="/social/category/edit?id=<?= $item1['id']; ?> ">编辑</a>]
                                [<a href="/social/category/view?id=<?= $item1['id']; ?>">查看</a>]
                        <?php if (empty($list[$key][$item['id']][$key1][$item1['id']])) :
                        ?>
                            [<a onclick="Delete(<?= $item1['id']; ?>)" href="javascript:void(0)">删除</a>]
                        <?php endif?>
                            </td>
                        </tr>
                        <?php if (empty($list[$key][$item['id']][$key1][$item1['id']])) {
                    } else {
                        foreach ($list[$key][$item['id']][$key1][$item1['id']] as $item2):
                            ?>
                            <tr id="tr_<?= $item2['id']; ?>">
                                <td style="text-align: left;margin-left: 5px">　　
                                    　　|-----<?= $item2['name']; ?>(<?= $item2['description']; ?>)　
                                    [<a href="/social/category/edit?id=<?= $item2['id']; ?> ">编辑</a>]
                                    [<a href="/social/category/view?id=<?= $item2['id']; ?>">查看</a>]
                                    [<a onclick="Delete(<?= $item2['id']; ?>)" href="javascript:void(0)">删除</a>]
                                </td>
                            </tr>

                        <?php endforeach;
                    }
                    endforeach;
                }
                endforeach;
            }
            ?>
            </tbody>

        </table>
    </div>
</div>
<script>
    function Delete(id){
        var msg = "您真的确定要删除吗？";
        if (confirm(msg)==true){
            $.ajax(
                {
                    type: "GET",
                    url: '/social/category/delete',
                    data: {'id':id},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result['code']==200){
                            window.location.reload()
                        } else {
                            gf.alert(result['msg']);
                        }
                    }
                }
            );

        }else{
            return false;
        }

    }
</script>
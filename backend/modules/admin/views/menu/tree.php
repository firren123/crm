<?php
$this->title = "权限列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>导航列表</legend>
</legends>

<div class="tab-content">
    <div class="row-fluid">
        [<a href="/admin/menu/add">添加模块</a>]
        <table class="table table-bordered table-hover">
            <tbody>

           <?php  //echo "<pre>" ;  print_r($list);?>


            <?php if(empty($list)) {
                echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list as $key =>$item):
                    ?>
                    <tr>

                        <td style="text-align: left;"><?= $item['title'];?>(<?= $item['name'];?>)　[<a href="/admin/menu/add?p_id=<?= $item['id'];?>   ">添加控制器</a>]　[<a href="/admin/menu/edit?id=<?= $item['id'];?> ">编辑</a>]</td>
                    </tr>

                    <?php if(empty($list[$key][$item['id']])) {
                echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list[$key][$item['id']] as $key1 => $item1):
                    ?>
                    <tr>
                        <td style="text-align: left;" >　　|----<?= $item1['title'];?>(<?= $item1['name'];?>)　[<a href="/admin/menu/add?p_id=<?= $item1['id'];?>&act=1 ">添加方法</a>]　[<a href="/admin/menu/edit?id=<?= $item1['id'];?> ">编辑</a>]</td>
                    </tr>

                    <?php if(empty($list[$key][$item['id']][$key1][$item1['id']])) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach ($list[$key][$item['id']][$key1][$item1['id']] as $item2):
                        ?>
                        <tr id="tr_<?= $item2['id'];?>">
                            <td style="text-align: left;">　　　　|------------<?= $item2['title'];?>(<?= $item2['name'];?>)　导航级别：<?= $item2['level'];?>　<?php if($item2['display']){  echo '导航显示'; }else { echo '导航不显示'; } ?>　排序：<input type="text" name="sort" id="sort_<?= $item2['id'];?>" onblur="editSort(<?= $item2['id'];?>)" value="<?= $item2['sort'];?>" style="width: 60px;">　[<a href="/admin/menu/edit?id=<?= $item2['id'];?>&act=1 ">编辑</a>]　[<a href="javascript:;void(0)" onclick="Delete(<?= $item2['id'];?>)">删除</a>]</td>
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
                    url: '/admin/menu/delete',
                    data: {'id':id},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==1){
                            $("#tr_"+id).remove();
                        }
                    }
                }
            );

        }else{
            return false;
        }

    }

    function editSort(id) {
        $.ajax(
            {
                type: "GET",
                url: '/admin/menu/edit-sort',
                data: {'id':id, 'sort':$("#sort_"+id).val()},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {

                }
            }
        );
    }
</script>
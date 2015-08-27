<?php
$this->title = "网站配置";
use yii\widgets\LinkPager;
?>
<legends style="fond-size:12px;">
    <?php if(2 == $type):?><legend>用户配置管理</legend><?php endif;?>
    <?php if(1 == $type):?><legend>商家配置管理</legend><?php endif;?>
</legends>
<?php if(1 == $type):?>
<form method="get" action="/admin/link/indexweb" class="well form-inline" id="search-form">
    <label for="name">城市：</label>
    <input type="text" class="form-control" value="" name="title" id="name" >
    <button type="submit" name="yt0" class="btn btn-primary" id="yw3">搜索</button>
</form>
<?php endif;?>
<a style="margin-bottom:20px;" href="javascript:;" class="btn btn-primary" id="delall">删除所选记录</a>
<?php if(1 == $type):?><a style="margin-bottom:20px;" href="/admin/link/web?type=<?=$type ?>" class="btn btn-primary">添加商家网站配置</a><?php endif;?>
<?php if(2 == $type):?><a style="margin-bottom:20px;" href="/admin/link/web?type=<?=$type ?>" class="btn btn-primary">添加用户网站配置</a><?php endif;?>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" class="findtitall" name="check" id="check_all">全选
                </th>
                <th>ID</th>
                <th>免运费价格</th>
                <th>起送价</th>
                <th>运费</th>
                <?php if($type == 1): ?> <th>最大值</th>
                    <th>所在城市</th><?php endif;?>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($info)){
                echo '<tr><td colspan="5" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach($info as $data){
                    ?>
                    <tr>
                        <td> <input type="checkbox" class="findtitall chooseAll" name="check"  conf="<?=$data['id'] ?>"></td>
                        <td><?= $data['id']?></td>
                        <td><?= $data['free_shipping']?></td>
                        <td><?= $data['send_price']?></td>
                        <td><?= $data['freight']?></td>
                        <?php if($type == 1):?>
                            <td><?= $data['community_num']?></td>
                            <td><?=$city_list[$data['bc_id']] ?></td>
                        <?php endif;?>
                        <td><a href="/admin/link/web?id=<?=$data['id'] ?>&type=<?=$type ?>">编辑</a> | <a href="javascript:;" class="delete" conf="<?=$data['id'] ?>">删除</a></td>
                    </tr>
                <?php  } }?>
            </tfoot>
        </table>
    </div>
</div>
<div class="pagination pull-right">
    <?php
    $link_pager = new LinkPager(['pagination' => $pages]);
    $link_pager->nextPageLabel = '下一页';
    $link_pager->prevPageLabel = '上一页';
    $link_pager->run();
    ?>
</div>
<script type="text/javascript">
    $(function(){

        $('#delall').click(function(){
            if($('.chooseAll:checkbox:checked').length == 0){alert('您还没选中呢');return false;}
            var arr=[];
            $('.chooseAll:checkbox:checked').each(function(){
                arr.push($(this).attr('conf'));
            })
            var  char = arr.join(',');
            if(confirm('确定要删除选中部分吗')){
                $.ajax({
                    url : "/admin/link/delweb",
                    type : 'post',
                    data  : {'id': char},
                    dataType : 'json',
                    success : function(result){
                        if(1 == result.status){
                            window.location.reload();
                        }else{
                            alert('删除失败');
                        }
                    }
                })
            }else{
                return false;
            }
        })
        $('.delete').click(function(){
            var id = $(this).attr('conf');
            if(confirm('确定要删除吗')){
                $.ajax({
                    url : "/admin/link/delweb",
                    type : 'post',
                    data  : {'id': id, 'csrf': $('#csrf').val()},
                    dataType : 'json',
                    success : function(result){
                        if(1 == result.status){
                            window.location.reload();
                        }else{
                            alert('删除失败');
                        }
                    }
                })
            }else{
                return false;
            }
        })
        $('#check_all').click(function(){
            var conf=$(this).prop('checked');
            $('.chooseAll').prop("checked",conf);
        })
    })
</script>
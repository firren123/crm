<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  comment_list.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 下午2:40
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$this->title = "帖子评论管理";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>帖子评论管理</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/post/comments-list" method="get">
        <label for="name">评论：</label>
        <input id="name" type="text" name="mobile" value="<?= $mobile;?>" class="form-control">
        <input type="hidden" name="post_id" value="<?= $post_id;?>"/>
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />
                    <label for="check">全选</label>
                </th>
                <th>ID</th>
                <th>手机号</th>
                <th>帖子ID</th>
                <th>评论内容</th>
                <th>点赞数</th>
                <th>是否禁用</th>
                <th>是否删除</th>
                <th>创建时间</th>
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
                        <td><?= $item['mobile'];?></td>
                        <td><?= $item['post_id'];?></td>
                        <td><?= $item['content'];?></td>
                        <td><?= $item['thumbs'];?></td>
                        <td><?= $item['status']==1?'禁用':'可用';?></td>
                        <td><?= $item['is_deleted']==1?'已删除':'正常';?></td>
                        <td><?= $item['create_time'];?></td>
                        <td><a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a>
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
<script type="text/javascript">
    function Delete(id){
        var msg = "您真的确定要删除吗？";
        if (confirm(msg)==true){
            $.ajax(
                {
                    type: "GET",
                    url: '/social/post/comments-del',
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
                        url: "/social/post/comments-del",
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
<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  index.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 下午12:00
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$this->title = "帖子管理";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>帖子管理</legend>
</legends>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/post/index" method="get">
        <label for="name">用户名：</label>
        <input id="name" type="text" name="mobile" value="<?= $mobile;?>" class="form-control">
        <label for="keyword">帖子关键字：</label>
        <input id="keyword" type="text" name="keyword" value="<?= $keyword;?>" class="form-control">
        <label for="forum_id">板块：</label>
        <select name="f_id" id="forum_id" style="width: 300px;">
            <option value="0">请选择</option>
            <?php foreach($forum_list1 as $k =>$v){
                if($k == $forum_id){
                    echo "<option value='".$k."' selected>".$v."</option>";
                }else{
                    echo "<option value='".$k."'>".$v."</option>";
                }

            }
            ?>
        </select>
        <br/><br/>
        <label for="start_time">开始时间：</label>
        <input id="start_time" type="text" id="start_time" name="start_time" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php if(isset($activity['start_time'])){echo $activity['start_time']; };?>">
        <label for="end_time">结束时间：</label>
        <input id="end_time" type="text" name="end_time" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php if(isset($activity['end_time'])){echo $activity['end_time']; };?>">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<a id="yw0" class="btn btn-primary" href="/social/post/add" style="margin-bottom:20px;">发布帖子</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />全选
                </th>
                <th>ID</th>
                <th>手机号</th>
                <th>版块ID</th>
                <th>帖子标题</th>
                <th>点赞数</th>
                <th>浏览量</th>
                <th>评论数</th>
                <th>是否置顶</th>
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
                        <td><?php if(isset($forum_list[$item['forum_id']])){ echo $forum_list[$item['forum_id']]; } ;?></td>
                        <td><?= $item['title'];?></td>
                        <td><?= $item['thumbs'];?></td>
                        <td><?= $item['views'];?></td>
                        <td><?= $item['comment_num']; ?></td>
                        <td><?= $item['top']==1?'是':'否';?></td>
                        <td><?= $item['status']==1?'禁用':'可用';?></td>
                        <td><?= $item['is_deleted']==1?'已删除':'正常';?></td>
                        <td><?= $item['create_time'];?></td>
                        <td>
                            <a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a>|
                            <a style="cursor:pointer" href="/social/post/edit?id=<?= $item['id'];?>">修改</a>|
                        <a style="cursor:pointer" href="/social/post/comments-list?post_id=<?= $item['id'];?>">查看评论</a> |
                            <a style="cursor:pointer" href="/social/post/view?id=<?= $item['id'];?>">查看详情</a>
                        </td>

                    </tr>
                <?php endforeach;
            }
            ?>

            </tbody>
            <tfoot>
            <tr><td colspan="4" style="text-align:left;">
                    <span><a style="cursor:pointer" onclick="checkSelectd()">批量删除</a></span>
                <td colspan="24" style="text-align:left;">
                    <select name="forum_id3" id="forum_id3" style="width: 300px;">
                        <option value="0">请选择</option>
                        <?php foreach($forum_list1 as $k => $v) {
                                echo "<option value='".$k."'>".$v."</option>";
                        }
                        ?>
                    </select>
                    <span><a style="cursor:pointer" class="remove">批量转移</a></span>
                </td></tr>
            </tfoot>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<script type="text/javascript" src="/js/social/post.js?_s=<?= Yii::$app->params['jsVersion']; ?>"></script>
<script type="text/javascript">
    $(function(){
       $('.remove').click(function(){
           post.remove();
       })
    });



    function Delete(id){
        var msg = "您真的确定要删除吗？";
        if (confirm(msg)==true){
            $.ajax(
                {
                    type: "GET",
                    url: '/social/post/del',
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
                //var ids = $("input[name='ids[]']:checkbox").valueOf();
                var ids = $('input[id="brandid"]:checked').map(function () {
                    return this.value
                }).get().join();
                $.ajax(
                    {
                        type: "GET",
                        url: "/social/post/all-del",
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
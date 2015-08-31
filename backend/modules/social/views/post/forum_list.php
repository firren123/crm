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
$this->title = "帖子板块管理";
?>
<legends style="fond-size:12px;">
    <legend>帖子板块管理</legend>
</legends>
<div class="tab-content">
    <div class="row-fluid">
        [<a href="/social/post/forum-add?p_id=0">添加模块</a>]
        <table class="table table-bordered table-hover">
            <tbody>
            <?php if (empty($list)) {
            } else {
                foreach ($list as $key => $item):
                    ?>
                    <tr id="tr_<?= $item['id']; ?>">
                        <td style="text-align: left;">
                            <?= $item['title']; ?>(<?= $item['describe']; ?>)　
                            [<a href="/social/post/forum-add?p_id=<?= $item['id']; ?> ">添加子模板</a>]　
                            [<a href="/social/post/forum-edit?id=<?= $item['id']; ?> ">编辑</a>]
                            [<a href="/social/post/forum-view?f_id=<?= $item['id']; ?>">查看板块</a>]
                            [<a href="#" onclick="Delete(<?= $item['id'] ?>)">删除帖子</a>]
                            [<a href="/social/post/index?f_id=<?= $item['id']; ?>">查看帖子</a>]
                        </td>
                    </tr>

                    <?php if (empty($list[$key][$item['id']])) {
                } else {
                    foreach ($list[$key][$item['id']] as $key1 => $item1):
                        ?>
                        <tr id="tr_<?= $item1['id']; ?>">
                            <td style="text-align: left;">　　
                                |----<?= $item1['title']; ?>(<?= $item1['describe']; ?>)　
                                [<a href="/social/post/forum-add?p_id=<?= $item1['id']; ?>&act=1 ">添加子模板</a>]　
                                [<a href="/social/post/forum-edit?id=<?= $item1['id']; ?> ">编辑</a>]
                                [<a href="/social/post/forum-view?f_id=<?= $item1['id']; ?>">查看板块</a>]
                                [<a href="#" onclick="Delete(<?= $item1['id'] ?>)">删除帖子</a>]
                                [<a href="/social/post/index?f_id=<?= $item['id']; ?>">查看帖子</a>]
                            </td>
                        </tr>
                        <?php if (empty($list[$key][$item['id']][$key1][$item1['id']])) {
                    } else {
                        foreach ($list[$key][$item['id']][$key1][$item1['id']] as $item2):
                            ?>
                            <tr id="tr_<?= $item2['id']; ?>">
                                <td style="text-align: left;">　　
                                    |----|----<?= $item2['title']; ?>(<?= $item2['describe']; ?>)　
                                    [<a href="/social/post/forum-add?p_id=<?= $item2['id']; ?>&act=1 ">添加子模板</a>]　
                                    [<a href="/social/post/forum-edit?id=<?= $item2['id']; ?> ">编辑</a>]
                                    [<a href="/social/post/forum-view?f_id=<?= $item2['id']; ?>">查看板块</a>]
                                    [<a href="#" onclick="Delete(<?= $item2['id'] ?>)">删除帖子</a>]
                                    [<a href="/social/post/index?f_id=<?= $item['id']; ?>">查看帖子</a>]
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
    function Delete(id) {

        var msg = "您真的确定要删除吗？";
        if (confirm(msg) == true) {
            $.ajax(
                {
                    type: "GET",
                    url: '/social/post/forum-del',
                    data: {'id': id},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if (result == 1) {
                            $("#tr_" + id).remove();
                        } else if (result == 2) {
                            alert('请先删除子模块');
                        } else {
                            alert('网络失败');
                        }
                        return false;
                    }
                }
            );

        } else {
            return false;
        }


    }


</script>
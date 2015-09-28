<?php
use yii\widgets\LinkPager;
$this->title = '商品分类管理';
?>
<script type="text/javascript" src="/js/goods/category.js"></script>
    <legends  style="fond-size:12px;">
        <legend>商品分类管理</legend>
    </legends>
<?php
echo $this->render('_search');
?>
<div class="tab-content">
    <div class="row-fluid">
        [<a href="/goods/cate/add?p_id=0">添加分类</a>]
        <table class="table table-bordered table-hover">
            <tbody>
            <?php if (empty($data)) {
            } else {
                ?>
            <?php
                foreach ($data as $key => $item):
                    ?>
                    <tr id="tr_<?= $item['id']; ?>">
                        <td style="text-align: left;">
                            <?= $item['name']; ?>(<?= $item['status']==1 ? '隐藏' : '显示';?>)　
                            [<a href="/goods/cate/add?p_id=<?= $item['id']; ?> ">添加子分类</a>]　
                            [<a href="/goods/cate/add?id=<?= $item['id']; ?>">编辑</a>]
                            [<a href="/goods/cate/view?id=<?= $item['id']; ?>">查看</a>]
                            <?php if (empty($item['data'])) :
                                ?>
                                [<a onclick="Delete(<?= $item['id'];?>)" href="javascript:void(0)">删除</a>]
                            <?php endif?>
                        </td>
                    </tr>
                    <?php
                        if (!empty($item['data'])) {
                            foreach ($item['data'] as $data):
                                ?>
                                <tr id="tr_<?= $data['id']; ?>">
                                    <td style="text-align: left;">　　
                                        |----<?= $data['name']; ?>(<?= $data['status']==1 ? '隐藏' : '显示';?>)　
                                        <!--                                [<a href="/social/category/add?p_id=--><?//= $item1['id']; ?><!-- ">添加子类型</a>]-->
                                        [<a href="/goods/cate/add?id=<?= $data['id']; ?> ">编辑</a>]
                                        [<a href="/goods/cate/view?id=<?= $data['id']; ?>">查看</a>]
                                            [<a onclick="Delete(<?= $data['id']; ?>)" href="javascript:void(0)">删除</a>]
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        }
                endforeach;
            }
            ?>
            </tbody>

        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
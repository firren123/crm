<?php
use yii\widgets\LinkPager;
$this->title = '商品分类管理';
?>
<script type="text/javascript" src="/js/goods/category.js"></script>
<legends  style="fond-size:12px;">
    <legend>商品分类管理</legend>
    </legends>
<?php
echo $this->render('_search', ['name'=>$name]);
?>

<a id="yw0" class="btn btn-primary" href="/goods/category/add" style="margin-bottom:20px;">添加分类</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
                <tr>
                    <th style="width: 8%">
                        <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />全选
                    </th>
                    <th>分类ID</th>
                    <th>分类名称</th>
                    <th>分类图片</th>
                    <th>排序</th>
                    <th>是否显示</th>
                    <th>操作</th>
                </tr>
            </tbody>
            <tfoot>
                <?php if(empty($data)) {
                    echo '<tr><td style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach ($data as $item):
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="<?php echo $item['id'] ?>" id="brandid" class="check"/>
                            </td>
                            <td><?= $item['id'];?></td>
                            <td><?= $item['name'];?></td>
                            <td><img src="<?= empty($item['img']) ? '/images/05_mid.jpg' :\Yii::$app->params['imgHost'].$item['img'];?>" style="width: 100px;height: 100px;" ></td>
                            <td><?= $item['sort'];?></td>
                            <td><?= $item['status']==1 ? '隐藏' : '显示';?></td>
                            <td>
                                <?php if($item['cate_status']==0):?>
                                <a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a> |
                                <?php endif?>
                        <a style="cursor:pointer" href="/goods/category/add?id=<?= $item['id'];?>">修改</a> </td>
                        </tr>
                    <?php endforeach;
                }
                ?>

            <tr style="display: none">
                <td colspan="7">
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
    clickCheckbox();
</script>

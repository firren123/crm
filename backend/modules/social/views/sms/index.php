<?php
use yii\widgets\LinkPager;
$this->title = '短信管理';
?>
<legends  style="fond-size:12px;">
    <legend>短信管理</legend>
</legends>
<?php
echo $this->render('_search', ['search'=>$search]);
?>
<div class="tab-content">
    <div class="summary pull-right" >共 <span style="color: red"><?= $total?></span> 条短信</div>
    <br>
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 10%">手机号</th>
                <th>短信内容</th>
                <th width="130px">发送时间</th>
            </tr>
            </tbody>
            <tfoot>
            <?php
            if (empty($data)) {
                echo '<tr><td colspan="15">暂无记录</td></tr>';
            } else {
                foreach ($data as $list):
                    ?>
                    <tr>
                        <td><?= $list['id'];?></td>
                        <td><a href="/social/user/view?mobile=<?= $list['mobile'];?>" title="<?= $list['mobile'];?>"><?= $list['mobile'];?></a></td>
                        <td><?= $list['content'];?></td>
                        <td><?= $list['create_time'];?></td>
                    </tr>

                <?php
                endforeach;
            }
            ?>
            </tfoot>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<script>
    clickCheckbox();

</script>
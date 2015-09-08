<?php
use yii\widgets\LinkPager;
$this->title = '退换货管理';
?>
<legends  style="fond-size:12px;">
    <legend>退换货管理</legend>
</legends>

<div class="tab-content">
    <div class="summary pull-right" >共 <span style="color: red"><?= $count?></span> 个用户</div>
    <br>
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 10%">用户名</th>
                <th>最后登陆时间</th>
                <th width="130px">最后登陆IP</th>
                <th width="130px">最后登陆渠道</th>
                <th width="130px">最后登陆来源</th>
                <th>登陆次数</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php
            if (empty($data)) {
                echo '<tr><td colspan="15">暂无记录</td></tr>';
            } else {
                foreach ($data as $list):
                    ?>

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
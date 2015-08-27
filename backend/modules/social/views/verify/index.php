<?php
use yii\widgets\LinkPager;
$this->title = '验证码管理';
?>
<legends  style="fond-size:12px;">
    <legend>验证码管理</legend>
</legends>
<?php
echo $this->render('_search', ['search'=>$search,'conf_data'=>$conf_data]);
?>
<div class="tab-content">
    <div class="summary pull-right" >共 <span style="color: red"><?= $total?></span> 条验证码</div>
    <br>
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 10%">手机号</th>
                <th>验证码</th>
                <th>类型</th>
                <th>添加时间</th>
                <th>验证码有效期</th>
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
                        <td><?= $list['code'];?></td>
                        <td><?= empty($conf_data[$list['type']]) ? '未知' : $conf_data[$list['type']];?></td>
                        <td><?= $list['create_time'];?></td>
                        <td><?= $list['expires_in'];?></td>
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
<?php
use yii\widgets\LinkPager;
$this->title = '用户管理';
?>
<legends  style="fond-size:12px;">
    <legend>用户管理</legend>
</legends>
<script type="text/javascript" src="/js/social/user.js"></script>
<a id="yw0" class="btn btn-primary" href="/social/user/add" style="margin-bottom:10px;">添加用户</a>
<?php
echo $this->render('_search', ['search'=>$search]);
?>
<div class="tab-content">
    <div class="summary pull-right" >共 <span style="color: red"><?= $total?></span> 个用户</div>
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
                    <tr>
                        <td><?= $list['id'];?></td>
                        <td><a href="/social/user/view?mobile=<?= $list['mobile'];?>" title="<?= $list['mobile'];?>"><?= $list['mobile'];?></a></td>
                        <td><?= $list['last_login_time'];?></td>
                        <td><?= $list['last_login_ip'];?></td>
                        <td>
                            <?php
                            switch ($list['last_login_channel']) {
                            case 1;
                                echo "账号";
                                break;
                            case 2;
                                echo "qq";
                                break;
                            case 3;
                                echo "微信";
                                break;
                            case 4;
                                echo "微博";
                                break;
                            }
                            ?></td>
                        <td>
                            <?php
                            switch ($list['last_login_source']) {
                            case 1;
                                echo "wap";
                                break;
                            case 2;
                                echo "android";
                                break;
                            case 3;
                                echo "ios";
                                break;
                            }
                            ?></td>
                        <td><?= $list['login_count'];?></td>
                        <td><a href="javascript:void(0)" onclick="user.updateStatus(<?= $list['status']?>,<?= $list['mobile']?>)"><?= $list['status']==2 ? "可用" : "禁用";?></a></td>
                        <td><a href="/social/user/edit?mobile=<?= $list['mobile']?>">编辑信息</a><br><a href="/social/user/password?mobile=<?= $list['mobile']?>"> 修改密码</a></td>
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

<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
$this->title='用户样品申请列表';?>
<legends  style="fond-size:12px;">
    <legend>用户样品申请列表</legend>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>手机号</th>
                    <th>申请码</th>
                    <th>样品名称</th>
                    <th>申请时间</th>
                    <th>是否领取</th>
                    <th>操作</th>
                </tr>
                <?php if(empty($apply_info)) {
                    echo '<tr><td colspan="8" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($apply_info as $item){
                        ?>
                        <tr>
                            <td><?= $item['id'];?></td>
                            <td><?= $item['phone'];?></td>
                            <td><?= $item['apply_code'];?></td>
                            <td><?= $item['sample_name'];?></td>
                            <td><?= $item['create_time'];?></td>
                            <td><?= $status_data[$item['is_get']];?></td>
                            <td>
                                <a
                                    href="/user/sampleapply/view?sa_id=<?= $item['id']; ?>">查看反馈</a>
                            </td>
                        </tr>
                    <?php } }?>
                </tbody>
            </table>
            <?php echo LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</legends>


<?php
$this->title = "短信列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>短信列表</legend>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<div class="tab-content">
    <div class="row-fluid">
        <table table align='center' class="table table-bordered table-hover">
            <tr>
                <th>编号 </a></th><th>电话号码</th><th>短信内容</th><th>发送时间</th><th>操作</th>
            </tr>
            <?php
            if(empty($result)){
                echo '<tr><td colspan="9" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach($result as $k => $v){?>
                    <tr>
                        <td><?= $v['id'];?></td>
                        <td><?= $v['mobile'];?></td>
                        <td style="text-align:left;"><?= $v['content'];?></td>
                        <td>
                            <?= date("Y-m-d H:i:s", $v['create_time']);?>
                        </td>
                        <td>
                            <a style="cursor:pointer" href="/admin/smslog/two?id=<?=$v['id'];?>">重新发送</a>
                        </td>
                    </tr>
                <?php }?>
            <?php }?>
        </table>
        <div class="pagination pull-left">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
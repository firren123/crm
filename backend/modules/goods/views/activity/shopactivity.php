<?php
$this->title = "活动列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>活动管理</legend>
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class=""><a href="/goods/activity/activity-shop">商家活动列表</a></li>
        <li class="active">商铺列表</li>
    </ul>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>

<div class="tab-content">
    <p style="padding-bottom:20px;"><span style="float:right;">共计&nbsp;<span style="color: red;"><?php echo $count;?></span>&nbsp;条记录</span></p>
    <div class="row-fluid">
        <table table align='center' class="table table-bordered table-hover">
            <tr>
                <th>活动id</th>
                <th>活动名称</th>
                <th>活动类型</th>
                <th>开始日期</th>
                <th>结束日期</th>
                <th>当前状态</th>
                <th>商铺名称</th>
                <th>操作</th>
            </tr>
            <?php
            if(empty($data)){
                echo '<tr><td colspan="9" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach($data as $k => $v){?>
                    <tr>
                        <td><?= $v['id'];?></td>
                        <td><?= $v['name'];?></td>
                        <td><?php if(isset($v['type'])){
                                switch($v['type']){
                                    case 1 : echo '买赠'; break;
                                    case 2 : echo '满赠'; break;
                                    case 3 : echo '限购'; break;
                                }
                            }?></td>
                        <td><?= $v['start_time'];?></td>
                        <td><?= $v['end_time'];?></td>
                        <td><?= $v['status_name'];?></td>
                        <td><?= $v['shop_name'];?></td>
                        <td>
                            <?php if(!empty($v['key'])){?>
                            <a style="cursor:pointer" href="">审核</a> |
                        <?php }?>
                            <a href="/goods/activity/look-shop?id=<?= $v['id'];?>&shopid=<?= $v['shop_id'];?>" style="cursor: pointer";>详情</a>
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



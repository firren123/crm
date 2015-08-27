<?php
$this->title = "商家起送费列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>商家起送费管理</legend>
</legends>
<a id="yw0" class="btn btn-primary" href="/shop/config/add" style="margin-bottom:20px;">添加分公司起送费</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />全选
                </th>
                <th>ID</th>
                <th>免运费价格</th>
                <th>起送价</th>
                <th>运费</th>
                <th>所属分公司</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if (empty($list)) {
                echo '<tr><td colspan="5" style="text-align:center;">暂无记录</td></tr>';
            } else {
                foreach ($list as $list){
                    ?>
                    <tr>
                    <td><?= $list['id'];?></td>
                    <td><?= $list['id'];?></td>
                    <td><?= $list['free_shipping'];?></td>
                    <td><?= $list['send_price'];?></td>
                    <td><?= $list['freight'];?></td>
                    <td><?= $list['branch_name'];?></td>
                    <td>编辑 | 删除</td>
                    </tr>
            <?php }}?>
            </tfoot>
            </table>
        </div>
    </div>
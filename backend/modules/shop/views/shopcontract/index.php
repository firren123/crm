<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  index.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/6 上午11:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$this->title = '商家合同列表';
?>
<div class="wide form">
    <h3>商家合同列表</h3>
    <form id="search-form" class="well form-inline" action="/shop/shopcontract/index" method="get">

        <label for="ship_status">合同号：</label>
        <input id="order_sn" type="text" size="25" maxlength="8" name="htnumber" value="<?= @$_GET['htnumber'];?>" class="form-control"/>
        <label for="shop_name">&nbsp;&nbsp;注册名称：</label>
        <input id="shop_name" type="text" size="25" name="shop_contract_name" value="<?= @$_GET['shop_contract_name'];?>" class="form-control"/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit"> &nbsp;&nbsp;搜索 &nbsp;&nbsp;</button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a class="btn btn-primary" href="/shop/shopcontract/add">&nbsp;&nbsp;添加合同&nbsp;&nbsp;</a>
        <br /><br />
        <label for="status">&nbsp;&nbsp;&nbsp;电话：</label>
        <input id="order_sn" type="text" size="25" maxlength="13" name="contacts_umber" value="<?= @$_GET['contacts_umber'];?>" class="form-control"/>
        <label for="pay_status">&nbsp;&nbsp;联系人： &nbsp; &nbsp;</label>
        <input id="order_sn" type="text" size="25" name="contacts" value="<?= @$_GET['contacts'];?>" class="form-control"/>
        <label for="order_sn">&nbsp;&nbsp;业务员ID：</label>
        <input id="order_sn" type="text" size="25" maxlength="6" name="counterman" value="<?= @$_GET['counterman'];?>" class="form-control"/>
    </form>
</div>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tr>
                <th>合同号</th>
                <th>注册名称</th>
                <th>联系人</th>
                <th>电话</th>
                <th>业务员ID</th>
                <th>状态</th>
            </tr>
            <?php
                foreach ($list as $k=>$v) {
            ?>
            <tr onclick="window.location.href='/shop/shopcontract/detail?id='+<?= $list[$k]['id']?>" onmouseover="this.style.backgroundColor='#aaaaaa';return true;" onmouseout="this.style.backgroundColor='#ffffff';return true;">
                <td><?= $list[$k]['htnumber'];?></td>
                <td><?= $list[$k]['shop_contract_name'];?></td>
                <td><?= $list[$k]['contacts'];?></td>
                <td><?= $list[$k]['contacts_umber'];?></td>
                <td><?= $list[$k]['counterman'];?></td>
                <td><?php if($list[$k]['status']){echo "已生效" ;}else{ echo"未生效";} ?></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <div class="pages">
        <?= \yii\widgets\LinkPager::widget(['pagination' => $pages]) ?>
    </div>
</div>
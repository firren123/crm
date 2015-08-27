<?= $this->registerCssFile("@web/css/globalm.css");?>
<link rel="stylesheet" href="css/ui-dialog.css">
<script src="dist/dialog-min.js"></script>
<script>
    var dialog = require('./src/dialog');
    function al(){
        var d = dialog({
            title: '欢迎',
            content: '欢迎使用 artDialog 对话框组件！'
        });
        d.show();
    }
    $("#sss").click(function(){
        al();
    })
</script>

<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '商家协议详情';
?>
<div class="content fr">
    <div class="breadcrumbs">您现在的位置：<a href="/shop/cupboardagreement/index">首页</a><span>&gt;</span><a href="/shop/cupboardagreement/index">商家展位协议管理</a><span>&gt;</span><span class="current">详情</span></div>
    <div class="currrnttitle">展位协议详情</div>
    <div class="indentbox">
        <div class="indenttitle">商家信息</div>
        <ul>
            <li>
                <dl>
                    <dt>商户名称：</dt>
                    <dd><?= $list['shop_name'];?></dd>
                </dl>
                <dl>
                    <dt>联系人：</dt>
                    <dd><?= $list['contact_name']; ?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>电话：</dt>
                    <dd><?= $list['mobile']?$list['mobile']:$list['phone']; ?></dd>
                </dl>
                <dl>
                    <dt>商家地址：</dt>
                    <dd><?= $list['address']; ?></dd>
                </dl>
            </li>
        </ul>
    </div>
    <div class="indentbox">
        <div class="indenttitle">协议信息</div>
        <ul>
            <li>
                <dl>
                    <dt>协议编号：</dt>
                    <dd><?= $list['sn']; ?></dd>
                </dl>
                <dl>
                    <dt>展位名称：</dt>
                    <dd><?= $list['title']; ?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>收费类型：</dt>
                    <dd><?= $jiesuan_data[$list['type']]; ?></dd>
                </dl>
                <dl>
                    <dt>租用金额:</dt>
                    <dd><?= $list['cupboard_amount'];?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>展位时常：</dt>
                    <dd><?= $list['cupboard_period'];?></dd>
                </dl>
                <dl>
                    <dt>协议说明：</dt>
                    <dd><?= $list['description'];?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>创建时间：</dt>
                    <dd><?= $list['create_time'];?></dd>
                </dl>
                <dl>
                    <dt>审核状态：</dt>
                    <dd><?= $status_data[$list['status']];?></dd>
                </dl>
            </li>
        </ul>
    </div>


    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tr>
                    <th>操作</th>
                    <th>备注</th>
                    <th>管理员</th>
                    <th>时间</th>
                </tr>
                <?php if($log_list){
                    foreach($log_list as $k => $v){
                        ?>
                        <tr>
                            <td><?= $status_data_log[$v['status']];?></td>
                            <td><?= $v['info']; ?></td>
                            <td><?= $v['name'];?></td>
                            <td><?= $v['create_time'];?></td>
                        </tr>
                    <?php   }}else{ ?>
                    <tr>
                        <td colspan="4">暂无记录</td>
                    </tr>
                <?php }
                ?>

            </table>

            <table class="table table-bordered table-hover">
                <tbody>
                <?php
                if($list['status'] ==0){ ?>
                <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/shop/cupboardagreement/edits',]); ?>
                <tr>
                    <th colspan="4">审核通过/不通过</th>
                    <th colspan="1">
                        <input type="radio" class="valStatus" name="status" value="1"/>通过
                        <input type="radio" class="valStatus" name="status" value="2"/>不通过
                    </th>

                </tr>
                <tr>
                    <td colspan="8">备注：
                        <input type="hidden" name="id" value="<?= $list['id']; ?>"/>
                        <input type="hidden" name="sn" value="<?= $list['sn']; ?>"/>
                        <textarea rows="6" cols="50" name="info" placeholder="请填写备注信息" class="error"></textarea>
                        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
                    </td>
                </tr>
                <?php ActiveForm::end(); }?>
                </tbody>
            </table>
        </div>
    </div>
    <!--javascript:history.go(-1)-->
    <a href="/shop/cupboardagreement/" class="btn waitbtn btn-primary">取&nbsp;&nbsp;&nbsp;&nbsp;消</a>
    <a href="/shop/cupboardagreement/editone?id=<?=$list['id'];?>" class="btn waitbtn btn-primary">编&nbsp;&nbsp;&nbsp;&nbsp;辑</a>
</div>
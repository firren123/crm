<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/18
 * Time: 11:02
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '供应商详细信息';
date_default_timezone_set("PRC");
?>
<style>
    ul{ }
    li{ float: left; list-style-type: none;}
    td{border: 0.5px solid powderblue;}
    tr{}
    .left{ width: 200px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;}
    .right{width: 400px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;}
</style>
<div class="tab-content" style="margin-left: 30px; margin-top: -20px;">
    <ul>
        <li><a href="../..">首页</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li><a href="index">所有供应商基本信息</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li>供应商详细信息</li>
    </ul>
    <div style="clear: both;"></div>
    <table style="width: 600px; height: auto;margin-top: 20px; margin-left: 20px; border: 1px solid #ddd; text-align: left;">
        <tr>
            <td class="left">供应商代码：</td>
            <td class="right"><?= $list[0]['supplier_code'] ?></td>
        </tr>
        <tr>
            <td class="left">公司名称：</td>
            <td class="right"><?= $list[0]['company_name'] ?></td>
        </tr>
        <tr>
            <td class="left">账号：</td>
            <td class="right"><?= $list[0]['account'] ?></td>
        </tr>
        <tr>
            <td class="left">联系方式：</td>
            <td class="right"><?= $list[0]['contact'] ?></td>
        </tr>
        <tr>
            <td class="left">性别 ：</td>
            <td class="right">
                <?php if($list[0]['sex']==1){ ?>
                    男
                <?php }else{ ?>
                    女
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="left">电子邮件：</td>
            <td class="right"><?= $list[0]['email']  ?></td>
        </tr>
        <tr>
            <td class="left">手机号：</td>
            <td class="right"><?= $list[0]['mobile'] ?></td>
        </tr>
        <tr>
            <td class="left">电话：</td>
            <td class="right"><?= $list[0]['phone'] ?></td>
        </tr>
        <tr>
            <td class="left">QQ：</td>
            <td class="right"><?= $list[0]['qq'] ?></td>
        </tr>

        <tr>
            <td class="left">上次登录IP：</td>
            <td class="right"><?= $list[0]['last_login_ip'] ?></td>
        </tr>
        <tr>
            <td class="left">上次登录时间：</td>
            <td class="right"><?= $list[0]['last_login_time'] ?></td>
        </tr>
    </table>
    <?php
    $form = ActiveForm::begin([
        'id' => "login-form",
        'action' => 'showedit',
        'method' => 'get',
        'layout' => 'horizontal',
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data'],
    ]);
    ?>
    <input type="hidden" name="id" value="<?= $list[0]['id'] ?>" />
    <div class="form-actions">
        <?= Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="index">返回</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>
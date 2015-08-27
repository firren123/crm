<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/18
 * Time: 11:02
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '用户详细信息';
date_default_timezone_set("PRC");
?>
<style>
    ul{ }
    li{ float: left; list-style-type: none;}
    td{border: 1px solid #DDD;}
    tr{}
    .left{ width: 200px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;}
    .right{width: 400px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;}
</style>
<div class="tab-content">
    <ul>
        <li><a href="../..">首页</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li><a href="index">所有用户基本信息</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li>用户个人详细信息</li>
    </ul>
    <div style="clear: both;"></div>
    <table style="width: 600px; height: auto;margin-top: 20px; border: 1px solid #DDD; text-align: left;">
        <tr>
            <td class="left">用户ID：</td>
            <td class="right"><?= $list['id'] ?></td>
        </tr>
        <tr>
            <td class="left">用户名：</td>
            <td class="right"><?= $list['username'] ?></td>
        </tr>
        <tr>
            <td class="left">姓名：</td>
            <td class="right"><?= $list['name'] ?></td>
        </tr>
        <tr>
            <td class="left">电子邮箱：</td>
            <td class="right"><?= $list['email'] ?></td>
        </tr>
        <tr>
            <td class="left">手机号：</td>
            <td class="right"><?= $list['mobile'] ?></td>
        </tr>
        <tr>
            <td class="left">创建时间：</td>
            <td class="right"><?= $list['add_time'] ?></td>
        </tr>
        <tr>
            <td class="left">最后登录时间：</td>
            <td class="right"><?= date("Y-m-d H:i:s", $list['login_time'])  ?></td>
        </tr>
        <tr>
            <td class="left">最后登录IP：</td>
            <td class="right"><?= $list['login_ip'] ?></td>
        </tr>
        <tr>
            <td class="left">状态：</td>
            <td class="right">
                <?php if($list['status']==2){ ?>
                    <label style="color: #3b97d7;">已激活</label>
                <?php }?>
                <?php if($list['status']==1){ ?>
                    <label style="color: red; ">已锁定</label>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td class="left">账户余额：</td>
            <td class="right"><?= $list['balance'] ?></td>
        </tr>

        <tr>
            <td class="left">头像：</td>
            <td class="right">
                <img src="<?= empty($list['headerpic']) ? '/images/05_mid.jpg' :\Yii::$app->params['imgHost'].$list['headerpic'];?>" width="100px" height="150px"/>
            </td>
        </tr>
        <tr>
            <td class="left">未读消息数：</td>
            <td class="right"><?= $list['unread_count'] ?></td>
        </tr>
        <tr>
            <td class="left">商家ID：</td>
            <td class="right"><?= $list['shop_id'] ?></td>
        </tr>
        <tr>
            <td class="left">邀请成功人数：</td>
            <td class="right"><?= $list['invite_number'] ?></td>
        </tr>
        <tr>
            <td class="left">要求注册成功人ID：</td>
            <td class="right"><?= $list['invite_id'] ?></td>
        </tr>
        <tr>
            <td class="left">注册类型：</td>
            <td class="right">
                <?php if($list['type']==1){ ?>
                    用户名
                <?php }?>
                <?php if($list['type']==2){ ?>
                    手机号
                <?php }?>
            </td>
        </tr>
    </table>
    <?php
    $form = ActiveForm::begin([
        'id' => "login-form",
        'action' => 'editoneuser',
        'method' => 'get',
        'layout' => 'horizontal',
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data'],
    ]);
    ?>
    <input type="hidden" name="id" value="<?= $list['id'] ?>" />
    <div class="form-actions">
        <?= Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="index">返回</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$this->title = '用户身份证审核';
use yii\bootstrap\ActiveForm;
?>
<script type="text/javascript" src="/js/social/user.js"></script>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/user">用户管理</a></li>
        <li class="active">用户身份证审核</li>
    </ul>
    <table class="table table-bordered table-hover" style="width: 60%">
        <tbody>
        <tr>
            <td colspan="2">身份证等信息</td>
        </tr>
        <tr>
            <td style="width:20%;">姓名：</td>
            <td><?= empty($item['realname']) ? '--' : $item['realname']?></td>
        </tr>
        <tr>
            <td style="width:20%;">身份证：</td>
            <td><?= empty($item['user_card']) ? '--' : $item['user_card']?></td>
        </tr>
        <tr>
            <td style="width:20%;">审核状态：</td>
            <td><?php
                if ($item['card_audit_status']) {
                        switch ($item['card_audit_status']) {
                            case 0;
                                echo "未审核";
                                break;
                            case 1;
                                echo "审核中";
                                break;
                            case 2;
                                echo "审核通过";
                                break;
                            case 3;
                                echo "审核失败";
                                break;
                        }
                } else {
                    echo '--';
                }
                ?></td>
        </tr>
        <tr>
            <td style="width:20%;">审核:</td>
            <td>
                <?php if ($item['card_audit_status'] and $item['card_audit_status']==1):
                ?>
                <label>
                    <input type="radio" value="2" id="status" name="status">&nbsp;审核通过&nbsp;&nbsp;
                </label>
                <label>
                    <input type="radio" value="3" id="status" name="status">&nbsp;审核失败
                </label>
                <?php endif?>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="form-actions">
        <?php if ($item['card_audit_status'] and $item['card_audit_status']==1):
        ?>
        <a class="btn btn-primary" href="javascript:void(0)" onclick="user.cardStatus(<?php echo $_GET['mobile']?>)">审核</a>
        <?php endif?>
        <a class="btn cancelBtn" href="/social/user">返回</a>
    </div>

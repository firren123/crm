<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  setting_detail.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 上午11:02
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
?>
<?= $this->registerCssFile("@web/css/globalm.css"); ?>
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '店铺设置详情';

?>
<legends  style="fond-size:12px;">
    <legend>店铺设置详情</legend>
</legends>
<div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a href="/social/service/setting">店铺设置</a><span>&gt;</span><span>店铺设置详情</span>
</div>
    <table class="table table-bordered table-hover">
    <tr>
        <th width="100px">用户ID：</th>
        <td><?=$list['uid'];?></td>
    </tr>
    <tr>
        <th>手机号：</th>
        <td><?=$list['mobile'];?></td>
    </tr>
    <tr>
        <th>店铺名称：</th>
        <td><?=$list['name'];?></td>
    </tr>
    <tr>
        <th>店铺描述：</th>
        <td><?=Html::decode($list['description']);?></td>
    </tr>
    <tr>
        <th>省份：</th>
        <td><?=$list['province_id'];?></td>
    </tr>
    <tr>
        <th>检索出的详细地址：</th>
        <td><?=$list['search_address'];?></td>
    </tr>
    <tr>
        <th>楼号、单元和门牌号：</th>
        <td><?=$list['details_address'];?></td>
    </tr>
    <tr>
        <th>经度：</th>
        <td><?=$list['lng'];?></td>
    </tr>
    <tr>
        <th>纬度：</th>
        <td><?=$list['lat'];?></td>
    </tr>
    <tr>
        <th>是否禁用：</th>
        <td><?=$list['status']==1?'禁用':'可用';?></td>
    </tr>

    <tr>
        <th>是否删除：</th>
        <td><?=$list['is_deleted']==1?'已删除':'未删除';?></td>
    </tr>
    <tr>
        <th>创建时间：</th>
        <td><?=$list['create_time'];?></td>
    </tr>
    <tr>
        <th>更新时间：</th>
        <td><?=$list['update_time'];?></td>
    </tr>
        <tr>
            <td colspan="10">
                <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
                <button id="jinyong" class="btn btn-primary">启/禁用</button>
                <button id="del" class="btn btn-primary">删除</button>
            </td>
        </tr>

</table>

<dev class="jinyong_status" style="width: 400px;height: 300px;display: none;position:absolute; left:20%; top:40%; margin:-16px 0 0 -16px; z-index:999;background: #fff;">
    <table class="table table-bordered table-hover">
        <tr>
            <th colspan="10">是否禁用店铺</th>
        </tr>
        <!--        <form action="/social/service/service-up-field" method="post">-->
        <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/social/service/service-setting-up-field',]); ?>
        <tr>
            <th class="ok_info">
                <input type="radio" name="status" value="2"/>启用
                <input type="radio" name="status" value="1"/>禁用
            </th>
        </tr>
        <tr>
            <td><textarea id="remark" style="width: 300px;height: 80px;border: 1px solid #999;" name="remark"></textarea></td>
        </tr>
        <tr>
            <td><input type="submit" value="提交" class="btn-primary btn"/>
                <button id="quxiao" class="btn cancelBtn">取消</button>
            </td>
        </tr>
        <input type="hidden" name="del" value="<?= $list['id']; ?>"/>
        <input type="hidden" name="id" value="<?= $list['id']; ?>"/>
        <input type="hidden" id="_csrf" name="YII_CSRF_TOKEN" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <?php ActiveForm::end(); ?>
    </table>
</dev>
<dev class="del_status" style="width: 400px;height: 300px;display: none;position:absolute; left:20%; top:40%; margin:-16px 0 0 -16px; z-index:999;background: #fff;">
    <table class="table table-bordered table-hover">
        <tr>
            <th colspan="10">删除店铺</th>
        </tr>
        <!--        <form action="/social/service/service-up-field" method="post">-->
        <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/social/service/service-setting-up-field',]); ?>

        <tr>
            <td><textarea id="remark" style="width: 300px;height: 80px;border: 1px solid #999;" name="remark"></textarea></td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="提交" class="btn-primary btn"/>
                <button id="quxiao" class="btn cancelBtn">取消</button>
            </td>
        </tr>
        <input type="hidden" name="del" value="<?= $list['id']; ?>"/>
        <input type="hidden" name="id" value="<?= $list['id']; ?>"/>
        <input type="hidden" id="_csrf" name="YII_CSRF_TOKEN" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <?php ActiveForm::end(); ?>
    </table>
</dev>
<script type="text/javascript">
    $("button").click(function(){
        var id = $(this).attr("id");
        switch (id){
            case 'del':
                $(".del_status").show();
                break;
            case 'jinyong':
                $(".jinyong_status").show();
                break;
            case 'quxiao':
                $(".del_status").hide();
                $(".audit_status").hide();
                $(".jinyong_status").hide();
                return false;
            default :
                break;
        }

    })
</script>


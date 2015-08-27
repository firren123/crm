<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加权限';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/admin"></a> 权限管理</a></li>
        <li class="active">增加/修改 权限</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
    'action'=>'/admin/auth/add',
]);
?>

    <?php foreach($module_list as $k=>$v){ ?>

<table class="module" style="margin-bottom: 20px">
        <tr >
        <td align="left"><input type="checkbox" name="access[]" <?php if(in_array( $v['id'], $menu_ids)) {?> checked <?php } ?>  level="1" value="<?= $v['id']?>"/><?= $v['title'];?></td>
        </tr>
        <tr>
        <td>
            <?php if(isset($v['child'])) { ?>
        <?php foreach($v['child'] as $kk=>$vv){?>
        <input type="checkbox" name="access[]"  <?php if(in_array( $vv['id'], $menu_ids)) {?> checked <?php } ?> value="<?= $vv['id']?>"/><?= $vv['title'];?> &nbsp;&nbsp;|&nbsp;&nbsp;
        <?php }?>
        <?php } ?>
        </td>
        </tr>
</table>
   <?php }?>

<input type="hidden" name="rid" value="<?= $role_id;?>"/>
<div class="form-actions">
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
<?php ActiveForm::end(); ?>

    <script type="text/javascript">
        $(function(){
            $("input[level=1]").click(function(){
                var checks = $(this).parents('.module').find('input');
                $(this).prop('checked')? checks.prop('checked',true):checks.removeAttr('checked');
            })

        });
    </script>
<!--<form action="{:U('Admin/Rbac/setAccess')}" method="post">-->
<!--    <div id="warp">-->
<!--        <a href="{:U('Admin/Rbac/listrole')}" class="add-app">返回</a>-->
<!--        <foreach name="list" item="app">-->
<!--            <div class="app">-->
<!--                <p>-->
<!--                    <strong>{$app.title}</strong>-->
<!--                    <input type="checkbox" name="access[]" value="{$app.id}_1" level='1' <if condition="$app['access']"> checked="checked" </if>/>-->
<!---->
<!--                </p>-->
<!--                <foreach name="app.child" item="action">-->
<!--                    <dl>-->
<!--                        <dt>-->
<!--                            <strong>{$action.title}</strong>-->
<!--                            <input type="checkbox" name="access[]" value="{$action.id}_2" level='2' <if condition="$action['access']"> checked="checked" </if>/>-->
<!--                        </dt>-->
<!--                        <foreach name="action.child" item="method">-->
<!--                            <dd>-->
<!--                                <span>{$method.title}</span>-->
<!--                                <input type="checkbox" name="access[]" value="{$method.id}_3" level="3" <if condition="$method['access']"> checked="checked" </if> />-->
<!--                            </dd>-->
<!--                        </foreach>-->
<!---->
<!--                    </dl>-->
<!--                </foreach>-->
<!--            </div>-->
<!--        </foreach>-->
<!--    </div>-->
<!--    <input type="hidden" name="rid" value="{$rid}" />-->
<!--    <input type="submit" style="display:block;padding:0px 20px;margin:0 auto;cursor:pointer;" value="保存提交" />-->
<!--</form>-->
<!--</body>-->

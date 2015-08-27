<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  index.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/12 上午11:17
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '供应商管理';
?>
<script type="text/javascript" src="/js/goods/product.js"></script>
<script language="JavaScript">
    function allchecke(field){
        //alert(123);
        var checkboxes = document.getElementsByClassName("checkbox");
        for(var i=0;i<checkboxes.length;i++){
            checkboxes[i].checked = field.checked;
        }
    }
    function showid(){
        var checkboxes = document.getElementsByClassName("checkbox");
        var arr1=new Array();
        for(var i=0;i<checkboxes.length;i++){
            if(checkboxes[i].checked){
                arr1[i]=checkboxes[i].value;
            }
        }
        if(!arr1.length == 0){
            window.location.href="alldel?arrid="+arr1;
        }else{
            alert("请选择要删除的人员！");
        }
    }
    function check(a,value){
        //alert(a);
        //alert(value);
        if (!/^\d+$/.test(value)){

            a.value = /^\d+/.exec(a.value);}

        return false;
    }
        //value=value.replace(/[^\d]/g,'');

</script>
<style>
    td{border: 1px solid #c0c0c0;}
</style>
<div class="tab-content">
    <!--搜索表单开始-->
        <?php
        $form = ActiveForm::begin([
            'id' => "login-form",
            'action' => 'index',
            'method' => 'get',
            'layout' => 'horizontal',
            'enableAjaxValidation' => true,
            'options' => ['enctype' => 'multipart/form-data'],
        ]);
        ?>
        <legends  style="fond-size:12px;">
            <legend>供应商信息</legend>
        </legends>
        <p style="float: left;">供应商代码：</p>
        <input type="text" value="<?= @$_GET['supplierid'];?>" name="supplierid" style="float: left;" />
        <div style="float: left; margin-left: 20px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <a href="showadd" class="btn btn-primary" style=" float:left; margin-left: 20px;">添加信息</a>
        <a class="bulk-actions-btn btn btn-danger btn-small active" style=" float:right; margin-right: 20px;"  onclick="if(confirm('确定要全部删除？')) showid() ;return false;">使选中全部删除</a>
    </form>
    <!--搜索表单结束-->
<table style='width: 900px; height: auto; border: 0.5px solid #DDD; text-align: center; margin-top: 20px; '>
    <tr style='border: 0.5px solid #DDD; height: 40px; font-weight: bold; color:#3b97d7;'>
        <td style='width: 30px;'><input type="checkbox" onclick="allchecke(this)"/>全选 </td>
        <td style='width: 60px;'>供应商代码</td>
        <td style='width: 50px;'>公司名称</td>
        <td style='width: 50px;'>联系人</td>
        <td style='width: 50px;'>电话</td>
        <td style='width: 50px;'>QQ</td>
        <td style='width: 55px;'>操作</td>
    </tr>
    <br/>
    <?php
    //执行查找功能
    if($control=='sel') {
            if (empty($list)) {
                echo '<tr><td colspan="24" style="text-align:center;">暂无记录!</td></tr>';
            } else {
                foreach ($list as $key => $item) {
 ?>
                    <tr>
                        <td style="padding-left: 20px;"><input type="checkbox" name="<?= $item['id'] ?>" value="<?= $item['id'] ?>" class="checkbox"></td>
                        <td><?= $item['supplier_code'] ?></td>
                        <td><?= $item['company_name'] ?></td>
                        <td><?= $item['contact'] ?></td>
                        <td><?= $item['phone'] ?></td>
                        <td><?= $item['qq'] ?></td>
                        <td>
                            <a href="showonesupplier?id=<?= $item['id'] ?>">详情</a><br/>
                            <a href="delgoods?id=<?= $item['id'] ?>" onclick="if(confirm('确定要删除？'))return true;return false;">删除</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            <?php
            }

    }
    ?>
    </table>
    <div style="float: right;">
        <?= LinkPager::widget(['pagination' => $pages]); ?>
    </div>
</div>

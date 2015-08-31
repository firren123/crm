<?php
$this->title = "活动列表";
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;

?>
<legends  style="fond-size:12px;">
    <legend>活动管理</legend>
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class=""><a href="/goods/activity/activity-shop">商家活动列表</a></li>
        <li class="active">商铺商品列表</li>
    </ul>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>

<div class="tab-content">
    <div class="row-fluid">
        <?php if($type != 3){?>
            <table align='center' class="table table-bordered table-hover">
                <tr>
                    <th class="hdTabTit">赠品名称</th>
                    <th>赠品总数</th>
                </tr>
                <?php if(empty($data_z)) {
                    echo '<tr><td colspan="5" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach ($data_z as $item):
                        ?>
                        <tr class="order_list">
                            <td width="30%"><?= $item['product_name'];?></td>
                            <td><?= $item['number'];?></td>
                        </tr>
                    <?php endforeach;
                }
                ?>
            </table>
        <?php }?>
        <table align='center' class="table table-bordered table-hover" <?php if($type != 3){?>style="margin-top: 10px"<?php }?>>
            <tr>
                <th class="hdTabTit">商品名称</th>
                <th>商品总数</th>
                <th>活动价（元）</th>
            </tr>
            <?php if(empty($data)) {
                echo '<tr><td colspan="5" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($data as $item):
                    ?>
                    <tr class="order_list">
                        <td width="30%"><?= $item['product_name'];?></td>
                        <td><?= $item['day_confine_num'];?></td>
                        <td><span class="numColor"><?=$item['price']?></span></td>
                    </tr>
                <?php endforeach;
            }
            ?>
        </table>
        <div class="pagination pull-left">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
            <table align='center' class="table table-bordered table-hover">
                <?php $form = ActiveForm::begin(['id' => 'login-form','action' => '/goods/activity/check',]); ?>
                <tr>
                    <th class="hdTabTit"><span class="red">*</span>审核:</th>
                    <td>
                        审核不通过:<input  name="check" type="radio" value="1"> &nbsp;&nbsp;
                        审核通过:<input  name="check" type="radio" value="2">
                    </td>
                </tr>
                <tr>
                    <th><span class="red">*</span>原因:</th>
                    <td>
                        <textarea id="center" name="center"></textarea>
                    </td>
                </tr>
                <tr>
                    <input type="hidden" name="act_id" value="<?=$id;?>">
                    <input type="hidden" name="shop_id" value="<?=$shop_id;?>">
                    <td colspan="2"><a id="check" class="btn btn-primary" href="javascript:;">提交</a></td>
                </tr>
                <?php ActiveForm::end(); ?>
            </table>
    </div>
</div>
<script type="text/javascript">
 $(function(){
     $("#check").click(function(){
         var check_val = $.trim($('input:radio:checked').val());
         var center = $.trim($("#center").val());

         var d = dialog({title:"提示",
             okValue: '确定',
             ok: function () {}
         });
         if(check_val == ''){
             content = "选择审核状态不能为空";
             d.content(content);
             d.showModal();
             return false;
         }
         if(center == ''){
             content = "原因不能为空！！！";
             d.content(content);
             d.showModal();
             return false;
         }
         $("#login-form").submit();
     })
 })
</script>

<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  index.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/24 下午5:22
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$this->title = "合同列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>合同列表</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/iyangpin/contract/index" method="get">
        <label for="shop_name">合同账号：</label>
        <input id="shop_name" type="text" name="shop_name" value="<?= $shop_name;?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<a id="yw0" class="btn btn-primary" href="/iyangpin/contract/add" style="margin-bottom:20px;">添加合同</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />全选
                </th>
                <th>ID</th>
                <th>商家名称</th>
                <th>审核状态</th>
                <th>操作</th>
            </tr>
            <?php if(empty($list)) {
                echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="ids[]" value="<?php echo $item['id'] ?>" id="brandid" class="check"/>
                        </td>
                        <td><?= $item['id'];?></td>
                        <td><?= $item['shop_name'];?></td>

                        <td><?= $item['status']==1?'不容许':'容许';?></td>
                        <td><a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a>
                            <br/>
                            <a style="cursor:pointer" href="/iyangpin/contract/edit?id=<?= $item['id'];?>">修改</a> </td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<script>
    function Delete(id){
        var msg = "您真的确定要删除吗？";
        if (confirm(msg)==true){
            $.ajax(
                {
                    type: "GET",
                    url: '/iyangpin/contract/del',
                    data: {'id':id},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==1){
                            window.location.reload()
                        }
                    }
                }
            );

        }else{
            return false;
        }

    }

    clickCheckbox();
    /**
     * 全选
     * @return ''
     */
    function clickCheckbox(){
        $(".chooseAll").click(function(){
            var status=$(this).prop('checked');
            $(".check").prop("checked",status);
            //$(".chooseAll").prop("checked",status);
        });
    }
    /**
     * 判断是否选中
     * @returns {boolean}
     */
    function checkSelectd(){
        var falg = 0;
        $("input[name='ids[]']:checkbox").each(function () {
            if ($(this).prop("checked")==true) {
                falg += 1;
            }
        })
        if (falg > 0){
            if(confirm('确定要删除勾选的吗')) {
                var token = $('#token').val();
                var ids = $("input[name='ids[]']:checkbox").valueOf();
                var ids = $('input[id="brandid"]:checked').map(function () {
                    return this.value
                }).get().join();
                $.ajax(
                    {
                        type: "POST",
                        url: "/iyangpin/contract/ajax-delete",
                        data: {'ids': ids, '_csrf': token},
                        asynic: false,
                        dataType: "json",
                        beforeSend: function () {
                        },
                        success: function (result) {
                            if (result == 1) {
                                window.location.reload()
                            }
                        }
                    });
            }
        }else{
            alert('请选择要删除项');
            return false;
        }

    }
</script>
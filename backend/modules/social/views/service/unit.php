<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  unit.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 下午7:14
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$this->title = "服务单位列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>服务单位列表</legend>
</legends>

<a id="yw0" class="btn btn-primary" href="/social/service/unit-add" style="margin-bottom:20px;">添加服务单位</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />全选
                </th>
                <th>ID</th>
                <th>单位</th>
                <th>状态</th>
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
            <td><?= $item['unit'];?></td>
            <td><?= $item['status']==1?'禁止':'正常'?></td>
            <td><a style="cursor:pointer" onclick="unit_del(<?= $item['id'];?>)">删除</a>
                <br/>
                <a style="cursor:pointer" href="/social/service/unit-edit?id=<?= $item['id'];?>">修改</a> </td>
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
    function unit_del(id){
        var msg = "您真的确定要删除吗？";
        if (confirm(msg)==true){
            $.ajax(
                {
                    type: "GET",
                    url: '/social/service/unit-del',
                    data: {'id':id},
                    asynic: false,
                    dataType: "json",
                    success: function (result) {
                        if(result==1){
                            window.location.reload()
                        }else{
                            alert(result);
                        }
                    }
                }
            );

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
                        url: "/social/service/ajax-unit-del",
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

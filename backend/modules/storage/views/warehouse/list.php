<?php
$this->title = "库房列表";
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>库房管理</legend>
</legends>
<a id="yw0" class="btn btn-primary" href="/storage/warehouse/add" style="margin-bottom:20px;">添加库房</a>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="" method="get">
        <label for="name">库房名称：</label>
        <input id="name" type="text" name="name" value="<?= $name;?>" class="form-control">&nbsp;&nbsp;&nbsp;&nbsp;
        <label for="name">分公司：</label>
            <select id="bc_id"  name="bc_id" style="width:200px">
                <option value="">请选择分公司</option>
                <?php if(!empty($branch_arr)) :?>
                    <?php foreach($branch_arr as $data) :?>
                        <option value="<?= $data['id'];?>" <?php if(!empty($bc_id) and $data['id']==$bc_id):?>selected="selected"<?php endif;?>><?= $data['name']; ?></option>
                    <?php endforeach;?>
                <?php endif;?>
            </select>
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<div class="tab-content">
    <div class="row-fluid">
        <table table align='center' class="table table-bordered table-hover">
            <tr>
                <th>库房编号</th><th>库房名称</th><th>库房联系人</th><th>联系电话</th><th>所属分公司</th><th>详细地址</th><th>有效性</th><th>操作</th>
            </tr>
            <?php
            if(empty($res)){
                echo '<tr><td colspan="9" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach($res as $k => $v){?>
                    <tr>
                        <td><?= $v['sn'];?></td>
                        <td><?= $v['name'];?></td>
                        <td><?= $v['username'];?></td>
                        <td><?= $v['phone'];?></td>
                        <td><?= $v['bc_name'];?></td>
                        <td><?= $v['province_name'];?>&nbsp;<?= $v['city_name'];?>&nbsp;<?= $v['district_name'];?></td>
                        <td>
                            <?= $v['status']==1 ? '禁用' : '有效';?>
                        </td>
                        <td>
                            <a style="cursor:pointer" href="/storage/warehouse/edit?id=<?= $v['id'];?>">修改</a> | <a style="cursor:pointer" onclick="Delete(<?= $v['id'];?>)">删除</a>
                        </td>
                    </tr>
                <?php }?>
            <?php }?>
        </table>
        <div class="pagination pull-left">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
        <span style="float:right;">共计&nbsp;<span style="color: red;"><?php echo $count;?></span>&nbsp;条记录</span>
    </div>
</div>
<script>
    function Delete(id){
        var msg = "您真的确定要删除吗？";
        if (confirm(msg)==true){
            $.ajax(
                {
                    type: "GET",
                    url: '/storage/warehouse/delete',
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
</script>
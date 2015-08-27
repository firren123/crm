<?php
use yii\widgets\LinkPager;
$this->title = '商家提现申请管理';
?>
<legends  style="fond-size:12px;">
    <legend>商家提现申请管理</legend>
</legends>
<div class="wide form">
        <!--
                        time: 2015 07 31
                        auther: weitonghe
                        status: start
                        -->
    <form id="search-form" class="well form-inline" action="/shop/cash" method="get" name="form1">
<!--<form id="search-form" class="well form-inline" action="/shop/cash" method="get">-->
        <!--
                        time: 2015 07 31
                        auther: weitonghe
                        status: end
                        -->
        <label for="name">商家id：</label>
        <input id="name" type="text" name="Search[shop_id]" value="<?= empty($search['shop_id']) ? '' : $search['shop_id']?>" class="form-control">
        <label for="name">审核状态：</label>
        <select id="SearchForm_type" class="SearchForm_type"  name="Search[status]">
            <option selected="selected" value="">不限制</option>
            <option value="2" <?php if(!empty($search['status']) and $search['status']==2):?>selected="selected"<?php endif;?>>审核中</option>
            <option value="1" <?php if(!empty($search['status']) and $search['status']==1):?>selected="selected"<?php endif;?>>审核通过</option>
        </select>
        <!--
                        time: 2015 07 31
                        auther: weitonghe
                        status: start
                        -->
        <input id="isexport" name="isexport" type="hidden" value="2"/>
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit" onclick="javascript:document.getElementById('isexport').value='2';">搜索</button>
        <!--        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>-->
        <!--
                        time: 2015 07 31
                        auther: weitonghe
                        status: start
                        -->
    </form>
</div>
<div class="tab-content">
    <div class="summary pull-right" >共 <span style="color: red"><?= $total?></span> 条记录</div>
    <br>
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>商家ID</th>
                <th style="width: 100px">商家名称</th>
                <th>账户类型</th>
                <th>卡号</th>
                <th>提现金额</th>
                <th>申请状态</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($list)) {
                echo '<tr><td colspan="14">暂无申请</td></tr>';
            }else {
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td><?= $item['id']?></td>
                        <td><?= $item['shop_id']?></td>
                        <td><?= $item['shop_name']?></td>
                        <td><?= $item['account_type']?></td>
                        <td><?= $item['account_card']?></td>
                        <td><?= $item['money']?></td>
                        <td><?php
                            if ($item['status']==0) {
                                echo "审核中";
                            } elseif($item['status']==1) {
                                echo "审核通过";
                            } elseif($item['status']==2) {
                                echo "审核驳回";
                            } else {
                                echo "--";
                            }
                            ?>
                        </td>

                        <td>
                            <?php if($item['status']==0):?>
                                <a href="javascript:void(0)" onclick="cash.update(<?= $item['id']?>,1)">确认</a>
                            <?php endif?>
                            <?php if($item['status']==1):?>
                                <a href="javascript:void(0)" onclick="cash.update(<?= $item['id']?>,0)">取消确认</a>
                            <?php endif?>
                            <a href="/shop/cash/info?id=<?= $item['id']?>">详情</a></td>
                    </tr>
                <?php
                endforeach;
            }
            ?>
            <tr>
                <td colspan="14" style="text-align: right;font-size: 16px">已经确认金额：<?= $total_price;?>
                    <!--
                        time: 2015 07 30
                        auther: weitonghe
                        status: start
                        -->
                    <?php if(!empty($search['status']) and $search['status']==1):?>
                        <a class="btn cancelBtn" href="/shop/cash/export">全部导出到EXCEL</a>
                    <?php endif; ?>
                    <!--
                    time: 2015 07 30
                    auther: weitonghe
                    status: end
                    -->
                    <?php if($total_price>0):?>
                    <a class="btn cancelBtn" href="">去结算</a>
                    <?php endif?>
                </td>
            </tr>
            </tfoot>
            </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
        </div>
    </div>
<script>
    var cash;
    cash = {
      update:function(id,status){
          $.ajax(
              {
                  type: "GET",
                  url: '/shop/cash/update-status',
                  data: {'id':id,'status':status},
                  asynic: false,
                  dataType: "json",
                  beforeSend: function () {
                  },
                  success: function (result) {
                      if(result['code']==1){
                          var content = "<div style='width: 200px;text-align: center'>"+result['message']+"</div>"
                          var d = dialog({
                              title: "提示",
                              content: content,
                              ok: function () {
                                  window.location.reload()
                              }});
                          d.showModal();
                      } else {
                          gf.alert(result['message']);
                      }
                  }
              }
          );
      }
    };
</script>
<?php
use yii\widgets\LinkPager;
$this->title = '商家返利列表';
?>
<script type="text/javascript" src="/js/rebate.js"></script>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<legends  style="fond-size:12px;">
    <legend>商家返利列表</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/shop/rebate" method="get">
        <label for="name">分公司：</label>
        <select id="branch_id" class="SearchForm_type" name="Search[branch_id]" style="width: 120px;">
            <option value="">全部分公司</option>
            <?php if(!empty($branch_list)) :?>
                <?php foreach($branch_list as $data) :?>
                    <option value="<?= $data['id'];?>" <?php if(!empty($search['branch_id']) and $data['id']==$search['branch_id']):?>selected="selected"<?php endif;?>><?= $data['name']; ?></option>
                <?php endforeach;?>
            <?php endif;?>
        </select>
        <label id="two" for="name">城市：</label>
        <select id="city_id" class="SearchForm_type" name="Search[city_id]" style="width: 120px">
            <option  value="">全部城市</option>
            <?php if(!empty($city_list)){
                $branch_id = $search['city_id'];
                ?>
                <?php foreach($city_list as $data){
                    $selected = '';
                    if(!empty($branch_id) && $branch_id == $data['id']) {
                        $selected = ' selected';
                    }

                    echo '<option value="'.$data['id'].'"'.$selected.'>'.$data['name'].'</option>';
                } ?>
            <?php
            }?>
        </select>
        <label id="two" for="name">时间：</label>
        <input type="text" class="form-control" style="width: 120px" name="Search[create_time]"
               value="<?php if (isset($search['create_time']) && $search['create_time'] !== '') { ?><?php echo $search['create_time']; ?><?php } ?>"
               onFocus="WdatePicker({isShowClear:true,readOnly:false})"
            />
        <label id="two" for="name">商家id：</label>
        <input type="text" class="form-control" name="Search[shop_id]" style="width: 120px" value="<?php if (isset($search['shop_id']) && $search['shop_id'] !== '') { ?><?php echo $search['shop_id']; ?><?php } ?>">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
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
                <th>商家名称</th>
                <th>返利金额</th>
                <th>日期</th>
                <th>状态</th>
                <th>分公司</th>
                <th>城市</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($list)) {
                echo '<tr><td colspan="14">暂无记录</td></tr>';
            }else {
                foreach ($list as $item):
                    ?>
                <tr>
                    <td><?= $item['id']?></td>
                    <td><?= $item['shop_id']?></td>
                    <td><?= $item['shop_name']?></td>
                    <td><?= $item['money']?></td>
                    <td><?= date('Y-m-d', strtotime($item['create_time']))?></td>
                    <td><?php
                        switch($item['status']){
                            case 0;
                                echo "无效";
                                break;
                            case 1;
                                echo "未返利";
                                break;
                            case 2;
                                echo "已返利";
                                break;
                        }

                        ?></td>
                    <td><?= empty($item['branch_name']) ? '--' :$item['branch_name']?></td>
                    <td><?= empty($item['city_name']) ? '--' :$item['city_name']?></td>
                    <td><a href="/shop/rebate/detail?id=<?= $item['id']?>">明细</a></td>
                </tr>
                <?php
                endforeach;
            }
            ?>
            <tr><td colspan="4"></td>
                <td>小计</td>
                <td>未返利金额</td>
                <td><?= number_format($settled_total, 2 , '.', '')?></td>
                <td>已返利金额</td>
                <td><?= number_format($unsettled_total, 2 , '.', '')?></td>
            </tr>
            </tfoot>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
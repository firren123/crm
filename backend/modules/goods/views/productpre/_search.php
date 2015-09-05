<div class="wide form">
    <form id="search-form" class="well form-inline" action="/goods/productpre" method="get">
        <label for="name">商品id：</label>
        <input id="name" type="text" name="Search[id]" value="<?= empty($search['id']) ? '' : $search['id']?>" class="form-control">
        <label for="name">商品名称：</label>
        <input id="name" type="text" name="Search[name]" value="<?= empty($search['name']) ? '' : $search['name']?>" class="form-control">

        <label id="two" for="name">商品分类：</label>
        <select id="cate_id" class="SearchForm_type" name="Search[cate_id]">
            <option value="">全部分类</option>
            <?php if(!empty($cate_list)) :?>
                <?php foreach($cate_list as $data) :?>
                    <option value="<?= $data['id'];?>" <?php if(!empty($search['cate_id']) and $data['id']==$search['cate_id']):?>selected="selected"<?php endif;?>><?= $data['name']; ?></option>
                <?php endforeach;?>
            <?php endif;?>
        </select>
        <label id="two" for="name">是否推荐：</label>
        <select id="SearchForm_type" class="SearchForm_type" name="Search[type]">
            <option selected="selected" value="">不限制</option>
            <option value="2" <?php if(!empty($search['type']) and $search['type']==2):?>selected="selected"<?php endif;?>>不推荐</option>
            <option value="1" <?php if(!empty($search['type']) and $search['type']==1):?>selected="selected"<?php endif;?>>推荐</option>
        </select>
        <br><br>
        <label for="name">上下架：</label>
        <select id="SearchForm_type" class="SearchForm_type"  name="Search[status]">
            <option selected="selected" value="">不限制</option>
            <option value="2" <?php if(!empty($search['status']) and $search['status']==2):?>selected="selected"<?php endif;?>>下架</option>
            <option value="1" <?php if(!empty($search['status']) and $search['status']==1):?>selected="selected"<?php endif;?>>上架</option>
        </select>
        <label id="two" for="name">限定区域：</label>
        <select id="cate_id" class="SearchForm_type" name="Search[city_id]" style="width: 200px">
            <option selected="selected" value="">不限制</option>
            <?php if(!empty($city_data)) :?>
                <?php foreach($city_data as $city_data) :?>
                    <option value="<?= $city_data['id'];?>" <?php if(!empty($search['city_id']) and $city_data['id']==$search['city_id']):?>selected="selected"<?php endif;?>><?= $city_data['name']; ?></option>
                <?php endforeach;?>
            <?php endif;?>
        </select>
        <label for="name">商品条形码：</label>
        <input id="name" type="text" name="Search[bar_code]" value="<?= empty($search['bar_code']) ? '' : $search['bar_code']?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>

    </form>
</div>
<script>
    $(document).ready(function(){
        $("#cate_id").change(function()
        {
            var cate_id=$(this).val();
            $.get
            (
                "/goods/brand/list?cate_id="+cate_id,
                function(str_json)
                {
                    obj=JSON.parse(str_json);

                    var html_option="<option value=''>选择品牌</option>";
                    var len=obj.length;
                    for(var i=0;i<len;i++)
                    {
                        html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                    }
                    $("#brand_id").html('全部品牌');
                    $("#brand_id").append(html_option);
                }
            );
        });
    });
</script>
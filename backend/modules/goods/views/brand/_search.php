<div class="wide form">
    <form id="search-form" class="well form-inline" action="/goods/brand" method="get">
        <label for="name">所属分类：</label>
        <select id="SearchForm_type" class="SearchForm_type" style="width:100px;height: 34px;" name="Search[cate_id]">
            <option value="">全部</option>
            <?php if(!empty($cate_list)) :?>
                <?php foreach($cate_list as $data) :?>
                    <option value="<?= $data['id'];?>" <?php if(!empty($search['cate_id']) and $data['id']==$search['cate_id']):?>selected="selected"<?php endif;?>><?= $data['name']; ?></option>
                <?php endforeach;?>
            <?php endif;?>
        </select>
        <label for="name">是否有效：</label>
        <select id="SearchForm_type" class="SearchForm_type" style="width:100px;height: 34px;" name="Search[status]">
            <option value="">不限制</option>
            <option value="1" <?php if(!empty($search['status']) and 1==$search['status']):?>selected="selected"<?php endif;?>>无效</option>
            <option value="2" <?php if(!empty($search['status']) and 2==$search['status']):?>selected="selected"<?php endif;?>>有效</option>
        </select>
        <label for="name">品牌名称：</label>
        <input id="name" type="text" name="Search[name]" value="<?= empty($search['name']) ? '' : $search['name']?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/admin/coupons" method="get">
        <label for="name">分类：</label>
        <select id="SearchForm_type" class="SearchForm_type" style="width:200px;height: 34px;" name="type_id">
            <?php if(!empty($type_list)) :?>
                <?php foreach($type_list as $data) :?>
                    <option value="<?= $data['type_id'];?>" <?php if(!empty($type_id) and $data['type_id']==$type_id):?>selected="selected"<?php endif;?>><?= $data['type_name']; ?></option>
                <?php endforeach;?>
            <?php endif;?>
        </select>
        <label for="name">系统：</label>
        <select id="SearchForm_type" class="SearchForm_type" style="width:200px;height: 34px;" name="use_system">
            <option value="1" <?php if(!empty($use_system) and $use_system==1):?>selected="selected"<?php endif;?>>i500</option>
            <option value="2" <?php if(!empty($use_system) and $use_system==2):?>selected="selected"<?php endif;?>>社交平台系统</option>
        </select>
        <label for="name">优惠券名称：</label>
        <input id="name" type="text" name="name" value="<?= empty($name) ? '' : $name?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
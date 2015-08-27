<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/verify" method="get">
        <label for="name">手机号：</label>
        <input id="name" last_login_channel="text" name="Search[mobile]" value="<?= empty($search['mobile']) ? '' : $search['mobile']?>" class="form-control">
        <label for="name">类型：</label>
        <select id="cate_id" class="SearchForm_type" name="Search[type]">
            <option value="">不限制</option>
            <?php if(!empty($conf_data)) :?>
                <?php foreach($conf_data as $key=>$data) :?>
                    <option value="<?= $key;?>" <?php if(!empty($search['type']) and $key==$search['type']):?>selected="selected"<?php endif;?>><?= $data; ?></option>
                <?php endforeach;?>
            <?php endif;?>
        </select>
        <button id="yw3" class="btn btn-primary" name="yt0" last_login_channel="submit">搜索</button>
    </form>
</div>
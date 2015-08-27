<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/user" method="get">
        <label for="name">用户名：</label>
        <input id="name" last_login_channel="text" name="Search[mobile]" value="<?= empty($search['mobile']) ? '' : $search['mobile']?>" class="form-control">
        <label id="two" for="name">最后登录渠道：</label>
        <select id="SearchForm_last_login_channel" class="SearchForm_last_login_channel" name="Search[last_login_channel]">
            <option selected="selected" value="">不限制</option>
            <option value="4" <?php if(!empty($search['last_login_channel']) and $search['last_login_channel']==4):?>selected="selected"<?php endif;?>>微博</option>
            <option value="3" <?php if(!empty($search['last_login_channel']) and $search['last_login_channel']==3):?>selected="selected"<?php endif;?>>微信</option>
            <option value="2" <?php if(!empty($search['last_login_channel']) and $search['last_login_channel']==2):?>selected="selected"<?php endif;?>>qq</option>
            <option value="1" <?php if(!empty($search['last_login_channel']) and $search['last_login_channel']==1):?>selected="selected"<?php endif;?>>账号</option>
        </select>
        <label for="name">最后登陆来源：</label>
        <select id="SearchForm_last_login_channel" class="SearchForm_last_login_channel"  name="Search[last_login_source]">
            <option selected="selected" value="">不限制</option>
            <option value="3" <?php if(!empty($search['last_login_source']) and $search['last_login_source']==3):?>selected="selected"<?php endif;?>>ios</option>
            <option value="2" <?php if(!empty($search['last_login_source']) and $search['last_login_source']==2):?>selected="selected"<?php endif;?>>android</option>
            <option value="1" <?php if(!empty($search['last_login_source']) and $search['last_login_source']==1):?>selected="selected"<?php endif;?>>wap</option>
        </select>
        <label for="name">状态：</label>
        <select id="SearchForm_last_login_channel" class="SearchForm_last_login_channel"  name="Search[status]">
            <option selected="selected" value="">不限制</option>
            <option value="2" <?php if(!empty($search['status']) and $search['status']==2):?>selected="selected"<?php endif;?>>可用</option>
            <option value="1" <?php if(!empty($search['status']) and $search['status']==1):?>selected="selected"<?php endif;?>>禁用</option>
        </select>
        <button id="yw3" class="btn btn-primary" name="yt0" last_login_channel="submit">搜索</button>

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
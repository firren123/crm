<legends  style="fond-size:12px;">
    <legend>
    <ul class="breadcrumb">
        <li>
            <a href="/admin/link/index">后台管理</a>
        </li>
        <?php if(1 == $type):?>
        <li><a href="/admin/link/indexweb"> 商家网站配置</a></li>
        <li class="active">添加/修改商家网站配置</li>
        <?php endif;?>
        <?php if(2 == $type):?>
            <li><a href="/admin/link/userweb"> 用户网站配置</a></li>
            <li class="active">添加/修改用户网站配置</li>
        <?php endif;?>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
    </legend>
</legends>
<a style="margin-bottom:20px;" href="/admin/link/indexweb" class="btn btn-primary" id="yw0">查看网站配置</a>
<form enctype="multipart/form-data" method="post" action="/admin/link/addweb" class="form-horizontal" id="login-form">
    <input type="hidden" value="WHN4cURwaXQaQUADFjsFOzcRVUEOCVw6FEIVNisVOgZ1MBwpCjwhMg==" name="_csrf" id="csrf"><div class="form-group field-shopconfig-free_shipping">
        <label for="shopconfig-free_shipping" class="control-label col-sm-3">免运费金额</label>
        <div class="col-sm-6">
            <input type="text" style="width:200px" name="info[free_shipping]" value="<?php  echo $info['free_shipping'] ?>" class="form-control" id="free_shipping">
            <div class="help-block help-block-error "></div>
        </div>

    </div><div class="form-group field-shopconfig-send_price">
        <label for="shopconfig-send_price" class="control-label col-sm-3">起送费</label>
        <div class="col-sm-6">
            <input type="text" style="width:200px" name="info[send_price]" value="<?php  echo $info['send_price'] ?>" class="form-control" id="send_price">
            <div class="help-block help-block-error "></div>
        </div>

    </div><div class="form-group field-shopconfig-freight">
        <label for="shopconfig-freight" class="control-label col-sm-3">运费</label>
        <div class="col-sm-6">
            <input type="text" style="width:200px" name="info[freight]"  class="form-control" id="freight" value="<?=$info['freight']?>">
            <div class="help-block help-block-error "></div>
        </div>

    </div>
    <?php if($type == 1):?>
    <div class="form-group field-shopconfig-freight">
        <label for="shopconfig-freight" class="control-label col-sm-3">设置最大值</label>
        <div class="col-sm-6">
            <input type="text" style="width:200px" name="info[community_num]" value="<?php  echo $info['community_num'] ?>" class="form-control" id="community_num">
            <div class="help-block help-block-error "></div>
        </div>

    </div>
    <div class="form-group field-coupontype-used_status required">
        <label for="coupontype-city_id" class="control-label col-sm-3">限定区域</label>
        <div class="col-sm-6">
            <label style="float: left;height: 34px ;line-height: 34px">分公司:   </label>
            <select style="width: 150px;float: left;" name="bc_id" class="form-control" id="bc_id">
                <?php if(isset($info['id'])):?><option value="0">--请选择--</option><?php endif;?>
                <?php foreach($city as $city_data){?>
                   <option value="<?=$city_data['id'] ?>" <?php if($branch['id']==$city_data['id']): ?>selected="selected"<?php endif;?>><?=$city_data['name'] ?></option>
                <?php }?>
            </select>
            <label style="float: left;height: 34px ;line-height: 34px">城市:   </label>
            <select style="width: 150px;float: left;" name="info[bc_id]" class="form-control" id="city_id">
                <?php foreach($city_name_arr as $value):?>
                    <option value="<?=$value['id']?>" <?php if($value['id'] == $city_name['id']):?>selected="selected" <?php endif;?>><?=$value['name'] ?>
                <?php endforeach;?>
            </select>

        </div>
        <div style="color: red;" class="help-block help-block-error "></div>
    </div>

    <div class="form-group field-shopconfig-price_limit">
        <label for="shopconfig-price_limit" class="control-label col-sm-3">是否限制售价</label>
        <div class="col-sm-6">
           <div id="shopconfig-price_limit"><div class="radio"><label><input type="radio" value="1" name="info[price_limit]"  <?php if($info['price_limit']==0):?> checked <?php endif;?>> 不限制</label></div>
                <div class="radio"><label><input type="radio" value="0"  name="info[price_limit]" <?php if($info['price_limit']==1):?> checked <?php endif;?> >限制</label></div></div>
            <div class="help-block help-block-error "></div>
        </div>
    </div>
    <?php endif;?>
    <input type="hidden" id="id"  name="info[id]" value="<?php if(isset($info['id'])): ?><?=$info['id'] ?><?php endif;?><?php if(!(isset($info['id']))): ?>0<?php endif;?>">
<input type="hidden" value="<?=$type ?>" name="type">
    <div class="form-actions">
        <button class="btn btn-primary" type="button" id="sub">提交</button>      <?php if($type == 1):?>  <a href="/admin/link/indexweb" class="btn cancelBtn">返回</a><?php endif?> <?php if($type == 2):?>  <a href="/admin/link/userweb" class="btn cancelBtn">返回</a><?php endif?>
    </div>
</form>
<script  type="text/javascript">
    $(function(){
        <?php if($type == 1):?>
       $('#bc_id').change(function(){
               var id=$(this).val();
           $('#city_id').empty().append('<option>加载中...</option>');
           $.ajax({
               url : "/admin/link/ajax",
               type : 'post',
               data : {'id': id,'_csrf': $('#csrf').val()},
               dataType : 'JSON',
               success : function(result){
                   var html = '';
                  for(i in result){
                    html+='<option value="'+i+'">'+result[i]+'</option>'
                  }
                  $('#city_id').empty().append(html);
               }
           })
       })
        <?php endif;?>
        $('#sub').click(function(){
            if($.trim($('#free_shipping').val())==''){alert('免运费金额不能为空');return false;}
            if($.trim($('#send_price').val())==''){alert('起送费不能为空');return false;}
            if($.trim($('#freight').val())==''){alert('运费不能为空');return false;}
            <?php if($type == 1):?>
            if($.trim($('#community_num').val()) == ''){alert('请设置最大值');return false;}
            if($.trim($('#city_id').val()) == 0){alert('城市不能为空');return false;}
            <?php endif;?>

            <?php if($type == 2):?>
            $('#login-form').submit();
            <?php endif;?>

            <?php if($type == 1):?>
            $.ajax({
                url : "/admin/link/check",
                data : {'bc_id':$('#city_id').val(),'id':$('#id').val(),'csrf': $('#csrf').val() },
                type : 'post',
                dataType : 'json',
                success  : function(result){
                    if(0 == result.status){
                        alert(result.message);
                        window.location.reload();
                    }else{
                        $('#login-form').submit();
                    }
                }
        })
            <?php endif;?>
        })

    })
   </script>
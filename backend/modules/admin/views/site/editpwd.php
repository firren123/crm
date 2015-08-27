<?php
$this->title = "密码修改";
?>
<div class="warpB fr">
    <div class="compileForm">
        <div class="passwardMain">
            <div class="supplierBox">
                <dl class="formBox">
                    <dt class="formBoxDt">名称：<?php echo  Yii::$app->user->identity->name;
                        ?></dt>
                </dl>
                <dl class="formBox">
                    <dt class="formBoxDt">旧密码：</dt>
                    <dd class="formBoxDd">
                        <p class="txtF">
                            <input type="password" id="old_pwd" maxlength="18">
                        </p>
                    </dd>
                </dl>
                <dl class="formBox">
                    <dt class="formBoxDt">新密码：</dt>
                    <dd class="formBoxDd">
                        <p class="txtF">
                            <input type="password" maxlength="18"  id="new_pwd"><span style="color: #FE0102">注:6到18位的数字或字母组合</span>
                        </p>
                    </dd>
                </dl>
                <dl class="formBox">
                    <dt class="formBoxDt">确认新密码：</dt>
                    <dd class="formBoxDd">
                        <p class="txtF">
                            <input type="password"  id="res_new_pwd" maxlength="18">
                        </p>
                    </dd>
                </dl>
                <div class="merchantBtn">
                    <input id="user_id" type="hidden" value="<?php echo Yii::$app->user->identity->id;
                    ?>">
                    <input class="btn btn-primary submit-news sub_loading" type="button" id="sub" value="提交">
                    <a class="btn btn-primary submit-news option_news" href="/admin/site/pwd">重置</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript">

    $(function(){
        $('#sub').click(function(){
            var pwd=/^[0-9a-zA-Z]{6,18}$/;
            var res_new_pwd= $.trim($('#res_new_pwd').val());
            var new_pwd=$.trim($('#new_pwd').val());
            var old_pwd=$.trim($('#old_pwd').val());
            var id=$('#user_id').val();
            if('' == $.trim(old_pwd)){
               alert('旧密码不能为空');
                return false;
            }
            if(new_pwd.match(pwd) == null){
               alert('新密码必须是6到18位数字或字母组合');
                return false;
            }
            if(res_new_pwd!=new_pwd){
              alert('两次输入新密码不一样');
                return false;
            }
            $.ajax({
                url : "/admin/site/editpwd",
                type : 'POST',
                data : {'old_pwd': old_pwd,'new_pwd':new_pwd,'id' : id},
                dataType : 'JSON',
                success : function($result){
                    if(1 == $result.status){
                      alert($result.message);
                        window.location.href="index";
                    }else if(0 == $result.status){
                       alert($result.message);
                    }else if(-1 == $result.status){
                        alert($result.message);
                    }
                }
            })
        })
    })
</script>
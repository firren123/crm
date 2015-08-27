/**
 * 资讯管理
 * @author    linxinliang <linxinliang@iyangpin.com>
 */
var news;
news = {
    //添加资讯分类'
    'addCategory':function (fid) {
        var str = '<div style="float: left;padding-left:0px;"><div class="input-group">';
        str += '<span class="input-group-addon" id="sizing-addon1">分类名称</span>';
        str += '<input type="text" id="cate_name_dialog" class="form-control" placeholder="分类名称">';
        str += '</div></div>';
        str += '<div style="float: left;padding-left:5px;"><a class="btn btn-primary submit-news add-category_2" onclick="news.doAddCategory('+fid+',2);" href="javascript:;">添加分类</a><a class="btn btn-primary submit-news sub_loading_2" style="display: none;" href="javascript:;">提交中...</a></div>';
        var d = dialog({
            title: '新增分类',
            content:str,
            lock:true
        });
        d.showModal();
    },
    //执行添加资讯分类
    'doAddCategory':function (pid,type) {
        var cate_name;
        var parent_id;
        if (type=='1'){  //列表中添加
            cate_name = $("#cate_name").val();
            parent_id = $("#parent_id").val();
        } else if(type=='2') {  //弹窗中添加
            cate_name = $("#cate_name_dialog").val();
            parent_id = pid;
        }
        if (cate_name=='') {
            gf.alert('请输入分类名称');
            return false;
        }
        if (type=='2') {
            if (parent_id==0) {
                gf.alert('请选择父类');
                return false;
            }
        }
        var server_url      = $('#base_url').val()+'admin/news/add-category';
        var token           = $('#token').val();
        $.ajax(
            {
                type: "POST",
                url : server_url,
                data: {
                    'cate_name':cate_name,
                    'parent_id':parent_id,
                    '_csrf':token
                },
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                    $(".add-category_"+type).hide();
                    $(".sub_loading_"+type).show();
                },
                success: function (result) {
                    if (result['code'] === 'ok') {
                        /** 成功 **/
                        window.location.reload();
                    } else {
                        /** 失败 **/
                        gf.alert(result['msg']);
                    }
                    $(".add-category_"+type).show();
                    $(".sub_loading_"+type).hide();
                }
            });
    },
    //执行编辑的方法
    'doEditCategory':function () {
        var server_url  = $('#base_url').val()+'admin/news/do-edit-category';
        var token       = $("#token").val();
        var id          = $("#id").val();
        var name        = $("#name").val();
        if (name=='') {
            gf.alert('请输入名称');
            return false;
        }
        var parent_id   = $("#parent_id").val();
        var description = $("#description").val();
        var sort        = $("#sort").val();
        $.ajax(
            {
                type: "POST",
                url : server_url,
                data: {
                    'id':id,
                    'name':name,
                    'parent_id':parent_id,
                    'description':description,
                    'sort':sort,
                    '_csrf':token
                },
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                    $(".sub_category").hide();
                    $(".sub_loading").show();
                },
                success: function (result) {
                    if (result['code'] === 'ok') {
                        /** 成功 **/
                        window.location.href="/admin/news/category";
                    } else {
                        /** 失败 **/
                        gf.alert(result['msg']);
                    }
                    $(".sub_category").show();
                    $(".sub_loading").hide();
                }
            });

    },
    //删除资讯
    'delNews':function (id) {
        var server_url  = $('#base_url').val()+'admin/news/del';
        $.ajax(
            {
                type: "GET",
                url : server_url,
                data: {'id':id},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                },
                success: function (result) {
                    if (result['code'] === 'ok') {
                        /** 成功 **/
                        window.location.reload();
                    } else {
                        /** 失败 **/
                        gf.alert(result['msg']);
                    }
                }
            });
    },
    //删除资讯分类
    'delNewsCategory':function (id) {
        var server_url  = $('#base_url').val()+'admin/news/del-category';
        $.ajax(
            {
                type: "GET",
                url : server_url,
                data: {'id':id},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                },
                success: function (result) {
                    if (result['code'] === 'ok') {
                        /** 成功 **/
                        window.location.reload();
                    } else {
                        /** 失败 **/
                        gf.alert(result['msg']);
                    }
                }
            });
    },
    /** 保存资讯 **/
    'submitNews':function () {
        var act = $("#act").val();
        var server_url      = $('#base_url').val()+'admin/news/news-operation';
        var post_data;
        var token           = $('#token').val();
        var bc_id           = $('#bc_id').val();
        var title           = $.trim($('#title').val());
        var category_id     = $('#category_id').val();
        var author          = $.trim($('#author').val());
        var content         = $.trim(UE.getEditor('editor').getPlainTxt());
        var seo_title       = $.trim($('#seo_title').val());
        var seo_keywords    = $.trim($('#seo_keywords').val());
        var seo_description = $.trim($('#seo_description').val());
        if (bc_id == '' || bc_id == 0 || bc_id == null) {
            gf.alert('请选择分公司');
            return false;
        }
        if (title=='') {
            gf.alert('请输入标题');
            return false;
        }
        if (category_id =='' || category_id == 0) {
            gf.alert('请选择分类');
            return false;
        }
        if (author=='') {
            gf.alert('请输入作者');
            return false;
        }
        if (content=='') {
            gf.alert('请输入内容');
            return false;
        }
        if (act=='edit') {
            var status = $.trim($('#status').val());
            if (status!='1' && status!='2') {
                gf.alert('请选择合法的状态');
                return false;
            }
        }
        if (seo_title=='') {
            gf.alert('请输入SEO标题');
            return false;
        }
        if (seo_keywords=='') {
            gf.alert('请输入SEO关键字');
            return false;
        }
        if (seo_description=='') {
            gf.alert('请输入SEO描述');
            return false;
        }
        if (act != 'add' && act != 'edit') {
            gf.alert('非法请求');
            return false;
        }
        if (act == 'add') {
            post_data = {
                'act':'add',
                'title':title,
                'category_id':category_id,
                'bc_id':bc_id,
                'author':author,
                'content':content,
                'seo_title':seo_title,
                'seo_keywords':seo_keywords,
                'seo_description':seo_keywords,
                '_csrf':token
            }
        } else if (act == 'edit') {
            var id = $('#news_id').val();
            post_data = {
                'act':'edit',
                'id':id,
                'title':title,
                'category_id':category_id,
                'bc_id':bc_id,
                'author':author,
                'content':content,
                'status':status,
                'seo_title':seo_title,
                'seo_keywords':seo_keywords,
                'seo_description':seo_keywords,
                '_csrf':token
            }
        } else {
            return false;
        }
        $.ajax(
            {
                type: "POST",
                url : server_url,
                data: post_data,
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                    $(".option_news").hide();
                    $(".sub_loading").show();
                },
                success: function (result) {
                    if (result['code'] === 'ok') {
                        /** 成功 **/
                        if (act == 'add') {
                            //跳转到列表页
                            window.location.href = '/admin/news';
                        } else {
                            //跳转到上一页 并刷新
                            self.location=document.referrer;
                        }
                    } else {
                        /** 失败 **/
                        gf.alert(result['msg']);
                    }
                    $(".option_news").show();
                    $(".sub_loading").hide();
                }
            });
    },
    //获取分类列表
    'getCategory' : function() {
        var category_id = $("#info_category_id").val();
        var selected = '';
        $.ajax(
            {
                type: "GET",
                url : $('#base_url').val()+'admin/news/get-category',
                data: {},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                },
                success: function (result) {
                    if (result['code'] === 'ok') {
                        /** 成功 **/
                        if(result.data.length){
                            var op_html = "";
                            for(var i=0;i<result.data.length;i++){
                                if (category_id==result.data[i].id) {
                                    selected = "selected='selected'"
                                } else {
                                    selected = '';
                                }
                                op_html += "<option "+selected+" value="+result.data[i].id+">"+result.data[i].fullname+"</option>";
                            }
                            $(".category_option").append(op_html);
                        }
                    }
                }
            });
    },
    //获取分公司列表
    'getBranchCompany' : function() {
        var bc_id = $("#info_bc_id").val();
        var selected = '';
        $.ajax(
            {
                type: "GET",
                url : $('#base_url').val()+'admin/news/get-branch-company',
                data: {},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                },
                success: function (result) {
                    if (result['code'] === 'ok') {
                        /** 成功 **/
                        if(result.data.length){
                            var bc_op_html = "";
                            for(var i=0;i<result.data.length;i++){
                                if (bc_id==result.data[i].id) {
                                    selected = "selected='selected'"
                                } else {
                                    selected = '';
                                }
                                bc_op_html += "<option "+selected+" value="+result.data[i].id+">"+result.data[i].name+"</option>";
                            }
                            $(".bc_option").append(bc_op_html);
                        } else {
                            $(".bc_option").append('<option value="0">暂无分公司</option>');
                        }
                    }
                }
            });
    }
};
$(document).ready(function(){
    var act = $("#act").val();
    var base_url = $('#base_url').val();
    if(act=='add' || act=='edit'){ //编辑页面
        /** 初始化编辑器 **/
        UE.getEditor('editor');
        UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
        UE.Editor.prototype.getActionUrl = function(action) {
            if (action == 'uploadimage' || action == 'uploadscrawl') {
                return base_url+'admin/upload/upload-img';
            } else if (action == 'uploadvideo') {
                return '';
            } else {
                return this._bkGetActionUrl.call(this, action);
            }
        };
        /** 获取分类 **/
        news.getCategory();
        /** 获取分公司 **/
        news.getBranchCompany();
    }
    if(act=='add-category' || act=='edit_category') { //添加分类页面
        /** 获取分类 **/
        news.getCategory();
    }
    if(act=='news_list') {
        /** 获取分类 **/
        news.getCategory();
    }
    //搜索文件
    $(".btn_search").click(function(){
        var title       = $("#title").val();
        var category_id = $("#category_id").val();
        window.location.href = '/admin/news/index?title='+title+'&category_id='+category_id;
    });
    //删除文章
    $(".del").click(function(){
        var id = $(this).attr('data_id');
        gf.confirm('确定要删除吗？','news.delNews('+id+')');
    });
    //添加分类页面
    $(".add_son").click(function(){
        var fid = $(this).attr('data_id');
        news.addCategory(fid);
    });
    //添加分类
    $(".add-category_1").click(function(){
        news.doAddCategory(0,1);
    });
    //编辑分类
    $(".sub_category").click(function(){
        news.doEditCategory();
    });
    //删除分类
    $(".del_category").click(function(){
        var id = $(this).attr('data_id');
        gf.confirm('确定要删除吗？','news.delNewsCategory('+id+')');
    });
    //回车事件
    $("body").keydown(function() {
        if (event.keyCode == "13") { //keyCode=13是回车键
            $(".btn_search").click();
        }
    });
});
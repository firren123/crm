//author : zy
//create time : 15/5/26

//require jq 1.7+
//require layer



function Z_GLOBAL_POPUP()
{
    this.config_auto_reload_interval=120000;//毫秒
    this.config_show_debug=1;//正式0

    this.runtime_global_stop=0;
    this.runtime_timer=0;
    this.runtime_layer_index=0;
}




Z_GLOBAL_POPUP.prototype.start=function()
{
    if(this.runtime_global_stop==1){
        clearInterval(this.runtime_timer);
    }
    else{
        var self=this;
        this.run();
        this.runtime_timer=setInterval(function(){self.run();},this.config_auto_reload_interval);
    }
};

Z_GLOBAL_POPUP.prototype.run=function()
{
    if(this.runtime_global_stop==1){
        clearInterval(this.runtime_timer);
    }
    else{
        this.getdata();
    }
};

Z_GLOBAL_POPUP.prototype.stop=function()
{
    this.log("stop() "+this.get_time());
    clearInterval(this.runtime_timer);
    this.runtime_global_stop=1;
};

Z_GLOBAL_POPUP.prototype.popup=function(obj)
{
    this.log("popup() "+this.get_time());

    layer.close(this.runtime_layer_index);

    if((obj.userorder!==undefined && obj.userorder=='have') || (obj.shoporder!==undefined && obj.shoporder=='have')){
        var html='';
        var height=0;

        html+='<div class="zjs_global_popup" style="text-align:center;background-color:#1EB4E6;font-size:16px;">';
        if(obj.userorder!==undefined && obj.userorder=='have'){
            html+='<div style="height:40px;line-height:40px;"><a href="/user/userorder/index" style="color:#fff;">有未确认的用户订单</a></div>';
            height+=40;
        }
        if(obj.shoporder!==undefined && obj.shoporder=='have'){
            html+='<div style="height:40px;line-height:40px;border-top:1px solid #fff;"><a href="/shop/shoporder/index" style="color:#fff;">有未确认的商家订单</a></div>';
            height+=40;
        }
        html+='</div>';

        this.runtime_layer_index=layer.open({
            type: 1,
            title: false,
            closeBtn: false,
            shade: false,
            area: ['220px', height+'px'],
            offset: 'rb',
            shift: 2,
            content: html
        });
    }
};


Z_GLOBAL_POPUP.prototype.getdata=function()
{
    var self=this;
    $.ajax
    ({
        "url":"/admin/common/notify",
        "dataType":"json",
        "success":function(obj,textStatus, jqXHR){
            self.log("ajax-success:");
            self.log(obj);
            self.log(textStatus);
            self.log(jqXHR);
            self.popup(obj);
        },
        "error":function(XMLHttpRequest, textStatus, errorThrown){
            self.log("ajax-error:");
            self.log(XMLHttpRequest);
            self.log(textStatus);
            self.log(errorThrown);
            self.stop();
        }
    });
};



//调试用
Z_GLOBAL_POPUP.prototype.d=function()
{
    this.log("debug() "+this.get_time());
    this.config_show_debug=1;
};
Z_GLOBAL_POPUP.prototype.get_time=function()
{
    var d=new Date();
    return d.getFullYear()+'-'+this.prefix0(d.getMonth()+1)+'-'+this.prefix0(d.getDate())+' '+d.getHours()+':'+this.prefix0(d.getMinutes())+':'+this.prefix0(d.getSeconds());
};
Z_GLOBAL_POPUP.prototype.prefix0=function(num)
{
    if(num<10){return '0'+num;}
    else{return num;}
};
Z_GLOBAL_POPUP.prototype.log=function(str)
{
    if(this.config_show_debug==1){
        this.z_log(str);
    }
    else{
        //nothing
    }
};
Z_GLOBAL_POPUP.prototype.z_log=function(str)
{
    try {
        console.log(str);
    } catch (e) {
        //
    }
};




$(function()
{
    $(document).on("click",".zjs_global_popup a",function()
    {
        var index_loading = layer.load(1, {
            shade: [0.3,'#fff'] //0.1透明度的白色背景
        });
        zzzz.stop();
        layer.close(zzzz.runtime_layer_index);
    });

});


var zzzz=new Z_GLOBAL_POPUP();
zzzz.config_auto_reload_interval=120000;
zzzz.config_show_debug=0;

zzzz.start();








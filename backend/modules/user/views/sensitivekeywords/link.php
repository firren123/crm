<link href="/css/cart.css" type="text/css" rel="stylesheet"/>
<link href="css/globalm.css" type="text/css" rel="stylesheet"/>
<div class="cart warp">
    <div class="emptymain warp">
        <div style="float: left;padding:20px 10px 720px 140px;"><img src="/images/winblue.gif"></div>
        <div class="emptybox" style="border: solid 1px #4e6a81;width: 670px;height: 100px;margin: 111px;padding: 30px 40px">
            <p><span style="font-size: 18px;">此敏感词你已添加过</span><br /><span id="mil"></span></p>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script>
    function f1() {
        var info = $("#mil").text();
        info--;
        if(info < 1){
            window.location.href="/user/sensitivekeywords/add";
        }else{
            $("#mil").text(info);
            window.setTimeout("f1()",1000);
        }
    }
    $(function(){
        f1();
    })

</script>
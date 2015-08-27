/**
 * Created by Administrator on 2015/6/10 0010.
 */
$(document).ready(function(){
    $("#branch_id").change(function()
    {
        var branch_id=$(this).val();
        $.get
        (
            "/shop/rebate/city?branch_id="+branch_id,
            function(str_json)
            {
                obj=JSON.parse(str_json);

                var html_option="<option value=''>选择城市</option>";
                var len=obj.length;
                for(var i=0;i<len;i++)
                {
                    html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                }
                $("#city_id").html('全部城市');
                $("#city_id").append(html_option);
            }
        );
    });
});

/**
 * Created by lichenjun on 15/9/7.
 */
$(document).ajaxStart(function(){
    $("#submit_button").addClass('inoperable').attr("disabled", true);
}).ajaxStop(function(){
    $("#submit_button").removeClass('inoperable').attr("disabled", false);
});
var _csrf = $("input[name='_csrf']").val();
var uploader1 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker1',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

uploader1.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('img').attr("src",url);
    $('#images1').val(data.url);
});

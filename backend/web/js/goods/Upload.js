/**
 * Created by lichenjun on 15/6/2.
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

var uploader2 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker2',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader3 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker3',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader4 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker4',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader5 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker5',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});


uploader1.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images1').val(data.url);
    $('#filePicker1_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader2.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images2').val(data.url);
    $('#filePicker2_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader3.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images3').val(data.url);
    $('#filePicker3_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader4.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images4').val(data.url);
    $('#filePicker4_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader5.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images5').val(data.url);
    $('#filePicker5_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

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
var uploader6 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker6',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader7 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker7',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader8 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker8',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader9 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker9',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader10 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker10',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader11 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker11',
    accept: {title: 'Images', extensions: 'gif,jpg,jpeg,bmp,png', mimeTypes: 'image/*'},
    formData: {'_csrf' : _csrf}
});

var uploader12 = WebUploader.create({
    auto: true,
    swf: "/js/webuploader/uploadify.swf",
    server: "/public/upload",
    pick: '#filePicker12',
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

uploader6.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images6').val(data.url);
    $('#filePicker6_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader7.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images7').val(data.url);
    $('#filePicker7_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader8.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images8').val(data.url);
    $('#filePicker8_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader9.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images9').val(data.url);
    $('#filePicker9_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader10.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images10').val(data.url);
    $('#filePicker10_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader11.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images11').val(data.url);
    $('#filePicker11_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

uploader12.on( 'uploadSuccess', function( file, data ) {
    var BaseUrl = $("#img_url").val();
    var url = BaseUrl+data.url;
    $('#images12').val(data.url);
    $('#filePicker12_img').html('<img src="'+url+'" style="width:90px;height:90px;" />');
});

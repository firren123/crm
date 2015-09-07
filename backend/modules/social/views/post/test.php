<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link href="/js/social/jcrop/css/jquery.Jcrop.min.css" rel="stylesheet" />
    <script src="/js/social/jcrop/js/jquery.Jcrop.min.js"></script>
    <script type="text/javascript" src="/js/social/jcrop/imageCropperUpload.js?_<?= Yii::$app->params['jsVersion'];?>"></script>

    <script>
        $(function () {

            var btn = $("#Button1");

            btn.cropperUpload({
                url: "post.php",
                fileSuffixs: ["jpg", "png", "bmp"],
                errorText: "{0}",
                onComplete: function (msg) {
                    $("#testimg").attr("src", msg);
                },
                cropperParam: {//Jcrop参数设置，除onChange和onSelect不要使用，其他属性都可用
                    maxSize: [500, 500],//不要小于50，如maxSize:[40,24]
                    minSize: [400, 400],//不要小于50，如minSize:[40,24]
                    bgColor: "black",
                    bgOpacity: .4,
                    allowResize: false,
                    allowSelect: false,
                    animationDelay:150
                },
                perviewImageElementId: "fileList", //设置预览图片的元素id
                perviewImgStyle: { width: '700px', height: '500px', border: '1px solid #ebebeb' }//设置预览图片的样式
            });

            var upload = btn.data("uploadFileData");

            $("#files").click(function () {
                upload.submitUpload();
            });
        });
    </script>
</head>
<body>
<div style="width: 400px; height: 300px; float:left">
    <input id="Button1" type="button" value="选择文件" />
    <input id="files" type="button" value="上传截图" />
    <div id="fileList" style="margin-top: 10px; padding-top:10px; border-top:1px solid #C0C0C0;font-size: 13px; width:400px">

    </div>
</div>

<div id="testdiv" style="padding-top: 580px">
    <img alt="" src="" id="testimg"/>
</div>
</body>
</html>
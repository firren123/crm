<?php

?><!DOCTYPE html>
<html lang="en">
<head>
    <title>Live Cropping Demo</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>

    <link rel="stylesheet" type="text/css" href="/js/webuploader/webuploader.css">
    <script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
    <link href="/js/social/jcrop/css/dialog.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="/js/social/jcrop/js/jquery.Jcrop.min.js"></script>
    <link rel="stylesheet" href="/js/social/jcrop/demos/demo_files/main.css" type="text/css"/>
    <link rel="stylesheet" href="/js/social/jcrop/demos/demo_files/demos.css" type="text/css"/>
    <link rel="stylesheet" href="/js/social/jcrop/css/jquery.Jcrop.min.css" type="text/css"/>
    <script type="text/javascript" src="/js/webuploader/webuploader.js"></script>

    <script type="text/javascript">
        $(function () {

            $('#cropbox').Jcrop({
                aspectRatio: 1,
                onSelect: updateCoords
            });

        });
        function updateCoords(c) {
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        }
        ;
        function checkCoords() {
            if (parseInt($('#w').val())) return true;
            alert('Please select a crop region then press submit.');
            return false;
        }
        ;

    </script>
    <style type="text/css">
        #target {
            background-color: #ccc;
            width: 500px;
            height: 500px;
            font-size: 24px;
            display: block;
        }
    </style>
    <style>
        .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
            text-align: left;
        }
        .compileForm .imgListForm{padding-top:10px; height:117px;}
        ul li{
            float: left;

        }
        ul{list-style: none;}
        .imgListForm li{width:92px;min-height:117px; border:none; background:#fff;}
        .imgListForm a,.imgListForm span{display:block;}
        .imgListForm a{width:90px; height:90px; text-align:center; border:1px solid #dfdfdf; background:#f5f5f5;}
        .imgListForm span{color:#666; line-height:25px; cursor:pointer;text-align: center;}
    </style>
</head>
<body>
<div class="container">
    <input type="hidden" value="<?=\Yii::$app->params['imgHost'] ?>" id="img_url"/>
    <!-- This is the image we're attaching Jcrop to -->
    <?php if($model->forum_img){ ?>
        <img src="<?=  \Yii::$app->params['imgHost'].$model->forum_img; ?>" id="cropbox" />
    <?php }else { ?>
        <img src="/images/05_mid.jpg" max-width="500px" max-height="500px" id="cropbox" />
    <?php } ?>
    <div class="col-sm-6">
        <ul class="imgList imgListForm">
            <!--当上传图片后<span class="txt">上传</span>去掉-->
            <li>
                <span class="txt" id="filePicker1">上传</span>

            </li>

        </ul>
        <div class="help-block help-block-error "></div>
    </div>
    <!-- This is the form that our event handler fills -->
        <input type="hidden" id="x" name="x"/>
        <input type="hidden" id="y" name="y"/>
        <input type="hidden" id="w" name="w"/>
        <input type="hidden" id="h" name="h"/>
        <input type="hidden" value="<?= $model->forum_img;?>" name="forum_img" id="images1" />
        <input type="submit" value="提交" id="checkCoords" class="btn btn-large btn-inverse"/>
    </form>
</div>
</body>
<script src="/js/social/upload.js?_src=<?php echo  rand(1,10);?>"></script>
</html>
<script type="text/javascript">
    $(function(){
        $("#checkCoords").click(function(){
            if (!parseInt($('#w').val())){
                alert('请选择要剪辑的区域.');
                return false;
            }
            var x = $('#x').val();
            var y = $('#y').val();
            var w = $('#w').val();
            var h = $('#h').val();
            var photo = $('#images1').val();
            $(this).val('剪辑中...');
            $(this).unbind();
            $.post('/public/do-photo',{"x":x,"y":y,"w":w,"h":h,"photo":photo},function(result){
                var dialog = top.dialog.get(window);
                if (result !=0 ) {
                    parent.addImg(result);
                    dialog.close(result).remove();
                } else {
                    //dialog.close().remove();
                    window.location.reload();
                }
            })
        })
    })
</script>
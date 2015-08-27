<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  list.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/23 下午4:29
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = "用户订单列表";

?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<?= $this->registerJsFile("@web/js/shoporder.js"); ?>


<div class="wide form">
    <form id="search-form" class="well form-inline" action="/financial/userorder/list" method="get">


        <label for="order_sn">订单号：</label>
        <input id="order_sn" type="text" size="31" name="order_sn" value="<?php isset($order_sn) ? $order_sn : ''; ?>"
               class="form-control"/>
        <!--        <label for="name">用户名：</label>-->
        <!--        <input id="name" type="text" name="username" value="-->
        <? //= isset($username)?$username:'';?><!--" class="form-control">-->
        <br/><br/>
        <label for="start_time">添加开始时间：</label>
        <input id="start_time" type="text" id="start_time" name="start_time"
               onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if (isset($start_time)) {
            echo $start_time;
        }; ?>" class="form-control">
        <label for="end_time">添加结束时间：</label>
        <input id="end_time" type="text" name="end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})"
               value="<?php if (isset($end_time)) {
                   echo $end_time;
               }; ?>" class="form-control">
        <button id="sub" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>

<legends style="fond-size:12px;">
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th colspan="2">订单ID</th>
                    <th colspan="2">类型</th>
                    <th colspan="2">添加时间</th>
                    <th colspan="2">金额</th>
                    <th colspan="2">优惠券金额</th>
                    <th colspan="2">状态</th>
                    <th colspan="2">审核状态</th>
                    <th colspan="2">退款成功时间</th>
                    <th colspan="4">操作</th>
                </tr>
                <?php if (empty($data)) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                } else {
                    foreach ($data as $item) {
                        ?>
                        <tr>
                            <td><?=$item['id']?></td>
                            <td colspan="2"><a
                                    href="<?= '/user/userorder/detail?order_sn=' . $item['order_sn']; ?>"><?= $item['order_sn']; ?></a>
                            </td>
                            <td colspan="2">
                                <?php if (isset($item['type'])) {
                                    echo $item['type'] == 2 ? '部分退' : '整单退';
                                } ?>
                            </td>
                            <td colspan="2"><?= $item['add_time']; ?></td>
                            <td colspan="2"><?= $item['money']; ?></td>
                            <td colspan="2"><?= $item['code_money']; ?></td>
                            <td colspan="2">
                                <?php echo isset($refund_status[$item['status']]) ? $refund_status[$item['status']] : ''; ?></td>
                            <td colspan="2"><?php if (isset($item['audit_status'])) {
                                    echo $audit_status[$item['audit_status']];
                                } else {
                                    echo '未知';
                                };?></td>
                            <td colspan="2"><?= $item['refund_time']; ?></td>

                            <td colspan="4">
                                <a href="<?= '/user/userorder/detail?order_sn=' . $item['order_sn']; ?>">详情</a>
                                <?php if ($role['fin'] == 1) {
                                    if ($item['audit_status'] == 1) { ?>
                                        &nbsp;|&nbsp;<a href="javascript:;" class="financial">财务审核</a>
                                    <?php } else if ($item['audit_status'] == 2) {
                                        echo " &nbsp; 已审核";
                                    } else{
                                        echo " &nbsp; 部门审核";
                                    }
                                }
                                if ($role['dep'] == 1) {
                                    if ($item['audit_status'] == 0) { ?>
                                        &nbsp;|&nbsp;<a href="javascript:;" class="depart">部门审核</a>
                                    <?php } elseif ($item['audit_status'] == 1) {
                                        echo " &nbsp; 已审核";
                                    }elseif($item['audit_status'] == 2){
                                        echo " &nbsp; 财务已审";
                                    }
                                } ?>
                                <input type="hidden" class="order_id" value="<?= $item['id']; ?>"/>
                            </td>
                        </tr>
                    <?php }
                } ?>

                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</legends>
<script>
    $(function () {
        $("#sub").click(function () {
            var sn = $("#order_sn").val();
//            console.log(sn);
//            console.log(sn.length);
            if (sn.length > 0 && sn.length < 6) {
                alert('订单号搜索不得少于6位末尾订单号');
                return false;
            }
        })


        var html = '';
        html += "<table class='waitlist table table-bordered table-hover'>" +
        "<tr><th>审核:</th><td>" +
        "<input type='radio'  value='1' />审核通过" +
        "<input type='radio' value='3' />审核驳回" +
        "</td></tr>" +
        "<tr><th>简述:</th><td><textarea  type='text' id='sketch' value=''></textarea></td></tr></table>";

        $(".depart").click(function () {
            var id = $(this).parent().closest("tr").find(".order_id").val();
            var exam = dialog({
                title: '退款审核',
                width: '40em',
                content: html,
                okValue: '确定',
                lock: true,
                ok: function () {
                    var agree = $("input[type='radio']:checked").val();
                    var sketch = $("#sketch").val();
                    $.getJSON('/financial/userorder/audit-up-dep', {
                        'id': id,
                        'status': agree,
                        'remark': sketch
                    }, function (data) {
                        var d = dialog({
                            title: '提示',
                            content: data.date,
                            okValue: '确定',
                            ok: function () {
                                window.location.reload();
                            }
                        });
                        d.show();
                    });
                }

            })
            exam.show();
        })

        var html2 = '';
        html2 += "<table class='waitlist table table-bordered table-hover'>" +
        "<tr><th>审核:</th><td>" +
        "<input type='radio'  value='2' />审核通过" +
        "<input type='radio' value='3' />审核驳回" +
        "</td></tr>" +
        "<tr><th>简述:</th><td><textarea  type='text' id='sketch' value=''></textarea></td></tr></table>";

        $(".financial").click(function () {
            var id = $(this).parent().closest("tr").find(".order_id").val();
            var fina = dialog({
                title: '退款审核',
                width: '40em',
                content: html2,
                okValue: '确定',
                lock: true,
                ok: function () {
                    var agree = $("input[type='radio']:checked").val();
                    var sketch = $("#sketch").val();
                    $.getJSON('/financial/userorder/audit-up-fin', {
                        'id': id,
                        'status': agree,
                        'remark': sketch
                    }, function (data) {
                        var d = dialog({
                            title: '提示',
                            content: data.date,
                            okValue: '确定',
                            ok: function () {
                                window.location.reload();
                            }
                        });
                        d.show();
                    });
                }

            })
            fina.show();
        })
    })

</script>
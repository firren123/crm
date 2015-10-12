<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  detail_log.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/9 下午3:44
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
?>
<table class="table table-bordered table-hover">
    <tr>
        <th colspan="2">ID</th>
        <th colspan="2">状态</th>
        <th colspan="2">备注</th>
        <th colspan="2">时间</th>

    </tr>

    <?php if (empty($withdrawal_list)) {
        echo '<tr><td colspan="30" style="text-align:center;">暂无记录</td></tr>';
    } else {
        foreach ($withdrawal_list as $k => $item) {
            ?>
            <tr>
                <td colspan="2"><?= $k; ?></td>
                <td colspan="2"><?= $item['status']; ?></td>
                <td colspan="2"><?= $item['remark']; ?></td>
                <td colspan="2"><?= $item['create_time']; ?></td>
            </tr>
        <?php }
    } ?>
</table>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>jQuery饼状图比例分布数据显示代码 - 站长素材</title>

    <script type="text/javascript" src="/js/report/jquery.js"></script>
    <script type="text/javascript" src="/js/report/jsapi.js"></script>
    <script type="text/javascript" src="/js/report/corechart.js"></script>
    <script type="text/javascript" src="/js/report/jquery.gvChart-1.0.1.min.js"></script>
    <script type="text/javascript" src="/js/report/jquery.ba-resize.min.js"></script>

    <script type="text/javascript">
        gvChartInit();
        $(document).ready(function(){
            $('#myTable5').gvChart({
                chartType: 'PieChart',
                gvSettings: {
                    vAxis: {title: 'No of players'},
                    hAxis: {title: 'Month'},
                    width: 600,
                    height: 350
                }
            });
        });
    </script>

    <script type="text/javascript">
        gvChartInit();
        $(document).ready(function(){
            $('#myTable1').gvChart({
                chartType: 'PieChart',
                gvSettings: {
                    vAxis: {title: 'No of players'},
                    hAxis: {title: 'Month'},
                    width: 600,
                    height: 350
                }
            });
        });
    </script>

</head>


<body>

<div style="width:600px;margin:0 auto;">
    <table id='myTable5'>
        <thead>
        <tr>
            <th></th>
            <th>水果</th>
            <th>其他</th>
            <th>多余<?php echo $total?></th>


        </tr>
        </thead>
        <tbody>
        <tr>
            <th>1</th>
            <td><?php echo $fruits_total?></td>
            <td><?php echo $total?></td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>

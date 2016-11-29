<?php 
    include("../../config.php");
    
    $page = $_REQUEST['page'];
    if($page=='') $page = time();
    
    $month=date('m',$page);
    $mon=date('F',$page);
    $yy=date('Y',$page);
    $year=date('Y',$page);
    $tt = date('t', mktime(0,0,0,$month,1,$year));
    
    $next = $page + (30*24*60*60);
    $back = $page - (30*24*60*60);

echo <<<eot
    <div class="pagination pagination-small">
        <ul>
            <li><a href="javascript:;" onclick="loadform('includes/dashboard/dashboard_revenue_monthy_revenue.php?page=$back','#monthy_revenue','#monthy_load')"><i class="icon-chevron-left"></i></a></li>
            <li><a href="javascript:;" class='tooltips' data-original-title='Viewing report for $mon-$yy'>$mon, $yy</a></li>
            <li><a href="javascript:;" onclick="loadform('includes/dashboard/dashboard_revenue_monthy_revenue.php?page=$next','#monthy_revenue','#monthy_load')"><i class="icon-chevron-right"></i></a></li>
            <li><span href="javascript:;" class='hide' id='monthy_load'></span></li>
        </ul>
    </div>
    <script language='javascript'>
        $('.tooltips').tooltip();
    </script>
eot;

    $data = array(array());

    for($i=1;$i<=$tt;$i++){
        $data[$i][0] = 0;
        $data[$i][1] = 0;
    }
    $str = "SELECT SUM(`total_revenue`) AS revenue, day(date) AS day FROM rp_revenue_sumary WHERE month(date)=$month AND year(date) = $year GROUP BY date DESC, method";
    $que = $db->query($str);
    while ($row = $que->fetchRow()) {
        $data[$row['day']][0] += $row['revenue'];
    }
?>

<div class="plots"></div>


<script language="javascript">
var Script = function () {

//    flot chart (Sin and Cos)

    var metro = {
        showTooltip: function (x, y, contents) {
            $('<div class="metro_tips">' + contents + '</div>').css( {
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5
            }).appendTo("body").fadeIn(200);
        }

    }

    if (!!$(".plots").offset() ) {
        //var total_register = [];
        var total_revenue = [];
        <?php
            for($i=1;$i<=$tt;$i++){
                //echo "total_register.push([$i, {$data[$i][0]}]);\n";
                echo "total_revenue.push([$i, {$data[$i][0]}]);\n";
            }
        ?>

        // Display the Sin and Cos Functions
        $.plot($(".plots"), [{ label: "Total Revenue", data: total_revenue }],
            {
                colors: ["#DE5900"],
                
                series: {
                    lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true
                    },
                    points: {show: true},
                    shadowSize: 2,
                },

                grid: {
                    hoverable: true,
                    show: true,
                    borderWidth: 0,
                    labelMargin: 12,
                    
                },

                legend: {
                    show: true,
                    margin: [0,-24],
                    noColumns: 0,
                    labelBoxBorderColor: null
                },
                xaxis:{
                    ticks:<?php echo $tt ?>,
                    tickFormatter: function(val, axis) {
                        return val.toFixed(0);
                    }
                }
            });

        // plot tooltip show
        var previousPoint = null;
        $(".plots").bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
                    $(".charts_tooltip").fadeOut("fast").promise().done(function(){
                        $(this).remove();
                    });
                    var x = item.datapoint[0].toFixed(0),
                        y = item.datapoint[1].toFixed(0);
                    metro.showTooltip(item.pageX, item.pageY,item.series.label+" in day " + x + ": " + y);
                }
            }
            else {
                $(".metro_tips").fadeOut("fast").promise().done(function(){
                    $(this).remove();
                });
                previousPoint = null;
            }
        });
    }

}();
</script>
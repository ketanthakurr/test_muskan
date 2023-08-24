<?php

include 'server.php';

function convertDateFormat2($datetime)
{
    $timestamp = strtotime($datetime);
    $formatted_date = date("Y-m-d", $timestamp);
    return $formatted_date;
}

function convertDateFormat($datetime)
{
    $timestamp = strtotime($datetime) * 1000;

    return $timestamp;
}



function getEventData()
{
    $conn = OpenCon();
    $OwnerID = $_SESSION['username'];
    $sql = "SELECT EventDate, Value FROM ap_devicelog WHERE Tag='DE00001' AND InActive = 0";
    $result = $conn->query($sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $date = convertDateFormat($row['EventDate']);
        $value = (int) $row['Value'];

        $data[] = array(
            "date" => $date,
            "value" => $value
        );
    }

    CloseCon($conn);
    return $data;
}

$data = getEventData();
$json_data_line = json_encode($data);


function getMeterReadingSumByMonth($tag)
{
    $connBar = OpenCon();

    $sqlBar = "SELECT YEAR(EventDate) AS Year, MONTH(EventDate) AS Month, SUM(Value) AS TotalMeterReading
FROM ap_devicelog
WHERE Tag = '$tag'
GROUP BY YEAR(EventDate), MONTH(EventDate)";

    $resultBar = $connBar->query($sqlBar);

    $meterReadingSumByMonth = array();
    while ($row = $resultBar->fetch_assoc()) {
        $year = (int) $row['Year'];
        $monthNumber = (int) $row['Month'];
        $totalMeterReading = (float) $row['TotalMeterReading'];
        $totalMeterReading = round($totalMeterReading, 0);
        $monthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
        $yearMonth = "$year-$monthName";
        $meterReadingSumByMonth[] = array(
            "country" => $yearMonth,
            "value" => $totalMeterReading
        );
    }

    CloseCon($connBar);
    return $meterReadingSumByMonth;
}

function calculateChangeInValue($dataArray)
{
    $numElements = count($dataArray);
    if ($numElements < 2) {
        return null;
    }

    $lastElement = $dataArray[$numElements - 1];
    $secondLastElement = $dataArray[$numElements - 2];

    $changeInValue = $lastElement['value'] - $secondLastElement['value'];
    return $changeInValue;
}

$tag = 'DE00001';
$json_data_bar = json_encode(getMeterReadingSumByMonth($tag));
$data = json_decode($json_data_bar, true);

$changeInValue = calculateChangeInValue($data);
$percentChange = $changeInValue * 100 / $data[count($data) - 2]['value'];
$percentChange = round($percentChange, 2);



function getTotalWattRPhase()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00004'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}


function getTotalWattYPhase()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00005'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}

function getTotalWattBPhase()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00006'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}


$wattRPhase = getTotalWattRPhase();
$wattYPhase = getTotalWattYPhase();
$wattBPhase = getTotalWattBPhase();

?>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>


<script>
    am5.ready(function() {

        var root = am5.Root.new("chartdivline");
        root.setThemes([
            am5themes_Animated.new(root)
        ]);
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true
        }));
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
            behavior: "none"
        }));
        cursor.lineY.set("visible", false);
        var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
            maxDeviation: 0.5,
            baseInterval: {
                timeUnit: "day",
                count: 1
            },
            renderer: am5xy.AxisRendererX.new(root, {
                pan: "zoom"
            }),
            tooltip: am5.Tooltip.new(root, {})
        }));


        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 1,
            renderer: am5xy.AxisRendererY.new(root, {
                pan: "zoom"
            })
        }));

        var series = chart.series.push(am5xy.SmoothedXLineSeries.new(root, {
            name: "Series",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));

        series.fills.template.setAll({
            visible: true,
            fillOpacity: 0.2
        });

        series.bullets.push(function() {
            return am5.Bullet.new(root, {
                locationY: 0,
                sprite: am5.Circle.new(root, {
                    radius: 4,
                    stroke: root.interfaceColors.get("background"),
                    strokeWidth: 2,
                    fill: am5.color(0x6e9d3c)
                })
            });
        });

        series.set("fill", am5.color(0xa4d55c));
        series.set("stroke", am5.color(0xa4d55c));

        // Scroll bar
        // chart.set("scrollbarX", am5.Scrollbar.new(root, {
        //     orientation: "horizontal",
        // }));

        var data = <?php echo $json_data_line ?>;
        series.data.setAll(data);
        series.appear(1000);
        chart.appear(1000, 100);

    });
</script>



<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>

<script>
    am5.ready(function() {
        var root = am5.Root.new("chartdivbar");
        root.setThemes([
            am5themes_Animated.new(root)
        ]);
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true
        }));
        var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
        cursor.lineY.set("visible", false);
        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 30
        });
        xRenderer.labels.template.setAll({
            centerY: am5.p50,
            centerX: am5.p100,
            paddingRight: 15
        });

        xRenderer.grid.template.setAll({
            location: 1
        })

        var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            maxDeviation: 0.3,
            categoryField: "country",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {})
        }));

        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.3,
            min: 0,
            max: 5000,
            renderer: am5xy.AxisRendererY.new(root, {
                strokeOpacity: 0.1
            })
        }));
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
            name: "Series 1",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            sequencedInterpolation: true,
            categoryXField: "country",
            tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
            })
        }));

        series.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5,
            strokeOpacity: 0
        });

        var customColors = ["#a4d55c", "#a4d98a", "#b0d55c", "#80d55c", "#5cd590"];
        series.columns.template.adapters.add("fill", function(fill, target) {
            var columnIndex = series.columns.indexOf(target);
            return customColors[columnIndex];
        });

        series.columns.template.adapters.add("stroke", function(stroke, target) {
            return chart.get("colors").getIndex(series.columns.indexOf(target));
        });
        var data_bar = <?php echo $json_data_bar ?>;

        xAxis.data.setAll(data_bar);
        series.data.setAll(data_bar);
        series.appear(1000);
        chart.appear(1000, 100);

    });
</script>


<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>

<script>
    am5.ready(function() {
        var root = am5.Root.new("chartdivpie");
        root.setThemes([am5themes_Animated.new(root)]);

        var chart = root.container.children.push(am5percent.PieChart.new(root, {
            endAngle: 270,
            innerRadius: am5.percent(40)
        }));

        var series = chart.series.push(am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "category",
            endAngle: 270
        }));

        series.states.create("hidden", {
            endAngle: -90
        });

        series.data.setAll([{
                category: "Watt R",
                value: <?php echo $wattRPhase; ?>
            }

            , {
                category: "Watt Y",
                value: <?php echo $wattYPhase; ?>
            }

            , {
                category: "Watt B",
                value: <?php echo $wattBPhase; ?>
            }

            ,
        ]);

        series.appear(1000, 100);

    });
</script>



<?php
$energyTips = array(
    "Turn off lights and appliances when not in use.",
    "Use LED or CFL bulbs instead of incandescent ones.",
    "Unplug chargers and electronic devices when fully charged or not in use.",
    "Keep doors and windows properly sealed to prevent drafts.",
    "Use a programmable thermostat to regulate heating and cooling.",
    "Seal air leaks around windows and doors with weatherstripping.",
    "Wash clothes in cold water and hang them to dry when possible.",
    "Lower the thermostat on your water heater to save energy.",
    "Use energy-efficient appliances and electronics.",
    "Plant trees and shrubs to provide shade around your home.",
    "Install solar panels to harness renewable energy.",
    "Turn off the tap while brushing teeth or washing dishes.",
    "Keep refrigerator and freezer coils clean for better efficiency.",
    "Opt for natural ventilation when the weather allows.",
    "Use a power strip to easily turn off multiple devices at once.",
    "Consider using a bike, carpooling, or public transportation for commuting.",
    "Set computers and monitors to sleep mode when not in use.",
    "Use ceiling fans to reduce the need for air conditioning.",
    "Insulate your home to retain heat during winter and keep it cool in summer.",
    "Use curtains or blinds to block direct sunlight during hot days.",
    "Run the dishwasher and washing machine with full loads.",
    "Avoid using standby mode on electronics and fully power them down.",
    "Fix leaky faucets and pipes to conserve water and reduce heating costs.",
    "Cook with lids on pots and pans to retain heat and reduce cooking time.",
    "Avoid using space heaters and dress warmly during colder months.",
    "Limit the use of energy-intensive appliances during peak hours.",
);

function getTotalMeterReading()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00001'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}

function getTotalWattReading()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00003'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}

function getAverageCurrentReading()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00027'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}

function getTotalVAReading()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00015'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}
function getVLLAverageReading()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00019'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}

function getVLNAverageReading()
{
    $conn = OpenCon();

    $sql = "SELECT SUM(Value) AS sum_elements FROM ap_devicelog WHERE tag = 'DE00023'";

    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sumElements = $row['sum_elements'];
    } else {
        $sumElements = 0;
    }
    $conn->close();

    $sum = round($sumElements, 2);
    return $sum;
}

$total_meter_reading = getTotalMeterReading();
$total_watt_reading = getTotalWattReading();
$average_current_reading = getAverageCurrentReading();
$total_va_reading = getTotalVAReading();
$vll_average_reading = getVLLAverageReading();
$vln_average_reading = getVLNAverageReading();
?>
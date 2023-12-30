<!DOCTYPE HTML>
<html>
<head>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Purchase Return Quantity"
                },
                subtitles: [{
                    text: "Quantity Used"
                }],
                data: [{
                    type: "pie",
                    showInLegend: true,
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - #percent%",
                    yValueFormatString: "#,##0",
                    dataPoints: <?php echo json_encode(getDataPointsFromDB(), JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            // Function to fetch data from the database and format it for the pie chart
            function getDataPointsFromDB() {
                <?php
                // Handling connection to the database
                try {
                    $link = new \PDO('mysql:host=localhost;dbname=medix_inventory;charset=utf8mb4', 'root', '', array(
                        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_PERSISTENT => false
                    ));

                    // SQL query to get the purchase return quantity with the respective quantity
                    $query = "SELECT i.name, SUM(pr.quantity) as return_quantity FROM return_list rl
                              INNER JOIN items i ON rl.item_id = i.id
                              GROUP BY i.name";

                    $handle = $link->prepare($query);
                    $handle->execute();
                    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

                    $dataPoints = array();
                    foreach ($result as $row) {
                        array_push($dataPoints, array("label" => $row->name, "y" => $row->return_quantity));
                    }

                    $link = null;

                    // Return the data points as a JSON array
                    return json_encode($dataPoints);
                } catch (\PDOException $ex) {
                    print($ex->getMessage());
                    return "[]"; // Return empty array if there's an error
                }
                ?>
            }
        }
    </script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
</body>
</html>

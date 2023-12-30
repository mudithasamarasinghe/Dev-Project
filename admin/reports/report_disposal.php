<?php
$dataPoints1 = array();
// Handling connection to the database
try {
    $link = new \PDO('mysql:host=localhost;dbname=medix_inventory;charset=utf8mb4', 'root', '', array(
        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_PERSISTENT => false
    ));

    // SQL query to get the total sales amount for each item
    $query1 = "SELECT i.name, di.quantity as total FROM disposal d
              INNER JOIN disposal_items di ON d.id=di.disposal_id
              INNER JOIN items i ON i.id=di.item_id
                GROUP BY d.item_id";

    $handle1 = $link->prepare($query1);
    $handle1->execute();
    $result1 = $handle1->fetchAll(\PDO::FETCH_OBJ);

    foreach ($result1 as $row) {
        array_push($dataPoints1, array("label" => $row->name, "y" => $row->total));
    }
    $link = null;
} catch (\PDOException $ex) {
    print($ex->getMessage());
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function () {
            var chart1 = new CanvasJS.Chart("chartContainer1", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Item vs. Total Disposal Amount"
                },
                axisX: {
                    title: "Item Name"
                },
                axisY: {
                    title: "Total Disposal Amount"
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();
        }
    </script>
</head>
<body>
<h1>Item vs. Total Disposal Amount</h1>
<div id="chartContainer1" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>

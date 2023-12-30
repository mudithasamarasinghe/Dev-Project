<?php
// Initialize default date range
$start_date = null;
$end_date = null;

// Check if the form is submitted with date range values
if (isset($_POST['submit'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
}
$dataPoints1 = array();
// Handling connection to the database
try {
    $link = new \PDO('mysql:host=localhost;dbname=medix_inventory;charset=utf8mb4', 'root', '', array(
        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_PERSISTENT => false
    ));

    // SQL query to get the total sales amount for each item
    $query1 = "SELECT i.name, SUM(si.quantity) as total_sales FROM sales_items si
              INNER JOIN items i ON si.item_id=i.id
              GROUP BY i.name";

    $handle1 = $link->prepare($query1);
    $handle1->execute();
    $result1 = $handle1->fetchAll(\PDO::FETCH_OBJ);

    foreach ($result1 as $row) {
        array_push($dataPoints1, array("label" => $row->name, "y" => $row->total_sales));
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
                    text: "Item vs. Total Sales Amount"
                },
                axisX: {
                    title: "Item Name"
                },
                axisY: {
                    title: "Total Sales Amount"
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();
        }
    </script>
	 <script type="text/javascript">
        function handleChange() {
            var arr = document.getElementById( 'menu1' ) ;
            window.location = arr.value ;
        } 
        </script>
</head>
<body>
<div class="row">
<div class="col-md-6"><h3>Item vs. Total Sales Amount</h3></div>
<div class="text-right col-md-6">
<select id="menu1" onchange="handleChange()">
<option value="#" >Select a report</option>
<option value="index.php?page=reports/report_sales" >Sales report</option>
<option value="index.php?page=reports/report_sales_item">Sales report by Item</option>
<option value="index.php?page=reports/report_sales_date">Sales report by Date</option>
</select></div>
</div>

<div id="chartContainer1" style="height: 370px; width: 100%;">
</div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>

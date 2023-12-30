<?php
// Initialize default date range
$start_date = null;
$end_date = null;

// Check if the form is submitted with date range values

if (isset($_POST['submit'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
} else {
	$start_date =" last_day(curdate() - interval 1 month + interval 1 day)";
    $end_date =" curdate()+1 ";
}

$dataPoints = array();
// Handling connection to the database
try {
    $link = new \PDO('mysql:host=localhost;dbname=medix_inventory;charset=utf8mb4', 'root', '', array(
        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_PERSISTENT => false
    ));
$query = "SELECT i.name AS nm, si.quantity AS qtysold 
FROM sales_items si INNER JOIN items i ON si.item_id=i.id 
 join sales_list sl on si.sales_id = sl.id WHERE ";

// Add date range condition to the query if provided
if ($start_date && $end_date && isset($_POST['submit'])) {
    $query .= "sl.date_created BETWEEN '".$start_date."' AND '".$end_date."' GROUP BY i.name";
} else{
	$query .= "sl.date_created BETWEEN ".$start_date." AND ".$end_date." GROUP BY i.name";

}

$handle = $link->prepare($query);

// Bind the date range values to the query using question marks
//if ($start_date && $end_date) {
   // $handle->bindParam(1, $start_date);
    //$handle->bindParam(2, $end_date);
	$handle->execute();
//}
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    foreach ($result as $row) {
        array_push($dataPoints, array("label" => $row->nm, "y" => $row->qtysold));
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
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Item vs. Quantity Sold"
                },
                axisX: {
                    title: "Item"
                },
                axisY: {
                    title: "Quantity Sold"
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            // Function to update the chart with new data based on the selected date range
            function updateChart() {
                var startDate = document.getElementById('start_date').value;
                var endDate = document.getElementById('end_date').value;
            }

            // Event listener to update the chart when the date range is changed
            document.getElementById('date_range_form').addEventListener('change', function () {
                updateChart();
            });
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
<div class="col-md-6"><h3>Item vs. Quantity Sold</h3></div>
<div class="text-right col-md-6">
<select id="menu1" onchange="handleChange()">
<option value="#" >Select a report</option>
<option value="index.php?page=reports/report_sales" >Sales report</option>
<option value="index.php?page=reports/report_sales_item">Sales report by Item</option>
<option value="index.php?page=reports/report_sales_date">Sales report by Date</option>
</select></div>
</div>

<!-- Add the date range selector form -->
<form id="date_range_form" action="" method="post">
  <label for="start_date">Start Date:</label>
 <input type="date" id="start_date" name="start_date" value="<?php if (isset($_POST['submit'])) 
 { echo $start_date; } 
 else { echo date('Y-m-01');} ?>" placeholder="<?php echo date('Y-m-01') ?>" >
<label for="end_date">End Date:</label>
 <input type="date" id="end_date" name="end_date" value="<?php if (isset($_POST['submit'])) { echo $end_date; } else {
 echo date("Y-m-d", strtotime("+1 day")); } ?>">
 

 
 
 
<input type="submit" name="submit" value="Generate Chart">
</form>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>

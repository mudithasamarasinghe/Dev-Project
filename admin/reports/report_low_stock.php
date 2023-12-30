<!DOCTYPE HTML>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<?php
try {
    $link = new \PDO('mysql:host=localhost;dbname=medix_inventory;charset=utf8mb4', 'root', '', array(
        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_PERSISTENT => false
    ));

    // SQL query to get the items with their expiration date
    $query = "select i.name as name,sl.quantity as available quantity from stock_list sl inner join items i on sl.item_id=i.id 
              inner join item_min_stock m where SUM(m.quantity) between now() and adddate(now(), INTERVAL 30 DAY)
group by i.name;";

    $handle = $link->prepare($query);
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    // Get the current date
    $currentDate = date('Y-m-d');

    // Create an array to store the table rows
    $tableRows = array();

    // Loop through the items and calculate the days remaining for each item
    foreach ($result as $row) {
        $expirationDate = $row->expiration_date;
        $daysRemaining = floor((strtotime($expirationDate) - strtotime($currentDate)) / (60 * 60 * 24));

        // Store the data in a row array
        $rowArray = array(
            'item_name' => $row->name,
            'days_remaining' => $daysRemaining
        );

        // Add the row array to the table rows array
        $tableRows[] = $rowArray;
    }

    $link = null;
} catch (\PDOException $ex) {
    print($ex->getMessage());
}
?>

<!-- Display the table -->
<table>
    <tr>
        <th>Item Name</th>
        <th>Days Remaining</th>
    </tr>
    <?php
    // Loop through the table rows and display the data
    foreach ($tableRows as $row) {
        ?>
        <tr>
            <td><?php echo $row['item_name']; ?></td>
            <td><?php echo $row['days_remaining']; ?> days</td>
        </tr>
        <?php
    }
    ?>
</table>
</body>
</html>

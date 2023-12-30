<?php
require_once('../../config.php');
$con=mysqli_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD,DB_DATABASE);
// Check connection
                if (mysqli_connect_errno())
                {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }
 $sql= "select  a.address_id mid,CONCAT(a.street_address,',',a.suburb,',',a.city,',',a.state,',',a.postcode) nm, c.contact_mobile mp,c.contact_id conid ,c.contact_name cntnm,a.address_name btonm ,c.contact_email_address eml  from " . $tbl2 . " c LEFT JOIN " . $tbl1 . " a  ON  c.associate_id = a.association_id and c.contact_default_address_id = a.address_id   where c.records_type ='bill' and a.address_id =" .  (int)$bilid ." and c.associate_id=". (int)$cid." and a.authorization = 1 order by c.contact_id ";

                if ($result=mysqli_query($con,$sql))
    {
// Fetch one and one row
$row=mysqli_fetch_row($result);
echo "<option value=\"" . htmlspecialchars($row[0]) . "\">" . htmlspecialchars($row[1] . '~' . $row[2] . '~' . $row[3] . '~' . $row[4] . '~' . $row[5] . '~' . $row[6]) . "</option>";
        // Free result set
        mysqli_free_result($result);
    }
                mysqli_close($con);
?>

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
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Purchase Order Details</h3>
      
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped">
         
                    <?php
                    $i = 1;
//po records
                    $qry = $conn->query("SELECT p.*, s.name as supplier_name FROM `purchase_orders` p inner join suppliers s on p.supplier_id = s.id order by p.po_code desc");
                    while ($row = $qry->fetch_assoc()):
                       // $row['items'] = $conn->query("SELECT count(item_id) as `items` FROM `po_items")->fetch_assoc()['items'];
                        ?>
                        <tr style="color: #164906;">
                            <td class="text-left"><?php echo $i++; ?></td>
                            <td class="text-left"><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td class="text-left"><?php echo $row['po_code'] ?></td>
                            <td class="text-left"><?php echo $row['supplier_name'] ?></td>
                                     <td class="text-left has-details create_details" nowrap style="cursor: pointer;" data-id="<?php echo $row['id'] ?>">
                                <?php if ($row['status'] == 2): ?>
                                    <span class="">Fully Received</span>
                                <?php elseif ($row['status'] == 1): ?>
                                    <span class="">Partially received</span>
                                <?php elseif ($row['status'] == 0): ?>
                                    <span class="">Pending</span>
                                <?php else: ?>
                                    <span class="">N/A</span>
                                <?php endif; ?>
							                    </td>

                        </tr>
 				
				                    <?php
//po items
                    $i = 1;
                    $qry0 = $conn->query("SELECT p.*,i.id,i.name,i.description FROM `po_items` p inner join items i on p.item_id = i.id where p.po_id = '{$row['id']}'");
                    while ($row0 = $qry0->fetch_assoc()):
                        ?>
                        <tr style="color: #0a19bd;"><td class="text-left"></td>
						<td class="text-left"></td>
                            <td class="text-left"><?php echo $i++; ?></td>
                            <td class="text-left"><?php echo $row0['name']; ?></td>
                            <td class="text-right"><?php echo $row0['quantity'] ?></td>
                            <td class="text-left has-details create_details" nowrap style="cursor: pointer;" data-id="<?php echo $row['id'] ?>">
            

                        </tr>
  <?php
  // receivings
					   $qryr = $conn->query("SELECT sl.date_created,sl.rec_id,sl.form_id,sl.item_id AS items,sl.quantity  FROM stock_list sl where sl.form_id='{$row['id']}' and sl.item_id='{$row0['id']}'  and sl.type='1' order by `date_created` desc");
					   while($rowr = $qryr->fetch_assoc()):	
				         				
                        ?>
   <tr style="color:#bd0a93"><td></td>
							<td class="item"></td>
              <td></td>
							 <td class="text-left unit">
                            
                            <?php echo "Received on ".$rowr['date_created']; ?>
                            </td>
								<td class="text-right" ><?php echo number_format($rowr['quantity']); ?></td>
								     </td>
                        </tr>
	<?php endwhile; ?>
				  <?php
				  // returns
					   $qryret = $conn->query("SELECT sl.date_created,sl.rec_id,sl.form_id,sl.item_id AS items,sl.quantity  FROM stock_list sl where sl.form_id='{$row['id']}' and sl.item_id='{$row0['id']}'  and sl.type='2' order by `date_created` desc");
					   while($rowret = $qryret->fetch_assoc()):	
				         				
                        ?>
   <tr style="color:red"><td></td>
							<td class="item"></td>
              <td></td>
							 <td class="text-left unit">
                            
                            <?php echo "Returned on ".$rowret['date_created']; ?>
                            </td>
								<td class="text-right" ><?php echo number_format($rowret['quantity']); ?></td>
								     </td>
                        </tr>
						                    <?php endwhile; ?>
					<?php
// disposal records
 $qrydis = $conn->query("SELECT sl.date_created,sl.rec_id,sl.form_id,sl.item_id AS items,sl.quantity  FROM stock_list sl where sl.form_id='{$row['id']}' and sl.item_id='{$row0['id']}'  and sl.type='3' order by `date_created` desc");
                     while($rowdis = $qrydis->fetch_assoc()):
                
                    ?>
					   <tr style="color:#079b7d"><td></td>
							<td class="item"></td>
              <td></td>
							 <td class="text-left unit">
                             <?php echo "Disposed on ".$rowdis['date_created']; ?>
                            
                            </td>
								<td class="text-right" ><?php echo $rowdis['quantity']; ?></td>
								     </td>
                        </tr>
					



			
                        <?php endwhile; ?>
  
	
                    <?php endwhile; ?>	
				
	
                    <?php endwhile; ?>
  
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>

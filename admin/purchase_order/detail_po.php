<?php require_once('./../../config.php') ?>
<?php
$qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_orders p inner join suppliers s on p.supplier_id = s.id  where p.id = '{$_GET['id']}'");
if($qry->num_rows >0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>
   <style>
    #uni_modal .modal-footer{
        display:none;
    }
</style> 
<div class="card card-outline card-primary">
    <div class="card-body" id="print_out">
	<div class="form-group">
    <div class="col-12">
        <div class="card-tools">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>
        <div class="container-fluid">
		
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info">P.O. Code</label>
                    <div><?php echo isset($po_code) ? $po_code : '' ?></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_id" class="control-label text-info">Supplier</label>
                        <div><?php echo isset($supplier) ? $supplier : '' ?></div>
                    </div>
                </div>
            </div>
      <div>      
            <table border="1">
                <thead>
                    <tr>
					    <th>Date Created</th>
                        <th>Item</th>
						<th>Expiry Date</th>
                        <th>Unit</th>
                        <th>Qty</th>
						
                    </tr>
                </thead>
                <tbody>
				 <tr>
                        <td colspan="5" style="background:#7fb5da;">Purchase Order details</td>
	
						
                    </tr>
                    <?php
                    $qry = $conn->query("SELECT p.*,i.name,i.description ,po.date_created dtcrdt FROM `po_items` p inner join items i on p.item_id = i.id inner join purchase_orders po on p.po_id = po.id where p.po_id = '{$_GET['id']}'");
                    while($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
					<td><?php echo $row['dtcrdt']; ?></td>
                        <td class="py-1 px-2" nowrap>
                            <?php echo $row['name'] ?>
                            <?php echo $row['description'] ?>
                        </td>
						<td></td>
                        <td class="py-1 px-2 text-center"><?php echo ($row['unit']) ?></td>
                        <td style="text-align:right;" nowrap><?php echo $row['quantity'] ?></td>
						
                    </tr>

                    <?php endwhile; ?>
                    
<?php
$qry = $conn->query("SELECT r.date_created dtcre,DATE_FORMAT(sl.expiry_date,'%m/%d/%Y') expiry_date,sl.quantity,sl.unit unt,sl.quantity qty,sl.rec_id, itm.name nm FROM receivings r inner join stock_list sl on sl.rec_id=r.id inner join items itm on sl.item_id=itm.id where r.form_id='{$_GET['id']}' and sl.type='1'");
if($qry->num_rows >0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;

    }
}

?>   
<?php  if(isset($dtcre)): ?>
<tr><td colspan="5" style="background:#7fb5da;">Receiving details</td></tr>  
<?php endif; ?>

                        <?php
                        $rtndtl = $conn->query("SELECT r.date_created dtcre,DATE_FORMAT(sl.expiry_date,'%m/%d/%Y') expiry_date,sl.quantity,sl.unit unt,sl.quantity qty,sl.rec_id, itm.name nm FROM receivings r inner join stock_list sl on sl.rec_id=r.id inner join items itm on sl.item_id=itm.id where r.form_id='{$_GET['id']}' and sl.type='1'");
						        if($rtndtl->num_rows >0){
							 while($rowdc = $rtndtl->fetch_assoc()):
							$totqty = $rowdc['quantity']; 
							$stock_ids = ",".$rowdc['rec_id'];
							  if ($rowdc['expiry_date']=="00/00/0000" ){
								  $dc ="No expiry date";
							  } else {
							  $dc = (new DateTime($rowdc['expiry_date']))->format('Y-m-d'); 
							  }	
?>
                        <tr>
							<td class="py-1 px-2 item" nowrap>
                            <?php echo $rowdc['dtcre']; ?>
                            </td>
							<td class="py-1 px-2 item" nowrap>
                            <?php echo $rowdc['nm']; ?>
                            </td>
              <td class="py-1 px-2 text-center expiry_date" nowrap><?php echo $dc; ?></td>
							 <td class="py-1 px-2 text-center unit">
                            <?php echo $rowdc['unt']; ?>
                            </td>
							    <td  style="text-align:right;" nowrap><?php echo $rowdc['qty']; ?>

                            </td>
                        </tr>
<?php							  
							  endwhile;
								} 
								else {
									$dc="";
								}
				
                        ?>
<?php
          
$qry = $conn->query("SELECT rl.date_created cdt,s.*,i.name,i.description FROM `return_list` rl inner join `stock_list` s on rl.id=s.rec_id inner join items i on s.item_id = i.id where rl.form_id =  '{$_GET['id']}' and s.type='2'");
if($qry->num_rows >0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;

    }
}

?>
<?php  if(isset($cdt)): ?>
<tr><td colspan="5" style="background:#7fb5da;">Purchase Return details</td></tr>   
<?php endif; ?>

   <?php 
      $qry2 = $conn->query("SELECT distinct rl.date_created cdt,i.name,i.description,s.unit,s.quantity FROM `return_list` rl inner join `stock_list` s on rl.id=s.rec_id inner join items i on s.item_id = i.id where rl.form_id =  '{$_GET['id']}' and s.type='2'");  
	       if($qry2->num_rows >0){
	while($row2 = $qry2->fetch_assoc()):
  
                    ?>
                    <tr>
					<td class="py-1 px-2"><?php echo $row2['cdt']; ?></td>
					 <td class="py-1 px-2" nowrap><?php echo $row2['name']; ?></td>
                       <td></td>
                        <td class="py-1 px-2 text-center"><?php echo ($row2['unit']); ?></td>
						
          <td  style="text-align:right;" nowrap><?php echo number_format($row2['quantity']); ?></td>

                    </tr>

                    <?php endwhile;
		   }
		   ?>


				<?php
          
$qry = $conn->query("SELECT rl.date_created cdt,i.name,i.description FROM `disposal_list` rl inner join `stock_list` s on rl.id=s.rec_id inner join items i on s.item_id = i.id where rl.form_id =  '{$_GET['id']}' and s.type='3'");
if($qry->num_rows >0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;

    }
}

?>
<?php  if(isset($cdt)): ?>
<tr><td colspan="5" style="background:#7fb5da;">Diposal details</td></tr>   
<?php endif; ?>

   <?php 
     $qry2 = $conn->query("SELECT rl.date_created cdt,s.*,i.name,i.description,s.unit,s.quantity FROM `disposal_list` rl inner join `stock_list` s on rl.id=s.rec_id inner join items i on s.item_id = i.id where rl.form_id =  '{$_GET['id']}' and s.type='3'");  
	       if($qry2->num_rows >0){
	while($row2 = $qry2->fetch_assoc()):
  
                    ?>
                    <tr>
					<td class="py-1 px-2"><?php echo $row2['cdt']; ?></td>
					 <td class="py-1 px-2" nowrap><?php echo $row2['name']; ?></td>
                       <td></td>
                        <td class="py-1 px-2 text-center"><?php echo ($row2['unit']); ?></td>
						
          <td  style="text-align:right;" nowrap><?php echo number_format($row2['quantity']); ?></td>

                    </tr>

                    <?php endwhile;
		   }
		   ?>

 </tbody>
                <table>

</div>
<div class="form-group">
    <div class="col-12">
        <div class="card-tools">
		<button class="btn btn-flat btn-success" type="button" id="print">Print</button>
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script>
    
    $(function(){
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Purchase Order Details - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-navy")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Purchase Order</h4>'+
                      '</div>'+
                      '<div class="col-1 text-right">'+
                      '</div>'+
                      '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes")
                     nw.document.write(_el.html())
                     nw.document.close()
                     setTimeout(() => {
                         nw.print()
                         setTimeout(() => {
                            nw.close()
                            end_loader()
                         }, 200);
                     }, 500);
        })
    })
</script>
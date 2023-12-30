<div class="card  card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">BOM Item Detail</h3>

	</div>
	<div class="card-body">
        <div class="container-fluid">
		<div class="row">
                <div class="col-md-3">
                    <label class="control-label text-info">BOM Id.</label>                   
                </div>
				<div class="col-md-3">
                    <div><?php echo isset($_GET['id']) ? $_GET['id'] : '' ?></div>    
                </div>
				 <div class="col-md-3">
                <label class="control-label text-info">BOM Device</label>                   
                </div>
				<div class="col-md-3">
                    <div><?php echo isset($_GET['nm']) ? $_GET['nm'] : '' ?></div>   
                </div>
            </div>
		
		
		
            <table class="table table-striped table-bordered" id="list">
                       <colgroup>
                        <col width="5%">
                        <col width="25%">
                        <col width="25%">
                        <col width="10%">
                        <col width="10%">
                        <col width="20%">
                    </colgroup>
                <thead>
                    <tr>
					       <th>#</th>
                            <th>Item Name</th>
							<th>Supplier</th>
							<th class="text-center">Qty.</th>
							 <th class="text-center">Status</th>
							 <th>Date Created</th>
                           
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
				    	  $qry = $conn->query("SELECT i.*,s.name snm,COALESCE(sl.quantity,0) qty FROM `items` i inner join `suppliers`s on i.supplier_id=s.id left join stock_list sl on i.id=sl.item_id WHERE i.apparatus=0 and i.bom_id='{$_GET['id']}' order by i.date_created desc");
              	while($row = $qry->fetch_assoc()):
                     ?>
                      <tr>
					  <td class="text-center"><?php echo $i++; ?></td>
                        <td class="py-1 px-2 text-left"><?php echo ($row['name']) ?></td>
						<td class="py-1 px-2"><?php echo $row['snm']; ?> </td>
                        <td class="py-1 px-2 text-center"><?php 
$qtysql = $conn->query("SELECT bom.bom_quantity qty  FROM `bill_of_materials` bom where bom.device_id='{$_GET['id']}' and bom.item_id='{$row['id']}'");
if($qtysql->num_rows >0)
{ 
while($row1=$qtysql->fetch_assoc()):

?>
<?php echo number_format($row1['qty']); ?>                           

<?php 
 endwhile; 
} else { ?>
<?php echo "0"; ?>
<?php } ?></td>
						
                       <td class="text-center">
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge badge-danger rounded-pill">Inactive</span>
                                     <?php else: ?>
                                        <span class="badge badge-primary rounded-pill">Active</span>
                                    <?php endif; ?>
                                </td>
								<td class="py-1 px-2" nowrap><?php echo $row['date_created']; ?> </td>
                    </tr>

                    <?php endwhile; ?>
                    
                </tbody>
            
            </table>
		</div>
	</div>
	    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-success" type="button" id="print">Print</button>
        <a class="btn btn-flat btn-primary" href="<?php echo base_url.'/admin/index.php?page=bill_of_materials/manage_bom&id='.(isset($bom_id) ? $bom_id : $_GET['id']).'&nm='.$_GET['nm']; ?>">Edit</a>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=bill_of_materials' ?>">Back To List</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 text-center qty">
            <span class="visible"></span>
            <input type="hidden" name="item_id[]">
            <input type="hidden" name="unit[]">
            <input type="hidden" name="qty[]">
            <input type="hidden" name="price[]">
            <input type="hidden" name="total[]">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 item">
        </td>
        <td class="py-1 px-2 text-right cost">
        </td>
        <td class="py-1 px-2 text-right total">
        </td>
    </tr>
</table>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this BOM permanently?","delete_bom",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("Payment Details","transaction/view_payment.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.edit_data').click(function(){
			uni_modal("Create a new BOM","bill_of_materials/edit_bom.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.create_details').click(function(){
			uni_modal("Create a new BOM","bill_of_materials/create_bom.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_bom($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_bom",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
<div class="card  card-primary card-outline">
	<div class="card-header">
		<h3 class="card-title">Bill of materials</h3>
		<div class="card-tools">
		 <a class="btn btn-flat btn-primary create_details" href="javascript:void(0)" data-id="">Create a New BOM</a>
       </div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
			         <colgroup>
                    <col width="10%">
                    <col width="30%">
					<col width="15%">
                    <col width="15%">
                    <col width="10%">
                </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>                           
                            <th>BOM</th>
				    		 <th>Date Created</th>
                            <th >Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                          $qry = $conn->query("SELECT * FROM `items` WHERE `apparatus`=1 order by `date_created` desc");
                         while($row = $qry->fetch_assoc()):
                           ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
								<td><?php echo $row['name'] ?></td>
                             	   <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                <td class="text-l">
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge badge-danger rounded-pill">Inactive</span>
                                    
                                    <?php else: ?>
                                        <span class="badge badge-primary rounded-pill">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                    
                                        <a class="dropdown-item" href="<?php echo base_url.'admin/index.php?page=bill_of_materials/view_bom&id='.$row['id'].'&nm='.$row['name']; ?>" data-id="<?php echo $row['bom_id'] ?>"><span class="fa fa-eye text-dark"></span>View/Edit BOM Items</a>
										      <div class="dropdown-divider"></div>
										 <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
					  
                                             </div>
											 
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this BOM permanently?","delete_bom",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("Payment Details","transaction/view_payment.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.edit_data').click(function(){
			uni_modal("Edit BOM","bill_of_materials/create_bom.php?id="+$(this).attr('data-id'),'mid-large')
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
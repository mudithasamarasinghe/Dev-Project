
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Profile alert</h3>
		
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-striped">
				<colgroup>
					<col width="5%">
					<col width="10%">
					<col width="25%">
					<col width="25%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Profile id.</th>
						<th>Profile name</th>
						<th>Approve for</th>
						<th class="text-center">Status</th>

					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						//$qry = $conn->query("SELECT am.* from `alert` am order by am.approval_for asc ");
						$qry = $conn->query("SELECT pr.*,ap.profile_name,r.approval_for from `profile_alert` pr inner join admin_profiles ap on pr.profileid = ap.profile_id inner join alert r on pr.alertid = r.id order by pr.profileid asc ");
						
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['id'] ?></td>
							<td><?php echo $row['profile_name'] ?></td>
							<td><?php echo $row['approval_for'] ?></td>
								<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success rounded-pill">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger rounded-pill">Inactive</span>
                                <?php endif; ?>
                            </td>
		
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this alert asign to profile permanently?","delete_profilealert",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Role","maintenance/manage_profilealert.php","mid-large")
		})
		$('.open_data').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Include roles to profile","maintenance/alertstoprofiles.php?id="+$(this).attr('data-id'),"mid-large")
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_profilealert($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_profilealert",
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


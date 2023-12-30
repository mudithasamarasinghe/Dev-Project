<?php require_once('../config.php') ; 	 

    $get = $conn->query("SELECT * FROM `messages`");
    if($get->num_rows > 0){

        while($row = $get->fetch_assoc()):
             echo $row['role_name']."------------".$row['event_id']."------------".$row['msg_id']."------------".base64_decode($row['message'])."<br>";         
         
        endwhile;
    }


if(isset($_GET['id'])){
	$id = $_GET['id'];
$qry = $conn->query("SELECT * FROM messages a WHERE a.status = '0' and a.msg_id = '{$_GET['id']}' and a.profileid='". $profileid."'");

} else {
		$qry = $conn->query("SELECT * FROM messages a WHERE a.status = '0'  and a.profileid='". $profileid."'");

}
?>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
   <div class="wrapper">
<form action="" id="role-form">
    <!-- Content Wrapper. Contains page content -->
  <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>

        <!-- Main content -->
        <section class="content ">
          <div class="container-fluid">

<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Messages</h3>

	</div>
	<div class="card-body">
		<div class="container-fluid">
            <table id="myTable" class="table table-striped" >
            <colgroup>
					<col width="5%">
					<col width="15%">
					<col width="25%">
					<col width="15%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Name</th>
					<th class="text-center">Status</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
	  $qry0 = $conn->query("SELECT u.type FROM users u where u.id = '".$_SESSION['userdata']['id']."'");
	
     if($qry0->num_rows >0){
		 while($row0 = $qry0->fetch_assoc()):
       $profileid = $row0['type'];
	   endwhile;
     }		
					$i = 1;
					
if(isset($_GET['id']) && $_GET['id'] > 0){
  
}					
					while($row = $qry->fetch_assoc()):

					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td><?php echo $row['title'] ?></td>
								<td class="text-center">
                                <?php if($row['status'] == 0): ?>
                                    <span class="badge badge-success rounded-pill">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger rounded-pill">Inactive</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
							<?php if ($row['role_name']=="returns") { ?>
							<a class="dropdown-item approve_data" href="javascript:void(0)"
                                           data-id="<?php echo $row['event_id']; ?>"><span class="fa fa-check-circle text-success"></span>
                                Approve</a><a class="dropdown-item reject_data" href="javascript:void(0)"
                                                          data-id="<?php echo $row['event_id']; ?>"><span class="fa fas fa-registered text-danger"></span>
                                    Reject</a>

							<?php } elseif  ($row['role_name']=="expiry") {	?>
							<a class="dropdown-item expiry_data" href="javascript:void(0)"
                                           data-id="<?php echo $row['event_id']; ?>"><span class="fa fa-check-circle text-success"></span>
                                            Expiry Viewed</a>
                                <?php } elseif  ($row['role_name']=="mlcheck") {	?>
                                <a class="dropdown-item expiry_data" href="javascript:void(0)"
                                   data-id="<?php echo $row['event_id']; ?>"><span class="fa fa-check-circle text-success"></span>
                                    Minimum Level</a>
									<?php } elseif  ($row['role_name']=="disposals") {	?>
                                <a class="dropdown-item app_disp_data" href="javascript:void(0)"
                                   data-id="<?php echo $row['event_id']; ?>"><span class="fa fa-check-circle text-success"></span>
                                    Approval for disposal</a>
                                <?php } else {	?>
							<?php  } ?>	
				                  <div class="dropdown-menu" role="menu">
				                   
				                    <div class="dropdown-divider"></div>

				                  </div>
							</tr>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>

			          </div>
        </section>
        <!-- /.content -->
			</div>









			
		</div>
		</div>

</div>
</form>
</div>
<?php require_once('../inc/footer.php'); ?>
<script>

    $(document).ready(function () {
        $('.approve_data').click(function () {

            _conf("Are you sure to approve this Purchase Return ...?", "approve_return", [$(this).attr('data-id')])
        })
         })
		  $(document).ready(function () {
        $('.expiry_data').click(function () {

            _conf("Are you sure not to display this item  ...?", "expiry_return", [$(this).attr('data-id')])
        })
         })
    $(document).ready(function () {
        $('.reject_data').click(function () {

            _conf("Are you sure not to reject this Purchase Return  ...?", "reject_return", [$(this).attr('data-id')])
        })
    })
	$(document).ready(function () {
        $('.app_disp_data').click(function () {

            _conf("Are you sure not to approve this disposal  ...?", "approve_disposal", [$(this).attr('data-id')])
        })
    })


       function approve_return($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=approve_return",
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
	 function approve_disposal($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=approve_disposal",
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
    function reject_return($id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=reject_return",
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
       function expiry_return($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=expiry_return",
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

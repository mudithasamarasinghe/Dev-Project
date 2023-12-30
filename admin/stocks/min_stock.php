<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `items` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<form action="" id="minlevel-form">
<input type="text" name="item_id" id="id"  value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
<input type="text" name="item_id" id="item_id"  value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
<input type="text" name="status" id="status"  value="1">
<div class="card card-outline card-primary">
	<div class="card-body">
		<div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info">Item Name</label>
                   <div><?php echo isset($_GET['nm']) ? $_GET['nm'] : '' ?></div>
                </div>
                <div class="col-md-6">
        			
					                <div class="col-md-6">
                    <label class="control-label text-info">Minimun Level</label>
                   <div><input type="text" name="min_stock" id="min_stock" class="form-control rounded-0" value="<?php echo isset($_GET['mv']) ? $_GET['mv'] : 0; ?>" required=""></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                       
                    </div>
                </div>
                </div>
            </div>
		</div>
		</div>
	</div>
</div>
	</form>
<script>
  
	$(document).ready(function(){
   		$('#minlevel-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_minlevel",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload();
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})
	})
</script>


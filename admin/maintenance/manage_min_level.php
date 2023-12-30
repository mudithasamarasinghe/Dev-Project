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
<div class="container-fluid">
<form action="" id="item-form">
  <div class="row">
    <div class="col">
      <div class="col">
			<label for="name" class="control-label">Name   :</label><?php echo isset($name) ? $name : ''; ?>
		</div>
    </div>
    <div class="col">
      <label for="name" class="control-label">Device/Component   :
			<select name="apparatus" id="apparatus" class="custom-select selevt" disabled >
			<option value="1" <?php echo isset($apparatus) && $apparatus == 1 ? 'selected' : '' ?>>Device</option>
			<option value="0" <?php echo isset($apparatus) && $apparatus == 0 ? 'selected' : '' ?>>Component</option>
			</select></label>
    </div>
  </div>
    <div class="row">
    <div class="col">
      <div class="col">
					<label for="supplier_id" class="control-label">Supplier</label>
			<select name="supplier_id" id="supplier_id" class="custom-select select2" disabled>
			<option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
			<?php 
			$supplier = $conn->query("SELECT * FROM `suppliers` where status = 1 order by `name` asc");
			while($row=$supplier->fetch_assoc()):
			?>
			<option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?> ><?php echo $row['name'] ?></option>
			<?php endwhile; ?>
			</select>	</div>
    </div>
    <div class="col">
            <label for="orderedUnit" class="control-label">Unit</label>

  		<select name="unit" id="unit" class="custom-select selevt" disabled>
                <option value="Pieces" <?php echo isset($unit) && $unit == 0 ? 'selected' : '' ?>>Pieces</option>
                <option value="Bundles" <?php echo isset($unit) && $unit == 1 ? 'selected' : '' ?>>Bundles</option>
                <option value="Dozens" <?php echo isset($unit) && $unit == 2 ? 'selected' : '' ?>>Dozens</option>
                <option value="Kits" <?php echo isset($unit) && $unit == 3 ? 'selected' : '' ?>>Kits</option>
                <option value="Sets" <?php echo isset($unit) && $unit == 4 ? 'selected' : '' ?>>Sets</option>
                <option value="Pairs" <?php echo isset($unit) && $unit == 5 ? 'selected' : '' ?>>Pairs</option>
				<option value="Boxes" <?php echo isset($unit) && $unit == 6 ? 'selected' : '' ?>>Boxes</option>
            </select>
    </div>
  </div>
    <div class="row">
    <div class="col">
   <label for="status" class="control-label">Status</label>
            <select name="status" id="status" class="form-control rounded-0" disabled>
                <option value="1" <?php echo isset($status) && $status =="1" ? "selected": "1" ?> >Active</option>
                <option value="0" <?php echo isset($status) && $status =="0" ? "selected": "0" ?>>Inactive</option>
            </select>
    </div>
  </div>
</form>


	</form>
</div>
<script>
  
	$(document).ready(function(){
        $('.select2').select2({placeholder:"Please Select here",width:"relative"})
		$('#item-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_item",
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

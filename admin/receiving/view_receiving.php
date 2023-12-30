<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT p.* FROM receivings p where p.id = '{$_GET['id']}'");
    if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
        }
        if($from_order == 1){
            $qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_orders p inner join suppliers s on p.supplier_id = s.id  where p.id = '{$form_id}'");
            if($qry->num_rows >0){
                foreach($qry->fetch_array() as $k => $v){
                    if($k == 'id')
                    $k = 'po_id';
                    if(!isset($$k))
                    $$k = $v;
                }
            }
        }else{
            $qry = $conn->query("SELECT b.*,s.name as supplier,p.po_code FROM back_orders b inner join suppliers s on b.supplier_id = s.id inner join purchase_orders p on b.po_id = p.id  where b.id = '{$_GET['bo_id']}'");
            if($qry->num_rows >0){
                foreach($qry->fetch_array() as $k => $v){
                    if($k == 'id')
                    $k = 'bo_id';
                    if(!isset($$k))
                    $$k = $v;
                }
            }
        }
    }
}
if(isset($_GET['po_id'])){
    $qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_orders p inner join suppliers s on p.supplier_id = s.id  where p.id = '{$_GET['po_id']}'");
    if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            if($k == 'id')
            $k = 'po_id';
            $$k = $v;
        }
    }
}
if(isset($_GET['bo_id'])){
    $qry = $conn->query("SELECT b.*,s.name as supplier,p.po_code FROM back_orders b inner join suppliers s on b.supplier_id = s.id inner join purchase_orders p on b.po_id = p.id  where b.id = '{$_GET['bo_id']}'");
    if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            if($k == 'id')
            $k = 'bo_id';
            $$k = $v;
        }
    }
}
?>
<style>
    select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }
</style>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title"><?php echo !isset($id) ? "Receive Order from ".$po_code : 'Update Received Order' ?></h4>
    </div>
    <div class="card-body">
        <form action="" id="receive-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <input type="hidden" name="from_order" value="<?php echo isset($bo_id) ? 2 : 1 ?>">
            <input type="hidden" name="form_id" value="<?php echo isset($bo_id) ? $bo_id : $po_id ?>">
            <input type="hidden" name="po_id" value="<?php echo isset($po_id) ? $po_id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <?php if(!isset($bo_id)): ?>
                    <div class="col-md-6">
                        <label class="control-label text-info">P.O. Code</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($po_code) ? $po_code : '' ?>" readonly>
                    </div>
                    <?php else: ?>
                        <div class="col-md-6">
                        <label class="control-label text-info">B.O. Code</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($bo_code) ? $bo_code : '' ?>" readonly>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id" class="control-label text-info">Supplier</label>
                            <select id="supplier_id" name="supplier_id" class="custom-select select2" readonly="">
                            <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
                            <?php 
                            $supplier = $conn->query("SELECT * FROM `suppliers` where status = 1 order by `name` asc");
                            while($row=$supplier->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?> ><?php echo $row['name'] ?></option>
                            <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-striped table-bordered" id="list">
                       <colgroup>
                  
                    <col width="30%">
					<col width="20%">
                    <col width="30%">
                    <col width="20%">
                    </colgroup>
                    <thead>
                        <tr class="text-light bg-navy">
                            
							    <th class="text-center py-1 px-2">Item</th>
								<th class="text-center py-1 px-2">Expiry Date</th>
								 <th class="text-center py-1 px-2">Unit</th>
                            <th class="text-center py-1 px-2">Qty</th>
                           
                        
                        </tr>
                    <tbody>
                        <?php
                        if(isset($po_id)):
						$in=0;
						$out=0;
						$in0=0;
						$out0=0;
                        $qry = $conn->query("SELECT p.*,i.id,i.name,i.description FROM `po_items` p inner join items i on p.item_id = i.id where p.po_id = '{$po_id}'");
                        while($row = $qry->fetch_assoc()):	
						//$totqty = $row['quantity'];
						 $in0 = $conn->query("SELECT SUM(quantity) as total FROM stock_list where rec_id = '{$_GET['id']}' and item_id='{$row['id']}' and type = 1")->fetch_array()['total'];
                         $out0 = $conn->query("SELECT SUM(quantity) as total FROM stock_list where rec_id = '{$_GET['id']}' and item_id='{$row['id']}' and type = 2")->fetch_array()['total'];
                        
						  $in = $in+$in0;
						  $out = $out+$out0;
						  
						  $totqty = 0;
                        $row['qty'] = $row['quantity'];
                        $date_created = $conn->query("SELECT DATE_FORMAT(sl.expiry_date,'%m/%d/%Y') expiry_date,sl.quantity FROM receivings r inner join stock_list sl on sl.rec_id=r.id where r.form_id='{$po_id}' and sl.item_id = '{$row['item_id']}' and sl.rec_id = '{$_GET['id']}'");
						        if($date_created->num_rows >0){
							 while($rowdc = $date_created->fetch_assoc()):
							$totqty = $rowdc['quantity']; 
							  if ($rowdc['expiry_date']=="00/00/0000" ){
								  $dc ="No expiry date";
							  } else {
							  $dc = (new DateTime($rowdc['expiry_date']))->format('Y-m-d'); 
							  }							  
							  endwhile;
								} else {
									$dc="";
								}
				
                        ?>
  
							<?php if ($totqty==0) { ?>
								
								<?php } else { ?>
								                      <tr>
							<td class="py-1 px-2 item">
                            <?php echo $row['name']; ?>
                            </td>
              <td class="py-1 px-2 text-center expiry_date"><?php echo $dc; ?></td>
							 <td class="py-1 px-2 text-center unit">
                            <?php echo $row['unit']; ?>
                            </td>
								<td class="text-center" ><?php echo number_format($totqty) ?></td>
													
							    
                                <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                                <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                                <input type="hidden" name="oqty[]" value="<?php echo $row['quantity']; ?>">
                            </td>
                        </tr>
								<?php } ?>
			
                        <?php endwhile; ?>
                        <?php endif; ?>	
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks" class="text-info control-label">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control rounded-0" readonly ><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer py-1 text-center">
        <!--<button class="btn btn-flat btn-primary" type="submit" form="receive-form">Save</button>-->
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=receiving' ?>">Cancel</a>
    </div>
</div>
<script>
    $(function(){
        $('.select2').select2({
            placeholder:"Please select here",
            width:'resolve',
        })
        $('#receive-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_receiving",
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
					if(resp.status == 'success'){
						location.replace(_base_url_+"admin/index.php?page=receiving/view_receiving&id="+resp.id);
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
                    $('html,body').animate({scrollTop:0},'fast')
				}
			})
		})

        if('<?php echo (isset($id) && $id > 0) || (isset($po_id) && $po_id > 0) ?>' == 1){
            $('#supplier_id').attr('readonly','readonly')
            $('table#list tbody tr .rem_row').click(function(){
                rem($(this))
            })
                console.log('test')
            $('[name="qty[]"],[name="discount_perc"],[name="tax_perc"]').on('input',function(){
            })
        }
    })
    function rem(_this){
        _this.closest('tr').remove()
        if($('table#list tbody tr').length <= 0)
            $('#supplier_id').removeAttr('readonly')

    }
</script>

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
                        <col width="3%">
						 <col width="30%">
                        <col width="20%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr class="text-light bg-navy">
                            <th class="text-center py-1 px-2"></th>
							    <th class="text-center py-1 px-2">Item</th>
								<th class="text-center py-1 px-2">Expiry Date</th>
								 <th class="text-center py-1 px-2">Unit</th>
                            <th class="text-center py-1 px-2">Qty</th>
                           
                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php
              if(isset($po_id)):
			            $qry = $conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join items i on p.item_id = i.id where p.po_id = '{$po_id}'");
                         $stock_ids="";
						while($row = $qry->fetch_assoc()):	
				            $itemids = $conn->query("SELECT item_id,quantity FROM `po_items` where po_id = '{$row['po_id']}' and item_id = '{$row['item_id']}'");
							 
							 while($rowids=$itemids->fetch_assoc()):
							 
							 $qty = $conn->query("SELECT sl.* FROM `stock_list`sl inner join receivings r on sl.rec_id=r.id where r.form_id='{$po_id}' and sl.item_id = '{$row['item_id']}'");
							 $totqty = $rowids['quantity'];
							  while($rowqty = $qty->fetch_assoc()):
							  $totqty = $totqty-$rowqty['quantity'];  
							  endwhile;
                            endwhile; 
					
                        ?>
						<?php echo $row['quantity'];
						if ($totqty > 0){ ?>
                        <tr>
                            <td class="py-1 px-2 text-center">
                                <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                            </td>    <td class="py-1 px-2 item">
                            <?php echo $row['name']; ?> <br>
                            <?php echo $row['description']; ?>
							 <td class="py-1 px-2 text-center expiry_date"><input type="date" name="expiry_date[]" value=""></td>
                            </td><td class="py-1 px-2 text-center unit">
                            <?php echo $row['unit']; ?>
                            </td>
                            <td class="py-1 px-2 text-center qty">
                                <input type="number" name="qty[]" style="width:50px !important"  value="<?php echo $totqty; ?>" max = "<?php echo $row['quantity']; ?>" min="0">
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
                            <textarea name="remarks" id="remarks" rows="3" class="form-control rounded-0"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-primary" type="submit" form="receive-form" href="<?php echo base_url.'/admin/index.php?page=receiving' ?>" id="btnSave">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=receiving' ?>">Cancel</a>
    </div>
</div>
<script>
    $(function(){
        $('.select2').select2({
            placeholder:"Please select here",
            width:'resolve',
        })
///////////

var formBefore;

var $inps = $('#receive-form').find('input,select,textarea');
formBefore = $("#receive-form").serialize();
$(function() {
    formBefore = $("#receive-form").serialize();
});
$inps.change(function () {
    var changedForm = $("#receive-form").serialize();
    if (formBefore != changedForm) {
$("#btnSave").prop("disabled", false);
    } else {
		$("#btnSave").prop("disabled", true);
    }
});
//////////////////////
        $('#receive-form').submit(function(event){
			event.preventDefault();
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

        }
    })
    function rem(_this){
        _this.closest('tr').remove()
        if($('table#list tbody tr').length <= 0)
            $('#supplier_id').removeAttr('readonly')

    }
</script>

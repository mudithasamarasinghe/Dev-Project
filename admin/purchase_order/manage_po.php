<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_orders p inner join suppliers s on p.supplier_id = s.id where p.id = '{$_GET['id']}'");
    if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
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
        <h4 class="card-title"><?php echo isset($id) ? "Purchase Order Details - ".$po_code : 'Create New Purchase Order' ?></h4>
    </div>
    <div class="card-body">
        <form action="" id="po-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
			
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        // Fetch the last PO There is no item for this suplier.code from the database
                        $qry = $conn->query("SELECT po_code FROM `purchase_orders` ORDER BY po_code DESC LIMIT 1");
                        if($qry->num_rows >0) {
                            $last_po_code = $qry->fetch_assoc()['po_code'];

                                // Extract the numeric part from the last PO code and increment it
                                $numeric_part = intval(substr($last_po_code, 3));
                                $next_numeric_part = $numeric_part + 1;

                                // Concatenate the prefix "PO-" with the incremented numeric part to get the next PO code
                                $next_po_code = "PO-" . sprintf("%03d", $next_numeric_part);
//                            }
                        } else {
                            $next_po_code = "PO-001";
                        }
                        ?>

                        <label class="control-label text-info">P.O. Code</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($next_po_code) ? $next_po_code : '' ?>" readonly>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id" class="control-label text-info">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="custom-select select2">
                                <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
                                <?php
								$unit_arr = array();
                                $supplier = $conn->query("SELECT * FROM `suppliers` where status = 1 and id<>0 order by `name` asc");
                                while($row=$supplier->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?> ><?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <fieldset>
                    <legend class="text-info">Item Form</legend>
                    <div class="row justify-content-center align-items-end">
                        <?php
                        $item_arr = array();
                        $unit_arr = array();
						 $bom_arr = array();
                        $item = $conn->query("SELECT * FROM `items` where status = 1 order by `name` asc");
                        while($row=$item->fetch_assoc()):
                            $item_arr[$row['supplier_id']][$row['id']] = $row;
                            $unit_arr[$row['id']] = $row['unit'];
							$bom_arr[$row['id']] = $row['bom_id'];
                        endwhile;
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="item_id" class="control-label">Item</label>
                                <select  id="item_id" class="custom-select ">
                                    <option disabled selected></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit" class="control-label">Unit</label>
                                <input type="text" class="form-control rounded-0" id="unit" value="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="qty" class="control-label">Qty</label>
                                <input type="number" step="any" class="form-control rounded-0" id="qty">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"  style="width:100%">
                                <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Add to List</button>
					             </div>
                            </div>
                        </div>
					
                </fieldset>
                <hr>
                <table class="table table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="30%">
                        <col width="30%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2"></th>
                        <th class="text-center py-1 px-2">Item</th>
                        <th class="text-center py-1 px-2">Unit</th>
                        <th class="text-center py-1 px-2">Qty</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($id)):
                        $qry = $conn->query("SELECT p.*,i.name,i.description FROM `po_items` p left join items i on p.item_id = i.id where p.po_id = '{$id}'");
                        while($row = $qry->fetch_assoc()):
                            ?>
                            <tr data-id="<?php echo $row['item_id']; ?>">
                                <td class="py-1 px-2 text-center">
                                    <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                                </td>
                                <td class="py-1 px-2 text-left item">
                                    <span class="visible">
                                        <?php echo $row['name']; ?>
                                        <?php echo $row['description']; ?>
                                        </span>
                                    <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                                    <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                                    <input type="hidden" name="qty[]" value="<?php echo $row['quantity']; ?>">
                                </td>
                                <td class="py-1 px-2 text-center unit">
                                    <?php echo $row['unit']; ?>
                                </td>
                                <td class="qty" style="text-align:right;padding-right:11%;">
                                    <?php echo number_format($row['quantity']); ?>
                                </td>
                            </tr>
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
        <button class="btn btn-flat btn-primary" type="submit" form="po-form" id="btnSave">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=purchase_order' ?>">Cancel</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 item">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 text-center qty">
            <span class="visible"></span>
            <input type="hidden" name="item_id[]">
            <input type="hidden" name="unit[]">
            <input type="hidden" name="qty[]">
        </td>

    </tr>
</table>
<script>
    var items = $.parseJSON('<?php echo json_encode($item_arr) ?>')
    var units = $.parseJSON('<?php echo json_encode($unit_arr) ?>')
	var boms = $.parseJSON('<?php echo json_encode($bom_arr) ?>')
    $(function(){
        $('.select2').select2({
            placeholder:"Please select here",
            width:'resolve',
        })
        $('#item_id').select2({
            placeholder:"Please select supplier first",
            width:'resolve',
        })
        $('#item_id').change(function(){
            var item = $('#item_id').val()
            var tunit = units[item];
            $('#unit').val(tunit);
        })

        $('#supplier_id').change(function(){
			
			var options = $('#supplier_id').get(0).options;
            var supplier_id = $(this).val()
            $('#item_id').select2('destroy')

            if(!!items[supplier_id]){

                $('#item_id').html('')

                var list_item = new Promise(resolve=>{
                    Object.keys(items[supplier_id]).map(function(k){
                        var row = items[supplier_id][k]
                        var opt = $('<option>')
                        opt.attr('value',row.id)
                        opt.text(row.name)

                        $('#item_id').append(opt)
                    })
                    resolve()
                })
                list_item.then(function(){

                    $('#item_id').select2({
                        placeholder:"Please select item here",
                        width:'resolve',
                    })
                })
            }else{
				location.reload();
				alert('There is no item for this supplier.');

                list_item.then(function(){

                    $('#supplier_id').attr('disabled','disabled');
                    $('#item_id').select2({
                        placeholder:"No Items Listed yet",
                        width:'resolve',
                    })
                })
            }
            var item = $('#item_id').val()
            var tunit = units[item];
            $('#unit').val(tunit);
            $('#unit').attr('readonly','readonly')
        })

        $('#add_to_list').click(function(){
			
            var supplier = $('#supplier_id').val()
            var item = $('#item_id').val()
            var qty = $('#qty').val() > 0 ? $('#qty').val() : 0;
            var unit = $('#unit').val()
            // console.log(supplier,item)
            var item_name = items[supplier][item].name;
            var item_description = items[supplier][item].description;
            var tr = $('#clone_list tr').clone()
            if(item == '' || qty == '' || unit == '' ){
                alert_toast('Form Item textfields are required.','warning');
                return false;
            }
            if($('table#list tbody').find('tr[data-id="'+item+'"]').length > 0){
                alert_toast('Item is already exists on the list.','error');
                return false;
            }
			
			
            tr.find('[name="item_id[]"]').val(item)
            tr.find('[name="unit[]"]').val(unit)
            tr.find('[name="qty[]"]').val(qty)
            tr.attr('data-id',item)
            tr.find('.qty .visible').text(qty)
            tr.find('.unit').text(unit)
            tr.find('.item').html(item_name+'<br/>'+item_description)
            $('table#list tbody').append(tr)
            $('#item_id').val('').trigger('change')
            $('#qty').val('')
            $('#unit').val('')
            tr.find('.rem_row').click(function(){
                rem($(this))
            })
            $('#supplier_id').attr('readonly','readonly')
        })
		
        $('#add_bom_to_list').click(function(){
			
            var supplier = $('#supplier_id').val()
            var item = $('#item_id').val()
            var qty = $('#qty').val() > 0 ? $('#qty').val() : 0;
            var unit = $('#unit').val()
            // console.log(supplier,item)
            var item_name = items[supplier][item].name;
            var item_description = items[supplier][item].description;
            var tr = $('#clone_list tr').clone()
            if(item == '' || qty == '' || unit == '' ){
                alert_toast('Form Item textfields are required.','warning');
                return false;
            }
            if($('table#list tbody').find('tr[data-id="'+item+'"]').length > 0){
                alert_toast('Item is already exists on the list.','error');
                return false;
            }
			
			
            tr.find('[name="item_id[]"]').val(item)
            tr.find('[name="unit[]"]').val(unit)
            tr.find('[name="qty[]"]').val(qty)
            tr.attr('data-id',item)
            tr.find('.qty .visible').text(qty)
            tr.find('.unit').text(unit)
            tr.find('.item').html(item_name+'<br/>'+item_description)
            $('table#list tbody').append(tr)
            $('#item_id').val('').trigger('change')
            $('#qty').val('')
            $('#unit').val('')
            tr.find('.rem_row').click(function(){
                rem($(this))
            })
            $('#supplier_id').attr('readonly','readonly')
        })
///////////

var formBefore;

var $inps = $('#po-form').find('input,select,textarea');

$(function() {
    formBefore = $("#po-form").serialize();
});

$inps.change(function () {
    var changedForm = $("#po-form").serialize();
    if (formBefore != changedForm) {
//$("#btnSave").css('background-color', 'green');
$("#btnSave").prop("disabled", false);
    } else {
  //      $("#btnSave").css('background-color', '');
		$("#btnSave").prop("disabled", true);
    }
});
//////////////////////



        $('#po-form').submit(function(event){
            event.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_po",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    alert_toast("An error occured 1",'error');
                    end_loader();
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.replace(_base_url_+"admin/index.php?page=purchase_order/view_po&id="+resp.id);
                    }else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        end_loader()
                    }else{
                        alert_toast("An error occured 2",'error');
                        end_loader();
                        console.log(resp)
                    }
                    $('html,body').animate({scrollTop:0},'fast')
                }
            })
        })

        if('<?php echo isset($id) && $id > 0 ?>' == 1){
            $('#supplier_id').trigger('change')
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

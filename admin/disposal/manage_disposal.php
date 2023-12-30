<?php
require_once('../config.php');

if(isset($_GET['id'])){
	var_dump($_GET['id']);
    $qry = $conn->query("SELECT r.*,s.id,s.name as supplier FROM disposal_list r inner join suppliers s on r.supplier_id = s.id  where r.id = '{$_GET['id']}'");
    if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
			echo  $v;
        }
    }
}

?>
<style>
.select-option-secondary-text {
    font-size: var(--mdb-form-outline-select-option-secondary-text-font-size);
    color: var(--mdb-form-outline-select-option-secondary-text-color);
    display: block;
    line-height: normal;
}
</style>
<form action="" id="disposal-form">
<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
<input type="hidden" id="supplier_id" name="supplier_id" value="<?php echo isset($supplier_id) ? $supplier_id : '' ?>">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <?php
            // Fetch the last PO There is no item for this suplier.code from the database
            $qry = $conn->query("SELECT disposal_code FROM `disposal_list` ORDER BY disposal_code DESC LIMIT 1");
            if($qry->num_rows >0) {
                $last_disp_code = $qry->fetch_assoc()['disposal_code'];

                // Extract the numeric part from the last Sales code and increment it
                $numeric_part = intval(substr($last_disp_code, 3));
                $next_numeric_part = $numeric_part + 1;

                // Concatenate the prefix "PO-" with the incremented numeric part to get the next PO code
                $next_disp_code = "DISP-" . sprintf("%03d", $next_numeric_part);
//                            }
            } else {
                $next_disp_code = "DISP-001";
            }
            ?>
            <label class="control-label text-info">DisposaL Code</label>
            <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($next_disp_code) ? $next_disp_code : '' ?>" readonly>
        </div>

    </div>
    <hr>
    <fieldset>
        <legend class="text-info">Item Form</legend>
        <div class="row justify-content-center align-itemsDATA-ID-end">
            <?php
            $item_arr = array();
            $unit_arr = array();
            $qty_arr = array();
			$supid_arr = array();
			$supnm_arr = array();
			$poid_arr = array();
            $item = $conn->query("SELECT i.*,SUM(sl.quantity) as quantity,i.unit,s.name spnm ,i.supplier_id spid ,sl.form_id FROM `items` i inner join stock_list sl on i.id = sl.item_id  left join suppliers s on i.supplier_id = s.id where i.status = 1 and sl.quantity > 0 group by i.id,i.name order by i.name asc");
            $stock_arr = array();
			$in=0;
			$out=0;
            while($row=$item->fetch_assoc()):			
				$in = $conn->query("SELECT SUM(quantity) as total FROM stock_list where item_id = '{$row['id']}' and type = 1")->fetch_array()['total'];
                $out = $conn->query("SELECT SUM(quantity) as total FROM stock_list where item_id = '{$row['id']}' and type != 1")->fetch_array()['total'];
                $item_arr[$row['id']] = $row;
                $unit_arr[$row['id']] = $row['unit'];
                $qty_arr[$row['id']]=($in-$out);
                $poid_arr[$row['id']] = $row['form_id'];
            endwhile;
            ?>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="item_id" class="control-label">Item</label>
                    <select  id="item_id" class="custom-select select2">
                        <option disabled selected></option>
                        <?php foreach($item_arr as $k =>$v): 					
						
						$supnm_arr[$k]=$v['spnm'];
						$supid_arr[$k]=$v['spid'];
						?>
                            <option value="<?php echo $k ?>"><?php echo $v['name'] ?>-<?php echo $v['spnm'] ?></option>
                        <?php endforeach; ?>
                    </select>
					<small class="text-muted" id="sname"></small>
                </div>
				
            </div>
			
            <div class="col-md-3">
                <div class="form-group">
                    <label for="unit" class="control-label">Unit</label>
                    <input type="text" class="form-control rounded-0" id="unit" value="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="qty" class="control-label">Qty</label>
                    <input type="number" step="any" class="form-control rounded-0" id="qty" placeholder="">
                </div>
            </div>

                    <input type="hidden" step="any" class="form-control rounded-0" id="poid" placeholder="">
   
            <div class="col-md-3 text-center">
                <div class="form-group">
                    <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Add to List</button>
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
            $qry = $conn->query("SELECT d.*,i.name,i.description FROM `disposal` d inner join items i on d.item_id = i.id where d.sales_id = '{$id}'");
            while($row = $qry->fetch_assoc()):
                ?>
                <tr>
                    <td class="py-1 px-2 text-center">
                        <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                    </td>
                    <td class="py-1 px-2 item">
                        <?php echo $row['name']; ?> <br>
                        <?php echo $row['description']; ?>
                    </td>
                    <td class="py-1 px-2 text-center unit">
                        <?php echo $row['unit']; ?>
                    </td>
                    <td class="py-1 px-2 text-center qty">
                        <span class="visible"><?php echo number_format($row['quantity']); ?></span>
                        <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                        <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                        <input type="hidden" name="qty[]" value="<?php echo $row['quantity']; ?>">
						<input type="hidden" name="poid[]" value="<?php echo $row['form_id']; ?>">
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
    <button class="btn btn-flat btn-primary" type="submit" form="disposal-form">Save</button>
    <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=sales' ?>">Cancel</a>
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
			<input type="hidden" name="poid[]">
        </td>
    </tr>
</table>
<script>
    var items = $.parseJSON('<?php echo json_encode($item_arr) ?>')
    var units = $.parseJSON('<?php echo json_encode($unit_arr) ?>')
    var qty = $.parseJSON('<?php echo json_encode($qty_arr) ?>')
	var supnm = $.parseJSON('<?php echo json_encode($supnm_arr) ?>')
 var supid = $.parseJSON('<?php echo json_encode($supid_arr) ?>')
var poid = $.parseJSON('<?php echo json_encode($poid_arr) ?>')
    $(function(){
        $('.select2').select2({
            placeholder:"Please select here",
            width:'resolve',
        })
        $('#item_id').change(function(){
	        var item = $('#item_id').val()
            var tunit = units[item];
            var tqty = qty[item];
			var tsupnm = supnm[item];
			var tsupid = supid[item];
			var tpoid = poid[item];
			document.getElementById('sname').innerHTML ="";
			//document.getElementById('supplier_id').value ="";
            $('#unit').val(tunit);
			$('#poid').val(tpoid);
            //$('#qty').val(tqty);
			
			$('#qty').attr('max',tqty);
			if ( typeof(tqty) !== "undefined" && tqty !== null ){
			$('#qty').attr('placeholder', 'Max. stock value '+tqty);
			} else {
				$('#qty').attr('placeholder', '');
			}
			if ( typeof(tsupid) !== "undefined" && tsupid !== null ){
			document.getElementById('sname').innerHTML = 'Supplier :'+ tsupnm;
			}
			if ( typeof(tsupid) !== "undefined" && tsupid !== null ){
		    $('#supplier_id').val(tsupid);
			}
			
        })
        $('#add_to_list').click(function(){
            var client = $('#client').val()
            var item = $('#item_id').val()
            var qty = $('#qty').val() > 0 ? $('#qty').val() : 0;
            var unit = $('#unit').val()
			var poids = $('#poid').val()
            var item_id = $('#item_id').val()
            var item_name = items[item].name;
            var item_description = items[item].description;
            var tr = $('#clone_list tr').clone()
            if(item == '' || qty == '' || unit == '' ){
                alert_toast('Form Item textfields are required.','warning');
                return false;
            }
			
			
			var qty0 = $.parseJSON('<?php echo json_encode($qty_arr) ?>')
			var item0 = $('#item_id').val()
			var tqty0 = qty0[item0];
   	       if(qty > tqty0){
                alert_toast('Quantity exceeds the available quantity .','error');
                return false;
            }
			
			
			
            if($('table#list tbody').find('tr[data-id="'+item+'"]').length > 0){
                alert_toast('Item is already exists on the list.','error');
                return false;
            }
	
	
            tr.find('[name="item_id[]"]').val(item)
            tr.find('[name="unit[]"]').val(unit)
            tr.find('[name="qty[]"]').val(qty)
			tr.find('[name="poid[]"]').val(poids)
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
            $('#client').attr('readonly','readonly')
        })
        $('#disposal-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_disposal",
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
                        location.replace(_base_url_+"admin/index.php?page=disposal/view_disposal&id="+resp.id);
                    }else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        end_loader()
                    }else{
                        alert_toast("An error occured1",'error');
                        end_loader();
                        console.log(resp)
                    }
                    $('html,body').animate({scrollTop:0},'fast')
                }
            })
        })

        if('<?php echo isset($id) && $id > 0 ?>' == 1){
            $('#client').trigger('change')
            $('#client').attr('readonly','readonly')
            $('table#list tbody tr .rem_row').click(function(){
                rem($(this))
            })
        }
    })
    function rem(_this){
        _this.closest('tr').remove()
        if($('table#list tbody tr').length <= 0)
            $('#client').removeAttr('readonly')

    }
</script>


<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT r.*,s.id,s.name as supplier FROM return_list r inner join suppliers s on r.supplier_id = s.id  where r.id = '{$_GET['id']}'");
    if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
			echo  $v;
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
        <h4 class="card-title"><?php echo isset($id) ? "Return Details - ".$return_code : 'Create New Return Record' ?></h4>
    </div>
    <div class="card-body">
        <form action="" id="return-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
			<input type="hidden" id="supplier_id" name="supplier_id" value="<?php echo isset($supplier_id) ? $supplier_id : '' ?>">
			<input type="hidden" name="stock_ids" value="<?php echo isset($stock_ids) ? $stock_ids : '' ?>">
			<input type="hidden" id="form_id" oninput="" name="form_id" value="<?php echo isset($form_id) ? $form_id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        // Fetch the last Return There is no item for this suplier.code from the database
                        $qry = $conn->query("SELECT return_code FROM `return_list` ORDER BY return_code DESC LIMIT 1");
                        if($qry->num_rows >0) {
                            $last_return_code = $qry->fetch_assoc()['return_code'];

                            // Extract the numeric part from the last Return code and increment it
                            $numeric_part = intval(substr($last_return_code, 3));
                            $next_numeric_part = $numeric_part + 1;

                            // Concatenate the prefix "R-" with the incremented numeric part to get the next Return code
                            $next_return_code = "R-" . sprintf("%03d", $next_numeric_part);
//                            }
                        } else {
                            $next_return_code = "R-001";
                        }
                        ?>
                        <label class="control-label text-info">Return Code</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($next_return_code) ? $next_return_code : '' ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="po_code" class="control-label text-info">Purchase Order</label>
                            <select  id="po_code" class="custom-select select2" onchange="fetch_items(this.value)">
                            <option <?php echo !isset($po_code) ? 'selected' : '' ?> disabled></option>
                            <?php 
                            $supplier = $conn->query("SELECT * FROM `purchase_orders` where (status = 1 or status = 2) order by `po_code` asc");
                            while($row=$supplier->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id'] ?>" data-id="<?php echo $row['supplier_id'] ?>"  <?php echo isset($id) && $id == $row['id'] ? "selected" : "" ?> ><?php echo $row['po_code'] ?></option>
                            <?php endwhile; ?>
                            </select>
                        </div>
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
                        $item = $conn->query("SELECT i.*,pi.po_id,po.supplier_id FROM `items` i inner join po_items pi on  i.id=pi.item_id inner join purchase_orders po on pi.po_id=po.id where i.status = 1 order by i.name asc");
						$tqty =0;
 while($row=$item->fetch_assoc()):					
//////////////		]
  $qry2 = $conn->query("SELECT sum(quantity) qty FROM stock_list where item_id = '".$row['id']."' group by item_id ");
  while($row2 = $qry2->fetch_assoc()):                                             
  $tqty = $row2['qty'];
  endwhile;
  $qry3 = $conn->query("SELECT id,name FROM suppliers where id = '".$row['supplier_id']."'");
  while($row3 = $qry3->fetch_assoc()):                                             
  $tsupname = $row3['name'];
  endwhile;
///////////////			
			
						
						
						
                            $item_arr[$row['po_id']][$row['id']] = $row;
                            $unit_arr[$row['id']] = $row['unit'];
							$qty_arr[$row['id']] = $tqty;
							$supplier_arr[$row['id']]=$row['supplier_id'];
							$suppliernm_arr[$row['po_id']]=$tsupname;
                        endwhile;
                        ?>
<div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="item_id" class="control-label">Item</label>
                                <select  id="item_id" class="custom-select ">
                                    <option disabled selected></option>
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qty" class="control-label">Qty</label>
                                <input type="number" step="any" max="" step="1%" class="form-control rounded-0" id="qty" data-bind="value:replyNumber">
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Add to List</button>
                            </div>
                        </div>
						</div>
                </fieldset>
                <hr>
                <table class="table table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="10%">
                        <col width="25%">
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
                        $qry = $conn->query("SELECT s.*,i.name,i.description FROM `stock_list` s inner join items i on s.item_id = i.id where s.id in ({$stock_ids})");
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
        <button class="btn btn-flat btn-primary" type="submit" form="return-form">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=return' ?>">Cancel</a>
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
	var qtys = $.parseJSON('<?php echo json_encode($qty_arr) ?>')
	var supid = $.parseJSON('<?php echo json_encode($supplier_arr) ?>')
	var supnm = $.parseJSON('<?php echo json_encode($suppliernm_arr) ?>') 
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
	        var tunit = $('#item_id').find(':selected').attr('data-alpha');
			var tqty = $('#item_id').find(':selected').attr('data-id');	
            $('#unit').val(tunit);
			$('#qty').attr('max',tqty);
			if ( typeof(tqty) !== "undefined" && tqty !== null ){
			$('#qty').attr('placeholder', 'Max. onhand stock value '+tqty);
			} else {
				$('#qty').attr('placeholder', '');
			}
        })
$(document).on('change', '#po_code', function() { 
$('#unit').val("");
$('#qty').val(""); 
$('#qty').attr('placeholder', ''); 
		var item = $('#item_id').val()
	        var tunit = $('#item_id').find(':selected').attr('data-alpha');
			var tqty = $('#item_id').find(':selected').attr('data-id');	
            $('#unit').val(tunit);
$('#qty').attr('max',tqty);
			if ( typeof(tqty) !== "undefined" && tqty !== null ){
			$('#qty').attr('placeholder', 'Max. onhand stock value '+tqty);
			} else {
				$('#qty').attr('placeholder', '');
			}
        	var options = $('#po_code').get(0).options;
            var po_code = $(this).val()
			//fetch_items(po_code);
            var item = $('#item_id').val()
			document.getElementById('sname').innerHTML ="";
			var tsuppid = $('#po_code').find(':selected').attr('data-id');
			
			$('#supplier_id').val(tsuppid);
			$('#form_id').val(po_code);
	/////////////
var t_suppid = $('#po_code').find(':selected').attr('value');
	
const object = supnm;

for (const [key, value] of Object.entries(object)) {
	if (key==t_suppid) {
		document.getElementById('sname').innerHTML = 'Supplier :'+ value;
        var dataid = $("#item_id option:selected").attr('data-id');

	}
}
	////////////////////		
			
			
				//	document.getElementById('sname').innerHTML = tsnm;
            $('#item_id').select2('destroy')
            /*
			if(!!items[po_code]){
                $('#item_id').html('')
                var list_item = new Promise(resolve=>{
                    Object.keys(items[po_code]).map(function(k){
                        var row = items[po_code][k]
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
                list_item.then(function(){
					 $('#po_code').attr('disabled','disabled');
                    $('#item_id').select2({
                        placeholder:"No Items Listed yet",
                        width:'resolve',
                    })
                })
            }
			*/
            var item = $('#item_id').val()
	        var tunit = $('#item_id').find(':selected').attr('data-alpha');
			var tqty = $('#item_id').find(':selected').attr('data-id');	
            $('#unit').val(tunit);
			$('#qty').attr('max',tqty);
			if ( typeof(tqty) !== "undefined" && tqty !== null ){
			$('#qty').attr('placeholder', 'Max. onhand stock value '+tqty);
			} else {
				$('#qty').attr('placeholder', '');
			}
            $('#unit').attr('readonly','readonly')
        })

        $('#add_to_list').click(function(){
            var supplier = $('#po_code').val()
            var item = $('#item_id').val()
            var tunit = $('#item_id').find(':selected').attr('data-alpha');
			var tqty = $('#item_id').find(':selected').attr('data-id');	
            //var tqty = qtys[item];
if ( tqty - $('#qty').val() < 0 ) {
alert_toast('Quantity that you are trying to enter exceeds the stock quantity.','error');
                return false;	
}
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
			unit = $('#item_id').find(':selected').attr('data-alpha');
			qty = $('#qty').val(); 
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
            

            $('#po_code').attr('readonly','readonly')
        })
        $('#return-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_return",
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
						location.replace(_base_url_+"admin/index.php?page=return");
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(testqty)
					}
                    $('html,body').animate({scrollTop:0},'fast')
				}
			})
		})

        if('<?php echo isset($id) && $id > 0 ?>' == 1){
              $('#po_code').trigger('change')
            $('#po_code').attr('readonly','readonly')
            $('table#list tbody tr .rem_row').click(function(){
                rem($(this))
            })
        }
    })
    function rem(_this){
        _this.closest('tr').remove()
          if($('table#list tbody tr').length <= 0)
            $('#po_code').removeAttr('readonly')

    }
function fetch_items($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=fetch_select",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
	     	console.log(resp);
            $("#item_id").html(resp);

       		 end_loader();

			}
		})
	}
</script>

<?php 

if(isset($_GET['id'])){
   $qry = $conn->query("SELECT * FROM `items` WHERE `apparatus`=1 and bom_id='{$_GET['id']}' order by `date_created` desc");
  if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
        }
    }
	
	  $qry = $conn->query("SELECT p.* FROM bill_of_materials p where p.bom_id = '{$_GET['id']}'");
if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
        }
    }
}
$id = $_GET['id'];
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title"><?php echo isset($id) ? "BOM Details" : 'Create New Purchase Order' ?></h4>
    </div>
    <div class="card-body">
        <form action="" id="po-form">
            <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">BOM Id.</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bom_device_name" class="control-label text-info">BOM device name</label>
  <input type="text" class="form-control form-control-sm rounded-0" id="bom_device_name" value="<?php echo isset($_GET['nm']) ? $_GET['nm'] : '' ?>" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <fieldset>
                    <legend class="text-info">Item Form</legend>
                    <div class="row justify-content-center align-items-end">
                        <div class="col-md-1">
                            <div class="form-group">
                                 </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group text-center">
                                <label for="item_name" class="control-label">Item</label>
  								<input type="text" class="form-control rounded-0" name="item_name" id="item_name">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group text-center">
                                <label for="supplier" class="control-label">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="custom-select select2">
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
                        <div class="col-md-1">
                            <div class="form-group text-center">
                                <label for="bom_quantity" class="control-label">Qty.</label>
                                <input type="number" step="any" class="form-control rounded-0 text-center" id="bom_quantity">
                            </div>
                        </div>
							<div class="col-md-2">
                            <div class="form-group text-center">
                                <label for="status" class="control-label">Status</label>
           <select name="status" id="status" class="custom-select selevt" style="padding-left: 0px;">
			<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
			<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
			</select>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Add to List</button>
                            </div>
                        </div>
                </fieldset>
                <hr>
                <table class="table table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="21%">
                        <col width="22%">
                        <col width="5%">
                        <col width="10%">

                    </colgroup>
                    <thead>
                        <tr>
                          <th></th>

                            <th class="text-center py-1 px-2">Item</th>
							<th class="text-center py-1 px-2">Supplier</th>
                            <th class="text-center py-1 px-2">Qty.</th>
                            <th class="text-center py-1 px-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        if(isset($id)):
                    $qry = $conn->query("SELECT i.*,s.name snm,COALESCE(sl.quantity,0) qty FROM `items` i inner join `suppliers`s on i.supplier_id=s.id left join stock_list sl on i.id=sl.item_id WHERE i.apparatus=0 and i.bom_id='{$_GET['id']}' order by i.date_created desc");
						   while($row = $qry->fetch_assoc()):
                        ?>

						
						
                        <tr data-id="<?php echo $row['supplier_id']; ?>">
				                            <td class="py-1 px-1 text-center">
                                <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                            </td>
                            <td class="py-1 px-1 text-left item_name">
                                <span class="visible"><?php echo $row['name']; ?></span>
                                <input type="hidden" name="item_name[]" value="<?php echo $row['name']; ?>">
                                <input type="hidden" name="item_id[]" value="<?php echo $row['id']; ?>">
                                                    </td>
                			<td class="py-1 px-1 text-left supplier_id">
                            	<select name="supplier_id[]" id="supplier_id" style="padding-left: 0px;" class="custom-select select2">
                                <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
                                <?php
$supplier = $conn->query("SELECT s.name snm,i.supplier_id,i.id itemid  FROM `suppliers`s inner join items i on s.id=i.supplier_id where s.status = 1 and i.name='{$row['name']}' order by s.name asc");
while($row0=$supplier->fetch_assoc()):
if ($row['supplier_id'] == $row0['supplier_id'])
{ ?>
    <option value="<?php echo $row['supplier_id'] ?>" selected><?php echo $row0['snm'] ?></option>
                            

<?php } else { ?>
  <option value="<?php echo $row['supplier_id'] ?>" ><?php echo $row0['snm'] ?></option>
<?php } ?>

                                    ?>
                                     
								<?php endwhile; ?>
                            </select>
                            </td>
<td class="py-1 px-1 text-right bom_quantity">
							                               <?php
$qtysql = $conn->query("SELECT bom.bom_quantity qty  FROM `bill_of_materials` bom where bom.device_id='{$_GET['id']}' and bom.item_id='{$row['id']}'");


  if($qtysql->num_rows >0)
{ 
while($row1=$qtysql->fetch_assoc()):

?>
<input type="number" name="bom_quantity[]" class="custom-select text-center" size="2" value="<?php echo number_format($row1['qty']); ?>">
                 

<?php 
 endwhile; 
} else { ?>
<input type="number" name="bom_quantity[]" class="custom-select text-center" value="0" size="2">
<?php } ?>
</td> 
<td class="py-1 px-1  text-center status">
                  <select name="status[]" id="status" class="custom-select selevt" style="padding-left: 0px;">
			<option value="1" <?php echo isset($row['status']) && $row['status'] == 1 ? 'selected' : '' ?>>Active</option>
			<option value="0" <?php echo isset($row['status']) && $row['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
			</select>
                                </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </form>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-primary" type="submit" form="po-form">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=bill_of_materials' ?>">Cancel</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-1 text-center" style="color:red;">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-1 text-left item_name">
            <span class="visible" style="color: blue;"></span>
			<input type="hidden" name="item_id[]">
            <input type="hidden" name="item_name[]">
            <input type="hidden" name="bom_quantity[]">
			<input type="hidden" name="supplier_id[]">
             <input type="hidden" name="status[]">
        <td class="py-1 px-1 supplier_name" style="color: blue;">&nbsp;&nbsp;</td>
        <td class="py-1 px-1 text-center bom_quantity" style="color: blue;"></td>
        <td class="py-1 px-1 text-left status" style="color: blue;"></td>
 
    </tr>
</table>
<script>
    
    $(function(){
          $('#add_to_list').click(function(){
			var supplier = $('#supplier_id').val()	
var spnm = $("#supplier_id option:selected").text();			
		    var item_name = $('#item_name').val()
	       var bomi_remark = $('#bomi_remark').val()
            var bom_quantity = $('#bom_quantity').val() > 0 ? $('#bom_quantity').val() : 0;
            var status = $('#status').val()
            // console.log(supplier,item)
           var tr = $('#clone_list tr').clone()
            if(item_name == '' || bomi_remark == '' || bom_quantity == '' ){
                alert_toast('Form Item textfields are required.','warning');
                return false;
            }
			            $('table#list tbody input[name="item_name[]"]').each(function(){
				if($(this).val() == item_name){
	            alert_toast('Item is already exists on the list.','error');
                return false;
            }
            if($('table#list tbody').find('tr[data-id="'+item_name+'"]').length > 0){
	            alert_toast('Item is already exists on the list.','error');
                return false;
            }


            
        })
            tr.find('[name="item_name[]"]').val(item_name)
            tr.find('[name="supplier_id[]"]').val(supplier)
            tr.find('[name="bom_quantity[]"]').val(bom_quantity)
            tr.find('[name="status[]"]').val(status)
			
			tr.attr('data-id',item_name)
            tr.find('.item_name .visible').text(item_name)
            tr.find('.supplier_name').text(spnm)
            tr.find('.bom_quantity').text(bom_quantity)
			if (status==1)
			{
				sts = "Active";
			} else {
				sts = "Inactive";
			}
			
			tr.find('.status').text(sts)
			
            $('table#list tbody').append(tr)
  
            tr.find('.rem_row').click(function(){
                rem($(this))
            })
      
        })
        $('#po-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_bom_item",
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
						location.replace(_base_url_+"admin/index.php?page=bill_of_materials/view_bom&id="+resp.id);
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

        if('<?php echo isset($id) && $id > 0 ?>' == 1){
            calc()
            $('#supplier_id').trigger('change')
            $('#supplier_id').attr('readonly','readonly')
            $('table#list tbody tr .rem_row').click(function(){
                rem($(this))
            })
        }
    })
    function rem(_this){
        _this.closest('tr').remove()
        calc()
        if($('table#list tbody tr').length <= 0)
            $('#supplier_id').removeAttr('readonly')

    }
    function calc(){
        var sub_total = 0;
        var grand_total = 0;
        var discount = 0;
        var tax = 0;
        $('table#list tbody input[name="total[]"]').each(function(){
            sub_total += parseFloat($(this).val())
            
        })
        $('table#list tfoot .sub-total').text(parseFloat(sub_total).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2}))
        var discount =   sub_total * (parseFloat($('[name="discount_perc"]').val()) /100)
        sub_total = sub_total - discount;
        var tax =   sub_total * (parseFloat($('[name="tax_perc"]').val()) /100)
        grand_total = sub_total + tax
        $('.discount').text(parseFloat(discount).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2}))
        $('[name="discount"]').val(parseFloat(discount))
        $('.tax').text(parseFloat(tax).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2}))
        $('[name="tax"]').val(parseFloat(tax))
        $('table#list tfoot .grand-total').text(parseFloat(grand_total).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2}))
        $('[name="amount"]').val(parseFloat(grand_total))

    }
</script>
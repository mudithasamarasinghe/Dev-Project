<?php 
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT p.* FROM receivings p where form_id=0 and p.id = '{$_GET['id']}'");
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
        <h4 class="card-title"><?php echo "Received products"; ?></h4>
    </div>

    <div class="card-body">
        <form action="" id="receive-form">
            <div class="container-fluid">
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
							 <th class="text-center py-1 px-2">Unit</th>
                            <th class="text-center py-1 px-2">Qty</th>
                           
                        
                        </tr>
                    <tbody>
                        <?php
                        if(isset($id)):
						$in=0;
			$qry = $conn->query("SELECT items.*,receivings.id rid FROM items, receivings WHERE receivings.id = '{$_GET['id']}' AND FIND_IN_SET( items.id, receivings.stock_ids )");
	                     //   $qry = $conn->query("SELECT p.*,i.id,i.name,i.description FROM `po_items` p inner join items i on p.item_id = i.id where p.po_id = '0'");
                        while($row = $qry->fetch_assoc()):	
						 $in = $conn->query("SELECT quantity as total FROM stock_list where rec_id = '{$row['rid']}' and item_id='{$row['id']}' and type = 1")->fetch_array()['total'];
                     
				
                        ?>
  
							<?php //if ($totqty==0) { ?>
								
								<?php //} else { ?>
								                      <tr>
							<td class="py-1 px-2 item">
                            <?php echo $row['name']; ?>
                            </td>
 							 <td class="py-1 px-2 text-center unit">
                            <?php echo $row['unit']; ?>
                            </td>
								<td class="text-center" ><?php echo number_format($in) ?></td>
							   </td>
                        </tr>
								<?php //} ?>
			
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

<?php require_once('./../../config.php') ;
//$qry = $conn->query("SELECT m.min_stock FROM `items` i left join item_min_stock m on i.id=m.item_id  where i.name= '{$_GET['nm']}'");
//if($qry->num_rows >0){
 //   foreach($qry->fetch_array() as $k => $v){
//        $$k = $v;
 //   }
//}
?>
   <style>
    #uni_modal .modal-footer{
        display:none;
    }
</style> 
<div class="card card-outline card-primary">
    <div class="card-body" id="print_out">
	<div class="form-group">
    <div class="col-12">
        <div class="card-tools">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>
        <div class="container-fluid">
		
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info">Item Name</label>
                   <div><?php echo isset($_GET['nm']) ? $_GET['nm'] : '' ?></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                       
                    </div>
                </div>
            </div>
      <div>  
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="40%">
                        <col width="20%">

                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supplier</th>
                            <th class="text-center">Available Stocks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
						$in=0;
						$out=0;
						$tot=0;
						$qry = $conn->query("SELECT i.id,i.name,i.supplier_id,s.name snm FROM `items` i inner join suppliers s on i.supplier_id=s.id where i.name= '{$_GET['nm']}' order by i.name desc");
                        while($row = $qry->fetch_assoc()):
						$in=0;
						$out=0;
						$in0=0;
						$out0=0;
			
						$in0 = $conn->query("SELECT SUM(quantity) as total FROM stock_list where item_id = '{$row['id']}' and type = 1")->fetch_array()['total'];
                         $out0 = $conn->query("SELECT SUM(quantity) as total FROM stock_list where item_id = '{$row['id']}' and type != 1")->fetch_array()['total'];
                          $in = $in+$in0;
						  $out = $out+$out0;
					      $row['available'] = $in - $out;
						  $tot = $tot + $row['available'];
						  if ($row['available']>0 ){
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td nowrap><?php echo $row['snm']; ?></td>
								<td class="text-center"><?php echo number_format($row['available']); ?></td>							
                                
                            </tr>
                        <?php 
						  }
						endwhile; ?>
						 <tr>
                                <td class="text-center"></td>
                                <td nowrap><b>Total</b></td>
                                <td class="text-center"><b><?php echo number_format($tot); ?></b></td>
                            </tr>
                    </tbody>
                </table>

		</div>
	</div>
</div>

<div class="form-group">
    <div class="col-12">
        <div class="card-tools">
		<button class="btn btn-flat btn-success" type="button" id="print">Print</button>
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>
    </div>
</div>
<script>
    
    $(function(){
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Stock List Details - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-navy")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Stock List</h4>'+
                      '</div>'+
                      '<div class="col-1 text-right">'+
                      '</div>'+
                      '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes")
                     nw.document.write(_el.html())
                     nw.document.close()
                     setTimeout(() => {
                         nw.print()
                         setTimeout(() => {
                            nw.close()
                            end_loader()
                         }, 200);
                     }, 500);
        })
    })
</script>
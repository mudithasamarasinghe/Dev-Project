<?php
//$disposal_code = ''; // Initialize the variable to avoid "Undefined variable" notice
//$stock_ids = ''; // Initialize the variable to avoid "Undefined variable" notice

$qry = $conn->query("SELECT r.*,s.name as supplier FROM disposal_list r inner join suppliers s on r.supplier_id = s.id  where r.id = '{$_GET['id']}'");
if($qry->num_rows >0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Disposal Record - <?php echo $disposal_code ?></h4>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info">Disposal Code</label>
                    <div><?php echo isset($disposal_code) ? $disposal_code : '' ?></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_id" class="control-label text-info">Supplier</label>
                        <div><?php echo isset($supplier) ? $supplier : '' ?></div>
                    </div>
                </div>
            </div>
            <h4 class="text-info">Items</h4>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                         <col width="30%">
                        <col width="30%">
                        <col width="20%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
					<th class="text-center py-1 px-2">Item</th>
					<th class="text-center py-1 px-2">Unit</th>
                        <th class="text-center py-1 px-2">Qty</th>
                        
                        

                    </tr>
                </thead>
                <tbody>
                    <?php
					$tunit="";
					$tqty=0;
					//SELECT * FROM items, disposal_list WHERE disposal_list.id = '{$_GET['id']}' AND FIND_IN_SET( items.id, disposal_list.stock_ids );
					$qry = $conn->query("SELECT * FROM items, disposal_list WHERE disposal_list.id = '{$_GET['id']}' AND FIND_IN_SET( items.id, disposal_list.stock_ids )");
                   
					//$qry = $conn->query("SELECT r.*,s.name as name ,'' description FROM disposal_list r inner join suppliers s on r.supplier_id = s.id inner join items i on i.id in (r.stock_ids)  where r.id = '{$_GET['id']}'");
                     while($row = $qry->fetch_assoc()):
                     $slsql = $conn->query("SELECT quantity,unit FROM stock_list where item_id in ({$row['stock_ids']}) and type = 3");
                  	if($slsql->num_rows >0) {
					$tunit=$slsql->fetch_assoc()['unit'];
					$tqty=$slsql->fetch_assoc()['quantity'];
					}
					?>
                    <tr>
					               <td class="py-1 px-2">
                            <?php echo $row['name'] ?> <br>
                            <?php echo $row['description'] ?>
                        </td>
                       
          <td class="py-1 px-2 text-center"><?php echo $tunit; ?></td>
          <td class="py-1 px-2 text-center"><?php echo $tqty; ?></td>

                    </tr>

                    <?php endwhile; ?>
                    
                </tbody>

            </table>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="text-info control-label">Remarks</label>
                        <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-success" type="button" id="print">Print</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin?page=disposal' ?>">Back To List</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 text-center qty">
            <span class="visible"></span>
            <input type="hidden" name="item_id[]">
            <input type="hidden" name="unit[]">
            <input type="hidden" name="qty[]">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 item">
        </td>

    </tr>
</table>
<script>
    
    $(function(){
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Disposal Record - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-navy")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Disposal Record</h4>'+
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

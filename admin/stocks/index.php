<style>
td.rt-ctr {
  text-align:center;
  padding-right:50%;    
}
</style>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Stocks</h3>
        <!-- <div class="card-tools">
			<a href="<?php echo base_url ?>admin/index.php?page=purchase_order/manage_po" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div> -->
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="30%">
                        <col width="20%">
                        <col width="20%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Minimum Stock Level</th>
<!--                            <th>Description</th>-->
                            <th class="text-center">On-hand Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
						$in=0;
						$out=0;
                        $qry = $conn->query("SELECT i.id,i.name,m.min_stock FROM `items` i left join item_min_stock m on i.id=m.item_id group by i.name order by i.name desc");
                        while($row = $qry->fetch_assoc()):
						$in=0;
						$out=0;
						$in0=0;
						$out0=0;
						$qryn = $conn->query("SELECT i.id FROM `items` i where i.name='{$row['name']}'");
                        while($rown = $qryn->fetch_assoc()):
						$in0 = $conn->query("SELECT SUM(quantity) as total FROM stock_list where item_id = '{$rown['id']}' and type = 1")->fetch_array()['total'];
                         $out0 = $conn->query("SELECT SUM(quantity) as total FROM stock_list where item_id = '{$rown['id']}' and type != 1")->fetch_array()['total'];
                          $in = $in+$in0;
						  $out = $out+$out0;
						endwhile;
				       $row['available'] = $in - $out;
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo $row['name'] ?></td>
                                <td class="rt-ctr min_level" style="cursor: pointer;" data-id="<?php echo $row['name'] ?>" data-iid="<?php echo $row['id'] ?>" data-val="<?php echo $row['min_stock'] ?>"><u><?php echo $row['min_stock'] ?? '0'; ?></u></td>
								<?php if ($row['available']==0) { ?>
								<td class="rt-ctr" ><?php echo number_format($row['available']) ?></td>
								<?php } else { ?>
								<td class="rt-ctr create_details" style="cursor: pointer;" data-id="<?php echo $row['name'] ?>"><b><u><?php echo number_format($row['available']) ?></u></b></td>
								<?php } ?>
                                
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
		</div>
		</div>
	</div>
</div>
<script>
$('.create_details').click(function(){
			uni_modal("Details","stocks/detail_stocks.php?nm="+$(this).attr('data-id'),'mid-large')
		})
$('.min_level').click(function(){
			uni_modal("Minimum Stock Level","stocks/min_stock.php?nm="+$(this).attr('data-id')+"&mv="+$(this).attr('data-val')+"&id="+$(this).attr('data-iid'),'mid-large')
		})
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Received Orders permanently?","delete_receiving",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("Receiving Details","receiving/view_receiving.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_receiving($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_receiving",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>

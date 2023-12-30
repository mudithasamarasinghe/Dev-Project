
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Products</h3>
  <div class="card-tools">
			<a href="<?php echo base_url ?>admin/index.php?page=product/manage_product" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="25%">
                        <col width="25%">
						<col width="25%">
                        <col width="25%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Date/ Time Created</th>
                             <th>No of Items</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT r.id,r.date_created,r.from_order,r.form_id,IF(stock_ids, LENGTH(stock_ids) - LENGTH(REPLACE(stock_ids, ',', '')) + 1, 0) AS items,i.name nm  FROM receivings r inner join stock_list sl on sl.rec_id=r.id left join items i on sl.item_id=i.id where r.form_id=0  group by sl.rec_id order by `date_created` desc");
                        while($row = $qry->fetch_assoc()):
                           ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                <td><?php echo number_format($row['items']); ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
<div class="dropdown-menu" role="menu">
<a class="dropdown-item view_details" href="javascript:void(0)"
                                           data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span>
                                            View</a>
	
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.view_details').click(function(){
			uni_modal("Production detail","product/view_product.php?id="+$(this).attr('data-id'),'mid-large')
			document.getElementById('submit').style.visibility = 'hidden';
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
</script>

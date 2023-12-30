
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Disposals</h3>
        <div class="card-tools">
            <a href="<?php echo base_url ?>admin/index.php?page=disposal/manage_disposal"
               class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="14%">
                        <col width="8%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                        <col width="5%">
                          <col width="5%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Created</th>
                        <th>Code</th>
                        <th>Supplier</th>
                        <th>Approved</th>
                        <th>Approved</th>
                        <th>Items</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT r.*,(CHAR_LENGTH(stock_ids) - CHAR_LENGTH(REPLACE(stock_ids, ',','')) + 1) sids, s.name as supplier FROM `disposal_list` r inner join suppliers s on r.supplier_id = s.id group by r.disposal_code order by r.date_created desc");
                    while ($row = $qry->fetch_assoc()):


                        $qrynm = $conn->query("SELECT CONCAT(firstname,' ',lastname) as fnln FROM users where id = '{$row['disposal_approval']}'");
                        $k = "Not yet approved";
                        if ($qrynm->num_rows > 0) {
                            foreach ($qrynm->fetch_array() as $k => $v) {
                                $k = $v;

                                if ($k == "") {
                                    $k = "Not yet approved";
                                }
                            }
                        }

                        $item_names = '';
                        $cats = explode(",", $row['stock_ids']);
                       
						//foreach($cats as $cat) {
                         //   $cat = trim($cat);
                         //   $qrynm = $conn->query("SELECT sl.item_id,i.name,sl.quantity FROM stock_list sl inner join items i on sl.item_id=i.id where sl.id = '".$cat."' and sl.type='2'");
                         //   $kk = "";
                         //   if ($qrynm->num_rows > 0) {
                          //      foreach ($qrynm->fetch_array() as $kk => $vv) {
                          //          $kk = $vv;
                          //      }
                          //  }

                          //  $cat_qtys = explode(",", $row['quantities']);
                          //  foreach($cat_qtys as $cat_qty) {
                           //     $cat_qty = trim($cat_qty);
                           //     $qrynm = $conn->query("SELECT d.quantity FROM disposal d where d.id = '".$cat_qty."'");
                           //     $kkk = "";
                            //    if ($qrynm->num_rows > 0) {
                           //         foreach ($qrynm->fetch_array() as $kkk => $vvv) {
                            //            $kkk = $vvv;
                           //         }
                           //     }
							
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td><?php echo $row['disposal_code'] ?></td>
                            <td><?php echo $row['supplier'] ?></td>
                            <td nowrap><?php echo $k; ?></td>
                            <?php if ($row['date_approval'] <> '1970-01-01 00:00:01') { ?>
                                <td nowrap><?php echo $row['date_approval']; ?></td>
                            <?php } else { ?>
                                <td>&nbsp;</td>
                            <?php }
                            ?>
<!--                            <td class="text-left">--><?php //echo $kk; ?><!--</td>-->
                            <td class="text-right"><?php echo $row['sids']; ?></td>
						<td class="text-left"> <a class="dropdown-item"
                                       href="<?php echo base_url . 'admin/index.php?page=disposal/view_disposal&id=' . $row['id'] ?>"
                                       data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a></td>
                        </tr>
                    <?php
                      //  }
                      // }
                    endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.delete_data').click(function () {
            _conf("Are you sure to delete this Disposal Record permanently?", "delete_disposal", [$(this).attr('data-id')])
        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable();
    })

    function delete_disposal($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_disposal",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>

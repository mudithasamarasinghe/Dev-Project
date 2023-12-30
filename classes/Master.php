<?php
require_once('../config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once "../../vendor/autoload.php"; //PHPMailer Object  "F:\xampp\phpMyAdmin\vendor\autoload.php"

Class Master extends DBConnection {
    private $settings;

    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }
    public function __destruct(){
        parent::__destruct();
    }
    function capture_err(){
        if(!$this->conn->error)
            return false;
        else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
            return json_encode($resp);
            exit;
        }
    }
    function save_supplier(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        $check = $this->conn->query("SELECT * FROM `suppliers` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "supplier Name already exist.";
            return json_encode($resp);
            exit;
        }
        if(empty($id)){
            $sql = "INSERT INTO `suppliers` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `suppliers` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New Supplier successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "Supplier successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
    function delete_supplier(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `suppliers` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Supplier successfully deleted.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
	function fetch_select(){
		 extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data = " `{$k}`='{$v}' ";
            }
        }
		
        $iid = json_encode($_POST['id']);
		$resp['status'] = 'success';
		$resp = '';
		$qtybal = 0;
$qry = $this->conn->query("SELECT sl.item_id,i.name AS items,sum(sl.quantity) as qty ,i.unit FROM stock_list sl inner join items i on  sl.item_id=i.id where sl.form_id='{$_POST['id']}'  and sl.type='1' group by sl.form_id,sl.item_id order by sl.form_id,sl.item_id asc");

		// $qtybal =$this->conn->query("SELECT sum(sl.quantity) as quantity FROM stock_list sl inner join items i on  sl.item_id=i.id where sl.form_id='{$_POST['id']}'  and sl.type != '1' group by sl.form_id,sl.item_id order by sl.form_id,sl.item_id asc")->fetch_array()['quantity'];		
            
		  while($row = $qry->fetch_assoc()){
			  
$result = $this->conn->query("SELECT sum(sl.quantity) as quantity FROM stock_list sl inner join items i on sl.item_id=i.id where sl.form_id='{$_POST['id']}' and i.id='{$row['item_id']}' and sl.type != '1' group by sl.form_id,sl.item_id order by sl.form_id,sl.item_id asc");
     if ($result) {
    $row0 = $result->fetch_array();    
    if ($row0 !== null) {
        $qtybal = $row0['quantity'];
    } else {
        $qtybal = 0; 
		}
             }
			  
			  
				$resp .= '<option value="' . $row['item_id'] . '" data-alpha="'.$row['unit'] .'" data-id="'.$row['qty']-$qtybal .'">' . $row['items'] . '</option>';
		     }
		       return json_encode($resp);

    }
    function save_role(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }

        $check = $this->conn->query("SELECT * FROM `role` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;

        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Role Name already exist.";
            return json_encode($resp);
            exit;
        }
        if(empty($id)){
            $sql = "INSERT INTO `role` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `role` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New Role successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "Role successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
	    function save_minlevel(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('item_id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }

        $check = $this->conn->query("SELECT * FROM `item_min_stock` where item_id = {$item_id}")->num_rows;
        if($check > 0){
            $sql = "UPDATE `item_min_stock` set {$data} where item_id = '{$item_id}' ";
            $save = $this->conn->query($sql);

        }else{
            $sql = "INSERT INTO `item_min_stock` set {$data}".",item_id = '{$item_id}'";
            $save = $this->conn->query($sql);
        }
	        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New min_stock successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "min_stock successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
    function delete_role(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `role` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Role successfully deleted.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_item(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                $v = $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        $check = $this->conn->query("SELECT * FROM `items` where `name` = '{$name}' and `supplier_id` = '{$supplier_id}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Item already exists under selected supplier.";
            return json_encode($resp);
            exit;
        }
        if(empty($id)){
            $sql = "INSERT INTO `items` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `items` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }

        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $this->settings->set_flashdata('success',"New Item successfully saved.");
            else
                $this->settings->set_flashdata('success',"Item successfully updated.");
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
    function delete_item(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `items` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Item  successfully deleted.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_po(){
	
		if(empty($_POST['item_id'])){
			exit;
		}
        if(empty($_POST['id'])){
            $prefix = "PO";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `purchase_orders` where po_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['po_code'] = $prefix."-".$code;
        }
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }
        if(empty($id)){
            $sql = "INSERT INTO `purchase_orders` set {$data}";
        }else{
            $sql = "UPDATE `purchase_orders` set {$data} where id = '{$id}'";
        }
        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $po_id = $this->conn->insert_id;
            else
                $po_id = $id;
            $resp['id'] = $po_id;
            $data = "";
            foreach($item_id as $k =>$v){
                if(!empty($data)) $data .=", ";
                $data .= "('{$po_id}','{$v}','{$qty[$k]}','{$unit[$k]}')";
            }
            if(!empty($data)){
                $this->conn->query("DELETE FROM `po_items` where po_id = '{$po_id}'");
                $save = $this->conn->query("INSERT INTO `po_items` (`po_id`,`item_id`,`quantity`,`unit`) VALUES {$data}");
                if(!$save){
                    $resp['status'] = 'failed';
                    if(empty($id)){
                        $this->conn->query("DELETE FROM `purchase_orders` where id '{$po_id}'");
                    }
                    $resp['msg'] = 'PO has failed to save. Error: '.$this->conn->error;
                    $resp['sql'] = "INSERT INTO `po_items` (`po_id`,`item_id`,`quantity`,`unit`) VALUES {$data}";
                }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Purchase Order was Successfully created.");
            }else{
                $this->settings->set_flashdata('success'," Purchase Order's Details Successfully updated.");
            }
        }

        return json_encode($resp);
    }
    function delete_po(){
        extract($_POST);
        $bo = $this->conn->query("SELECT * FROM back_orders where po_id = '{$id}'");
        $del = $this->conn->query("DELETE FROM `purchase_orders` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"po's Details Successfully deleted.");
            if($bo->num_rows > 0){
                $bo_res = $bo->fetch_all(MYSQLI_ASSOC);
                $r_ids = array_column($bo_res, 'receiving_id');
                $bo_ids = array_column($bo_res, 'id');
            }
            $qry = $this->conn->query("SELECT * FROM receivings where (form_id='{$id}' and from_order = '1') ".(isset($r_ids) && count($r_ids) > 0 ? "OR id in (".(implode(',',$r_ids)).") OR (form_id in (".(implode(',',$bo_ids)).") and from_order = '2') " : "" )." ");
            while($row = $qry->fetch_assoc()){
                $this->conn->query("DELETE FROM `stock_list` where id in ({$row['stock_ids']}) ");
                // echo "DELETE FROM `stock_list` where id in ({$row['stock_ids']}) </br>";
            }
            $this->conn->query("DELETE FROM receivings where (form_id='{$id}' and from_order = '1') ".(isset($r_ids) && count($r_ids) > 0 ? "OR id in (".(implode(',',$r_ids)).") OR (form_id in (".(implode(',',$bo_ids)).") and from_order = '2') " : "" )." ");
            // echo "DELETE FROM receivings where (form_id='{$id}' and from_order = '1') ".(isset($r_ids) && count($r_ids) > 0 ? "OR id in (".(implode(',',$r_ids)).") OR (form_id in (".(implode(',',$bo_ids)).") and from_order = '2') " : "" )."  </br>";
            // exit;
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_receiving(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id','supplier_id','po_id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }

        if(empty($id)){
            $sql = "INSERT INTO `receivings` set {$data}";
        }else{
            $sql = "UPDATE `receivings` set {$data} where id = '{$id}'";
        }

        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $r_id = $this->conn->insert_id;
            else
                $r_id = $id;
            $resp['id'] = $r_id;
            if(!empty($id)){
                $stock_ids = $this->conn->query("SELECT stock_ids FROM `receivings` where id = '{$id}'")->fetch_array()['stock_ids'];
                $this->conn->query("DELETE FROM `stock_list` where id in ({$stock_ids})");
            }
            $stock_ids= array();
			$stkids = "";
            foreach($item_id as $k =>$v){
                if(!empty($data)) $data .=", ";
                $sql = "INSERT INTO stock_list (`item_id`,`form_id`,`quantity`,`expiry_date`,`unit`,`type`,`rec_id`) VALUES ('{$v}','{$po_id}','{$qty[$k]}','{$expiry_date[$k]}','{$unit[$k]}','1','{$r_id}')";
                $this->conn->query($sql);
                $stock_ids[] = $this->conn->insert_id;
				$stkids = $stkids.','.$v;
				$stkids = trim(str_replace(',,','',$stkids),',');
               
            }

           // if(count(explode(",",$stkids))-1 > 0){
				$this->conn->query("UPDATE `receivings` set stock_ids = '{$stkids}' where id = '{$r_id}'");
           // }
		   //check po receiving balance
		   
		   
            if(isset($po_ids)){
                $this->conn->query("UPDATE `purchase_orders` set status = 1 where id = '{$po_id}'");
 
               // $bo_save = $this->conn->query($sql);
                if(!isset($bo_id))
                 //   $bo_id = $this->conn->insert_id;
                $data = "";
                foreach($item_id as $k =>$v){
                    if(!in_array($k,$bo_ids))
                        continue;

                    if(!empty($data)) $data.= ", ";
                  //  $data .= " ('{$bo_id}','{$v}','".($oqty[$k] - $qty[$k])."','{$unit[$k]}') ";
                }
                //$this->conn->query("DELETE FROM `bo_items` where bo_id='{$bo_id}'");
                //$save_bo_items = $this->conn->query("INSERT INTO `bo_items` (`bo_id`,`item_id`,`quantity`,`unit`) VALUES {$data}");
//                if($save_bo_items){
//
//                    $amount = 0;
//                    $this->conn->query("UPDATE back_orders set amount = '{$amount}' where id = '{$bo_id}'");
//                }

            }else{
               // $this->conn->query("UPDATE `purchase_orders` set status = 2 where id = '{$po_id}'");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }



        /////////////////////////////////////

/*
        if(isset($po_id)):
           // if(!isset($bo_id))
                $qry = $this->conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join items i on p.item_id = i.id where p.po_id = '{$po_id}'");
           // else
               // $qry = $this->conn->query("SELECT b.*,i.name,i.description FROM `bo_items` b inner join items i on b.item_id = i.id where b.bo_id = '{$bo_id}'");
            while($row = $qry->fetch_assoc()):
                $row['qty'] = $row['quantity'];


                $qry1 = $this->conn->query("SELECT stock_ids FROM `receivings` where form_id= '{$po_id}'");
                $tqty = 0;
                while($row1 = $qry1->fetch_assoc()):

                    $qry2 = $this->conn->query("SELECT * FROM stock_list where item_id = ".$row['item_id']);
                    while($row2 = $qry2->fetch_assoc()):

                        $tqty = $tqty + $row2['quantity'];
                    endwhile;
                endwhile;

                if ($row['quantity']== $tqty){
                    //echo "UPDATE `purchase_orders` set status = 2 where id = '{$po_id}'";
//                  //  exit();
                    $this->conn->query("UPDATE `purchase_orders` set status = 2 where id = '{$po_id}'");
                }

            endwhile;
        endif;
*/

        ///////////////////////////////////////////

        if ($resp['status'] == 'success') {
            if (empty($id)) {
                $this->settings->set_flashdata('success', "New Stock was Successfully received.");
            } else {
                $this->settings->set_flashdata('success', "Received Stock's Details Successfully updated.");
            }

            // Check and update PO status
			$slapobal=0;
			$qrypo = $this->conn->query("SELECT p.po_id,p.item_id,p.quantity FROM `po_items` p where p.po_id = '{$po_id}'");
            while($rowpo = $qrypo->fetch_assoc()){
			$slbalance =$this->conn->query("SELECT sum(quantity) quantity FROM `stock_list` where form_id = '{$po_id}' and item_id='{$rowpo['item_id']}' group by form_id,item_id")->fetch_array()['quantity'];		
              // Check if the quantity is not zero for the current item
             //var_dump($rowpo['quantity']."---".$slbalance);
			 if (($rowpo['quantity']-$slbalance) > 0) {
            // Set slapobal to 1 and break out of the loop
             $slapobal = 1;
             break;
             }
			}
               
			
			///////////////////
            if (isset($po_id)) {
                $qry = $this->conn->query("SELECT p.*, i.name, i.description FROM `po_items` p INNER JOIN items i ON p.item_id = i.id WHERE p.po_id = '{$po_id}'");
                $po_fully_received = true; // Assume all items in the PO are fully received

                while ($row = $qry->fetch_assoc()) {
                    $qry1 = $this->conn->query("SELECT stock_ids FROM `receivings` WHERE form_id = '{$po_id}'");
                    $total_ordered_qty = $row['quantity'];
                    $total_received_qty = 0;

                    while ($row1 = $qry1->fetch_assoc()) {
                        $qry2 = $this->conn->query("SELECT * FROM stock_list WHERE item_id = " . $row['item_id']);

                        while ($row2 = $qry2->fetch_assoc()) {
                            $total_received_qty += $row2['quantity'];
                        }
                    }

                    if ($total_received_qty < $total_ordered_qty) {
                        $po_fully_received = false; // At least one item is not fully received
                        break;
                    }
                }

                // Update the PO status based on whether all items are fully received or not
               if ($slapobal==0){
			   //$po_status = $po_fully_received ? 2 : 1;
                $this->conn->query("UPDATE `purchase_orders` SET status = '2' WHERE id = '{$po_id}'");
            }
			}
        }
        return json_encode($resp);
    }
function save_products(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id','supplier_id','po_id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }

        if(empty($id)){
            $sql = "INSERT INTO `receivings` set {$data}";
        }else{
            $sql = "UPDATE `receivings` set {$data} where id = '{$id}'";
        }

        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $r_id = $this->conn->insert_id;
            else
                $r_id = $id;
            $resp['id'] = $r_id;
            if(!empty($id)){
                $stock_ids = $this->conn->query("SELECT stock_ids FROM `receivings` where id = '{$id}'")->fetch_array()['stock_ids'];
                $this->conn->query("DELETE FROM `stock_list` where id in ({$stock_ids})");
            }
            $stock_ids= array();
			$stkids = "";
			   var_dump("dddd");
            foreach($item_id as $k =>$v){
                if(!empty($data)) $data .=", ";
                $sql = "INSERT INTO stock_list (`item_id`,`form_id`,`quantity`,`expiry_date`,`unit`,`type`,`rec_id`) VALUES ('{$v}','{$po_id}','{$qty[$k]}','01-01-2070','{$unit[$k]}','1','{$r_id}')";
                var_dump($sql);
				$this->conn->query($sql);
                $stock_ids[] = $this->conn->insert_id;
				$stkids = $stkids.','.$v;
				$stkids = trim(str_replace(',,','',$stkids),',');
               
            }

     				$this->conn->query("UPDATE `receivings` set stock_ids = '{$stkids}' where id = '{$r_id}'");
 		   //check po receiving balance	   
		   
        
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }


        if ($resp['status'] == 'success') {
            if (empty($id)) {
                $this->settings->set_flashdata('success', "New Stock was Successfully received.");
            } else {
                $this->settings->set_flashdata('success', "Received Stock's Details Successfully updated.");
            }
             
        }
        return json_encode($resp);
    }
    function delete_receiving(){
        extract($_POST);
        $qry = $this->conn->query("SELECT * from  receivings where id='{$id}' ");
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
            $ids = $res['stock_ids'];
        }
        if(isset($ids) && !empty($ids))
            $this->conn->query("DELETE FROM stock_list where id in ($ids) ");
        $del = $this->conn->query("DELETE FROM receivings where id='{$id}' ");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Received Order's Details Successfully deleted.");

            if(isset($res)){
                if($res['from_order'] == 1){
                    $this->conn->query("UPDATE purchase_orders set status = 0 where id = '{$res['form_id']}' ");
                }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function delete_bo(){
        extract($_POST);
        $bo =$this->conn->query("SELECT * FROM `back_orders` where id = '{$id}'");
        if($bo->num_rows >0)
            $bo_res = $bo->fetch_array();
        $del = $this->conn->query("DELETE FROM `back_orders` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"po's Details Successfully deleted.");
            $qry = $this->conn->query("SELECT `stock_ids` from  receivings where form_id='{$id}' and from_order = '2' ");
            if($qry->num_rows > 0){
                $res = $qry->fetch_array();
                $ids = $res['stock_ids'];
                $this->conn->query("DELETE FROM stock_list where id in ($ids) ");

                $this->conn->query("DELETE FROM receivings where form_id='{$id}' and from_order = '2' ");
            }
            if(isset($bo_res)){
                $check = $this->conn->query("SELECT * FROM `receivings` where from_order = 1 and form_id = '{$bo_res['po_id']}' ");
                if($check->num_rows > 0){
                    $this->conn->query("UPDATE `purchase_orders` set status = 1 where id = '{$bo_res['po_id']}' ");
                }else{
                    $this->conn->query("UPDATE `purchase_orders` set status = 0 where id = '{$bo_res['po_id']}' ");
                }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }
    //////////////
    function save_return(){

        if(empty($_POST['id'])){
            $prefix = "R";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `return_list` where return_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['return_code'] = $prefix."-".$code;
        }
	     extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }

        ////////////get alert manager's profile ids
        $qry = $this->conn->query("SELECT pa.profileid FROM `alert` a inner join profile_alert pa on a.id=pa.alertid where approval_for = 'purchasereturns'");
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
            $pid = $res['profileid'];
        }
        /////////////

        if(empty($id)){
            $sql = "INSERT INTO `return_list` set {$data}";
        }else{
            $sql = "UPDATE `return_list` set {$data} where id = '{$id}'";
        }
	       $save = $this->conn->query($sql);

        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $return_id = $this->conn->insert_id;
            else
                $return_id = $id;
            $resp['id'] = $return_id;
            $data = "";
            $sids = array();
            $get = $this->conn->query("SELECT * FROM `return_list` where id = '{$return_id}'");
            if($get->num_rows > 0){
                $res = $get->fetch_array();
                if(!empty($res['stock_ids'])){

                    $sqlt = base64_encode("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
                    $sql = "INSERT INTO `messages` set profileid='{$pid}', `title` = 'Approval for return return id - ''{$_POST['return_code']}', `message` = '".$sqlt."',`type` = 'delete',`status` = '0', `role_name` = 'returns', `event_id` = '{$return_id}'";
                    $save = $this->conn->query($sql);
                    /////////////
                    //$this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
                }
            }
			$stkids = "";
            foreach($item_id as $k =>$v){

                /////////////
                $sqlt = base64_encode("INSERT INTO `stock_list` set item_id='{$v}',`form_id` = '{$form_id}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}',`type` = '2',`rec_id`='{$return_id}'; ");
                $sql = "INSERT INTO `messages` set profileid='".$pid."', `title` = 'Approval for return return id - ''{$_POST['return_code']}', `message` = '".$sqlt."',`type` = 'insert',`status` = '0', `role_name` = 'returns', `event_id` = '{$return_id}'";
                $save = $this->conn->query($sql);
                /////////////
                //$sql = "INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}',`type` = 2 ";
                //$save = $this->conn->query($sql);
				$stkids = $stkids.','.$v;
                if($save){
                    $sids[] = $this->conn->insert_id;
                }
            }
			$stkids = trim(str_replace(',,','',$stkids),',');
            $sids = implode(',',$sids);
            $this->conn->query("UPDATE `return_list` set stock_ids = '{$stkids}',form_id = '{$form_id}' where id = '{$return_id}'");
			
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Returned Item Record was Successfully created.");
            }else{
                $this->settings->set_flashdata('success'," Returned Item Record's Successfully updated.");
            }

            ////////////////////////////////////////////////////

            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 0;                     //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = SMTP_USERNAME;                     //SMTP username
                $mail->Password   = SMTP_PASSWORD;                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('mudithasamarasinghe08@gmail.com', 'MEDIX');
                $mail->addAddress(SMTP_RECIPIENT);     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Medix Purchase Return Notification';
                $mail->Body    = 'Hello,<br><br>
        This is a system generated notification. Do not reply to this mail.<br> You have a pending approval for return code <b>'.$_POST['return_code'].'</b>.<br><br>
        
        Please evaluate the return request. <br>
        <br><b>Regards,</b><br>Medix ';

                $mail->send();

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            //////////////////////////////////////////////////
        }

        return json_encode($resp);
    }
    //
function approve_return(){

    extract($_POST);

    $get = $this->conn->query("SELECT * FROM `messages` where event_id = '{$id}' and role_name='returns'");
    if($get->num_rows > 0){

        while($row = $get->fetch_assoc()):
            if ($row['type']=="delete" ) {
                $del = $this->conn->query("\"".base64_decode($row['message'])."\"");
                $resp['status'] = 'success';
            }  else {
                $sql = "\"".base64_decode($row['message'])."\"";
                $save = $this->conn->query(substr($sql, 1, -1));
                $resp['status'] = 'success';
            }
		
            $this->conn->query("UPDATE `messages` set status = '1' where event_id = '{$id}' and role_name='returns'");
        endwhile;
        $this->conn->query("UPDATE `return_list` set return_approval = '{$_SESSION['userdata']['id']}' ,date_approval = now() where id = '{$id}'");
        $resp['status'] = 'success';
    } else {
        $resp['status'] = 'failed';
    }
    if($resp['status'] == 'success'){
        if(empty($id)){
            $this->settings->set_flashdata('success'," New Returned Item Record was Successfully approved.");
        }else{
            $this->settings->set_flashdata('failed'," Returned Item Record was not approved.");
        }
    }
    return json_encode($resp);

}
   //
    function delete_return(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM return_list where id = '{$id}'");
        if($get->num_rows > 0){
            $res = $get->fetch_array();
        }
        $del = $this->conn->query("DELETE FROM `return_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Returned Item Record's Successfully deleted.");
            if(isset($res)){
                $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']})");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function reject_return(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM return_list where id = '{$id}'");
        if($get->num_rows > 0){
            $res = $get->fetch_array();
        }
        $del = $this->conn->query("DELETE FROM `return_list` where id = '{$id}'");
        $this->conn->query("UPDATE `messages` set status = '1' where event_id = '{$id}' and role_name='returns'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Returned Item Record's Successfully deleted.");
            if(isset($res)){

                $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']})");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
	//
 function save_disposal(){

        if(empty($_POST['id'])){
            $prefix = "D";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `disposal_list` where disposal_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['disposal_code'] = $prefix."-".$code;
        }
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }

        ////////////get alert manager's profile ids
        $qry = $this->conn->query("SELECT pa.profileid FROM `alert` a inner join profile_alert pa on a.id=pa.alertid where approval_for = 'disposals'");
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
            $pid = $res['profileid'];
        }
        /////////////

        if(empty($id)){
            $sql = "INSERT INTO `disposal_list` set {$data}";
        }else{
            $sql = "UPDATE `disposal_list` set {$data} where id = '{$id}'";
        }
	       $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $disposal_id = $this->conn->insert_id;
            else
                $disposal_id = $id;
            $resp['id'] = $disposal_id;
            $data = "";
            $sids = array();
            $get = $this->conn->query("SELECT * FROM `disposal_list` where id = '{$disposal_id}'");
            if($get->num_rows > 0){
                $res = $get->fetch_array();
                if(!empty($res['stock_ids'])){

                    $sqlt = base64_encode("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
                    $sql = "INSERT INTO `messages` set profileid='{$pid}', `title` = 'Approval for disposal disposal id - ''{$_POST['disposal_code']}', `message` = '".$sqlt."',`type` = 'delete',`status` = '0', `role_name` = 'disposals', `event_id` = '{$disposal_id}'";
                    $save = $this->conn->query($sql);
                    /////////////
                    //$this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
                }
            }
			$stkids = "";
            foreach($item_id as $k =>$v){

                /////////////
                $sqlt = base64_encode("INSERT INTO `stock_list` set item_id='{$v}',`form_id` = '{$poid[$k]}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}',`rec_id` = '{$disposal_id}',`type` = '3'; ");
                $sql = "INSERT INTO `messages` set profileid='".$pid."', `title` = 'Approval for disposal disposal id - ''{$_POST['disposal_code']}', `message` = '".$sqlt."',`type` = 'insert',`status` = '0', `role_name` = 'disposals', `event_id` = '{$disposal_id}'";
                $save = $this->conn->query($sql);
                /////////////
                //$sql = "INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}',`type` = 3 ";
                //$save = $this->conn->query($sql);
				$stkids = $stkids.','.$v;
                if($save){
                    $sids[] = $this->conn->insert_id;
                }
            }
			$stkids = trim(str_replace(',,','',$stkids),',');
            $sids = implode(',',$sids);
            $this->conn->query("UPDATE `disposal_list` set stock_ids = '{$stkids}' where id = '{$disposal_id}'");
			
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New disposal Item Record was Successfully created.");
            }else{
                $this->settings->set_flashdata('success'," Disposal Item Record's Successfully updated.");
            }

            ////////////////////////////////////////////////////

            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = 0;                     //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = SMTP_USERNAME;                     //SMTP username
                $mail->Password   = SMTP_PASSWORD;                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('mudithasamarasinghe08@gmail.com', 'MEDIX');
                $mail->addAddress(SMTP_RECIPIENT);     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Medix Disposal Notification';
                $mail->Body    = 'Hello,<br><br>
        This is a system generated notification. Do not reply to this mail.<br> You have a pending approval for disposal code <b>'.$_POST['disposal_code'].'</b>.<br><br>
        
        Please evaluate the disposal request. <br>
        <br><b>Regards,</b><br>Medix ';

                $mail->send();

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            //////////////////////////////////////////////////
        }

        return json_encode($resp);	
 }
function approve_disposal(){

    extract($_POST);

    $get = $this->conn->query("SELECT * FROM `messages` where event_id = '{$id}' and role_name='disposals'");
    if($get->num_rows > 0){

        while($row = $get->fetch_assoc()):
            if ($row['type']=="delete" ) {
                $del = $this->conn->query("\"".base64_decode($row['message'])."\"");
                $resp['status'] = 'success';
            }  else {
                $sql = "\"".base64_decode($row['message'])."\"";
                $save = $this->conn->query(substr($sql, 1, -1));
                $resp['status'] = 'success';
            }
		
            $this->conn->query("UPDATE `messages` set status = '1' where event_id = '{$id}' and role_name='disposals'");
        endwhile;
        $this->conn->query("UPDATE `disposal_list` set disposal_approval = '{$_SESSION['userdata']['id']}' ,date_approval = now() where id = '{$id}'");
        $resp['status'] = 'success';
    } else {
        $resp['status'] = 'failed';
    }
    if($resp['status'] == 'success'){
        if(empty($id)){
            $this->settings->set_flashdata('success'," New Disposal Item Record was Successfully approved.");
        }else{
            $this->settings->set_flashdata('failed'," Disposal Item Record was not approved.");
        }
    }
    return json_encode($resp);

}
function delete_disposal(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM disposal_list where id = '{$id}'");
        if($get->num_rows > 0){
            $res = $get->fetch_array();
        }
        $del = $this->conn->query("DELETE FROM `disposal_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Disposal Item Record's Successfully deleted.");
            if(isset($res)){
                $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']})");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function reject_disposal(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM disposal_list where id = '{$id}'");
        if($get->num_rows > 0){
            $res = $get->fetch_array();
        }
        $del = $this->conn->query("DELETE FROM `disposal_list` where id = '{$id}'");
        $this->conn->query("UPDATE `messages` set status = '1' where event_id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Disposal Item Record's Successfully deleted.");
            if(isset($res)){

                $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']})");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
	//
    function save_sale(){
        if(empty($_POST['id'])){
            $prefix = "SALE";
            $code = sprintf("%'.04d",1);
            while(true){
                $check_code = $this->conn->query("SELECT * FROM `sales_list` where sales_code ='".$prefix.'-'.$code."' ")->num_rows;
                if($check_code > 0){
                    $code = sprintf("%'.04d",$code+1);
                }else{
                    break;
                }
            }
            $_POST['sales_code'] = $prefix."-".$code;
        }
        extract($_POST);
        $data = "";

        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id')) && !is_array($_POST[$k])){
                if(!is_numeric($v))
                    $v= $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=", ";
                $data .=" `{$k}` = '{$v}' ";
            }
        }
        if(empty($id)){
            $sql = "INSERT INTO `sales_list` set {$data}";
        }else{
            $sql = "UPDATE `sales_list` set {$data} where id = '{$id}'";
        }
        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id))
                $sale_id = $this->conn->insert_id;
            else
                $sale_id = $id;
            $resp['id'] = $sale_id;
            $data = "";
            foreach($item_id  as $k =>$v){
                if(!empty($data)) $data .=", ";
                $data .= "('{$sale_id}','{$v}','{$qty[$k]}','{$unit[$k]}')";
                $sqlsl = " UPDATE `stock_list` set quantity=quantity - {$qty[$k]} where item_id = '{$v}'";
                $save = $this->conn->query($sqlsl);
            }
            if(!empty($data)){
                $this->conn->query("DELETE FROM `sales_items` where sales_id = '{$sale_id}'");
                $save = $this->conn->query("INSERT INTO `sales_items` (`sales_id`,`item_id`,`quantity`,`unit`) VALUES {$data}");
                if(!$save){
                    $resp['status'] = 'failed';
                    if(empty($id)){
                        $this->conn->query("DELETE FROM `sales_list` where id '{$sale_id}'");
                    }
                    $resp['msg'] = 'Sales has failed to save. Error: '.$this->conn->error;
                    $resp['sql'] = "INSERT INTO `sales_items` (`sales_id`,`item_id`,`quantity`,`unit`) VALUES {$data}";
               var_dump("INSERT INTO `sales_items` (`sales_id`,`item_id`,`quantity`,`unit`) VALUES {$data}");
			   }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured. Error: '.$this->conn->error;
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Sale was Successfully created.");
            }else{
                $this->settings->set_flashdata('success'," Sales Details Successfully updated.");
            }
        }
        return json_encode($resp);
    }
    function delete_sale(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM sales_list where id = '{$id}'");
        if($get->num_rows > 0){
            $res = $get->fetch_array();
        }
        $del = $this->conn->query("DELETE FROM `sales_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Sales Record's Successfully deleted.");
            if(isset($res)){
                $this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']})");
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
//


    function save_profile(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }

        $check = $this->conn->query("SELECT * FROM `admin_profiles` where `profile_name` = '{$profile_name}' ".(!empty($id) ? " and profile_id != {$id} " : "")." ")->num_rows;

        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Profile Name already exist.";
            return json_encode($resp);
            exit;
        }
        if(empty($id)){
            $sql = "INSERT INTO `admin_profiles` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `admin_profiles` set {$data} where profile_id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New Profile successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "Profile successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);

    }
    function delete_profile(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `admin_profiles` where profile_id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Profile successfully deleted.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function save_rtop()
    {
        if( isset( $_POST['myCheckboxes'] ))
        {
            $del = $this->conn->query("DELETE FROM `profile_role` where profileid = '{$_POST['id']}'");
            $check_code = $this->conn->query("SELECT max(id) FROM `profile_role`")->num_rows;
            if($check_code == 0){
                $seq = 1;
            }else{
                $seq=$check_code+1;
            }
            for ( $i=0; $i < count($_POST['myCheckboxes'] ); $i++ )
            {

                $sql = "INSERT INTO `profile_role` set `id`='{$seq}', `profileid` = '{$_POST['id']}', `roleid` = '{$_POST['myCheckboxes'][$i]}', `status` = '1'";
                $save = $this->conn->query($sql);
                $seq=$seq+1;
            }
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success'," Profile roles Successfully updated.");
        }
        else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        header('location:../admin/index.php?page=maintenance/userprofile');
        return json_encode($resp);
    }
    ////
    function save_atop()
    {
        if( isset( $_POST['myCheckboxes'] ))
        {
            $del = $this->conn->query("DELETE FROM `profile_alert` where profileid = '{$_POST['id']}'");
            $check_code = $this->conn->query("SELECT max(id) FROM `profile_alert`")->num_rows;
            if($check_code == 0){
                $seq = 1;
            }else{
                $seq=$check_code+1;
            }
            for ( $i=0; $i < count($_POST['myCheckboxes'] ); $i++ )
            {

                $sql = "INSERT INTO `profile_alert` set `id`='{$seq}', `profileid` = '{$_POST['id']}', `alertid` = '{$_POST['myCheckboxes'][$i]}', `status` = '1'";
                $save = $this->conn->query($sql);
                $seq=$seq+1;
            }
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success'," Profile alerts Successfully updated.");
        }
        else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        header('location:../admin/index.php?page=maintenance/userprofile');
        return json_encode($resp);
    }
    function save_alert(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }

        $check = $this->conn->query("SELECT * FROM `alert` where `approval_for` = '{$approval_for}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;

        if($this->capture_err())
            return $this->capture_err();
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Alert Name already exist.";
            return json_encode($resp);
            exit;
        }

        if(empty($id)){
            $sql = "INSERT INTO `alert` set {$data} ";
            $save = $this->conn->query($sql);
        }else{
            $sql = "UPDATE `alert` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                $res['msg'] = "New Alert successfully saved.";
                $id = $this->conn->insert_id;
            }else{
                $res['msg'] = "Alert successfully updated.";
            }
            $this->settings->set_flashdata('success',$res['msg']);
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        return json_encode($resp);
    }
    function delete_alert(){
        extract($_POST);
        $get = $this->conn->query("SELECT * FROM profile_alert where alertid = '{$id}'");
        if($get->num_rows > 0){
            $del = $this->conn->query("DELETE FROM `profile_alert` where alertid = '{$id}'");
        }
        $dela = $this->conn->query("DELETE FROM `alert` where id = '{$id}'");

        if($dela){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Alert Record Successfully deleted.");

        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);

    }
    function expiry_return(){

        extract($_POST);

        $get = $this->conn->query("SELECT * FROM `messages` where event_id = '{$id}'");
        if($get->num_rows > 0){

            while($row = $get->fetch_assoc()):
                $this->conn->query("UPDATE `messages` set status = '1' where event_id = '{$id}'");
            endwhile;
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Returned Item Record was Successfully approved.");
            }else{
                $this->settings->set_flashdata('failed'," Returned Item Record was not approved.");
            }
        }
        return json_encode($resp);

    }
    function mlcheck_return(){

        extract($_POST);

        $get = $this->conn->query("SELECT * FROM `messages` where event_id = '{$id}'");
        if($get->num_rows > 0){

            while($row = $get->fetch_assoc()):
                $this->conn->query("UPDATE `messages` set status = '0' where event_id = '{$id}'");
            endwhile;
            $resp['status'] = 'success';
        } else {
            $resp['status'] = 'failed';
        }
        if($resp['status'] == 'success'){
            if(empty($id)){
                $this->settings->set_flashdata('success'," New Returned Item Record was Successfully approved.");
            }else{
                $this->settings->set_flashdata('failed'," Returned Item Record was not approved.");
            }
        }
        return json_encode($resp);

    }
	function save_bom_item(){
		extract($_POST);
		//var_dump($_POST);
		$data = "";
		$j=0;
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!is_numeric($v))
				$v= $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=", ";
				$data .=" `{$k}` = '{$v}' ";
			}
		}
			$resp['status'] = 'success';
			$device_id = $id;
			$resp['id'] = $device_id;
			$data = "";
			$datai = "";
			foreach($item_name as $k =>$v){
				$itmid=0;
				if(!empty($data))
echo("---$j---".$j);
                if ($j == 0)	{
				$this->conn->query("DELETE FROM `bill_of_materials` where device_id = '{$device_id}'");
				$this->conn->query("DELETE FROM `items` where bom_id = '{$device_id}'");
				}
			    $datai =", ";				
				$datai = "('{$item_name[$k]}','0','{$supplier_id[$k]}','Pieces','{$status[$k]}','{$device_id}')";
				$save  = $this->conn->query("INSERT INTO `items` (`name`, `apparatus`, `supplier_id`, `unit`, `status`, `bom_id`) VALUES {$datai}");
			  	//var_dump("INSERT INTO `items` (`name`, `apparatus`, `supplier_id`, `unit`, `status`, `bom_id`) VALUES {$datai}");
				
				$itmid = $this->conn->insert_id;	
				$data =", ";
				$data = "('{$device_id}','{$itmid}','{$bom_quantity[$k]}','{$status[$k]}')";
				//var_dump("INSERT INTO `bill_of_materials` (`device_id`,`item_id`,`bom_quantity`,`status`) VALUES {$data}");
                $save = $this->conn->query("INSERT INTO `bill_of_materials` (`device_id`,`item_id`,`bom_quantity`,`status`) VALUES {$data}");
              
				
				
				
				//if($item_id[$k]==''){               
				//if(!empty($datai)) $datai .=", ";
				//$datai .= "('{$item_name[$k]}','0','{$supplier_id[$k]}','Pieces','{$status[$k]}','{$device_id}')";
				//} else {
				//if(!empty($datai)) $datai .=", ";
				//$datai .= "('{$item_name[$k]}','0','{$supplier_id[$k]}','Pieces','{$status[$k]}','{$device_id}')";
				//}
				
				/*
				$datai=rtrim($datai,",");
				//var_dump("INSERT INTO `bill_of_materials` (`device_id`,`item_id`,`bom_quantity`,`status`) VALUES {$data}");
				//var_dump("INSERT INTO `items` (`name`, `apparatus`, `supplier_id`, `unit`, `status`, `bom_id`) VALUES {$datai}");
				if(!empty($data)){
				$this->conn->query("DELETE FROM `bill_of_materials` where device_id = '{$device_id}'");
				$save = $this->conn->query("INSERT INTO `bill_of_materials` (`device_id`,`item_id`,`bom_quantity`,`status`) VALUES {$data}");
               if(!empty($datai)){
			   $this->conn->query("DELETE FROM `items` where bom_id = '{$device_id}'");
			   $save = $this->conn->query("INSERT INTO `items` (`name`, `apparatus`, `supplier_id`, `unit`, `status`, `bom_id`) VALUES {$datai}");
			   }				
				if(!$save){
					$resp['status'] = 'failed';
					if(empty($id)){
						//$this->conn->query("DELETE FROM `purchase_orders` where bom_id '{$bom_id}'");
					}
					$resp['msg'] = 'PO has failed to save. Error: '.$this->conn->error;
					//$resp['sql'] = "INSERT INTO `po_items` (`po_id`,`item_id`,`quantity`,`price`,`unit`,`total`) VALUES {$data}";
				} else {
					$resp['status'] = 'success';
				} */
				$j += 1;
			}
	}
function save_bom(){

		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!is_numeric($v))
				$v= $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=", ";
				$data .=" `{$k}` = '{$v}' ";
			}
		}

		if(empty($id)){
			$sql = "INSERT INTO `items` set {$data}";
		}else{
			$sql = "UPDATE `items` set {$data} where bom_id = '{$id}'";
		}

		$save = $this->conn->query($sql);
		if($save){
			$resp['status'] = 'success';
			
		
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'An error occured. Error: '.$this->conn->error;
		}
		if($resp['status'] == 'success'){
			if(empty($id)){
				$this->settings->set_flashdata('success'," New BOM was Successfully created.");
			}else{
				$this->settings->set_flashdata('success'," BOM's Details Successfully updated.");
			}
		}

		return json_encode($resp);
	}
		function delete_bom(){
			extract($_POST);
		$get = $this->conn->query("SELECT * FROM items where id = '{$id}'");
		if($get->num_rows > 0){
			$res = $get->fetch_array();
		}
		$del = $this->conn->query("DELETE FROM `items` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"BOM & Items Successfully deleted.");
			//if(isset($res)){
				$this->conn->query("DELETE FROM `bill_of_materials` where bom_id = '{$id}'");
			//}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
			

	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
    case 'save_supplier':
        echo $Master->save_supplier();
        break;
    case 'delete_supplier':
        echo $Master->delete_supplier();
        break;
    case 'save_item':
        echo $Master->save_item();
        break;
    case 'delete_item':
        echo $Master->delete_item();
        break;
    case 'get_item':
        echo $Master->get_item();
        break;
    case 'save_po':
        echo $Master->save_po();
        break;
    case 'delete_po':
        echo $Master->delete_po();
        break;
    case 'save_receiving':
        echo $Master->save_receiving();
        break;
		    case 'save_products':
        echo $Master->save_products();
        break;
    case 'delete_receiving':
        echo $Master->delete_receiving();
        break;
    case 'save_return':
        echo $Master->save_return();
        break;
    case 'delete_return':
        echo $Master->delete_return();
        break;
    case 'reject_return':
        echo $Master->reject_return();
        break;
    case 'approve_return':
        echo $Master->approve_return();
        break;
    case 'approve_disposal':
        echo $Master->approve_disposal();
        break;
    case 'save_sale':
        echo $Master->save_sale();
        break;
    case 'delete_sale':
        echo $Master->delete_sale();
        break;
    case 'save_rtop':
        echo $Master->save_rtop();
        break;
    case 'save_atop':
        echo $Master->save_atop();
        break;
    case 'save_role':
        echo $Master->save_role();
        break;
		case 'save_minlevel':
        echo $Master->save_minlevel();
        break;
    case 'delete_role':
        echo $Master->delete_role();
        break;
    case 'expiry_return':
        echo $Master->expiry_return();
        break;
    case 'mlcheck_return':
        echo $Master->mlcheck_return();
        break;
    case 'save_profile':
        echo $Master->save_profile();
        break;
    case 'delete_profile':
        echo $Master->delete_profile();
        break;
    case 'save_disposal':
        echo $Master->save_disposal();
        break;
    case 'delete_disposal':
        echo $Master->delete_disposal();
        break;
    case 'save_alert':
        echo $Master->save_alert();
        break;
    case 'delete_alert':
        echo $Master->delete_alert();
        break;
	case 'save_bom':
		echo $Master->save_bom();
	break;
	case 'delete_bom':
		echo $Master->delete_bom();
	break;
	case 'save_bom_item':
		echo $Master->save_bom_item();
	break;
	case 'fetch_select':
		echo $Master->fetch_select();
	break;
	
    default:
        // echo $sysset->index();
        break;
}


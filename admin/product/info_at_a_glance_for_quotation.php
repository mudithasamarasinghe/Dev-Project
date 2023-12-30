<?php
// Info at a Glance contribution v1.8 2010-Feb-10
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license        |
// +----------------------------------------------------------------------+
//

function info_at_a_glance_for_quotation($quotation_id) {
	echo $quotation_id;
//global $db;
//$qtnln = (int)QUOTATION_LENGTH;
$article = "<table style='width: 240px; min-height: auto; position: absolute; left: 0;border: 1px solid #ddd;bottom:0; background: #2564c9;  border-radius: 5px;  padding: 1rem;  display: flex;  flex-direction: column;  margin-right: 1.5rem;  margin-bottom: 1.5rem;  float: left;  color: #fff;display: flex;  flex-wrap: wrap;  justify-content: space-between;  align-items: center;'><tr><th class='text-center'><u>Comment for the quotation</u></th></tr>";
//$num = 1;
//$result_articles = $db->Execute("select q.remark from " . TABLE_QUOTATION .   " q where q.quotation_id = '" . $quotation_id . "'");

//while(!$result_articles->EOF){

//$article .= "<tr><td>".($result_articles->fields["remark"]) . "</td></tr>";
//$num++;
//$result_articles->MoveNext();
}

//rticle .= "</table>";
//$parsedComment = explode("\r", $article);
$cleanComment = "cdsssssss";
//$i=0;
//while($i < count($parsedComment)) {
//$cleanComment .= trim($parsedComment[$i]);
//$i++;
//if ($i < count($parsedComment)) $cleanComment .= '<br /> ';
//}
?>
<script language="javascript" type="text/javascript"><!--
document.write('<?php echo '<a href="javascript:void(0);" onmouseover="overlib(\\\'' . addslashes(addslashes($cleanComment)) . '\\\');" onmouseout="return nd();"><img src="./inc/icons/comment2.gif" align="top" border=0><\/a>'; ?>');
--></script>
<?php
//return;
 // }
?>
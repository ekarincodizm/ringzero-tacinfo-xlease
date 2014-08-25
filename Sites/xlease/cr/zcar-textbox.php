<?php
include("../config/config.php");
$id = pg_escape_string($_GET['id']);
$module = pg_escape_string($_GET['module']);
$billchange = pg_escape_string($_GET['billchange']);

if(!empty($id)){
    $qry=pg_query("select \"BillNumber\",\"TaxValue\" from carregis.\"DetailCarTax\" where \"IDDetail\"='$id'");
    if($res=pg_fetch_array($qry)){
        $b_BillNumber = $res["BillNumber"];
        $b_TaxValue = $res["TaxValue"];
    }
}
?>

<?php
switch ($module){
    case "showbill":
?>
        <input type="text" name="chbill<?php echo $id; ?>" id="chbill<?php echo $id; ?>" value="<?php echo "$b_BillNumber"; ?>" size="13" onblur="savebill('<?php echo $id; ?>','<?php echo "$b_BillNumber"; ?>');" title="Click Outside to Save">
<?php
    break;
    case "defaultbill":
?>
<a  onclick="editbill('<?php echo $id; ?>');" title="Click To Edit">
<b>
<?php
if(empty($b_BillNumber)){
    echo "...";
}else{
    echo "$b_BillNumber";
}
?>
</b>
</a>
<?php
    break;
    case "shownobill":
?>
        <input type="text" name="chnobill<?php echo $id; ?>" id="chnobill<?php echo $id; ?>" value="" size="13" onblur="savenobill('<?php echo $id; ?>');" title="Click Outside to Save">
<?php
    break;
    case "defaultnobill":
?>
<a  onclick="editnobill('<?php echo $id; ?>');" title="Click To Edit"><b>...</b></a>
<?php
    break;
    case "shownotnobill":
?>
        <input type="text" name="chnotnobill<?php echo $id; ?>" id="chnotnobill<?php echo $id; ?>" value="<?php echo number_format($b_TaxValue,2); ?>" size="13" onblur="savenotnobill('<?php echo $id; ?>','<?php echo number_format($b_TaxValue,2); ?>');" title="Click Outside to Save">
<?php
    break;
    case "defaultnotnobill":
?>
<a  onclick="editnotnobill('<?php echo $id; ?>');" title="Click To Edit"><b><?php echo number_format($b_TaxValue,2); ?></b></a>
<?php
    break;
}
?>

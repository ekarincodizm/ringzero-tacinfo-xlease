<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['idno']);

if(empty($idno)){
    exit;
}
?>

<table width="60%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
	<td width="50">ลำดับที่</td>
    <td>ชื่อ/สกุล</td>
    <td>สถานะลูกค้า</td>
    <td>แก้ไข</td>
</tr>

<?php
$qry_name=pg_query("select *,a.\"CusID\" as cus_id from \"ContactCus\" a 
left join \"Fa1\" b on a.\"CusID\" = b.\"CusID\" 
left join \"VContact\" c on a.\"IDNO\"=c.\"IDNO\" 
WHERE a.\"IDNO\" ='$idno' order by a.\"CusState\"");
$num_row = pg_num_rows($qry_name);
$p = 1;
while($res_name=pg_fetch_array($qry_name)){
    $CusID=$res_name["cus_id"];
    $IDNO=$res_name["IDNO"];
    $name=$res_name["A_FIRNAME"].$res_name["A_NAME"]." ".$res_name["A_SIRNAME"];
    $CusState=$res_name["CusState"];
        if($CusState == 0){ $show_type = "ผู้เช่าซื้อ"; }else{ $show_type = "ผู้ค้ำคนที่ $CusState"; }
        
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
	<td align="center"><?php echo "$p"; ?></td>
    <td><?php echo "$name"; ?></td>
    <td><?php echo "$show_type"; ?></td>
    <td align="center" width="200">
		<img src="images/edit.png" width="16" height="16" style="cursor:pointer" title="แก้ไขที่อยู่" onclick="window.location='frm_lt_edit_detail.php?idno=<?php echo $IDNO;?>&CusID=<?php echo $CusID;?>&CusState=<?php echo $CusState;?>'">
	</td>
</tr>

<?php
$p++;
} //end while

?>
</table>
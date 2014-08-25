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
    <td>ส่งจดหมาย</td>
</tr>

<?php

$qry_name=pg_query("select a.\"IDNO\",a.\"CusID\" as cus_id,\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"CusState\"  from \"ContactCus\" a 
left join \"Fa1\" b on a.\"CusID\" = b.\"CusID\" 
left join \"VContact\" c on a.\"IDNO\"=c.\"IDNO\" 
WHERE a.\"IDNO\" ='$idno' order by a.\"CusState\"");
$num_row = pg_num_rows($qry_name);
$p = 1;
while($res_name=pg_fetch_array($qry_name)){
    $CusID=$res_name["cus_id"];
    $IDNO=$res_name["IDNO"];
    $name=$res_name["A_FIRNAME"].$res_name["A_NAME"]." ".$res_name["A_SIRNAME"];
    //$dtl_ads=$res_name["address"]; $dtl_ads = nl2br($dtl_ads);
    $CusState=$res_name["CusState"];
        if($CusState == 0){ $show_type = "ผู้เช่าซื้อ"; }else{ $show_type = "ผู้ค้ำคนที่ $CusState"; }
        
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
	
	//ตรวจสอบว่ามีการส่งจดหมายหรือไม่
	$qry_name1=pg_query("select \"auto_id\" from letter.\"SendDetail\" A 
	left join letter.\"cus_address\" B on A.\"address_id\" = B.\"address_id\"  
	left join \"Fa1\" C on B.\"CusID\" = C.\"CusID\"
	WHERE A.\"IDNO\"='$IDNO' and B.\"CusID\"='$CusID' order by A.\"send_date\" DESC");
	$num_row2=pg_num_rows($qry_name1);
	
	
?>
	<td align="center"><?php echo "$p"; ?></td>
    <td><?php echo "$name"; ?></td>
    <td><?php echo "$show_type"; ?></td>
    <td align="center" width="200">
	<?php 
	if($num_row2 == 0){ //ยังไม่มีการส่งจดหมาย
		echo "<input type=\"button\" onclick=\"window.location='frm_lt_user_detail_new.php?id=$IDNO&CusID=$CusID&CusState=$CusState'\" value=\"ส่งจดหมายโดยระบุที่อยู่ใหม่\">";
	}else{ //แสดงว่ามีการส่งจดหมาย
		echo "<input type=\"button\" onclick=\"window.location='frm_lt_user_detail.php?idno=$IDNO&CusID=$CusID&CusState=$CusState'\" value=\"เลือก\">";
	}
	?>
	</td>
</tr>

<?php
$p++;
} //end while

?>
</table>
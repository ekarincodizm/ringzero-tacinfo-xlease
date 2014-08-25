<?php
session_start();
include("../../config/config.php");
$contempID=trim(pg_escape_string($_GET["contempID"]));
$IDNO=trim(pg_escape_string($_GET["IDNO"]));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียดที่แก้ไข</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>
<div style="text-align:center;"><h2>รายละเอียดที่แก้ไข</h2></div>
<div><b>เลขที่สัญญา :</b> <span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $IDNO;?></u></font></span></div>
<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE">

<?php
//ค้นหาข้อมูลที่แก้ไข
$qrydata=pg_query("select a.\"IDNO\",a.\"CusState\",c.\"full_name\" as \"cusname\",a.\"resultcancel\" from \"ContactCus_Temp\" a
inner join  
(select * from  \"ContactCus_Temp\"  
where \"contempID\"='$contempID') b on a.\"IDNO\"=b.\"IDNO\" and a.\"userRequest\"=b.\"userRequest\" and a.\"userStamp\"=b.\"userStamp\" and 
a.\"appUser\"=b.\"appUser\" and a.\"appStamp\"=b.\"appStamp\" and a.\"statusApp\"=b.\"statusApp\" 
left join \"VSearchCusCorp\" c on a.\"CusID\"=c.\"CusID\" order by \"CusState\"");

while($res_app=pg_fetch_array($qrydata)){
	$CusState=$res_app["CusState"];//สถานะลูกค้า 
	$cusname=$res_app["cusname"];//-- ชื่อลูกค้า
	$resultcancel=$res_app["resultcancel"];//-- เหตุผลที่แก้ไข
	
	if($CusState==0){
		$txtcus="ผู้เช่าซื้อ";
	}else{
		$txtcus="ผู้ค้ำคนที่ $CusState";
	}
	
	?>
	<tr height="30" bgcolor="<?php echo $color1;?>">
		<td align="right" bgcolor="#F5F5F5"><?php echo $txtcus;?> : </td>
		<td bgcolor="#FFFFFF"><?php echo "$cusname";?></td>
	</tr>
<?php

}
?>
</tr>
<tr height="30" bgcolor="#FFFFFF">
	<td align="right" valign="top"><b>เหตุผลที่แก้ไข :</b></td>
	<td><textarea name="note" id="note" cols="40" rows="5" readonly="true" ><?php echo $resultcancel;?></textarea></td>
</tr>
<tr align="center" height="50" bgcolor="#FFFFFF"><td colspan=2><input type="button" value="ปิด" onclick="window.close()"></td></tr>
</table>

<?php
include("../../config/config.php");
$idno=trim(pg_escape_string($_POST["h_id_s"]));
$status=pg_escape_string($_GET["status"]);  //สถานะของการทำงานขณะนั้น 
if(pg_escape_string($_GET["idnog"]) != ""){
	$idno = pg_escape_string($_GET["idnog"]);
}
if(empty($idno)){
}
//ตรวจสอบข้อมูลก่อนว่าข้อมูลนี้อยู่ในระหว่างรออนุมัติหรือไม่
$checkqry=pg_query("select distinct(\"statusApp\") as stsapp from \"ContactCus_Temp\" where \"IDNO\" = '$idno' and \"statusApp\" not in(0,1)");
if($rescheck=pg_fetch_array($checkqry)){
	$stsapp=$rescheck["stsapp"];
}

if($stsapp==2){
	echo "<div align=center style=\"padding:20px;\"><font size=4><b>รายการนี้อยู่ในระหว่างรออนุมัติ<br>สามารถทำรายการต่อได้หลังจากได้รับการอนุมัติแล้ว</b></font></div>";
	echo "<meta http-equiv=\"refresh\" content=\"8;URL=frmEditCustomer.php\">"; 
}else{
	//ตรวจสอบก่อนว่ามีค่าค้างอยู่ในฐานหรือไม่ถ้าค้างให้แสดงข้อมูลที่ค้างขึ้นมา
	if($stsapp==9){
		$status=9;
		$txt="ข้อมูลนี้ได้ถูกแก้ไข แล้วยังไม่ได้ส่งอนุมัติ!";
	}
	
	if($status==9){
		$query_cus0=pg_query("select A.\"CusID\",B.\"A_FIRNAME\",B.\"A_NAME\",B.\"A_SIRNAME\" from \"ContactCus_Temp\" A
		left join \"Fa1\" B on A.\"CusID\"=B.\"CusID\" where A.\"IDNO\" = '$idno' and A.\"CusState\" = '0' and \"statusApp\"='9'");
	}else{
		$query_cus0=pg_query("select A.\"CusID\",B.\"A_FIRNAME\",B.\"A_NAME\",B.\"A_SIRNAME\" from \"ContactCus\" A
		left join \"Fa1\" B on A.\"CusID\"=B.\"CusID\" where A.\"IDNO\" = '$idno' and A.\"CusState\" = '0'");
	}
	$res_cus0=pg_fetch_array($query_cus0);
	$CusID=$res_cus0["CusID"];
	$fullname = $res_cus0["A_FIRNAME"].$res_cus0["A_NAME"]." ".$res_cus0["A_SIRNAME"];
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html>
	<head>
		<title>แก้ไขผู้เช่าซื้อ/ผู้ค้ำ</title>
		<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
		<link type="text/css" rel="stylesheet" href="act.css"></link>
		<script language="javascript">
		function popU(U,N,T) {
			newWindow = window.open(U, N, T);
		}
		</script>
	</head>
	<body>

	<table width="60%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
	<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frmEditCustomer.php'"></div>
	<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
	<div style="clear:both; padding-bottom: 10px;"></div>        
			</td>
		</tr>
	</table>

	<table width="60%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#CCCCCC" align="center">

	<tr style="font-weight:bold;" valign="middle">
		<td colspan="3" bgcolor="#FFFFFF" height="30"><font color="red"><h2><?php echo $txt;?></h2></font> สัญญาเช่าซื้อเลขที่ : <span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $idno; ?>&type=outstanding','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $idno;?></u></font></span></td>
	</tr>
	<tr  valign="middle" bgcolor="#FFFFFF">
		<td bgcolor="#79BCFF" style="font-weight:bold;"><I>ผู้เช่าซื้อ</I></td>
		<td><?php echo $CusID." - ".$fullname;?>(<span onclick="javascript:popU('show_addr.php?IDNO=<?php echo $idno?>&CusID=<?php echo $CusID;?>&fullname=<?php echo $fullname?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')" style="cursor:pointer;"><u>ที่อยู่</u></span>)</td>
		<td align="center"><input type="button" value="เปลี่ยน" onclick="window.location='frm_select_contactcus.php?fIDNO=<?php echo $idno?>&stsup=update&CusState=0'" <?php if($idno ==""){ echo "disabled"; }?>></td>
	</tr>
	<tr>
		<td colspan="3" bgcolor="#FFFFFF" height="25"></td>
	</tr>
	<tr style="font-weight:bold;" valign="middle" bgcolor="#FFFFFF">
		<td bgcolor="#79BCFF" colspan="2" height="25"><I>ผู้ค้ำ</I></td>
		<td bgcolor="#79BCFF" align="right"><input type="button" value="เพิ่มผู้ค้ำ" onclick="window.location='frm_select_contactcus.php?fIDNO=<?php echo $idno;?>&stsup=add&CusState='" <?php if($idno ==""){ echo "disabled"; }?>></td>
	</tr>
	<?php
		if($status==9){
			$query_co=pg_query("select A.\"CusID\",B.\"A_FIRNAME\",B.\"A_NAME\",B.\"A_SIRNAME\",A.\"CusState\" from \"ContactCus_Temp\" A
			left join \"Fa1\" B on A.\"CusID\"=B.\"CusID\" where A.\"IDNO\" = '$idno' and A.\"CusState\" <> '0' and \"statusApp\"='9' order by A.\"CusState\" ");
		}else{
			$query_co=pg_query("select A.\"CusID\",B.\"A_FIRNAME\",B.\"A_NAME\",B.\"A_SIRNAME\",A.\"CusState\" from \"ContactCus\" A
			left join \"Fa1\" B on A.\"CusID\"=B.\"CusID\" where A.\"IDNO\" = '$idno' and A.\"CusState\" <> '0' order by A.\"CusState\"");
		}
		
		$num_rows= pg_num_rows($query_co);
		
		$p = 1;
		while($res_co=pg_fetch_array($query_co)){
			$CusID2=$res_co["CusID"];
			$CusState = $res_co["CusState"];
			$fullname2 = trim($res_co["A_FIRNAME"]).trim($res_co["A_NAME"])." ".trim($res_co["A_SIRNAME"]);
			echo "<tr bgcolor=#FFFFFF><td align=center>คนที่ $p</td><td>$CusID2 - $fullname2 (<span onclick=\"javascript:popU('show_addr.php?IDNO=$idno&CusID=$CusID2&fullname=$fullname2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\" style=\"cursor:pointer;\"><u>ที่อยู่</u></span>)</td><td align=center><input type=\"button\" value=\"เปลี่ยน\" onclick=\"window.location='frm_select_contactcus.php?fIDNO=$idno&stsup=update&CusState=$CusState'\"><input type=\"button\" value=\"ลบ\" onclick=\"if(confirm('คุณยืนยันที่จะลบรายการนี้!!')){location.href='process_cus_delete.php?idno=$idno&CusState=$CusState'}\"></td></tr>";
			
			$p++;
		} //end while
		if($num_rows == '0'){
			echo "<tr><td colspan=3 align=center height=50 bgcolor=#FFFFFF><b>----- ไม่มีผู้ค้ำ -----</b></td></tr>";
		}
	?>
	</table>
	<?php 
		if($status==9){
	?>
		<form method="post" name="form1" action="save_contactcus.php">
			<input type="hidden" name="idno" id="idno" value="<?php echo $idno; ?>" />
			<input type="hidden" name="CusState" id="CusState" value="" />
			<input type="hidden" name="stsup" id="stsup" value="confirm" />
			<div style="text-align:center;padding-top:20px;"><b>*ระบุเหตุผลที่ยกเลิก*</b></div>
			<div style="text-align:center;"><textarea name="resultcancel" cols="50" rows="5"></textarea></div>
			<div style="text-align:center;padding-top:20px;"><font color="red"><b>* หากท่านแก้ไขข้อมูลแล้วไม่ต้องการส่งไปอนุมัติ กรุณา "ยกเลิกข้อมูล" ด้วยทุกครั้ง มิฉะนั้นจะทำให้ข้อมูลค้างอยู่ในระบบ!</b></font></div>
			<div style="text-align:center;padding:20px;"><input type="submit" value="ส่งข้อมูลนี้ไปอนุมัติ"><input type="button" value="ยกเลิกข้อมูลนี้" onclick="if(confirm('คุณยืนยันที่จะยกเิลิกข้อมูลที่แก้ไข!!')){location.href='process_cus_delete.php?idno=<?php echo $idno;?>&CusState=<?php echo $CusState;?>&method=cancel'}"></div>
		</form>
	<?php
		}
}
	?>
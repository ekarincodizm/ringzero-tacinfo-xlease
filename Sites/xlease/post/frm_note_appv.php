<?php 
include('../config/config.php');

$typeDep = pg_escape_string($_GET["typeDep"]);
$IdCarTax = pg_escape_string($_GET["idcarTax"]);

//ดึง ข้อมูล  ที่ตาราง carregis."DetailCarTax" เพื่อ ทดสอบ ว่าจะ ลบ ข้อมูล หริอไม่
$qry_dataDetailCarTax=pg_query("select \"IDCarTax\",\"Cancel\" from carregis.\"DetailCarTax\" WHERE \"Cancel\" = 'false' AND \"IDCarTax\"='$IdCarTax'");
$numrow_dataDetailCarTax=pg_num_rows($qry_dataDetailCarTax);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script language="JavaScript">
function chk(){    
    if(document.getElementById("note").value==""){ 
		alert("กรุณาระบุหมายเหตุการขอยกเลิก");
		return false;
	}
	else{ return true;}
}
function chkPermit()
		{	
			if(document.getElementById("Permit").checked == true)
			{
				document.getElementById("ok").disabled = false;
			}
			else
			{
				document.getElementById("ok").disabled = true;
			}
		}
		
		function DeleteDataPermit(TName,IdCarTax,remark_doer)
		{ // ยอมให้ลบได้ แม้ ยอดค้างชำระนี้มีการคิดต้นทุนไว้แล้ว ก็ตาม
			
			$.post("process_frm_cal_cuspayment.php",{
				typeDep : TName,
				idcarTax : IdCarTax,
				note: remark_doer,
				permit : 'yes'
			},
			function(data){
				if(data=='0'){
						alert('ขอยกเลิกการลบข้อมูลเสร็จสิ้น');
						RefreshMe()
				}else if(data == '1' || data == '2'){
						alert('ไม่สามารถขอยกเลิกการลบข้อมูลได้ กรุณาลองใหม่ในภายหลัง !');
						RefreshMe();
				}else{
					alert(data);
					RefreshMe();
				}
			});
		}
</script>
<body>
<div style="text-align:center"><h2>หมายเหตุการขอยกเลิก</h2></div>
<center>
<form name="frm" method="post" action="process_frm_cal_cuspayment.php">	
<fieldset  style="width:500px;"><legend><font color="black"><b>รายละเอียด </legend>
	<?php if($numrow_dataDetailCarTax >0){?>
		<font color="#FF0000">ไม่สามารถลบข้อมูลได้ เนื่องจาก ยอดค้างชำระนี้มีการคิดต้นทุนไว้แล้ว!</font><br>
		<input type="checkbox" name="Permit" id="Permit" onChange="chkPermit();"> ยืนยันที่จะขอยกเลิก แม้จะมีการคิดต้นทุนไว้แล้วก็ตาม
		
	<?php } ?>
	<table align="center" border="0">		
		<tr><td align="right"><b>เลขที่:</b></td><td><?php echo $IdCarTax; ?></td></tr>
		<tr><td align="right"><b>รายการ :</b></td><td><?php echo $typeDep; ?></td></tr>		
		<tr><td align="right" valign="top"><b>หมายเหตุการขอยกเลิก:</b></td><td>
		<textarea name="note" id="note" cols="50" rows="4"></textarea></td></tr>
	</table>
</fieldset>

<div style="text-align:center;padding:20px">
	<input  type="hidden" name="idcarTax" id="idcarTax" value="<?php echo $IdCarTax; ?>">
	<input  type="hidden" name="typeDep" id="typeDep" value="<?php echo $typeDep; ?>">
	<font color="#FF0000">หมายเหตุ : รายการนี้จะมีผลต่อเมื่อผู้มีสิทธิอนมุติได้อนุมัติผ่านเมนู "อนุมัติยกเลิกหนี้ค่าใช้จ่ายค้างชำระ"</font>
	<br><br>
	<?php if($numrow_dataDetailCarTax >0){?>
	<input type="submit" name="ok" id="ok" value="ขอยกเลิก" onclick="return chk()"  disabled >
	<?php }else { ?>
	<input type="submit" name="ok" id="ok" value="ขอยกเลิก" onclick="return chk()" >
	<?php } ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="ยกเลิก" onClick="window.close();">
</div>

<fieldset  style="width:600px;"><legend><font color="black"><b>รายการที่อยู่ระหว่างรออนุมัติยกเลิกหนี้ค่าใช้จ่ายค้างชำระ </legend>
	<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
		<tr align="center" bgcolor="#79BCFF">
			<th>เลขที่</th>
			<th>รายการ</th>
			<th>จำนวนเงิน</th>
			<th>ผู้ทำรายการ</th>
			<th>วันเวลาที่ทำทำรายการ</th>
		</tr>
		<?php
		// หา IDNO
		$qryIDNO = pg_query("select \"IDNO\" from carregis.\"CarTaxDue\" WHERE \"IDCarTax\" = '$IdCarTax'");
		$IDNO = pg_fetch_result($qryIDNO,0);
		
		$query = pg_query("select * from carregis.\"CarTaxDue_reserve\" WHERE \"IDNO\" = '$IDNO' and \"Approved\" = '9'");
		$numrows = pg_num_rows($query);
		$i=0;
		while($result = pg_fetch_array($query))
		{
			$i++;
			$IDCarTaxLoop = $result["IDCarTax"];
			$TypeDep = $result["TypeDep"];
			$CusAmt = $result["CusAmt"];
			$doerID = $result["doerID"];
			$doerStamp = $result["doerStamp"];
			
			$qry_nn = pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
			if($res_nn = pg_fetch_array($qry_nn))
			{
				$TName = $res_nn["TName"];
			}
			
			$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
			while($result_name = pg_fetch_array($qry_name))
			{
				$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
			}
			
			if($i%2==0){
				echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
			}else{
				echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
			}
			
			echo "<td align=\"center\">$IDCarTaxLoop</td>";
			echo "<td align=\"center\">$TName</td>";
			echo "<td align=\"center\">$CusAmt</td>";
			echo "<td align=\"center\">$fullname</td>";
			echo "<td align=\"center\">$doerStamp</td>";
			echo "</tr>";
		}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=5 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=5><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
		}
		?>
</fieldset>
</form>
</center>
</body>
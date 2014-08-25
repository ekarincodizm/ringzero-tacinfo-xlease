<?php
$id_user = pg_escape_string($_GET['id_user']);
if($id_user == ""){

	$id_user2=pg_escape_string($_POST["id_user"]);
	$id_user = substr($id_user2,0,3);
	$name=substr($id_user2,4);
}else{
	include("../../config/config.php");

	$month = pg_escape_string($_GET["month"]);
	$year = pg_escape_string($_GET["year"]);
	$SelectChart=pg_escape_string($_GET["SelectChart"]);
	$qry_user = pg_query("SELECT  fullname FROM \"Vfuser\" where id_user = '$id_user'");
	list($name) = pg_fetch_array($qry_user);
	
	if($SelectChart=="a1"){
		$conmonth="";
		$txtcon="ประจำเดือนมกราคม-ธันวาคม";
	}else{
		$conmonth="AND (EXTRACT(MONTH FROM a.\"startDate\")='$month')";
		$txtcon="ประจำเดือน";
		if($month == "01"){
			$txtmonth="มกราคม";
		}else if($month == "02"){
			$txtmonth="กุมภาพันธ์";
		}else if($month == "03"){
			$txtmonth="มีนาคม";
		}else if($month == "04"){
			$txtmonth="เมษายน";
		}else if($month == "05"){
			$txtmonth="พฤษภาคม";
		}else if($month == "06"){
			$txtmonth="มิถุนายน";
		}else if($month == "07"){
			$txtmonth="กรกฎาคม";
		}else if($month == "08"){
			$txtmonth="สิงหาคม";
		}else if($month == "09"){
			$txtmonth="กันยายน";
		}else if($month == "10"){
			$txtmonth="ตุลาคม";
		}else if($month == "11"){
			$txtmonth="พฤศจิกายน";
		}else if($month == "12"){
			$txtmonth="ธันวาคม";
		}
	}
	
$stateshow = "noinclude";	
}
	
?>
<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
</script>

<?php if($stateshow == 'oninclude'){
		echo "<table width=\"1000\" border=\"0\" style=\"margin-top:1px\" align=\"center\">
    <tr>
        <td> ";
	
}	?>	
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />


<form method="post" name="form1" action="pdf_onlyreport.php" target="_blank" > 
<fieldset>
<table width="950" align="center">
<tr><td>
<div align="center"><h2>รายงานพนักงานขายแยกตามบุคคล</h2></div>
<div align="center"><h3><?php echo $txtcon.$txtmonth;?>&nbsp;ค.ศ. <?php echo $year;?></h3></h3></div>
<table width="940" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr align="laft" bgcolor="#FFFFFF">
		<td colspan="7"><b>ชื่อพนักงาน :</b> <?php echo $name;?></td>
	</tr>
	<tr align="center" bgcolor="#79BCFF">
		<th>ลำดับที่</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อลูกค้า</th>
		<th>ทะเบียนรถยนต์</th>
		<th>สีรถ</th>
		<th>ยอดสินเชื่อ</th>
		<th>สถานะ</th>
	</tr>
	<?php
		$query=pg_query("select a.\"IDNO\",b.\"P_BEGIN\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",d.\"C_REGIS\",d.\"C_COLOR\" ,b.\"P_STDATE\" , b.\"P_ACCLOSE\" , b.\"P_CLDATE\"
		
		from \"nw_startDateFp\" a
		left join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
		left join \"Fa1\" c on b.\"CusID\" = c.\"CusID\"
		left join \"VCarregistemp\" d on b.\"IDNO\" = d.\"IDNO\" where a.\"id_user\"='$id_user' AND EXTRACT(YEAR FROM a.\"startDate\")='$year' $conmonth order by a.\"IDNO\"");
		$numrows=pg_num_rows($query);
		
		$sumbegin=0;
		$i=1;
		$sumclose = 0;
		$sumone = 0;
		$sumtwo = 0;
		$sumthree = 0;
		$summormal = 0;
		while($result=pg_fetch_array($query)){
			$IDNO=$result["IDNO"];
			$P_BEGIN=$result["P_BEGIN"]; //ยอดสินเชื่อ
			$P_BEGIN2=number_format($P_BEGIN,2);
			$cusname=trim($result["A_FIRNAME"]).trim($result["A_NAME"])." ".trim($result["A_SIRNAME"]); //รายชื่อลูกค้า
			$C_REGIS =$result["C_REGIS"]; //ทะเบียนรถ
			$C_COLOR =$result["C_COLOR"]; //สีรถ
			$P_STDATE=$result["P_STDATE"];
			$P_ACCLOSE = trim($result["P_ACCLOSE"]);
			$P_CLDATE = trim($result["P_CLDATE"]);			
			if($i%2==0){
				echo "<tr class=\"odd\">";
			}else{
				echo "<tr class=\"even\">";
			}
			
			$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO',1)");
			list($state) = pg_fetch_array($qry_behind);
			$qry_behind = pg_query("SELECT xls_get_backduenum('$IDNO')");
			list($codestate) = pg_fetch_array($qry_behind);
				
			if($codestate == '00'){ $sumclose++ ;}
			else if($codestate == '1'){ $sumone++ ; }
			else if($codestate == '2'){ $sumtwo++ ; }
			else if($codestate >= '3'){ $sumthree++ ; }
			else{ $sumnormal++ ; }
			
			echo "<td align=center height=25>$i</td>";
			echo "<td align=center><a onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\"><u>$IDNO</u></a></td>";
			echo "<td>$cusname</td>";
			echo "<td align=center>$C_REGIS</td>";
			echo "<td align=right>$C_COLOR</td>";
			echo "<td align=right>$P_BEGIN2</td>";
			echo "<td align=center>$state</td>";
			echo "</tr>";

			$sumbegin = $sumbegin+$P_BEGIN;
			$i++;
		}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center>ไม่มีข้อมูล</td></tr>";
		}else{
	?>
		<tr bgcolor="#FFCCFF">
			<td colspan="5" align="right" height="25" ><b>รวมยอดสินเชื่อ</b></td>
			<td  align="right"><b><?php echo number_format($sumbegin,2);?></b></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาทั้งหมด: <b><?php echo number_format($numrows); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่มีสถานะเป็นปกติ: <b><?php echo number_format($sumnormal); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่มีสถานะค้าง  1 งวด: <b><?php echo number_format($sumone); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่มีสถานะค้าง  2 งวด: <b><?php echo number_format($sumtwo); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่มีสถานะค้าง  3 งวดขึ้นไป: <b><?php echo number_format($sumthree); ?></b></td>
		</tr>
		<tr>
			<td colspan="4">จำนวนสัญญาที่ปิดบัญชีแล้ว: <b><?php echo number_format($sumclose); ?></b></td>
		</tr>
		<?php }?>
		<tr bgcolor="#FFFFFF">
			<td height="25" ><input type="button" value="กลับ" onclick="window.location='frm_Index.php'"></td>
			<td colspan="6" align="right" height="25" >
				<input type="hidden" name="id_user" value="<?php echo $id_user?>">
				<input type="hidden" name="name" value="<?php echo $name?>">
				<input type="hidden" name="month" value="<?php echo $month?>">
				<input type="hidden" name="year" value="<?php echo $year?>">
				<input type="hidden" name="txtmonth" value="<?php echo $txtmonth?>">
				<input type="hidden" name="txtcon" value="<?php echo $txtcon;?>">
				<input type="hidden" name="SelectChart" value="<?php echo $SelectChart;?>">
				<input type="submit" value="พิมพ์รายงาน" <?php if($numrows==0){?> disabled <?php }?>>
			</td>
		</tr>
</table>
</td>
</tr>
</table>
<br>
</fieldset> 
</form>				
<?php if($stateshow == 'oninclude'){
		echo "</td>
    </tr>
</table>  "; 
	
}	?>					
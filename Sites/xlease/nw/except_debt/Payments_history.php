<?php
include("../../config/config.php");
$ConID = $_GET['ConID'];
$page = $_GET['page']; //เมนูที่เรียกใช้หน้านี้

if(empty($ConID)){
   $ConID = $_POST['ConID'];
}

$currentDate=nowDate();
$ycurent=substr($currentDate,0,4);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ขอยกเว้นหนี้</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function calculate()
{
	obj = document.myfrm.elements['chk[]'] ;
	var k=0;
	var total;
	
	if(obj.length > 0)
	{
		for(i=0; i<obj.length; i++)
		{    
			if(obj[i].checked)
			{		
				total = obj[i].value;
				var obj2=total.split(" ");
				k +=parseFloat(obj2[0]);
			}
		}
	}
	else
	{
		if(document.getElementById("chk").checked == true)
		{
			var str = document.getElementById("chk").value;
			var obj2 = str.split(" ");
			k = parseFloat(obj2[0]);
		}
	}
	
	//alert(k); //จำนวนเงิน
	document.getElementById("receiveAmountPost").value = k;
}

function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage
	
	obj = document.myfrm.elements['chk[]'];
	var c = 0;
	
	if(obj.length > 0)
	{
		for(i=0; i<obj.length; i++)
		{    
			if(obj[i].checked)
			{
				c  = 1; // ถ้ามีการเลือกรายการแล้ว ให้ c = 1
			}
		}
	}
	else
	{
		if(document.getElementById("chk").checked == true)
		{
			c  = 1; // ถ้ามีการเลือกรายการแล้ว ให้ c = 1
		}
	}
	
	if (c != 0){} else{ // ถ้า c = 0 แสดงว่ายังไม่ได้เลือกรายการที่จะยกเว้นหนี้
	theMessage = theMessage + "\n -->  กรุณาเลือกรายการที่จะยกเว้นหนี้";
	}

	if (document.getElementById("txtRemark").value != ""){} else{
	theMessage = theMessage + "\n -->  กรุณากรอกเหตุผลที่ยกเลิก";
	}

	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

</script>
<style type="text/css">
#warppage
{
width:800px;
margin-left:auto;
margin-right:auto;

min-height: 5em;
background: rgb(255, 255, 255);
padding: 5px;
border: rgb(128, 128, 128) solid 0.5px;
border-radius: .625em;
-moz-border-radius: .625em;
-webkit-border-radius: .625em;
}
/*
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
*/
</style>
</head>
<body>
<?php
echo "<input type=\"hidden\" name=\"renew\" id=\"renew\" value=\"$ConID\">";
echo "<input type=\"hidden\" name=\"ConID2\" id=\"ConID2\" value=\"$ConID\">";
?>

<center><a href="#" onclick="javascript:popU('../thcap_installments/frm_Index.php?idno=<?php echo "$ConID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')"><?php echo "<b><FONT COLOR=#0000FF><u>$ConID</u></FONT></b>"; ?></a></center>
<?php
$qry_name=pg_query("	select * from public.\"thcap_v_otherpay_debt_realother_current\" 
						where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtID\" not in (select \"debtID\" from \"thcap_temp_except_debt\" where \"Approve\" is null)
						order by \"typePayRefDate\" ");
$numrows = pg_num_rows($qry_name);
if($numrows == 0)
{
	$contractID = $ConID;
	echo "<center><h2>ไม่พบข้อมูลหนี้ที่ค้างชำระค่าอื่นๆชั่วคราวของสัญญา: $ConID</h2>"; 
	echo "<div class=\"style1\" style=\"width:1250px;margin-top:5px;margin-bottom:10px;\">";
		include("../thcap/Data_contract_detail.php");
	echo "</div>";
echo "<input type=\"button\" value=\"กลับไปหน้าค้นหา\" onclick=\"window.location='frm_Index.php'\"></center>";
}
else
{
?>
<form method="post" name='myfrm' action="Process_Payment.php">
<hr width="80%" color="#CCCCCC"><br>
<?php
//ค้นหาชื่อผู้กู้หลักจาก mysql
	// $db1="ta_mortgage_datastore";
	// $qry_namemain=mysql_query("select * from $db1.vcustomerbycontract
	// where contract_loans_code='$ConID' and cus_group_type_code='01'");
	// if($resnamemain=mysql_fetch_array($qry_namemain)){
		// $name3=trim($resnamemain["cusname"]);
	// }
	
	$db1="ta_mortgage_datastore";
	$qry_namemain=pg_query("select * from  \"vthcap_ContactCus_detail\"
	where \"contractID\"='$ConID' and \"CusState\"='0'");
	if($resnamemain=pg_fetch_array($qry_namemain)){
		$name3=trim($resnamemain["thcap_fullname"]);
	}
	$contractID = $ConID;
?>
<div style="margin-top:-20px;"></div>
<center><?php include("../thcap/Data_contract_detail.php"); ?></center>
<div style="margin-bottom:15px;"></div>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center">
<tr>
	<td colspan="3">
		<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
		<tr bgcolor="#097AB0" style="color:#FFFFFF" height="25">
			<th>รหัสประเภท<br>ค่าใช้จ่าย</th>
			<th>รายการ</th>
			<th>ค่าอ้างอิง<br>ของค่าใช้จ่าย</th>
			<th>วันที่ตั้งหนี้</th>
			<th>จำนวนหนี้</th>
			<th>ผู้ตั้งหนี้</th>
			<th>วันเวลาตั้งหนี้</th>
			<th>ทำรายการ</th>
		</tr>
		<?php
		while($res_name=pg_fetch_array($qry_name))
		{
			$typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
			$typePayRefValue=trim($res_name["typePayRefValue"]);
			$typePayRefDate=trim($res_name["typePayRefDate"]);
			$typePayAmt=trim($res_name["typePayAmt"]);
			$typePayLeft=trim($res_name["typePayLeft"]); // หนี้ค้างชำระ
			$doerID=trim($res_name["doerID"]); 
			$doerStamp=trim($res_name["doerStamp"]);
			$debtID=trim($res_name["debtID"]);
			$contractID=trim($res_name["contractID"]);
			
			$doerStamp = substr($doerStamp,0,19); // ทำให้อยู่ในรูปแบบวันเวลาที่สวยงาม
			
			$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
			while($res_type=pg_fetch_array($qry_type))
			{
				$tpDescview = trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
			}
			
			if($doerID == "000")
			{
				$doerName = "อัตโนมัติโดยระบบ";
			}
			else
			{
				$doerusername=pg_query("select * from public.\"Vfuser\" where \"id_user\"='$doerID'");
				while($res_username=pg_fetch_array($doerusername))
				{
					$doerName=$res_username["fullname"];
				}
			}
		
			echo "<tr bgcolor=#DBF2FD>";
			echo "<td align=center>$typePayID</td>";
			echo "<td align=center>$tpDescview</td>";
			echo "<td align=center>$typePayRefValue</td>";
			echo "<td align=center>$typePayRefDate</td>";
			echo "<td align=right>".number_format($typePayLeft,2)."</td>";
			echo "<td align=center>$doerName</td>";
			echo "<td align=center>$doerStamp</td>";
			echo "<td align=center><input id=\"chk\" name=\"chk[]\" type=\"checkbox\" value=\"$typePayLeft $debtID\" onclick=\"calculate()\"></td>";
			echo "</tr>";
		}
		echo "<input type=\"hidden\" id=\"ConID3\" name=\"ConID3\" value=\"$ConID\">";
		?>
		
		<!-- </table> -->
		
		
		
		
		
		
		<tr><td colspan="9" bgcolor="#FFCCCC" height="25"><b>เพิ่มข้อมูลการขอยกเว้นหนี้</b></td></tr>
		<tr bgcolor="#FFECEC"><td colspan="9">
			<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFECEC">
				<tr>
					<td align="right"><b>จำนวนเงิน : </b></td><td align="left"><input type="text" name="receiveAmountPost" id="receiveAmountPost" style="text-align: right;" readonly></td>
					<td align="right"><b>เหตุผลที่ยกเลิก : </b></td><td align="left"><textarea name="txtRemark" id="txtRemark" cols="35" rows="4"></textarea></td>
				</tr>
			</table>
		</tr>
		</table>
		
<table width="100%" align="center" border="0">
<tr height="50" align="center">
	<td width="50%" align="right"><input type="hidden" name="page" value="<?php echo $page;?>"><input type="submit" value="ยืนยันการขอยกเว้นหนี้" id="submitButton" onclick="return checkdata()">&nbsp;&nbsp;&nbsp;</td>
	<td width="50%" align="left">&nbsp;&nbsp;&nbsp;<input type="button" value="กลับไปหน้าค้นหา" onclick="window.location='frm_Index.php'"></td>
</tr>
</table>
</form>

<?php
}
?>

</body>
</html>
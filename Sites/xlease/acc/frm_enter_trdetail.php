<?php
session_start();
include("../config/config.php");
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
  <script type="text/javascript">
  	var gFiles = 0;
	var summary;
	function addFile() 
	
	{
	
	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="typepayment[]" id="typepayment"><?php 
	$qry_type=pg_query("select * from \"TypePay\" WHERE \"TypeID\" !=1 ");
	while($res_type=pg_fetch_array($qry_type))
	{ 
	echo  "<option value=\"$res_type[TypeID]\">$res_type[TName]</option>"; 
	}
	?></select>&nbsp;&nbsp;<input type="text" name="amt[]" id="amt" ><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
	document.getElementById('files-root').appendChild(li);
	
	    
	
	gFiles++;
	
	    
	
	}
	function removeFile(aId) {
	var obj = document.getElementById(aId);
	obj.parentNode.removeChild(obj);
	}
	
	
	function cal_fr()
	{ 
	 var sta1 =parseFloat(document.frm_ps.amts.value); //ยอดโอน
	 var va1 = parseFloat(document.frm_ps.count_fr.value); //จำนวนเดือนจ่าย
	 var va2 = parseFloat(document.frm_ps.fr_pay.value); //ค่างวด
	 var ress= parseFloat(document.frm_ps.rescal.value=va1*va2);
	 
	// var res_sum=parseFloat(document.frm_ps.sum.value=va2);
	 
	 
	 
	 if(ress > sta1)
	 {
	  alert("ยอดทำรายการเกินกว่ายอดเงินโอน");
	 }
	} 
	
	function alertday()
	{
	  var cc=document.frm_ps.ch_day.value; 
	  var qq=document.frm_ps.qryDate.value; 
	  
	  if(qq >= cc)
	  {
	   alert("วันที่ "+qq+" ไม่สามารถทำรายการได้" );
	  }
	}
	
	
	function validate() 
	{
	
	 var theMessage = "Please complete the following: \n-----------------------------------\n";
	 var noErrors = theMessage;
	 var cs=document.frm_ps.ch_day.value; 
	 
		if (document.frm_ps.hh.value=="") {
	        theMessage = theMessage + "\n --> ชั่วโมง = " + document.frm_ps.hh.value + "กรุณาใส่ชั่วโมง";
	    }
		
		
		if (document.frm_ps.hh.value >=23)  {
	        theMessage = theMessage + "\n --> ชั่วโมง = " + document.frm_ps.hh.value + "เลือกชั่วโมงให้ถูกต้อง";
	    }
		
		if (document.frm_ps.mm.value=="") {
	        theMessage = theMessage + "\n -->  นาที = " + document.frm_ps.mm.value + " กรุณาใส่นาที";
	    }
		
		if (document.frm_ps.mm.value >=60) {
	        theMessage = theMessage + "\n -->  นาที = " + document.frm_ps.mm.value + " เลือกนาทีให้ถูกต้อง";
	    }
		if (document.frm_ps.qryDate.value >= cs) {
	        theMessage = theMessage + "\n -->  วันที่ = " + document.frm_ps.qryDate.value + " ไม่สามารถทำรายการได้";
	    }
		if (document.frm_ps.b_br.value=="") {
	        theMessage = theMessage + "\n -->  วันที่ = " + document.frm_ps.b_br.value + " กรุณาใส่รหัสธนาคาร";
	    }
		
		
		if (document.frm_ps.b_br.value.length > 4) {
	        theMessage = theMessage + "\n -->  วันที่ = " + document.frm_ps.b_br.value + " รหัสธนาคารยาวเกินกำหนด";
	    }
		
		
			// If no errors, submit the form
		if (theMessage == noErrors) {
		return true;
		
		} 
		
		else 
		
		{
		
		// If errors were found, show alert message
		alert(theMessage);
		return false;
		}
    }
		
   </script>
</head>

<body>
<div style="width:auto; background-color:#66FFFF;">รายการโอน</div>
<?php 
echo 

$idno=trim(pg_escape_string($_POST["h_id"]));
$qry_cc=pg_query("select \"IDNO\",\"P_MONTH\",\"P_VAT\",\"CusID\",\"P_StopVat\",\"P_ACCLOSE\",\"P_LAWERFEE\" from \"Fp\" WHERE \"IDNO\"='$idno' ");
$res_cc=pg_fetch_array($qry_cc);
$pm=$res_cc["P_MONTH"]+$res_cc["P_VAT"];
$cusid=$res_cc["CusID"];

$stat_vat=$res_cc["P_StopVat"];
$stat_aclose=$res_cc["P_ACCLOSE"];
$stat_law=$res_cc["P_LAWERFEE"];

?>
<br />

  <form method="post" action="process_tr_transfer.php" name="frm_ps" onsubmit="return validate(this);">
  <input type="hidden" name="amts" id="amts" value="<?php echo $amtpost; ?>" />
	<input type="hidden" name="ch_day" id="ch_day" value="<?php echo $s=date("Y/m/d"); ?>"  />
	<table width="769" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
	<tr style="background-color:#DDE6B7">
	<td colspan="2">วันที่โอน</td>
	 <td><input name="qryDate" id="qryDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>" onchange="alertday();" tabindex="1"/>
      <input name="button" type="button" onclick="displayCalendar(document.frm_ps.qryDate,'yyyy/mm/dd',this)" value="ปฏิทิน" tabindex="2"  /></td>
	 <td>เวลาโอน</td>
	 <td>(ชั่วโมง : นาที) <input type="text" name="hh" style="width:30px;" tabindex="3" /> : <input type="text" name="mm" style="width:30px;" tabindex="4" /> </td>
	</tr>
	<tr style="background-color:#DDE6B7">
    <td colspan="2">รหัสสาขาที่โอน&nbsp;</td>
	<td width="178"><input type="text" name="b_br"  tabindex="5" /></td>
	<td width="56">ธนาคาร</td>
	<td width="394">
	<select name="bank_tr" tabindex="6">
	<?php 
	$qry_bank=pg_query("select * from \"BankCheque\" ");
	while($res_bank=pg_fetch_array($qry_bank))
	{
	?>
	<option value="<?php echo $res_bank["BankNo"]; ?>" ><?php echo $res_bank["BankName"]; ?></option>
	<?php
	}
	
	?>
	</select></td>
	</tr>
	


   <?php
	if(($stat_vat=="t") or ($stat_law=="t"))
	
	{
	?>
	
	<tr style="background-color:#EBFB91;">
	
	<td colspan="5" style="text-align:center"> ** STOP VAT OR LAWERFREE  ** </td>
	<input type="hidden" name="count_fr" value="0" />

	 </tr>

	 
	 <script type="text/javascript">
  	var gFiles = 0;
	var summary;
	function addFile() 
	
	{
	
	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="typepayment[]" id="typepayment"><?php 
	$qry_type=pg_query("select * from \"TypePay\" WHERE (\"TypeID\" !=1) AND (\"TypeID\"=200)");
	while($res_type=pg_fetch_array($qry_type))
	{ 
	echo  "<option value=\"$res_type[TypeID]\">$res_type[TName]</option>"; 
	}
	?></select>&nbsp;&nbsp;<input type="text" name="amt[]" id="amt" ><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
	document.getElementById('files-root').appendChild(li);
	gFiles++;
	}
	function removeFile(aId) {
	var obj = document.getElementById(aId);
	obj.parentNode.removeChild(obj);
	}
	</script>
	<?php
	}
	else
	{
	   $stat_aclose=$res_cc["P_ACCLOSE"];

    	if($stat_aclose=='t')
		{
		 $q_type="select * from \"TypePay\" WHERE (\"TypeID\" !=1) AND (\"TypeID\"!=0)";
		}
		else
		{
		 $q_type="select * from \"TypePay\" WHERE \"TypeID\" !=1 ";
		}
    ?>    
	<script type="text/javascript">
  	var gFiles = 0;
	var summary;
	function addFile() 
	
	{
	
	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="typepayment[]" id="typepayment"><?php 
	$qry_type=pg_query($q_type);
	while($res_type=pg_fetch_array($qry_type))
	{ 
	echo  "<option value=\"$res_type[TypeID]\">$res_type[TName]</option>"; 
	}
	?></select>&nbsp;&nbsp;<input type="text" name="amt[]" id="amt" ><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
	document.getElementById('files-root').appendChild(li);
	gFiles++;
	}
	function removeFile(aId) {
	var obj = document.getElementById(aId);
	obj.parentNode.removeChild(obj);
	}
	</script>
	<?php
	  if(($stat_aclose=="t") or ($stat_vat=="f"))
	  {
	  ?>
	
	
	
    <tr style="background-color:#DDE6B7">
    <td colspan="5">ชำระค่างวด ยอดค่างวด(รวม VAT) <input name="fr_pay" id="fr_pay" type="text" value="<?php echo $pm; ?>" tabindex="7"  />
	
	<select  name="count_fr" id="count_fr" onchange="cal_fr()" tabindex="8">
	<option value="0">เลือกจำนวนงวด</option>
	<?php
	$str="select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno')";
	 $qry_fr=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) order by \"DueDate\" ");
	 while($res_fr=pg_fetch_array($qry_fr))
	 {
	   $a++;
	?>
	   <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
	<?php
	 }
	 
	?>
	</select>
	<input name="rescal" id="rescal" type="text" value="0"  style="text-align:right"/></td>
    </tr>
     <?php
	  }
	 } 
	 ?>
	 
	 
   

	<tr style="background-color:#DDE6B7">
	
	<td colspan="5">
	<ol id="files-root">
	</ol>	</td>
	 </tr>
	 
	 <tr style="background-color:#D8E9E9;">
	  <td>
	  <input type="hidden" name="p_idno" id="p_idno" value="<?php echo $idno; ?>" />
	  <input type="hidden" name="p_cusid" id="p_cusid" value="<?php echo $cusid; ?>" />
	  <input type="button" name="btnAdd" id="btnAdd" onclick="JavaScript:addFile();" value="เพิ่มค่าใช้จ่ายอื่น ๆ" /></td>
	  <td colspan="3">&nbsp;</td>
	  <td><input type="submit" value="NEXT" name="submit" /></td>
	 </tr>
	 </table>	
</form>
</body>
</html>

<?php
session_start();
include("../../config/config.php");
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION["session_company_name"]; ?></title>

    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
  
<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
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
	 
		if (document.frm_ps.brn_pay.value ==0) {
	        theMessage = theMessage + "\n --> กรุณาเลือกสาขารับชำระ";
	    }
		if (document.frm_ps.qryDate.value >= cs) {
	        theMessage = theMessage + "\n -->  วันที่ = " + document.frm_ps.qryDate.value + " ไม่สามารถทำรายการได้";
	    }
		
     
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


<table cellpadding="0" cellspacing="0" border="0" width="800" align="center">
<tr>
<td> 

<fieldset><legend><B>รับเงินสดใบเสร็จชั่วคราว (วิทยุลูกค้านอก)</B></legend>

<?php
$idno=trim($_POST["h_id"]);
$arr_idno = explode("#",$idno);
$idno=$arr_idno[0];
echo "<a href=\"#\" title=\"ดูรายละเอียดสัญญาวิทยุ (ลูกค้านอก)\" onclick=\"javascript:popU('../RadioContract/vRadioContract.php?s_idno_t=$idno','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=768');\"><u>$idno</u></a>";
?>
<br />
  
 
  <form method="post" action="process_transfer.php" name="frm_ps" onsubmit="return validate(this);">
	<input type="hidden" name="ch_day" id="ch_day" value="<?php echo $s=date("Y/m/d"); ?>"  />
	<table width="769" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
	<tr style="background-color:#DDE6B7">
	<td width="153">วันที่รับเงิน	</td>
	 <td width="202"><input name="qryDate" id="qryDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>" onchange="alertday();" tabindex="1"/>
      <input name="button" type="button" onclick="displayCalendar(document.frm_ps.qryDate,'yyyy/mm/dd',this)" value="ปฏิทิน"  tabindex="2" /></td>
	 <td width="404">&nbsp;</td>
	</tr>
	<tr style="background-color:#DDE6B7">
    <td>เลือกสาขาที่ชำระเงิน &nbsp;</td>
	<td><select name="brn_pay" id="brn_pay" tabindex="3">
      <option value="0">เลือก</option>
      <option value="NV">นวมินทร์</option>
      <option value="TV">ติวานนท์</option>
      <option value="JR">จรัญสนิทวงศ์</option>
    </select></td>
	<td>&nbsp;</td>
	</tr>
	<tr style="background-color:#DDE6B7">
	<td colspan="3">
	<ol id="files-root">
	</ol>	</td>
	 </tr>
	 
	 <tr style="background-color:#DDE6B7">
	  <td>
	  <input type="hidden" name="p_idno" id="p_idno" value="<?php echo $idno; ?>" />
	  <input type="button" name="btnAdd" onclick="javascript:addFile();" value="เพิ่มค่าใช้จ่ายอื่น ๆ" /></td>
	  <td>&nbsp;</td>
	  <td><input name="submit" type="submit" value="NEXT" /></td>
	 </tr>
	 </table>	
</form>

  </fieldset>
 
 </td>
 </tr>
 </table>

<script type="text/javascript">
function Chk134(id){
    var typepayment = $('#typepayment'+id).val();
    
     $('#divUnit'+id).hide();
     $('#amt'+id).attr('readonly', false);
     $('#amt'+id).attr('value', "");
}
</script>

<script type="text/javascript">
  	var gFiles = 0;
	var summary;
	function addFile() 
	
	{

	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="typepayment[]" id="typepayment'+ gFiles +'" onchange="javascript:Chk134('+ gFiles +')"><?php 
	$qry_type=pg_query("select * from \"TypePay\" ");
	while($res_type=pg_fetch_array($qry_type))
	{ 
	echo  "<option value=\"$res_type[TypeID]\">$res_type[TName]</option>"; 
	}
	?></select>&nbsp;<span id="divUnit'+ gFiles +'" style="display:none">จำนวน : <input type="text" name="txtUnit[]" id="txtUnit'+ gFiles +'" value="1" style="text-align:right; width:50px" onkeyup="javascript:UpdateUnit('+ gFiles +')"></span>&nbsp;<input type="text" name="amt[]" id="amt'+ gFiles +'" ><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
	document.getElementById('files-root').appendChild(li);
	gFiles++;
	}
	function removeFile(aId) {
	var obj = document.getElementById(aId);
	obj.parentNode.removeChild(obj);
	}
</script>
</body>
</html>

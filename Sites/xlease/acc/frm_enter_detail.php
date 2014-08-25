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
<title><?php echo $_SESSION["session_company_name"]; ?></title>

    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
  
<script type="text/javascript">
var k=0;
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
function windowOpen(x) {
var
myWindow=window.open(x,'windowRef','width=600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}
	function cal_fr()
	{ 
	 var sta1 =parseFloat(document.frm_ps.amts.value); //ยอดโอน
	 var va1 = parseFloat(document.frm_ps.count_fr.value); //จำนวนเดือนจ่าย
	 var va2 = parseFloat(document.frm_ps.fr_pay.value); //ค่างวด
	 var ress= parseFloat(document.frm_ps.rescal.value=va1*va2);
	 
	 var ch_tt=parseFloat(document.frm_ps.ch_total.value); //งวดสุดท้าย
	 var cal=parseFloat(document.frm_ps.cal_total.value); //จำนวนงวดที่จ่ายแล้ว
	 
	 var m_cal=cal+va1;
	 
	 if(m_cal==ch_tt)
	 {
	   alert("กรุณากรอกส่วนลด");	   
	   document.frm_ps.dsc_fr.disabled=false;
	   document.frm_ps.dsc_fr.focus(); 
	 }
	 else if(m_cal!=ch_tt)
	 {
	  document.frm_ps.dsc_fr.value=0;
	  document.frm_ps.dsc_fr.disabled=true;
	 }
	 
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

<fieldset><legend><B>รับเงินสดใบเสร็จชั่วคราว</B></legend>

<?php
$idno=trim(pg_escape_string($_POST["h_id"]));
$arr_idno = explode("#",$idno);
$idno=$arr_idno[0];

//ค้นหาชื่อและทะเบียนรถ
$qrynamecar=pg_query("select \"C_REGIS\",\"full_name\",\"car_regis\" from \"VContact\" where \"IDNO\"='$idno'");
list($C_REGIS,$full_name,$car_regis)=pg_fetch_array($qrynamecar);

if($C_REGIS==""){
	$carregis=trim($car_regis);
}else{
	$carregis=trim($C_REGIS);
}
echo "<div align=\"right\"><input type=\"button\" value=\"กลับ\" onclick=\"window.location='frm_cash_acc.php'\"></div>";
echo "<a href=\"#\" title=\"ดูตารางการชำระเงิน\" onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$idno','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=768');\"><u>$idno</u></a>";
echo "&nbsp&nbsp($full_name  <b>ทะเบียนรถ</b> $carregis)";
$qry_vcus=pg_query("select amt from corporate.\"VCorpContact\" WHERE \"IDNO\" = '$idno' ");
if($res_vcus=pg_fetch_array($qry_vcus)){
    $amt_vc = $res_vcus["amt"];
}else{
    $amt_vc = 0;
}

$qry_cc=pg_query("select \"IDNO\",\"P_MONTH\",\"P_VAT\",\"CusID\" from \"VContact\" WHERE \"IDNO\"='$idno' ");
$res_cc=pg_fetch_array($qry_cc);
$pm=$res_cc["P_MONTH"]+$res_cc["P_VAT"];
$cusid=$res_cc["CusID"];

//chk for close FP //

$qry_fp=pg_query("select \"IDNO\",\"P_TOTAL\",\"P_StopVat\",\"P_ACCLOSE\",\"P_LAWERFEE\" from \"Fp\" WHERE \"IDNO\"='$idno' ");
$res_fp=pg_fetch_array($qry_fp);
$ch_total=$res_fp["P_TOTAL"];

$stat_vat=$res_fp["P_StopVat"];
$stat_law=$res_fp["P_LAWERFEE"];


////end chk for clise Fp///////////

$qry_fq=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$idno') AND (\"R_Receipt\" != '') order by \"DueDate\" ");
$fq_numr=pg_num_rows($qry_fq);
	
?>
<br />
  
 
  <form method="post" action="process_transfer.php" name="frm_ps" onsubmit="return validate(this);">
  <input type="hidden" name="ch_total" id="ch_total" value="<?php echo $ch_total; ?>"  />
  <input type="hidden" name="cal_total" id="cal_total" value="<?php echo $fq_numr; ?>"  />
  <input type="hidden" name="amts" id="amts" value="<?php echo $amtpost; ?>" />
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
      <option value="JR">จรัญสนิทวงศ์</option>
      <option value="TV">ติวานนท์</option>
      <option value="NV">นวมินทร์</option>
    </select></td>
	<td>&nbsp;</td>
	</tr>
	<?php
	if(($stat_vat=="t") or ($stat_law=="t"))
	
	{
	?>
	<tr style="background-color:#EBFB91;">
	<td colspan="3" style="text-align:center"> ** STOP VAT OR LAWERFREE  ** </td>
	<input type="hidden" name="count_fr" value="0" />
	</tr>
	 
	 <script type="text/javascript">
  	var gFiles = 0;
	var summary;
	function addFile() 
	
	{

	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="typepayment[]" id="typepayment'+ gFiles +'" onchange="javascript:Chk134('+ gFiles +')"><?php 
	$qry_type=pg_query("select * from \"TypePay\" WHERE (\"TypeID\" !=1) AND (\"TypeID\"=200)");
	while($res_type=pg_fetch_array($qry_type))
	{ 
	echo  "<option value=\"$res_type[TypeID]\">$res_type[TName]</option>"; 
	}
	?></select>&nbsp;<span id="type_detail' + gFiles + '"></span>&nbsp;<span id="divUnit'+ gFiles +'" style="display:none">จำนวน : <input type="text" name="txtUnit[]" id="txtUnit'+ gFiles +'" value="1" style="text-align:right; width:50px" onkeyup="javascript:UpdateUnit('+ gFiles +')"></span>&nbsp;<input type="text" name="amt[]" id="amt'+ gFiles +'" ><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
	document.getElementById('files-root').appendChild(li);
	gFiles++;
	}
	function removeFile(aId) {
	var obj = document.getElementById(aId);
	obj.parentNode.removeChild(obj);
	//gFiles--;
	}
	</script>
	
	
	 
	 
	<?php
	}
	else
	{
	   $stat_aclose=$res_fp["P_ACCLOSE"];

    	if($stat_aclose=='t')
		{
		 $q_type="select * from \"TypePay\" WHERE (\"TypeID\" !=1) AND (\"TypeID\"!=0) ";
		 
		 
		 
		}
		else
		{
		
		
		 $q_type="select * from \"TypePay\" WHERE \"TypeID\" !=1 ";
		}
    ?>    
	<script type="text/javascript">
  	var gFiles = 0;
	var summary;
	function addFile(){

	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="typepayment[]" id="typepayment'+ gFiles +'" onchange="javascript:Chk134('+ gFiles +')"><?php 
	$qry_type=pg_query($q_type);
	while($res_type=pg_fetch_array($qry_type))
	{ 
	echo  "<option value=\"$res_type[TypeID]\">$res_type[TName]</option>"; 
	}
	?></select>&nbsp;<span id="type_detail' + gFiles + '"></span>&nbsp;<span id="divUnit'+ gFiles +'" style="display:none">จำนวน : <input type="text" name="txtUnit[]" id="txtUnit'+ gFiles +'" value="1" style="text-align:right; width:50px" onkeyup="javascript:UpdateUnit('+ gFiles +')"></span>&nbsp;<input type="text" name="amt[]" id="amt'+ gFiles +'" ><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
	document.getElementById('files-root').appendChild(li);
	gFiles++;
	}
	function removeFile(aId) {
	var obj = document.getElementById(aId);
	obj.parentNode.removeChild(obj);
	//gFiles--;
	}
	</script>
	<?php
	  if(($stat_aclose=="t") or ($stat_vat=="f"))
	  {
	  ?>
	  <tr style="background-color:#DDE6B7">
    <td>ยอดค่างวด(รวม VAT)
      <input name="fr_pay" id="fr_pay" type="text" style="border:none; background-color:#DDE6B7;" value="<?php echo $pm; ?>"  /></td>
    <td><select  name="count_fr" id="count_fr" onchange="cal_fr()" tabindex="4">
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
      </select></td>
    <td>ผลคำนวณยอด
      <input name="rescal" id="rescal" type="text" value="0" readonly="" /></td>
    </tr>
	  <?php  
	  }

	}
	?>
   
	
	<tr style="background-color:#DDE6B7">
	<td>
	<input type="text" name="srt_dsc" id="srt_dsc" value="ส่วนลดปิดบัญชี "  style="border:none; background-color:#DDE6B7;" />	</td>
	 <td><input type="text" name="dsc_fr" id="dsc_fr" value="0" disabled="enabled" /></td>
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
	  <input type="hidden" name="p_cusid" id="p_cusid" value="<?php echo $cusid; ?>" />
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
    	var aa = 0;
	var bb = 0;
	for(i=0; i<gFiles; i++){
			var myString = '<?php $join_type1=pg_query("select join_get_join_type(1)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){    
			if($('#typepayment'+ i).val() == mySplitResult[z]){
			aa ++;
			//alert('aa '+$('#typepayment'+ i).val());
		}
}
			var myString = '<?php $join_type1=pg_query("select join_get_join_type(2)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){    
		if($('#typepayment'+ i).val() == mySplitResult[z]){
			bb ++;
			//alert('bb '+$('#typepayment'+ i).val());
		}
}
	}
	if( (aa>0 && bb>0) || (aa>1) || (bb>1) ){
		
                alert('ห้ามเลือกประเภทรายการ ค่าเข้าร่วมซ้ำ !');
				
//document.getElementById('typepayment'+ id).selectedIndex=0;
$('#typepayment'+ id).attr('selectedIndex', 0); 
		 return false;
 
    }
    if(typepayment == 134){
        $('#divUnit'+id).show();
        $('#amt'+id).attr('readonly', true);
        $('#amt'+id).attr('value', "<?php echo $amt_vc; ?>");
    }else{
		var ck_else = 0;
			var myString = '<?php $join_type1=pg_query("select join_get_join_type(1)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){  
if( $("#typepayment"+ id).val() == mySplitResult[z] ){ //ตรวจสอบถ้าเป็นค่าเข้าร่วม แรกเข้า
		if(k!=1){
			ck_else =1;
        $("#amt"+ id).attr("readonly", "readonly");
		$("#amt" + id).val("");
        windowOpen('../nw/join_cal/join_cal.php?idno=<?php echo $idno; ?>&inputName=amt'+ id + '&pay_date='+document.frm_ps.qryDate.value+'&change_pay_type=1');
           
           $("#type_detail"+ id).load("../postpay/api.php?cmd=load_join1&id="+ id+'&idno=<?php echo $idno; ?>&inputName=amt'+ id + '&pay_date='+document.frm_ps.qryDate.value+'&change_pay_type=1', function(){
            $("#type_detail"+ id).show();
            

            });
            
   // k=1 ;
		}else{
			 alert('ค่าเข้าร่วม สามารถเลือกได้รายการเดียวเท่านั้น!');
		
                return false;
		}
          
     
    }
}
	var myString = '<?php $join_type1=pg_query("select join_get_join_type(2)"); 
		print pg_fetch_result($join_type1,0); ?>';
		
var mySplitResult = myString.split("#");  
for(z = 0; z < mySplitResult.length; z++){  
if( $("#typepayment"+ id).val() == mySplitResult[z] ){ //ตรวจสอบถ้าเป็นค่าเข้าร่วม ธรรมดา
		if(k!=1){
			ck_else =1;
        $("#amt"+ id).attr("readonly", "readonly");
		$("#amt" + id).val("");
        windowOpen('../nw/join_cal/join_cal.php?idno=<?php echo $idno; ?>&inputName=amt'+ id + '&pay_date='+document.frm_ps.qryDate.value+'&change_pay_type=0');
               
				
				 $("#type_detail"+ id).load("../postpay/api.php?cmd=load_join1&id="+ id+'&idno=<?php echo $idno; ?>&inputName=amt'+ id + '&pay_date='+document.frm_ps.qryDate.value+'&change_pay_type=0', function(){
            $("#type_detail"+ id).show();
            
			

            });
			//k=1 ;
				}else{
			 alert('ค่าเข้าร่วม สามารถเลือกได้รายการเดียวเท่านั้น!');
			
                return false;
			
		}
          
    
    }
}

		if(ck_else ==0){
        $('#divUnit'+id).hide();
		
        $('#amt'+id).attr('readonly', false);
        $('#amt'+id).attr('value', "");
		}
    }
}

function UpdateUnit(id){
    var a1 = $('#txtUnit'+id).val();
    var a2 = '<?php echo $amt_vc; ?>';    
    var a3 = parseFloat( a1 ) * parseFloat( a2 );
    console.log(a3);
    $('#amt'+id).attr('value',a3);
}
</script>
 
</body>
</html>

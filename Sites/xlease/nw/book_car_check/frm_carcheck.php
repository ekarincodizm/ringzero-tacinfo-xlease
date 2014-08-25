<?php
include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];

$ID = pg_escape_string($_POST['idno']);
$check = pg_escape_string($_POST['check']);
list($IDNO,$cusname,$ID_register,$CusID)=explode("#",$ID);


//user
$qry_name=pg_query("select \"fullname\" from \"Vfuser\" WHERE \"id_user\" = '$id_user'");
$result=pg_fetch_array($qry_name); 
$thaiacename = $result["fullname"];

if($ID == ""){
	
	echo "<p>";
	echo "<h1> กรุณาค้นหาเลขที่สัญญาก่อนครับ <h1> ";				
	echo "<meta http-equiv=\"refresh\" content=\"2; URL=index.php\">";
	exit();
}


//  ที่อยู่ตามสัญญาเช่าซื้อแรกเริ่ม  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$qry_name=pg_query("SELECT C.\"CusID\",C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",B.\"N_ContactAdd\",
	D.\"A_NO\",D.\"A_SUBNO\",D.\"A_SOI\",D.\"A_RD\",D.\"A_TUM\",D.\"A_AUM\",D.\"A_PRO\",D.\"A_POST\",A.\"asset_id\"
	FROM  \"Fp\"  A
	LEFT OUTER JOIN \"Fn\" B ON B.\"CusID\"=A.\"CusID\"
	LEFT OUTER JOIN \"Fp_Fa1\" D ON A.\"IDNO\"=D.\"IDNO\" AND \"edittime\"='0' and \"CusState\"='0'
	LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=D.\"CusID\"
	WHERE (A.\"IDNO\"='$IDNO' )");

if($res_name=pg_fetch_array($qry_name)){
	$A_CusID=trim($res_name["CusID"]);
	$A_FIRNAME=trim($res_name["A_FIRNAME"]);
	$A_NAME=trim($res_name["A_NAME"]);
	$A_SIRNAME=trim($res_name["A_SIRNAME"]);
	$A_NO=trim($res_name["A_NO"]);
	$A_SUBNO=trim($res_name["A_SUBNO"]);
	$A_SOI=trim($res_name["A_SOI"]);
	$A_RD=trim($res_name["A_RD"]);
	$A_TUM=trim($res_name["A_TUM"]);
	$A_AUM=trim($res_name["A_AUM"]);
	$A_PRO=trim($res_name["A_PRO"]);
	$A_POST=trim($res_name["A_POST"]);
	
	$N_ContactAdd=$res_name["N_ContactAdd"];
	$asset_id=$res_name["asset_id"];
		
	$arr_contact = explode("\n",$N_ContactAdd);
	
	if($A_SUBNO=="" || $A_SUBNO=="-" || $A_SUBNO=="--"){ //ม.
		//ไม่ต้องทำอะไร
	}else{
		$subno="ม.$A_SUBNO";
	}
	if($A_SOI=="" || $A_SOI=="-" || $A_SOI=="--"){ //ซ.
		//ไม่ต้องทำอะไร
	}else{
		$soi="ซ.$A_SOI";
	}
	if($A_RD=="" || $A_RD=="-" || $A_RD=="--"){ //ถ.
		//ไม่ต้องทำอะไร
	}else{
		$road="ถ.$A_RD";
	}
	if($A_POST=="" || $A_POST=="-" || $A_POST=="--"){ //รหัสไปรษณีย์
		$A_POST="";
	}
	if($A_PRO=="กรุงเทพมหานคร" || $A_PRO=="กรุงเทพ" || $A_PRO=="กรุงเทพฯ" || $A_PRO=="กทม."){
		$txttum="แขวง".$A_TUM; //แขวง
		$txtaum="เขต".$A_AUM; //เขต
		$txtpro="$A_PRO"; //เขต
	}else{
		$txttum="ต.".$A_TUM; //ต.
		$txtaum="อ.".$A_AUM; //อำเภอ
		$txtpro="จ.".$A_PRO; //จังหวัด
	}
   
	$address1 = "$A_NO $subno $soi $road";
	$address2 = "$txttum $txtaum $txtpro $A_POST";
	$address3 = "";
}

//////๑////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//  ที่อยู่ตามสัญญาเช่าซื้อปัจจุบัน  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$qryadridno=pg_query("select \"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",
\"A_AUM\",\"A_PRO\",\"A_POST\",\"A_ROOM\",\"A_FLOOR\" ,\"A_BUILDING\",\"A_BAN\"
from \"Fp_Fa1\" where \"IDNO\"='$IDNO' and \"CusState\"='0' order by \"edittime\" DESC limit(1)");
		$resadridno=pg_fetch_array($qryadridno);
			$A_NO1=trim($resadridno["A_NO"]);
			$A_SUBNO1=trim($resadridno["A_SUBNO"]);
			$A_SOI1=trim($resadridno["A_SOI"]);
			$A_RD1=trim($resadridno["A_RD"]);
			$A_TUM1=trim($resadridno["A_TUM"]);
			$A_AUM1=trim($resadridno["A_AUM"]);
			$A_PRO1=trim($resadridno["A_PRO"]);
			$A_POST1=trim($resadridno["A_POST"]);
			$A_ROOM=trim($resadridno["A_ROOM"]);
			$A_FLOOR=trim($resadridno["A_FLOOR"]);
			$A_BUILDING=trim($resadridno["A_BUILDING"]);
			$A_BAN=trim($resadridno["A_BAN"]);
			
			if($A_ROOM=="" || $A_ROOM=="-" || $A_ROOM=="--"){ //ห้อง
				//ไม่ต้องทำอะไร
			}else{
				$room="ห้อง $A_ROOM";
			}
			
			if($A_FLOOR=="" || $A_FLOOR=="-" || $A_FLOOR=="--"){ //ชั้น
				//ไม่ต้องทำอะไร
			}else{
				$floor="ชั้น $A_FLOOR";
			}
			
			if($A_BAN=="" || $A_BAN=="-" || $A_BAN=="--"){ //หมู่บ้าน
				//ไม่ต้องทำอะไร
			}else{
				$ban="หมู่บ้าน$A_BAN";
			}
			
			if($A_SUBNO1=="" || $A_SUBNO1=="-" || $A_SUBNO1=="--"){
				//ไม่ต้องทำอะไร
			}else{
				$subno1="ม.$A_SUBNO1";
			}
			if($A_SOI1=="" || $A_SOI1=="-" || $A_SOI1=="--"){
				//ไม่ต้องทำอะไร
			}else{
				$soi1="ซ.$A_SOI1";
			}
			if($A_RD1=="" || $A_RD1=="-" || $A_RD1=="--"){
				//ไม่ต้องทำอะไร
			}else{
				$road1="ถ.$A_RD1";
			}
			if($A_POST1=="" || $A_POST1=="-" || $A_POST1=="--"){
				$A_POST1="";
			}
			
			if($A_PRO1=="กรุงเทพมหานคร" || $A_PRO1=="กรุงเทพ" || $A_PRO1=="กรุงเทพฯ" || $A_PRO1=="กทม."){
				$txttum1="แขวง".$A_TUM1;
				$txtaum1="เขต".$A_AUM1;
				$txtpro1="$A_PRO1"; //จังหวัด
			}else{
				$txttum1="ต.".$A_TUM1;
				$txtaum1="อ.".$A_AUM1;
				$txtpro1="จ.".$A_PRO1; //จังหวัด
			}	
			
			$address11 = "$A_NO1 $subno1 $ban $A_BUILDING $room $floor";
			$address22 = "$soi1 $road1 $txttum1 ";
			$address33 = "$txtaum1 $txtpro1 $A_POST1";

//////๑////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//  ที่อยู่ที่ส่งจดหมาย  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
		$qry_cus_ads=pg_query("select \"address\" from letter.cus_address where \"CusID\" = '$CusID' and \"Active\" = 'TRUE'");
		$num_letter = pg_num_rows($qry_cus_ads);		
		if($num_letter == 0){
			$qry_cus_ads2=pg_query("select * from letter.send_address where (\"IDNO\"='$IDNO') and (\"CusState\"='0') and (active=TRUE)");
			$res_ads2=pg_fetch_array($qry_cus_ads2);
			$add_letter=$res_ads2["dtl_ads"]; 
		}else{
			$res_ads=pg_fetch_array($qry_cus_ads);
			$add_letter=$res_ads["address"]; 
		}
		
		//เพิ่มการตรวจสอบ อีกครั้งว่า ที่อยู่หรือไม่
		if($add_letter==0)
		{
			$add_letter=$address11."\n".$address22."\n".$address33;
		}
		
		
		
//////๑////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script> 

<script type="text/javascript">
<!--เพิ่ม function การเรียกใช้ หน้าฟอร์มอื่น -->  
function popUPO(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<script type="text/javascript">
function refreshTextAddress() // refresh ที Textarea name=address6
{  	
	var dataAddressList = $.ajax({    
	  url: "dataForfrm_lt_edit_detail.php?idno=<?php echo $IDNO; ?>", // ส่ง ค่า $IDNO ไปยัง หน้า  dataForfrm_lt_edit_detail.php
	  async: false  
	}).responseText;
	$("#address6").html(dataAddressList); // นำค่า  dataAddressList มาแสดงใน Textarea name=address6
	
}

</script>
<script language=javascript>
$(document).ready(function(){
    //document.frm1.updateaddress.style.visibility = 'hidden';
	$("#participating_start").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
			
		});
	$("#participating_end").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
			
		});
		
		
document.getElementById("meter_vat_price").disabled = true;		
document.getElementById("meter_vat_price").style.backgroundColor = "#c0c0c0";
document.getElementById("insurance_price").disabled = true;		
document.getElementById("insurance_price").style.backgroundColor = "#c0c0c0";
document.getElementById("act_price").disabled = true;		
document.getElementById("act_price").style.backgroundColor = "#c0c0c0";
document.getElementById("insureance_act_price").disabled = true;		
document.getElementById("insureance_act_price").style.backgroundColor = "#c0c0c0";


			$("input[type='radio']").change(function(){

					if(document.getElementById("meter").checked){
					
						document.getElementById("meter_vat_price").disabled = true;
						document.getElementById("meter_vat_price").value = "";
						document.getElementById("meter_vat_price").style.backgroundColor = "#c0c0c0";
						document.getElementById("insurance_price").disabled = true;	
						document.getElementById("insurance_price").value = "";						
						document.getElementById("insurance_price").style.backgroundColor = "#c0c0c0";
						document.getElementById("act_price").disabled = true;
						document.getElementById("act_price").value = "";							
						document.getElementById("act_price").style.backgroundColor = "#c0c0c0";
						document.getElementById("insureance_act_price").disabled = true;	
						document.getElementById("insureance_act_price").value = "";						
						document.getElementById("insureance_act_price").style.backgroundColor = "#c0c0c0";
					
						document.getElementById("meter_price").disabled = false;
						document.getElementById("meter_price").value = "";
						document.getElementById("meter_price").style.backgroundColor = "";
						
						calsum();
						
					}else if(document.getElementById("metervat").checked){
					
						document.getElementById("meter_vat_price").disabled = false;
						document.getElementById("meter_vat_price").value = "";
						document.getElementById("meter_vat_price").style.backgroundColor = "";
						document.getElementById("insurance_price").disabled = false;	
						document.getElementById("insurance_price").value = "";						
						document.getElementById("insurance_price").style.backgroundColor = "";
						document.getElementById("act_price").disabled = false;
						document.getElementById("act_price").value = "";							
						document.getElementById("act_price").style.backgroundColor = "";
						document.getElementById("insureance_act_price").disabled = false;	
						document.getElementById("insureance_act_price").value = "";						
						document.getElementById("insureance_act_price").style.backgroundColor = "";
						
						
						document.getElementById("meter_price").disabled = true;
						document.getElementById("meter_price").value = "";
						document.getElementById("meter_price").style.backgroundColor = "#c0c0c0";
						
						calsum();
							
					}
			});

$("#a2").hide();
$("#a3").hide();
$("#a4").hide();
$("#a5").hide();
$("#a6").hide();
$("#updateaddress6").hide();
$("#add").change(function(){
        var src = $('#add option:selected').attr('value');
        if ( src == "1" ){
		$("#a1").show();
		$("#updateaddress6").hide();
		 $("#a2").hide();
		  $("#a3").hide();
		   $("#a4").hide();		
			$("#a5").hide();		
			 $("#a6").hide();		
		}else if ( src == "2" ){
		$("#a1").hide();
		 $("#a2").show();
		 $("#updateaddress6").hide();
		  $("#a3").hide();
		   $("#a4").hide();
			$("#a5").hide();		
			 $("#a6").hide();		   
		}else if ( src == "3" ){
		$("#a1").hide();
		 $("#a2").hide();
		  $("#a3").show();
		  $("#updateaddress6").hide();
		   $("#a4").hide();	
			$("#a5").hide();		
			 $("#a6").hide();		   
		}else if ( src == "4" ){
		$("#a1").hide();
		 $("#a2").hide();
		  $("#a3").hide();
		   $("#a4").show();
		   $("#updateaddress6").hide();
			$("#a5").hide();		
			 $("#a6").hide();
		}else if ( src == "5" ){
		$("#a1").hide();
		 $("#a2").hide();
		  $("#a3").hide();
		   $("#a4").hide();
			$("#a5").show();
			$("#updateaddress6").hide();	
			 $("#a6").hide();
		}else if ( src == "6" ){
		$("#a1").hide();
		 $("#a2").hide();
		  $("#a3").hide();
		   $("#a4").hide();
			$("#a5").hide();		
			 $("#a6").show(); 
			 // ปุ่ม "เปลี่ยนที่อยู่อื่น ๆ" จะแสดง ก็ต่อเมื่อ ที่อยู่ลูกค้า เป็น ที่อยู่อื่นๆ
			 $("#updateaddress6").show();
		}		
});

});

function checkList()
{
	if(document.getElementById("radio_month").value!="" && document.getElementById("radio_price").value =="")
	{
		
			alert('กรุณากรอก จำนวนเงินค่าวิทยุสื่อสารรายเดือน ด้วยครับ ');
			return false;
		
		
	}
	else if(document.getElementById("radio_price").value!="" && document.getElementById("radio_month").value =="")
	{
		
			alert('กรุณากรอก จำนวนเดือนของค่าวิทยุสื่อสาร ด้วยครับ ');
			return false;
		
	}
	else if(document.getElementById("participating_start").value!="" && document.getElementById("participating_end").value !="" && document.getElementById("participating_price").value =="")
	{
		
		
			alert('กรุณา ระบุค่าเข้าร่วมประจำเดือน ด้วยครับ ');
			return false;
		
	}
	else if(document.getElementById("participating_price").value!="" && document.getElementById("participating_start").value =="" && document.getElementById("participating_end").value =="")
	{
		
			alert('กรุณา เลือกเดือนของค่าเข้าร่วม ด้วยครับ');
			return false;
		
	}
	else if(document.getElementById("participating_start").value!="" && document.getElementById("participating_end").value =="")
	{
		
			alert('กรุณา เลือกเดือนของค่าเข้าร่วม ด้วยครับ');
			return false;
		
	}
	else if(document.getElementById("participating_start").value=="" && document.getElementById("participating_end").value !="")
	{
		
			alert('กรุณา เลือกเดือนของค่าเข้าร่วม ด้วยครับ');
			return false;
		
	}
	else if(document.getElementById("insurance_price").value!="" && document.getElementById("act_price").value !="" && document.getElementById("insureance_act_price").value =="")
	{

			alert('กรุณา ราคารวม พรบ.กับประกัน ด้วยครับ ');
			return false;
		
	}
	
	else if(document.getElementById("other").value!="" && document.getElementById("other_price").value =="")
	{
		
			alert('กรุณา ระบุราคาค่าใช้จ่ายอื่นๆ ด้วยครับ ');
			return false;
		
	}
	else if(document.getElementById("other_price").value!="" && document.getElementById("other").value =="")
	{
		
			alert('กรุณา ระบุ รายละเอียดขจงค่าใช้จ่ายอื่นๆ ด้วยครับ ');
			return false;
		
	}
	else{
	return true;
	}
}

function check_num(e)
{
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
if (key > 57)
      window.event.returnValue = false;
    }else{
        key = e.which; // Firefox       
if (key > 57)
      key = e.preventDefault();
  }
} 






function number_format (number, decimals, dec_point, thousands_sep) {
      var exponent = "";
      var numberstr = number.toString ();
      var eindex = numberstr.indexOf ("e");
      if (eindex > -1) {
         exponent = numberstr.substring (eindex);
         number = parseFloat (numberstr.substring (0, eindex));
      }
      if (decimals != null) {
         var temp = Math.pow (10, decimals);
         number = Math.round (number * temp) / temp;
      }
      var sign = number < 0 ? "-" : "";
      var integer = (number > 0 ? Math.floor (number) : Math.abs (Math.ceil (number))).toString ();
      var fractional = number.toString ().substring (integer.length + sign.length);
      dec_point = dec_point != null ? dec_point : ".";
      fractional = decimals != null && decimals > 0 || fractional.length > 1 ? (dec_point + fractional.substring (1)) : "";
      if (decimals != null && decimals > 0) {
         for (i = fractional.length - 1, z = decimals; i < z; ++i) {
            fractional += "0";
         }
      }
      thousands_sep = (thousands_sep != dec_point || fractional.length == 0) ? thousands_sep : null;
      if (thousands_sep != null && thousands_sep != "") {
         for (i = integer.length - 3; i > 0; i -= 3){
            integer = integer.substring (0 , i) + thousands_sep + integer.substring (i);
         }
      }
      return sign + integer + fractional + exponent;
		
}





function cal1(){
		
		if(document.frm.act_price.value == "" && document.frm.insurance_price.value == ""){
			document.frm.insureance_act_price.value="";
			exit();
		}else{
		
			if(document.frm.insurance_price.value == ""){
				var a = 0;
			}else{
				var a = parseFloat(document.frm.insurance_price.value);
			}
			if(document.frm.act_price.value == ""){
				var b = 0;
			}else{
				var b = parseFloat(document.frm.act_price.value);
			}

			
			parseFloat(document.frm.insureance_act_price.value=a+b);
			calsum();
		}
};

function calsum(){


	if(document.frm.radio_price.value=="" && document.frm.meter_price.value=="" && document.frm.meter_vat_price.value=="" && document.frm.participating_price.value=="" && document.frm.insureance_act_price.value=="" && document.frm.gas_price.value=="" && document.frm.other_price.value==""){
		
		document.frm.sumprice.value="";
		
	}else{
		
		if(document.frm.radio_price.value=="")
		{
			var c = 0;
		}else{
			var c = parseFloat(document.frm.radio_price.value);
		}
		if(document.frm.meter_price.value=="")
		{
			var d = 0;
		}else{
			var d = parseFloat(document.frm.meter_price.value);
		}
		if(document.frm.meter_vat_price.value=="")
		{
			var e = 0;
		}else{
			var e = parseFloat(document.frm.meter_vat_price.value);
		}
		if(document.frm.participating_price.value=="")
		{
			var f = 0;
		}else{
			var f = parseFloat(document.frm.participating_price.value);
		}
		if(document.frm.insureance_act_price.value=="")
		{
			var g = 0;
		}else{
			var g = parseFloat(document.frm.insureance_act_price.value);
		}
		if(document.frm.gas_price.value=="")
		{
			var h = 0;
		}else{
			var h = parseFloat(document.frm.gas_price.value);
		}
		if(document.frm.other_price.value=="")
		{
			var i = 0;
		}else{
			var i = parseFloat(document.frm.other_price.value);
		}
	
	 parseFloat(document.frm.sumprice.value=c+d+e+f+g+h+i);
			
	}
};
</script>
</head>
<body bgcolor="#DFE6EF">
<?php
//หาข้อมูลลูกค้าโอนสิทธิ์เข้าร่วม
	$textco="";
	$cusnameold="";
	$qryjoin=pg_query("SELECT \"prefix\", \"f_name\",\"l_name\", \"address\" FROM \"VJoinMain\"  
	where car_license_seq='0' and  carid='$asset_id' and idno='$IDNO' and deleted='0' and cancel='0' order by id DESC limit 1");
	$numjoin=pg_num_rows($qryjoin);
	list($prefix,$f_name,$l_name,$addressco)=pg_fetch_array($qryjoin);
	
	$sql_query5=pg_query("select \"P_ACCLOSE\",\"prefix\", \"f_name\",\"l_name\" from \"VJoin\" v WHERE v.\"asset_id\" = '$asset_id' and \"IDNO\"='$IDNO' order by v.\"P_STDATE\" desc limit 1 ");// ข้อมูลล่าสุด
	list($P_ACCLOSE,$prefix1,$f_name1,$l_name1)=pg_fetch_array($sql_query5);
	
	if($P_ACCLOSE=="f"){
		$prefix=trim($prefix1);
		$f_name=trim($f_name1);
		$l_name=trim($l_name1);
	}else{
		$prefix=trim($prefix);
		$f_name=trim($f_name);
		$l_name=trim($l_name);
	}
	if($prefix=="" || $prefix=="นาง" || $prefix=="นาย" || $prefix=="นางสาว" || $prefix=="น.ส." || $prefix=="นส."){
		$cusnameco="คุณ$f_name  $l_name";
	}else{
		$cusnameco="$prefix$f_name  $l_name";
	}
	
	if($numjoin>0){
		$textco="
		<tr><td colspan=3 align=right><font color=\"red\" size=\"3\"><b>(สัญญาเข้าร่วม)</b></font></td></tr>
		";
		$cusnameold="(ลูกค้าก่อนโอนสิทธิ์เข้าร่วม : $cusname)"; //กำหนดชื่อลูกค้าก่อนโอนสิทธิ์ในตัวแปร $cusnameold
		$cusname=$cusnameco; //กำหนดชื่อลูกค้าใหม่แทนที่ลูกค้าคนเดิม
	}
?>

<form name="frm" method="post" action="save_carcheck.php">
<table width="900" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td align="center"> 
			 <h1> <u>นัดตรวจสภาพรถ </u></h1>
			 <br>
			
					<table width="850" frame="border" cellSpacing="0" cellPadding="5" align="center" bgcolor="#FFFF99">
						<?php echo $textco;?>
						<tr>
							<td align="center" colspan="3">
								วันที่  <?php echo $date = date('d-m-Y');?>
							</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2">
								เรื่อง  นัดตรวจรถ
							</td>
						</tr>
						<tr align="center">
							<td></td>
							<td align="left" colspan="2">			
								เรียน  <input type="text" name="cusname" value="<?php echo $cusname; ?>" size="30"> <?php echo $cusnameold;?>				
							</td>
					   </tr>
					   <tr>  
							<td></td>
							<td  align="left">			
								 สัญญาเลขที่  <?php echo $IDNO; ?><input type="hidden" name="IDNO" value="<?php echo $IDNO; ?>">					
							</td>
							<td  align="left">			
								 ทะเบียน  <?php echo $ID_register; ?><input type="hidden" name="ID_register" value="<?php echo $ID_register; ?>">					
							</td>
					   </tr>
					   <tr bgcolor="#FFCC66">
					   <td>
					   </td> 
							<td colspan="2" align="center">
								<input type="radio" name="check" id="meter" value="1" checked>ตรวจมิเตอร์								
								<input type="radio" name="check" id="metervat" value="2" >ตรวจมิเตอร์ + ภาษี 
							</td>
					   </tr>
					  	
					   </table>
							
								<table width="850" frame="border"  cellSpacing="1" cellPadding="3" align="center" bgcolor="#EEF2F7">
									<tr>
										<td>
											<br>
										</td>
									</tr>
									<tr>
										<td width="3%">
									
										</td>
										<td width="50%">
											1.ค่าวิทยุสื่อสารรายเดือนจำนวน <input type="text" name="radio_month" id="radio_month" >เดือน
										</td>
										<td width="30%" align="right">
											เป็นเงิน <input type="text" name="radio_price" id="radio_price" onkeyup="calsum()" autocomplete="off"  OnKeyPress="check_num(event)"> บาท
										</td>
										
									</tr>
									<tr>
										<td>											
										</td>
										<td>
											2.ค่าตรวจมิเตอร์
										</td>
										<td align="right">
											เป็นเงิน <input type="text" name="meter_price" id="meter_price" onkeyup="calsum()" autocomplete="off"  OnKeyPress="check_num(event)"> บาท
										</td>
									</tr>
									<tr>
										<td>											
										</td>
										<td>
											3.ค่าตรวจมิเตอร์พร้อมต่อภาษี
										</td>
										<td align="right">
											เป็นเงิน <input type="text" name="meter_vat_price" id="meter_vat_price" onkeyup="calsum()" autocomplete="off"  OnKeyPress="check_num(event)"> บาท
										</td>
									</tr>
									<tr>
										<td>											
										</td>
										<td>
											4.ค่าเข้าร่วมประจำเดือน <select name="participating_start" id="participating_start"> 
																<option value="">--เลือกเดือน--</option>
																<option value="มกราคม">มกราคม</option>
																<option value="กุมภาพันธ์">กุมภาพันธ์</option>
																<option value="มีนาคม">มีนาคม</option>
																<option value="เมษายน">เมษายน</option>
																<option value="พฤษภาคม">พฤษภาคม</option>
																<option value="มิถุนายน">มิถุนายน</option>
																<option value="กรกฎาคม">กรกฎาคม</option>
																<option value="สิงหาคม">สิงหาคม</option>
																<option value="กันยายน">กันยายน</option>
																<option value="ตุลาคม">ตุลาคม</option>
																<option value="พฤศจิกายน">พฤศจิกายน</option>
																<option value="ธันวาคม">ธันวาคม</option>
															</select>		
															
											ปี  <select name="participating_year_start" id="participating_year_start"> 	

												<?php $datenow = nowDate();
														list($year,$month,$day)=explode("-",$datenow);
														$yearback = $year -10;
														
													  for($t=$yearback;$t<=$year+10;$t++){
													  
															 if($t == $year){ ?> 
																<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
												<?php		}else{ ?>
																<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
												<?php  
															}
														} 
												?>	
												</select>
															</select>	 ถึง <select name="participating_end" id="participating_end"> 
																<option value="">--เลือกเดือน--</option>
																<option value="มกราคม">มกราคม</option>
																<option value="กุมภาพันธ์">กุมภาพันธ์</option>
																<option value="มีนาคม">มีนาคม</option>
																<option value="เมษายน">เมษายน</option>
																<option value="พฤษภาคม">พฤษภาคม</option>
																<option value="มิถุนายน">มิถุนายน</option>
																<option value="กรกฎาคม">กรกฎาคม</option>
																<option value="สิงหาคม">สิงหาคม</option>
																<option value="กันยายน">กันยายน</option>
																<option value="ตุลาคม">ตุลาคม</option>
																<option value="พฤศจิกายน">พฤศจิกายน</option>
																<option value="ธันวาคม">ธันวาคม</option>
															</select>	
															ปี  <select name="participating_year_end" id="participating_year_end"> 	

												<?php $datenow = nowDate();
														list($year,$month,$day)=explode("-",$datenow);
														$yearback = $year -10;
														
													  for($t=$yearback;$t<=$year+10;$t++){
													  
															 if($t == $year){ ?> 
																<option value="<?php echo $t;?>" selected="selected"><?php echo $t; ?></option>	
												<?php		}else{ ?>
																<option value="<?php echo $t;?>" ><?php echo $t; ?></option>																
												<?php  
															}
														} 
												?>	
												</select>
										</td>
										<td align="right">
											เป็นเงิน <input type="text" name="participating_price" id="participating_price" onkeyup="calsum()" autocomplete="off"  OnKeyPress="check_num(event)"> บาท
										</td>
									</tr>
									<tr>
										<td>											
										</td>
										<td>
											5.ค่าประกัน <input type="text" name="insurance_price" id="insurance_price" size="10" onkeyup="cal1()" OnKeyPress="check_num(event)"> ค่าพรบ <input type="text" name="act_price"  id="act_price" size="10" onkeyup="cal1()" OnKeyPress="check_num(event)">
										</td>
										<td align="right">
											เป็นเงิน <input type="text" name="insureance_act_price" id="insureance_act_price" onkeyup="calsum()" autocomplete="off"  OnKeyPress="check_num(event)" Readonly="true"> บาท
										</td>
									</tr>
									<tr>
										<td>											
										</td>
										<td>
											6.ค่าแจ้งใช้ก๊าซ ( กรณีติดตั้งใหม่หรือเปลี่ยนถังก๊าซให้นำใบวิศวะมาด้วยในวันตรวจรถ )
										</td>
										<td align="right">
											เป็นเงิน <input type="text" name="gas_price" id="gas_price" onkeyup="calsum()" autocomplete="off"  OnKeyPress="check_num(event)"> บาท
										</td>
									</tr>
									<tr>
										<td>											
										</td>
										<td>
											7.ค่าใช้จ่ายอื่นๆ ( ถ้ามี ) <input type="text" name="other" id="other" size="30">
										</td>
										<td align="right">
											เป็นเงิน <input type="text" name="other_price" id="other_price" onkeyup="calsum()" autocomplete="off"  OnKeyPress="check_num(event)"> บาท
										</td>
									</tr>
									<tr>
										<td>											
										</td>
										<td align="left" colspan="2">
											รวมค่าใช้จ่ายโดยประมาณ <input type="text" name="sumprice" id="sumprice" size="20" OnKeyPress="check_num(event)"> บาท
										</td>
										
									</tr>
									<tr>
										<td>											
										</td>
										<td align="left" colspan="2">
											ที่อยู่ลูกค้า :  <select name="add" id="add">
														
														<!--<option value="1">ที่อยู่ตามสัญญาเช่าซื้อปัจจุบัน</option>-->
														<!--<option value="2">ที่อยู่ตามสัญญาแรกเริ่ม</option>-->
														<option value="3">ที่อยู่ส่งจดหมายล่าสุด</option>
														<!--<option value="4">ที่อยู่อื่นๆ</option> (กรณีให้กรอกเอง)-->
														<?php if($numjoin>0){?>
														<option value="5">ที่อยู่โอนสิทธิ์เข้าร่วม</option>
														<?php }?>
														<option value="6">อื่นๆ</option>
													</select>	
										</td>
										
									</tr>									
									<tr>
										<td>											
										</td>
										<td align="left" colspan="2" id="a1"> <!--ที่อยู่ตามสัญญาเช่าซื้อปัจจุบัน -->
											<textarea name="address2" id="address2" cols="80" rows="3" readonly="true"><?php echo  $address11."\n".$address22."\n".$address33; ?></textarea>
										</td>
										<td align="left" colspan="2" id="a2"> <!--ที่อยู่ตามสัญญาเช่าซื้อแรกเริ่ม -->
											<textarea name="address1" id="address1" cols="80" rows="3" readonly="true"><?php echo  $address1."\n".$address2."\n".$address3; ?></textarea>
										</td>									
										<td align="left" colspan="2" id="a3">  <!--ที่อยู่ส่งจดหมายล่าสุด-->
											<textarea name="address3" id="address3" cols="80" rows="3" readonly="true"><?php echo  $add_letter; ?></textarea>
										</td>
										<td align="left" colspan="2" id="a4"> <!--ที่อยู่อื่นๆ -->
											<textarea name="address4" id="address4" cols="80" rows="3" ></textarea>
										</td>
										<td align="left" colspan="2" id="a5"> <!--ที่อยู่โอนสิทธิ์ -->
											<textarea name="address5" id="address5" cols="80" rows="3" readonly="true"><?php echo  $addressco; ?></textarea>
										</td>
										<td align="left" colspan="2" id="a6"> <!--อื่นๆ กรณีเลือก-->
											<?php
											//ดึงที่อยู่อื่นๆที่ถูกแก้ไขล่าสุดมา
											$qryadreach=pg_query("select \"addEach\" from \"Fp_Fa1\" where \"IDNO\"='$IDNO' and \"CusState\"='0' order by \"edittime\" DESC limit(1)");
											list($adr_each)=pg_fetch_array($qryadreach);
											?>
											<!--เพิ่ม Link เชื่อมไปยัง หน้า "แก้ไขที่อยู่ส่งจดหมาย" -->
											<?php $pathroot=redirect($_SERVER['PHP_SELF'],'letter'); 
												$pathfrm = "(<a style=\"cursor:pointer;\" onclick=\"javascipt:popUPO('$pathroot/frm_lt_edit_detail.php?idno=$IDNO&CusID=$A_CusID&CusState=0&statusedit=2&FrmState=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\"><font color=\"#FF1493\"><u>เชื่อมไปยัง แก้ไขที่อยู่ส่งจดหมาย</u></font></a>)";
											?>
											<textarea name="address6" id="address6" cols="80" rows="3" readonly="true"><?php echo  $adr_each; ?></textarea>
											<!--เพิ่ม ปุ่ม เปลี่ยนที่อยู่อื่น ๆ-->
											<input type="button" name="updateaddress6" id="updateaddress6" value="เปลี่ยนที่อยู่อื่น ๆ" onclick="javascipt:popUPO('<?php echo $pathroot; ?>/frm_lt_edit_detail.php?idno=<?php echo $IDNO ?>&CusID=<?php echo $A_CusID?>&CusState=<?php echo 0 ?>&statusedit=<?php echo 2 ?>&FrmState=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')">
										</td>
										
									</tr>
									<tr>
										<td>											
										</td>
										<td align="left" valign="top" colspan="2">
											หมายเหตุ :  <input type="text" name="remark" size="80"><font color="red">(ไม่เกิน 1 บรรทัด เนื่องจากพื้นที่ในจดหมายจำกัด)</font>
										</td>	
									</tr>
									<tr>
										<td>											
										</td>
										<td align="left" colspan="2">
											ผู้ลงข้อมูล :  <?php echo $thaiacename;?>
										</td>
										
									</tr>
									<tr>
										<td><br></td>
									</tr>
									<tr>
										<td>
										</td>
										<td colspan="2" align="center">
											<input type="submit" value=" บันทึก " onclick="return checkList();" style="height:50px;width:100px;">
											<input type="button" value=" กลับ " onclick="window.location='index.php'" style="height:50px;width:100px;">
											<input type="button" name="updateaddress" id="updateaddress" value="click" onclick="refreshTextAddress()" hidden>
										</td>
										
										
										
									</tr>
									<tr>
										<td>
										</td>
										<td>
										<br>
										</td>
									</tr>
								</table>	
			</form>
		</td>
    </tr>
</table> 
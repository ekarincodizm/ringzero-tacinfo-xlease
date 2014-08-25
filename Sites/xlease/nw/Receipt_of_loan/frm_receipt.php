<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$data = $_POST['idsearch'];
list($ID,$cusname)=explode("#",$data);


$qry_name=pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$id_user'");
$result=pg_fetch_array($qry_name); 
$thaiacename = $result["fullname"];

$sql_select=pg_query("SELECT \"contractID\", \"CusState\", \"CusID\", fullname, type
  FROM \"vthcap_ContactCus_detail\" where \"contractID\" like '$ID' AND \"CusState\" = 0");
$numrows = pg_num_rows($sql_select);					  
if($numrows == 0)
{
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
	echo "<script type='text/javascript'>alert(' ขออภัย! ไม่พบข้อมูลของสัญญาฉบับนี้ ')</script>";
	exit();
}
if($data == ""){
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
	echo "<script type='text/javascript'>alert(' ขออภัย! กรุณาค้นหาใบสัญญาก่อน ')</script>";
	exit();
}	


function convert($number){ 
  $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
  $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
  $number = str_replace(",","",$number); 
  $number = str_replace(" ","",$number); 
  $number = str_replace("บาท","",$number); 
  $number = explode(".",$number); 
  if(sizeof($number)>2){ 
    return 'ทศนิยมหลายตัวนะจ๊ะ'; 
    exit; 
  } 
  $strlen = strlen($number[0]); 
  $convert = ''; 
  for($i=0;$i<$strlen;$i++){ 
    $n = substr($number[0], $i,1); 
    if($n!=0){ 
      if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
      elseif($i==($strlen-2) AND $n==2){ $convert .= 'ยี่'; } 
      elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
      else{ $convert .= $txtnum1[$n]; } 
      $convert .= $txtnum2[$strlen-$i-1]; 
    } 
  } 
  $convert .= 'บาท'; 
  if($number[1]=='0' OR $number[1]=='00' OR $number[1]==''){ 
    $convert .= 'ถ้วน'; 
  }else{ 
    $strlen = strlen($number[1]); 
    for($i=0;$i<$strlen;$i++){ 
      $n = substr($number[1], $i,1); 
      if($n!=0){ 
        if($i==($strlen-1) AND $n==1){$convert .= 'เอ็ด';} 
        elseif($i==($strlen-2) AND $n==2){$convert .= 'ยี่';} 
        elseif($i==($strlen-2) AND $n==1){$convert .= '';} 
        else{ $convert .= $txtnum1[$n];} 
        $convert .= $txtnum2[$strlen-$i-1]; 
      } 
    } 
    $convert .= 'สตางค์'; 
  } 
  return $convert; 
  
  
} 					  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>-- ใบรับเงิน --</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};


$(document).ready(function(){

CreateNewRow();

	$("#date").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });

	
});

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
	
function checkid(){

	$.post("checkid.php",{
			id : document.frm.idre.value
			
		},
		function(data){		
			
				if(data=='No'){
						alert(' เลขที่นี้ มีอยู่ในระบบแล้ว กรุณาเปลี่ยนเลขที่ด้วยครับ ');
						document.getElementById("idre").style.backgroundColor = "#CC3333";
				}else if(data == 'YES'){
						document.getElementById("idre").style.backgroundColor = "#33FF33";
				}
		});
};


	
function CreateSelectOption_detail(ele)
	{
		var objSelect = document.getElementById(ele);
		<?php $sql = "select * from \"list_of_mg_3dreceipt\"";
			   $sqlquery = pg_query($sql);	
				while($re = pg_fetch_array($sqlquery)){
		?>
		var Item = new Option( "<?php echo $re['list_detail'] ?>","<?php echo $re['listreID'] ?>"); 
		objSelect.options[objSelect.length] = Item;	
		<?php } ?>
		
	
	}	
	
var intLine = 0;

function CreateNewRow()
	{
		if(parseInt(intLine) < 10)
		{
		intLine++;
			
		var theTable = document.getElementById("tbExp");
		var newRow = theTable.insertRow(theTable.rows.length-1)
		newRow.id = newRow.uniqueID

		var newCell
		
		//*** Column 1 ***//
		newCell = newRow.insertCell(0);
		newCell.id = newCell.uniqueID;
		newCell.setAttribute("className", "css-name");
		newCell.innerHTML = "<center>"+intLine+"</center>";

		//*** Column 2 ***//
		newCell = newRow.insertCell(1);
		newCell.id = newCell.uniqueID;
		newCell.setAttribute("className", "css-name");		
		newCell.innerHTML = "<center><SELECT NAME=\"detaillist[]\" ID=\"Column2_"+intLine+"\" style=\"width:200px; text-align:center\" ></SELECT></center>";
	
		//*** Column 5 ***//
		newCell = newRow.insertCell(2);
		newCell.id = newCell.uniqueID;
		newCell.setAttribute("className", "css-name");
		newCell.innerHTML = "<center><input style=\"text-align:right\" type=\"text\" NAME=\"moneylist[]\" ID=\"Column3_"+intLine+"\" autocomplete=\"off\" onkeyup=\"javascript : calsum();\"  ></center>";
		
		newCell = newRow.insertCell(3);
		newCell.id = newCell.uniqueID;
		newCell.setAttribute("className", "css-name");
		

		//*** Create Option ***//
		CreateSelectOption_detail("Column2_"+intLine)
		}
	}
	
function RemoveRow()
	{

		if(parseInt(intLine) > 1)
		{
				document.getElementById("Column3_"+intLine+"").value=="";
				theTable = document.getElementById("tbExp");				
				theTableBody = theTable.tBodies[0];
				theTableBody.deleteRow(intLine);
				intLine--;
				calsum();
				
		}	
	}		

function checkList()
{
	if(document.getElementById("idre").value=="")
	{
		
			alert(' กรุณากรอกเลขที่ด้วยครับ ');
			return false;
		
		
	}else if(document.getElementById("address").value=="")
	{
		
			alert(' กรุณากรอกที่อยู่ของลูกค้าด้วยครับ ');
			return false;
		
		
	}else{
		for(i=1;i<=intLine;i++){

			if(document.getElementById("Column3_" + intLine).value== ""){	
				alert(' ใส่จำนวนเงินให้ครบด้วยครับ ');		
				return false;
			}else{
				return true;
			}
		}	
		
	}

}	


function calsum(){ 
		 var sss = 0;
    for( i=1; i<=intLine; i++ ){
        var c1 = $('#Column3_'+ i).val();
        if ( isNaN(c1) || c1 == ""){
            c1 = 0;
        }
        sss += parseFloat(c1);
    }
	//thaitext(sss);
	var numfinal = number_format(sss,2,'.',',');	
	$("#sum").text(numfinal);
	
		
}

function thaitext(numtext){

	$.post("numtext.php",{
			num : numtext
			
		},
		function(data){		
			
			$("#sumtext").text(data);
				
		});


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
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body bgcolor="#DFE6EF">
<form name="frm" action="receipt_query.php" method="POST">
<table width="750" frame="border" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
	<tr>
		<td>
			<br>
		</td>
	</tr>
	<tr>
		<td align="center">
			<h1> ใบรับเงิน  </h1>
			<hr width="600">
		</td>
	</tr>
	<tr>
		<td>
			<br>
		</td>
	</tr>
	<tr>
		<td>
			<table width="600" border="0" cellspacing="2" cellpadding="2" align="center">
				<tr>
					<td width="65%">
					
					</td>
					<td align="right">
						เลขที่ : 
					</td>
					<td align="left">
						<input type="text" name="idre" id="idre" onblur="checkid()">
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td align="right">
						วันที่ : 
					</td>
					<td align="left">
						<input type="text" name="date" id="date" value="<?php echo $date = nowDate();?>">
					</td>
				</tr>	
			</table>
			<table width="750" border="0" cellspacing="0" cellpadding="4" align="center">
				<tr>
					<td colspan="3"><br></td>
				</tr>
				<tr>					
					<td align="right" width="18%">
						ได้รับเงินจาก : 
					</td>
					<td align="left" colspan="2">
						<?php echo $cusname;?>
						<input type="hidden" name="cusname" value="<?php echo $cusname;?>">
						<input type="hidden" name="idcontract" value="<?php echo $ID;?>">
					</td>
					
				</tr>
				<tr>					
					<td align="right">
						ที่อยู่ : 
					</td>
					<td align="left" colspan="2">
						<textarea rows="3" cols="50" name="address" id="address"></textarea>
					</td>
					
				</tr>
				<tr>
					<td colspan="3"><br></td>
				</tr>
				<tr>
					<td colspan="3">
						<table width="600" border="1" cellspacing="0" cellpadding="2" align="center" id="tbExp">
							
							<tr>
								<td align="center" width="10%">
									ลำดับที่
								</td>
								<td align="center" width="60%">
									รายการ
								</td>
								<td align="center" width="25%">
									จำนวนเงิน
								</td>
								<td align="center">
									<input type="button" value=" + " onclick="CreateNewRow();">
									<input type="button" value="-" onClick="javascript : RemoveRow();">
								</td>
							</tr>
							<tr>
								<td  align="center" width="10%">
									รวม
								</td>
								<td align="center" width="60%">
									<span id="sumtext" name="sumtext"></span>
								</td>
								<td align="center" width="25%">
									<span id="sum" name="sum">0</span>
								</td>
								<td>
								</td>
							</tr>							
						</table>					
					
						
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<br>
					</td>
				</tr>	
				<tr>
					<td align="right">
						ข้าพเจ้า
					</td>
					<td align="center" width="35%">
						<?php echo $cusname;?>
					</td>
					<td>
						ยินดีชำระ  ค่าใช้จ่ายตามรายการข้างต้น  ทุกประการ
					</td>
				</tr>	
				<tr>
					<td align="right">
						ข้าพเจ้า
					</td>
					<td align="center">
						<?php echo $thaiacename;?>
					</td>
					<td>
						ได้รับค่าใช้จ่ายจากผู้ชำระครบถ้วนแล้วในวันนี้
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<br>
					</td>
				</tr>	
			</table>
			<table width="750" border="0" cellspacing="0" cellpadding="2" align="center">
					<tr>
						<td colspan="3">
							<br>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<br>
						</td>
					</tr>
					<tr>
						<td width="50%">				
						</td>
						<td width="40%" align="center">
							ลงชื่อ...................................ผู้ชำระ
						</td>
						<td width="10%">				
						</td>
					</tr>
					<tr>	
						<td>
						</td>
						<td align="center">
							(			<?php echo $cusname;?>				)
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<br>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<br>
						</td>
					</tr>
					<tr>
						<td width="50%">				
						</td>
						<td width="40%" align="center">
							ลงชื่อ...................................ผู้รับเงิน
						</td>
						<td width="10%">				
						</td>
					</tr>
					<tr>	
						<td>
						</td>
						<td align="center">
							(			<?php echo $thaiacename;?>		)
						</td>
						<td>
						</td>
					</tr>						
			</table>
		
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<br>
		</td>
	</tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="2" align="center">
	<tr>
		<td colspan="3">
			<br>
		</td>
	</tr>
	<tr>
		<td  align="center">
			<input type="submit" value=" บันทึก " onclick="return checkList()" style="width:150px;  height:50px; background-color:#C2CFDF">
		</td>
		<td>
		</td>
		<td align="center">
			<input type="button" value=" ยกเลิก " style="width:150px;  height:50px; background-color:#C2CFDF" onclick="parent.location.href='index.php'">
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<br>
		</td>
	</tr>
		
</table>
</form>
</body>
</html>

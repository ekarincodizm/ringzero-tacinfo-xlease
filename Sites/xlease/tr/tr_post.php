<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title><?php echo $_SESSION["session_company_name"]; ?></title>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="javascript">
var k = 0 ;
function cal_fr()
{ 
 var sta1 =parseFloat(document.frm_ps.amts.value); //ยอดโอน
 var va1 = parseFloat(document.frm_ps.count_fr.value); //จำนวนเดือนจ่าย
 var va2 = parseFloat(document.frm_ps.fr_pay.value); //ค่างวด
 var ress= parseFloat(document.frm_ps.rescal.value=va1*va2);
 
 /*
	// *todo แก้ไขเนื่องจากรองรับค่างวดไม่เท่ากัน แต่ comment ไว้เนื่องจากไม่ได้ใช้งาน
  var ress;
  //หาค่างวดทั้งหมดที่เลือก
  $.get('../deposit/api.php?cmd=loaddueamt&idno='+ $('#p_idno').val()+'&s1='+document.frm_ps.count_fr.value, function(data){
		ress=parseFloat(document.frm_ps.rescal.value=data);
  });
  */
 
 if(ress > sta1)
 {
  alert("ยอดทำรายการเกินกว่ายอดเงินโอน");
 }
} 
function windowOpen(x) {
var
myWindow=window.open(x,'windowRef','width=600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}
function Chk134(id){
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
        windowOpen('../nw/join_cal/join_cal.php?idno=<?php echo $_GET["m_idno"]; ?>&inputName=amt'+ id + '&pay_date=<?php echo $_GET["trd"]  ?>&change_pay_type=1');
           
           $("#type_detail"+ id).load("../postpay/api.php?cmd=load_join1&id="+ id+'&idno=<?php echo $_GET["m_idno"]; ?>&inputName=amt'+ id + '&pay_date=<?php echo $_GET["trd"]  ?>&change_pay_type=1', function(){
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
        windowOpen('../nw/join_cal/join_cal.php?idno=<?php echo $_GET["m_idno"]; ?>&inputName=amt'+ id + '&pay_date=<?php echo $_GET["trd"]  ?>&change_pay_type=0');
               
				
				 $("#type_detail"+ id).load("../postpay/api.php?cmd=load_join1&id="+ id+'&idno=<?php echo $_GET["m_idno"]; ?>&inputName=amt'+ id + '&pay_date=<?php echo $_GET["trd"]  ?>&change_pay_type=0', function(){
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
		
		     $("#amt"+ id).val("");
      $("#amt"+ id).removeAttr("readonly");   
	  $("#type_detail"+ id).hide();
	}
}

</script>

<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<?php
$chk_sid = $_GET['sid'];
$chk_trd = $_GET['trd'];
$chk_plog = $_GET['plog'];

$qry_tr=pg_query("select * from \"TranPay\" WHERE id_tranpay='$chk_sid' AND \"PostID\"='$chk_plog' AND tr_date='$chk_trd' ");
if($res_tr=pg_fetch_array($qry_tr)){
	$chk_post_on_asa_sys = $res_tr["post_on_asa_sys"];
}

if( $chk_post_on_asa_sys == "t" ){
	echo "<div style=\"width:300px; text-align:center; margin:0px auto; padding:10px; border:1px dashed #CCCCCC; background-color:#FFE4E1\">ա÷¡ù<br /><button onclick=\"window.location='frm_transpaydate.php'\">BACK</button></div>";
	exit;
}
?>

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <script type="text/javascript">
  	var gFiles = 0;
	function addFile() 
	
	{
	
	var li = document.createElement('li');
	li.setAttribute('id', 'file-' + gFiles);
	li.innerHTML = '<select name="typepayment[]" id="typepayment'+ gFiles +'" onchange="javascript:Chk134('+ gFiles +')"><?php 
	$qry_type=pg_query("select * from \"TypePay\" WHERE \"TypeID\" !=1 and \"TypeID\" != '133' ");
	while($res_type=pg_fetch_array($qry_type))
	{ 
	echo  "<option value=\"$res_type[TypeID]\">$res_type[TName]</option>"; 
	}
	?></select>&nbsp;<span id="type_detail' + gFiles + '"></span>&nbsp;<input type="text" name="amt[]" id="amt'+ gFiles +'" ><button onClick="removeFile(\'file-' + gFiles + '\')">REMOVE</button>';
	document.getElementById('files-root').appendChild(li);
	gFiles++;
	}
	function removeFile(aId) {
	var obj = document.getElementById(aId);
	obj.parentNode.removeChild(obj);
	//gFiles--;
	}
   </script>
    
    <table width="769" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
    <tr style="background-color:#FCF1C5;">
    <td colspan="4">รายการโอน</td>
    </tr>
	 <?php
	 // chk detail Transpay--//
	 $qry_dtltrnpay=pg_query("select * from \"DetailTranpay\" WHERE \"PostID\"='$_GET[plog]' ");
	 
	
	 
	 $res_dpay=pg_num_rows($qry_dtltrnpay);
	 
	      
		  
		   
	 
	 
	 //echo $res_dpay;
	 
	 if($res_dpay > 0)
	 {
	 // echo $res_dpay;
	 ?>
	  <tr style="background-color:#F5F7E1;">
    	<td colspan="4">ข้อมูลโอนได้ทำรายการไปแล้ว กรุณาตรวจสอบกับผู้ดูแลระบบ <button onclick="window.location='frm_transpaydate.php'">BACK</button></td>
    	</tr>
	 <tr style="background-color:#F5F7E1;">
    	<td colspan="4">Detail TranPay</td>
    	</tr>
		<?php
		$qry_tp=pg_query("select A.*,B.* from \"DetailTranpay\" A
		                  LEFT OUTER JOIN \"TypePay\" B on B.\"TypeID\"=A.\"TypePay\" 
						  WHERE (A.\"PostID\"='$_GET[plog]') ");
		while($res_ty=pg_fetch_array($qry_tp))
		{
		
		   $postdate=$res_ty["post_on_date"];
		   $post_ip=$res_ty["PostID"];
		   if(empty($res_ty["ReceiptNo"]))
		   {
		     $stor="";
			 $bt_rec="<button onclick=\"window.location='process_pass_tranpay_back.php?pid=$post_ip'\">รับเงินและออกในเสร็จ</button>";
			 
		   }
		   else
		   {
		     $stor="รับเงินแล้ว :".$res_ty["ReceiptNo"];
		   }
		   
		   
		?>
	<tr style="background-color:#D3E4F5;">
    <td colspan="4">IDNO=<?php echo $res_ty["IDNO"]; ?> , จ่ายค่า =[<?php echo $res_ty["TypePay"]; ?>] <?php echo $res_ty["TName"]; ?> , จำนวนเิงิน = <?php echo number_format($res_ty["Amount"],2); ?> , <?php echo $stor; ?></td>
    	</tr>
	  <?php
	    }
	  ?>
	  <tr style="background-color:#F5F7E1;">
    	<td colspan="4"><div align="center"><?php echo $bt_rec; ?></div></td>
    	</tr>
	 <?php
	 
	 }
	 else
	 {
	 // echo $res_dpay;
	 
	 
	 
	 
	 
	 
	 //----------------------//
	 $rr1=$_GET["r1"]; 
	 $rr2=$_GET["r2"];
	 $trdate=$_GET["trd"];
	 $amtpost=$_GET["amt"];
	 
	 $p_idno=$_GET["m_idno"];
	 
	 $qry_c=pg_query("select * from \"Fp\" where(\"TranIDRef1\"='$rr1')AND(\"TranIDRef2\"='$rr2') AND (\"IDNO\"='$p_idno')");
	 $numr=pg_num_rows($qry_c);
	 if($numr==0)
	 {
		// ไม่มีข้อมูลในระบบปัจจุบัน ... ลองเช็คกับระบบเก่าดู ถ้ามีให้เก็บค่า IDNO ที่ Ref1, Ref2 ตรงกันในระบบเก่าไว้
		$qry_oldref = pg_query("select \"IDNO\" from pmain.new_fp_trans where (\"TranIDRef1\"='$rr1') AND (\"TranIDRef2\"='$rr2') ");
		$num_oldref = pg_num_rows($qry_oldref);
		if($num_oldref > 0)
		{
			$c_idno = pg_result($qry_oldref,0);
			if($c_idno == $p_idno)
			{ // ถ้ามีข้อมูลอยู่ในระบบเก่า
				$haveData = "y"; // มีข้อมูล
				
				// หา ref ในระบบใหม่
				$qry_n = pg_query("select \"TranIDRef1\", \"TranIDRef2\" from \"Fp\" where \"IDNO\" = '$c_idno' ");
				$rr1 = pg_result($qry_n,0);
				$rr2 = pg_result($qry_n,1);
			}
			else
			{
				$haveData = "n"; // ไม่มีข้อมูล
			}
		}
		else
		{
			$haveData = "n"; // ไม่มีข้อมูล
		}
	 }
	 else
	 {
		$haveData = "y"; // มีข้อมูล
	 }
	 
	 if($haveData == "n")
	 { // ถ้าไม่มีข้อมูล
		?>
			<tr style="background-color:#DDE6B7">
				<td colspan="4">ข้อมูลโอนมีปัญหา กรุณาตรวจสอบการโอน <button onclick="window.location='frm_transpaydate.php'">BACK</button></td>
			</tr>
		<?php
	 }
	 elseif($haveData == "y")
	 { // ถ้ามีข้อมูล
	    $qry_cc=pg_query("select * from \"Fp\" where(\"TranIDRef1\"='$rr1')AND(\"TranIDRef2\"='$rr2')");
	    $res_cc=pg_fetch_array($qry_cc); 
	    $idno=$res_cc["IDNO"];
		$ref1=$res_cc["TranIDRef1"];
		$ref2=$res_cc["TranIDRef2"];
		
		
		
		$p_month=$res_cc["P_MONTH"]+$res_cc["P_VAT"];
		
	    $cuslist=pg_query("select A.*,B.* from \"VContact\" A  
				     LEFT OUTER JOIN \"Fa1\" B ON A.\"CusID\" = B.\"CusID\" 
				     WHERE A.\"IDNO\" ='$idno' ");
  		$rescus=pg_fetch_array($cuslist);
	
          if($rescus["C_REGIS"]=="")
			{
			 $rec_regis=$rescus["car_regis"];
			 $rec_cnumber=$rescus["gas_number"];
			 $res_band=$rescus["gas_name"];
			}
		  else
			{	
			 $rec_regis=$rescus["C_REGIS"];
			 $rec_cnumber=$rescus["C_CARNUM"];
			 $res_band=$rescus["C_CARNAME"];
			}
	 
	 ?>
	
	 <tr style="background-color:#FEFAE9;">
    <td width="119">ชื่อ</td>
    <td colspan="3"><?php echo $rescus["full_name"]; ?></td>
    </tr>
  <tr style="background-color:#FEFAE9;">
    <td>เลขที่สัญญา</td>
    <td width="337"><?php echo $rescus["IDNO"]; ?></td>
    <td width="68">ทะเบียน</td>
    <td width="217"><?php echo $rec_regis; ?></td>
  </tr>
  <tr style="background-color:#FEFAE9;">
    <td>Ref.1</td>
    <td><?php echo $ref1; ?></td>
    <td>Ref.2</td>
    <td><?php echo $ref2; ?></td>
  </tr>
  <tr style="background-color:#FEFAE9;">
    <td>tr_date</td>
    <td><?php echo $trdate; ?></td>
    <td>amt</td>
    <td><?php echo number_format($amtpost,2); ?></td>
  </tr>
</table>
    
	<form method="post" action="process_transfer.php" name="frm_ps">
	<input type="hidden" name="amts" id="amts" value="<?php echo $amtpost; ?>" />
	<input type="hidden"  name="h_plog" value="<?php echo $_GET["plog"]; ?>" />
	<table width="769" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
    <tr style="background-color:#DDE6B7">
    <td colspan="3">ชำระค่างวด ยอดค่างวด(รวม VAT) <input name="fr_pay" id="fr_pay" type="text" value="<?php echo $p_month; ?>"  />
	
	<select  name="count_fr" id="count_fr" onchange="cal_fr()">
	<option value="0">เลือกจำนวนงวด</option>
	<?php
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
	<tr style="background-color:#DDE6B7">
	<td colspan="3">
	<ol id="files-root">
	</ol>
	<?php
	}
	?>	</td>
	 </tr>
	 <tr style="background-color:#DDE6B7">
	  <td>
	  <input type="hidden" name="id_tpay" value="<?php echo $_GET["sid"]; ?>"  />
	   <input type="hidden" name="s_amt" value="<?php echo $amtpost; ?>" />
	  <input type="hidden" name="ref1" value="<?php echo $rr1; ?>" />
	  <input type="hidden" name="ref2" value="<?php echo $rr2; ?>" />
	  <input type="hidden" name="p_idno" id="p_idno" value="<?php echo $idno; ?>" />
	  <input type="button" name="btnAdd" onclick="javascript:addFile();" value="เพิ่มค่าใช้จ่ายอื่น ๆ" /></td>
	  <td>&nbsp;</td>
	  <td><input name="submit" type="submit" value="NEXT" /></td>
	  </tr>
	 </table>	
	</form>
    </div>
</div>
<?php
}
?>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

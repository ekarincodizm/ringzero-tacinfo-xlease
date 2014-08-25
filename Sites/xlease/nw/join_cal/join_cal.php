<?php
include("../../config/config.php");
include("function_join.php");
$page_name = $_REQUEST['page_name'];
?>

<html>
<head>
<title>คำนวณค่าเข้าร่วม</title>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> -->

    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../../postpay/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
   <script src="../../jqueryui/js/number.js" type="text/javascript"></script>
</head>
 
<body>
<script language="JavaScript">
function updateOpener() {
	window.opener.document.forms[0].<?php print $_REQUEST['inputName'] ?>.value =
document.forms[0].amount.value.replace(/,/g,'') ;

	<?php if($page_name=='detail'){ ?>

window.opener.updateSummary();
<?php }else if($page_name=='deposit'){  ?>

window.opener.ChangeMoney();

<?php }?>
window.close();
}

function cal_ar(){
	   $change_pay_type = <?php print $_REQUEST['change_pay_type'] ?>;

   var amount = parseFloat(document.getElementById('amount1').value.replace(/,/g,'')); 

 $.post("cal_arrears.php", { amount: amount,
 change_pay_type: $change_pay_type,
 arrears: document.getElementById('arrears').value,
 pay_type: document.getElementById('pay_type').value,
 start_pay_date: document.getElementById('start_pay_date').value,
 expire_date: document.getElementById('expire_date').value
 


  },
  function(data){

		document.getElementById('cal_arrears').innerHTML=data;  

	  
  });


 }
</script>
<center>
  
<?php 


$change_pay_type=$_REQUEST['change_pay_type'];
$idno=$_REQUEST['idno'];
$pay_date=$_REQUEST['pay_date'];

if($page_name=='cheque1')
$pay_date =date_ch_form($pay_date);
					
					
					if($pay_date=="")
					$pay_date = nowDate(); // วันที่ชำระ 
					else $pay_date = str_replace("/","-",$pay_date);
					
					
	$query5 = "SELECT id,car_license,start_pay_date FROM \"VJoinMain\" WHERE idno='$idno' and deleted='0' ORDER BY id desc limit 1 ";
				
				
				$sql_query5 = pg_query($query5);
	$num_row=pg_num_rows($sql_query5);
	if($num_row!=0){
				while($sql_row5 = pg_fetch_array($sql_query5))
				{			
						$id2 = $sql_row5['id'];
					$start_pay_date = $sql_row5['start_pay_date'];
					//$start_pay_date = date_ch_form_m($start_pay_date);	
				}
					  $query = "SELECT pay_type,expire_date FROM ta_join_payment WHERE id_main='$id2' and deleted='0' ORDER BY pay_date desc,id desc limit 1 ";//หารายการสุดท้าย
				//echo $query ;
				     $sql_query = pg_query($query);
					$num_row=pg_num_rows($sql_query);
					if($num_row!=0){
					while($sql_row = pg_fetch_array($sql_query))
				{	
				//$period_date = $sql_row['period_date'];
				$expire_date = $sql_row['expire_date'];
				$pay_type = $sql_row['pay_type'];
				
				if($expire_date==""){
					$cre_fr=pg_query("select join_date_diff_month('$start_pay_date','1')"); // ลบ 1 เดือน หลังจาก ชำระครั้งแรก
		$expire_date=pg_fetch_result($cre_fr,0); 

				} 

				}
					}
					else{ // ชำระครั้งแรก
						$pay_type = 0 ; //300
						
						$cre_fr=pg_query("select join_date_diff_month('$start_pay_date','1')"); // ลบ 1 เดือน หลังจาก ชำระครั้งแรก
		$expire_date=pg_fetch_result($cre_fr,0); 
		
		
						
					}
					// ค่าค้างชำระ
					


					$ar_qr=pg_query("select join_arrears_cal('$expire_date', '$pay_date', '$pay_type','$change_pay_type')"); // คำนวณค่าค้างชำระ
					$arrears=pg_fetch_result($ar_qr,0); 
					
if($pay_type==1 && $change_pay_type==1){
echo "<br><br><h3><font color=red>สัญญาเลขที่ $_REQUEST[idno] ได้ชำระค่าแรกเข้า 5,000 บาท แล้ว</font></h3>";
?>
		<input type="button" value=" Close " class="ui-button" onClick="window.close();"><?php }
else{
                ?>


<form name="form">
<input name="pay_date" type="hidden" id="pay_date" value="<?Php print $pay_date ?>" size="20"/>
<input name="arrears" type="hidden" id="arrears" value="<?Php print $arrears ?>" readonly size="20"/>
 <input name="expire_date" type="hidden" id="expire_date" value="<?Php print $expire_date ?>" size="20"/>
 <input name="pay_type" type="hidden" id="pay_type"  value="<?Php print $pay_type ?>" size="20"/>
 <input name="change_pay_type" type="hidden" id="if($pay_type==0)" value="<?Php print $change_pay_type ?>" size="20"/>
  <input name="start_pay_date" type="hidden" id="start_pay_date"  value="<?Php print $start_pay_date ?>" size="20"/>
<table border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="2" bgcolor="#66CCFF" height="25px" ><b><a href="../join_payment/extensions/ta_join_payment/pages/ta_join_payment_view_new.php?idno_names=<?php echo $id2 ?>" target="_blank"><u>รายละเอียด</u></a></b></td>
    </tr>
  <tr>
    <td bgcolor="#F0FFFF">เดือนที่หมดอายุ  </td>
    <td bgcolor="#F0FFFF"><?Php print date_ch_form_m($expire_date) ?></td>
  </tr>
  <tr>
    <td bgcolor="#F0FFFF">ประเภทการชำระ </td>
    <td bgcolor="#F0FFFF"><?Php if($pay_type==0)echo "300 บาท/เดือน";else if($pay_type==1)echo "100 บาท/เดือน";else echo "-"; ?></td>
  </tr>
    <tr>
    <td bgcolor="#F0FFFF">วันที่ชำระ </td>
    <td bgcolor="#F0FFFF"><?Php print date_ch_form_c($pay_date) ?></td>
  </tr>
  <?php if($arrears!=0){ ?>
  <tr>
    <td bgcolor="#F0FFFF">ค่าค้างชำระ<?Php if($change_pay_type==1)echo "รวมค่าแรกเข้า" ?></td>
    <td bgcolor="#F0FFFF"><?Php print number_format($arrears) ?> บาท</td>
  </tr>
<?php } ?>
</table>
 <br>
<font size="3" >จำนวนเงินที่ชำระ : <input name="amount" type="text" id="amount1" onChange="dokeyup(this,event);cal_ar();" size="15" onKeyUp="dokeyup(this,event);" onKeyPress="checknumber2(event)"  /> บาท</font>
 <input value="คำนวน" id="credit_b2" type="button" name="credit_b" 
				  onclick="javaScript:cal_ar()" />
                  

<br><br><div id="cal_arrears" align="center"></div>
<br>
<br>
<?php }	}
	else {
		echo "<br><br><h3><font color=red>ไม่พบข้อมูล สัญญาเลขที่ $_REQUEST[idno] </font></h3>"; ?>
		<input type="button" value=" Close " class="ui-button" onClick="window.close();"><?php
	} ?>
</form>
</center>

</body>
</html>
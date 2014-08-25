<?php
include("../../../../join_cal/function_join.php");
include("../../../../../config/config.php");
$page_name = $_REQUEST['page_name'];
$realpath = redirect($_SERVER['PHP_SELF'],'');
$config =1;// config =1 คือ Update ทุกครั้ง ที่เปิดหน้านี้
?>

<html>
<head>
<title>คำนวณค่าเข้าร่วม</title>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> -->

    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../../../../../postpay/act.css"></link>
    
    <link type="text/css" href="../../../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
   <script src="../../../../../jqueryui/js/number.js" type="text/javascript"></script>
</head>
 
<body>
<script language="JavaScript">

function cal_ar(){

	   $change_pay_type = <?php print $_REQUEST['change_pay_type'] ?>;

   var amount = parseFloat(document.getElementById('amount1').value.replace(/,/g,'')); 

 $.post("../../../../join_cal/cal_arrears.php", { amount: amount,
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
 function updateOpener(){

	    var amount = parseFloat(document.getElementById('amount1').value.replace(/,/g,'')); 
 $.post("insert_join_discount.php", { amount: amount,
 amount: amount,
 change_pay_type: '<?php print $_REQUEST['change_pay_type'] ?>',
 pay_date: document.getElementById('settlement').value,
 idno: '<?php echo $_REQUEST['idno']; ?>',
 reason: document.getElementById('reason').value



  },
  function(data){
//alert(data);
		if(data==0)alert('บันทึกรายการเรียบร้อยแล้ว!!');
		if(data!=0)alert('ไม่สามารถบันทึกรายการได้!!');
 $("#panel").hide();
	  $("#car_no").val('');
  });

	 
 }

 $(document).ready(function(){

	  $("#non_ap").click(function() {
        $("#panel2").load("join_discount_approve_show.php");
		$("#panel2").show();
		document.getElementById('non_ap').style.display='none';
		document.getElementById('non_ap_h').style.display='';
		 });
		 
		  $("#non_ap_h").click(function() {
        $("#panel2").hide(); 
		
		document.getElementById('non_ap_h').style.display='none';
		document.getElementById('non_ap').style.display='';
		 });	
});

$("#settlement").datepicker({
	showOn: 'button',
	buttonImage: '../images/calendar.gif',
	buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	//minDate: 0,
	dateFormat: 'yy-mm-dd'
});
 
</script>
<center>
<?php 
	$qry_fr=pg_query("SELECT m.car_license,f.\"O_RECEIPT\",f.\"O_MONEY\",cpro_name,m.idno,f.approve_status,f.create_by,f.create_datetime,f.\"O_memo\" FROM \"FOtherpayDiscount\" f left join \"VJoinMain\" m on m.idno = f.\"IDNO\" WHERE m.deleted ='0' and m.car_license_seq = 0
			 and f.approve_status!=1 order by f.approve_status , f.create_datetime ");
	$nub=pg_num_rows($qry_fr); ?>
    <input value="แสดงรายการที่ยังไม่อนุมัติ(<?php echo $nub ?>)" id="non_ap" type="button" name="non_ap"  />
    <input value="ซ่อนรายการที่ยังไม่อนุมัติ(<?php echo $nub ?>)" style="display:none" id="non_ap_h" type="button" name="non_ap_h"  />
    <span id="panel2" style="padding-top: 20px;"></span>
<?php 
	$change_pay_type=$_REQUEST['change_pay_type'];
	$idno=$_REQUEST['idno'];
	$id2 =$_REQUEST[id];
	$pay_date = nowDate(); // วันที่ชำระ 
	$id=$id2;
	if($id!="undefined"){
		include("ta_join_data.php");
			
		$start_pay_date = $start_pay_date2;//จาก include("ta_join_data.php")
	
		$query = "SELECT pay_type,expire_date FROM ta_join_payment WHERE id_main='$id2' and deleted='0' ORDER BY period_date desc,pay_date desc , id desc limit 1 ";//หารายการสุดท้าย
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
	<input type="button" value=" Close " class="ui-button" onClick="window.close();">
<?php
}else{
?>

<form name="form">
<input name="pay_date" type="hidden" id="pay_date" value="<?Php print $pay_date ?>" size="20"/>
<input name="arrears" type="hidden" id="arrears" value="<?Php print $arrears ?>" readonly size="20"/>
<input name="expire_date" type="hidden" id="expire_date" value="<?Php print $expire_date ?>" size="20"/>
<input name="pay_type" type="hidden" id="pay_type"  value="<?Php print $pay_type ?>" size="20"/>
<input name="change_pay_type" type="hidden" id="if($pay_type==0)" value="<?Php print $change_pay_type ?>" size="20"/>
<input name="start_pay_date" type="hidden" id="start_pay_date"  value="<?Php print $start_pay_date ?>" size="20"/>
  
 <br>

<table border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="2" bgcolor="#66CCFF" height="25px" ><b>รายละเอียดการขอส่วนลดเข้าร่วม : </a></b></td>
    </tr>
  <tr>
    <td width="200" bgcolor="#EEFBFA"><div align="right">เดือนที่หมดอายุ : </div></td>
    <td width="200" bgcolor="#EEFBFA"><?Php print date_ch_form_m($expire_date) ?></td>
  </tr>
  <tr>
    <td bgcolor="#EEFBFA"><div align="right">ประเภทการชำระ : </div></td>
    <td bgcolor="#EEFBFA"><?Php if($pay_type==0)echo "300 บาท/เดือน";else if($pay_type==1)echo "100 บาท/เดือน";else echo "-"; ?></td>
  </tr>
    <tr>
    <td bgcolor="#EEFBFA"><div align="right">วันที่ชำระ : </div></td>
    <td bgcolor="#EEFBFA"><input type="text" name="settlement" id="settlement" value="<?Php print $pay_date; ?>" /></td>
	<!--<td bgcolor="#EEFBFA"><?Php print date_ch_form_c($pay_date); ?></td>-->
  </tr>
  <?php if($arrears!=0){ ?>
  <tr>
    <td bgcolor="#EEFBFA"><div align="right">ค่าค้างชำระ
      <?Php if($change_pay_type==1)echo "รวมค่าแรกเข้า" ?>
     : </div></td>
    <td bgcolor="#EEFBFA"><?Php print number_format($arrears) ?> บาท</td>
  </tr>
<?php } ?>
 <tr>
    <td bgcolor="#EEFBFA"><div align="right"><b>จำนวนเงินที่ขอส่วนลด : </b></div></td>
    <td bgcolor="#EEFBFA"><input name="amount" type="text" id="amount1" onChange="dokeyup(this,event);cal_ar();" size="15" onKeyUp="dokeyup(this,event);" onKeyPress="checknumber2(event)"  /> บาท</font>
 <input value="คำนวน" id="credit_b2" type="button" name="credit_b" 
				  onclick="javaScript:cal_ar()" /></td>
  </tr>
   <tr>
    <td bgcolor="#EEFBFA"><div align="right">เหตุผลในการขอส่วนลด : </div></td>
    <td bgcolor="#EEFBFA"><textarea name="reason" style="width:195px;height:40px;" id="reason" ></textarea></td>
  </tr>
</table>

<div id="cal_arrears" align="center"></div>

<?php } ?>
</form>

<?php
//แสดงรายการชำระเงิน
include("join_list_payment.php");
?>
<fieldset style="width:99%">
	<legend>
		<font color="black"><b>รายการที่รออนุมัติ</b></font>
	</legend>
    <br>
    <table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
        <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
        <td align="center">ลำดับ</td>
            <td align="center">เลขที่สัญญา</td>
            <td align="center">เลขทะเบียนรถ</td>
            <td align="center">ชื่อลูกค้า</td>
            <td align="center">จำนวนเงินที่ขอ</td>
            <td align="center">ผู้ขออนุมัติ</td>
            <td align="center">วันเวลาที่ขอ</td>
            <td align="center">เหตุผล</td>
        </tr>
		<?php
        $i = 0;
        $qry_fr1=pg_query("SELECT m.car_license,f.\"O_RECEIPT\",f.\"O_MONEY\",cpro_name,m.idno,f.approve_status,f.create_by,f.create_datetime,f.\"O_memo\" FROM \"FOtherpayDiscount\" f left join \"VJoinMain\" m on m.idno = f.\"IDNO\" WHERE m.deleted ='0' and m.car_license_seq = 0
         and f.approve_status=0 order by f.create_datetime ");
        $nub=pg_num_rows($qry_fr1);
        while($sql_row4=pg_fetch_array($qry_fr1))
        {
            $cpro_name = $sql_row4['cpro_name'];
            $O_RECEIPT = $sql_row4['O_RECEIPT'];
            $car_license = $sql_row4['car_license'];
            $create_datetime =$sql_row4['create_datetime']; 
            $reason =$sql_row4['O_memo']; 
            $approve_status = $sql_row4['approve_status'];
            $O_MONEY =$sql_row4['O_MONEY']; 
            $create_by = $sql_row4['create_by'];//เอาไปหา id_card ด้วย
            $idno2 = trim($sql_row4['idno']);
            $dt = $create_datetime;
            $by = $create_by;
            
            $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$by'");
            $res_userprofile=pg_fetch_array($res_profile);
            $by=  $by."-".$res_userprofile["fullname"];
        
            $i+=1;
            if($i%2==0){
                echo "<tr class=\"odd\" align=center>";
            }else{
                echo "<tr class=\"even\" align=center>";
            }
        ?>
        	<td align="center"><?php echo $i; ?></td>
            <td><a onclick="javascript:popU('<?php echo $realpath; ?>post/frm_viewcuspayment.php?idno_names=<?php echo $idno2; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1050,height=600')" style="cursor:pointer"><u><?php echo $idno2; ?></u>
             </a></td>
            <td align="left"><?php echo $car_license; ?></td>
            <td align="left"><?php echo $cpro_name; ?></td>
            <td align="right"><?php echo number_format($O_MONEY); ?></td>
            <td align="left"><?php echo $by; ?></td>
            <td align="center"><?php echo $dt; ?></td>
            <td align="left"><?php echo $reason; ?></td>
        </tr>
		<?php
        }
        if($nub == 0){
            echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
        }
        ?>
	</table>
</fieldset>
<?php
include("appv_history_limit.php");
}
?>
</center>

</body>
</html>
<?php
include("../../../../../config/config.php");
include("../../../../hr_payroll/function_payroll.php");

$id =pg_escape_string($_REQUEST['id']);
$f_d =pg_escape_string($_REQUEST['f_d']);

$qry_fr=pg_query("SELECT f.\"amount\",f.approve_status,f.create_by,f.create_datetime,f.\"memo\",f.pay_type,f.pay_date,f.change_pay_type,f.id_main,m.car_license,m.cpro_name,m.idno FROM \"ta_join_add_money_app\" f left join \"VJoinMain\" m on m.id = f.\"id_main\" 
			WHERE f.id='$id' ");
			$nub=pg_num_rows($qry_fr);


if($sql_row4=pg_fetch_array($qry_fr)){
				
				$cpro_name = $sql_row4['cpro_name'];
					$car_license = $sql_row4['car_license'];
					$create_datetime =$sql_row4['create_datetime']; 
					$memo =$sql_row4['memo']; 
					$approve_status = $sql_row4['approve_status'];
					$O_MONEY =$sql_row4['amount']; 
					$create_by = $sql_row4['create_by'];
					$idno = trim($sql_row4['idno']);
					$id_main = trim($sql_row4['id_main']);
					$pay_type =$sql_row4['pay_type']; 
					$pay_date =$sql_row4['pay_date']; 
					$change_pay_type =$sql_row4['change_pay_type']; 
					if($change_pay_type =='0'){$change_pay_type_text ="เฉพาะค่าเข้าร่วม";}
					else if($change_pay_type =='1'){$change_pay_type_text ="รวมค่าแรกเข้า";}
		

		$dt = $create_datetime;
			$by = $create_by;
		
		
		
					$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$by'");
   $res_userprofile=pg_fetch_array($res_profile);
   $by=  $by."-".$res_userprofile["fullname"];
}
	
?>
    
<fieldset>
  
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.button {
    border: 1px solid #006;
    background: #ccf;
}
.button:hover {
    border: 1px solid #f00;
    background: #eef;
}
</style>

<form name="frm1" id="frm1" action="join_add_money_app_api.php" method="post">

<table width="98%" cellpadding="0" cellspacing="1" border="0">
<tr>
  <td ><div align="right"><strong>เลขที่สัญญา : </strong></div></td>
   
    <td  >&nbsp;  <a href="ta_join_payment_view_new.php?idno_names=<?php print $id_main ?>&config=0&rf=1" target="_blank"><u><?php echo $idno ?></u></a></td>
    
   
</tr>
<tr>
  <td ><div align="right"><strong>เลขทะเบียนรถ : </strong></div></td>
   
    <td>&nbsp;  <?php echo $car_license ?></td>
    
   
</tr>
<tr>
  <td ><div align="right"><strong>ชื่อลูกค้า : </strong></div></td>
   
    <td>&nbsp;  <?php echo $cpro_name ?></td>
    
   
</tr>

<tr>
  <td ><div align="right"><strong>ประเภทการชำระ : </strong></div></td>
    <td>&nbsp; <?php echo $change_pay_type_text ?></td>
   
   
</tr>
<tr>
  <td ><div align="right"><strong>วันที่ชำระ : </strong></div></td>
   
    <td>&nbsp;  <?php echo $pay_date ?></td>
    
   
</tr>

<tr>
  <td ><div align="right"><strong>จำนวนเงิน : </strong></div></td>
   
    <td>&nbsp;  <font color=red><b><?php echo number_format($O_MONEY); ?></b></font></td>
    
   
</tr>

<tr>
   
    <td align="right"><div align="right"><b>ผู้ขออนุมัติ : </b></div></td><td>&nbsp;  <?php echo $by ?></td>
    
   
</tr>
<tr>
   
    <td align="right"><div align="right"><b>วันเวลาที่ขอ : </b></div></td><td>&nbsp;  <?php echo $dt ?></td>
    
   
</tr>
<tr>
   
    <td align="right"><div align="right"><b>หมายเหตุ : </b></div></td><td>&nbsp; <font color=blue><?php echo $memo ?></font></td>
    
   
</tr>
<tr>
   
    <td align="right"><div align="right"><b>ไฟล์แนบ : </b></div></td><td>&nbsp; <?php
				//ค้นหาไฟล์ scan จากตาราง thcap_fa_prebill_file
				$qryfile=pg_query("select * from ta_join_file_path where \"id\"='$id'");
				$numfile=pg_num_rows($qryfile);
				$i=1;
				while($resfile=pg_fetch_array($qryfile)){
					$file22=$resfile["file"];					
					echo "<a href=\"../upload/$file22\" target=\"_blank\"><img src=\"../images/open.png\" width=18 heigh=18 title=\"ไฟล์ $i\"></a>";
					if($i%5==0){
						echo "<br>";
					}
					$i++;
				}
				if($numfile==0){
					echo "<img src=\"../images/noimage.png\" width=20 heigh=20 title=\"ไม่พบไฟล์\">";
				}
				?></td>
    
   
</tr>
<?php if($f_d==""){?>
<tr>
   
    <td align="right"><div align="right"><b>หมายเหตุในการอนุมัติ/ไม่อนุมัติ : </b></div></td><td>&nbsp;  <b><font color=red><textarea name="memo_app" id="memo_app" cols="40" rows="2"></textarea></font></b></td>
    
   
</tr>
<?php } ?>
</table>
<?php if($f_d==""){?>
<div style="text-align:right; margin-top:10px">
  <input type="hidden"  id="transaction_type" value="<?php echo $transaction_type ?>">
	<input name="appv" type="submit" value="อนุมัติ" hidden />
	<input name="unappv" type="submit" value="ไม่อนุมัติ" hidden  />
	<input type="hidden" name="id" id="id" value="<?php echo $id;?>">
	
	<input class="button ui-button" type="button" id="cancelvalue" value="ไม่อนุมัติ"  >
	<input type="button" class="ui-button " name="btn_save" id="btn_save" value="อนุมัติ"  />
</div>
<?php } ?>
</form>
</fieldset>
<script type="text/javascript">

$('#btn_save').click(function(){
  
$("#btn_save").attr('disabled', true);	
	document.forms['frm1'].appv.click();	
	});	
	$('#cancelvalue').click(function(){
  
$("#cancelvalue").attr('disabled', true);
	document.forms['frm1'].unappv.click();	
	});
	

  
</script>

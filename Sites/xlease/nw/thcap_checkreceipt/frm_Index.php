<?php
set_time_limit(0);
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ
//กรณีเริ่มค้นหา
$val=$_REQUEST["val"]; //ค่าในการค้น ถ้า=1 แสดงว่าให้เริ่มค้น
$condate=$_REQUEST["condate"]; //เงื่อนไขวันที่
$month=$_REQUEST["month"]; //เดือนที่เลือก
$year=$_REQUEST["year"]; //ปีที่เลือก
$bankint=$_REQUEST["bankint"];
if($year==""){
	$year=date('Y');
} 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตรวจสอบรายการรับชำระเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/number.js"></script>

<script type="text/javascript">
/*$(document).ready(function(){
	$("#btnsave").click(function(){
	   alert('<?php echo $numberno;?>');
	
		var ele=$('input[name="chk[]"]');
		var receipt = [];
		if(ele.length==0){
			alert("กรุณาเลือกรายการ");
		}else{
			var j;
			j=0;
			for( i=0; i<ele.length; i++ ){
				if($(ele[i]).is(':checked')){
					receipt[j]={receiptid:$(ele[i]).val()};
					j=j+1;
				}
			}
			
			$.post("process_checkreceipt.php",{
				receipt : JSON.stringify(receipt) 
			},
			function(data){
				if(data == 1){
					alert("มีบางรายการตรวจสอบก่อนหน้านี้แล้ว กรุณาตรวจสอบ");
				}else if(data==2){
					alert("บันทึกรายการเรียบร้อย");
					location.href = "frm_Index.php?val=1&condate="+'<?php echo $condate;?>'+"&month="+'<?php echo $month;?>'+"&year="+'<?php echo $year;?>';//refresh เป็นหน้าแรก
				}else{
					alert("ผิดผลาด ไม่สามารถบันทึกได้ กรุณาตรวจสอบ");
				}
			});
		}
	});
});*/
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body>
<div style="text-align:center"><h2>(THCAP) ตรวจสอบรายการรับชำระเงิน</h2></div> 
<!--สิทธิการเข้า(THCAP) STATEMENT BANK-->
<?php $qry=pg_query("select id_user from f_usermenu where id_user='$id_user' and id_menu='TMA07' and status='true'");
if(pg_num_rows($qry)>0){?>
<table align="right">
<tr>
	<td>
	<div style="float:right">
	<input type="button" value="(THCAP) STATEMENT BANK" onclick="javascript:popU('../thcap_statement_select_load/frm_Index.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')">
	</td>
</tr>
</div><?php }?>
<tr>
	<td>
	<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
	</td>
</tr>
</table>
<div style="clear:both;"></div>
<form method="post" name="from1" action="#">	
<div style="width:940px;margin:0 auto;">
<fieldset><legend><B>เลือกเงื่อนไข</B></legend>
	<div align="center">
		<div class="ui-widget">
			<p align="center">
				<b>ช่องทาง </b>
				<select name="bankint" id="bankint">
				<?php 	
						$sql_bank = pg_query("select * from \"BankInt\" where \"isChannel\" = '1' order by \"BID\"");
						echo "<option value=\"\">- ทุกช่องทาง -</option>";
						while($re_bank = pg_fetch_array($sql_bank)){ ?>
							
							<option value="<?php echo $re_bank["BID"];?>"<?php if($bankint==$re_bank["BID"]){ echo "selected";}?>>
							<?php echo $re_bank["BAccount"]."-".$re_bank["BName"];?>
							<?php //if($bankint==$re_bank["BID"]){ echo "selected";  }					
							echo "</option>";							
						} 
				?></select>		
				<b>แสดงตาม</b>
				<select name="condate">
					<option value="1" <?php if($condate=="1") echo "selected";?>>วันที่ทำรายการ</option>
					<option value="2" <?php if($condate=="2") echo "selected";?>>วันที่รับชำระ</option>
				</select>
				<b>เดือน </b><?php include "select_month.php";?>
				<b>ปี ค.ศ. </b>
				<input type="text" id="year" name="year" value="<?php echo $year;?>" size="10" style="text-align:center" maxlength="4" onKeyPress="checknumber2(event)">
				<input type="hidden" name="val" value="1"/>
				<input type="submit" id="btn" value="เริ่มค้น"/>
				
			</p>
</fieldset>
</div>
</form>
<?php
if($val=="1"){	
	//แสดงในส่วนตรวจสอบจากที่ค้น
	include "frm_checkreceipt.php";
}
//แสดงในส่วนรายการที่ต้องตรวจสอบทั้งหมด
include "frm_checkall.php";
?>	
</body>
</html>
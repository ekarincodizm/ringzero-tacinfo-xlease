<?php
set_time_limit(0);
include("../config/config.php");
function fc_run($search)
{
  $query = 'SELECT 
				"Fr"."IDNO" as n_IDNO, 
				"Fr"."R_DueNo" as n_DueNo, 
				"Fr"."R_Date" as n_Date, 
				"Fr"."R_Receipt" as n_Receipt, 
				"Fr"."R_Money" as n_Money, 
				"Fr"."R_Bank" as n_Bank, 
				"Fr"."R_Prndate" as n_Prndate, 
				"Fr"."PayType" as n_PayType, 
				"Fr"."R_memo" as n_memo, 
				"fotherpay"."IDNO" as o_IDNO, 
				"fotherpay"."O_DATE" as o_Date, 
				"fotherpay"."O_RECEIPT" as o_Receipt, 
				"fotherpay"."O_STATE" as o_Dueno, 
				"fotherpay"."O_MONEY" as o_Money, 
				"fotherpay"."O_DESCRIPTION" as o_memo, 
				"fotherpay"."O_BANK" as o_Bank, 
				"fotherpay"."O_PRNDATE" as o_Prndate, 
				"fotherpay"."PAYTYPE" as o_PayType
				FROM 
				  "pmain"."fotherpay", 
				  "public"."Fr"
				WHERE 
				  "fotherpay"."O_RECEIPT" = "Fr"."R_Receipt" AND
				  "Fr"."R_DueNo" = 900 AND
				  "fotherpay"."O_RECEIPT" LIKE \'%'.$search.'%\'
				ORDER BY
				  "fotherpay"."IDNO" ASC';

			$sql_query = pg_query($query);
			$num_row = pg_num_rows($sql_query);
			
			echo "แก้ไขใบเสร็จใน FOtheypay ที่ขึ้นมีตัว : ".$search."  อยู่ด้วย จำนวนทั้งหมด <font color=#FABEC2>$num_row</font> ข้อมูล<br><br>";
			
			
			while($sql_row = pg_fetch_array($sql_query))
			{
				$n_IDNO 		= 	$sql_row[n_idno];
				$n_DUENO		=	$sql_row[n_dueno];
				$n_DATE 		= 	$sql_row[n_date];
				$n_RECEIPT		=	$sql_row[n_receipt];
				$n_MONEY 		= 	$sql_row[n_money];
				$n_BANK			=	$sql_row[n_bank];
				$n_PRNDATE 		= 	$sql_row[n_prndate];
				$n_PAYTYPE		=	$sql_row[n_paytype];
				$n_MEMO			= 	$sql_row[n_memo];
				
				$o_IDNO 		= 	$sql_row[o_idno];
				$o_DUENO		=	$sql_row[o_dueno];
				$o_DATE 		= 	$sql_row[o_date];
				$o_RECEIPT		=	$sql_row[o_receipt];
				$o_MONEY 		= 	$sql_row[o_money];
				$o_BANK			=	$sql_row[o_bank];
				$o_PRNDATE 		= 	$sql_row[o_prndate];
				$o_PAYTYPE		=	$sql_row[o_paytype];
				$o_MEMO			= 	$sql_row[o_memo];
				
				// อัพเดทข้อมูลใหม่
				$query2 = 	'UPDATE "Fr"
							   SET "IDNO"='."'".$o_IDNO."'".',
									"R_DueNo"=900,
									"R_Date"='."'".$o_DATE."'".',
									"R_Money"='.$o_MONEY.',
									"R_Bank"='."'".$o_BANK."'".',
									"R_Prndate"='."'".$o_PRNDATE."'".',
									"PayType"='."'".$o_PAYTYPE."'".',
									"R_memo"='."'".$o_MEMO."' ".
									'WHERE "R_Receipt" = '."'".$o_RECEIPT."'".'';
							 
				$sql_query2 = pg_query($query2);
				echo $n_IDNO.' : 900; '.$o_RECEIPT.'; '.$o_DATE.'; '.$o_MONEY.'; '.$o_BANK.'; '.$o_PRNDATE.'; '.$o_PAYTYPE.'; '.$o_MEMO.'; => updated </br>';
			}
			echo "-------------------------------------------------------------------------<br><br>";
			
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการที่ไม่ตรงกัน</title>

<script type="text/javascript">  
function popup(url,name,windowWidth,windowHeight){       
    myleft=(screen.width)?(screen.width-windowWidth)/2:100;    
    mytop=(screen.height)?(screen.height-windowHeight)/2:100;      
    properties = "width="+windowWidth+",height="+windowHeight;   
    properties +=",scrollbars=yes, top="+mytop+",left="+myleft;      
    window.open(url,name,properties);   
}   
</script> </head><body bgcolor="#F5F5F5">
<center>
<div class="form_description">
				<h2>อัพเดทรายการที่ไม่ตรงกัน... </h2>

					</div>

  <?php
  //$connection = pg_connect("host=172.16.2.5 port=5432 dbname=devxleasenw user=dev password=nextstep") or die ("Not Connect PostGres");
  // รันจากเก่ามาใหม่เพื่อให้ไล่ update ข้อมูลรถให้หมด
  fc_run('R'); // update ข้อมูลใน Fr ของใบเสร็จที่เคยอยู่ใน FOtherPay และเป็น R


?>
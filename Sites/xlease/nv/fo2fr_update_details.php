
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
				<h2>รายการที่ไม่ตรงกัน </h2>

					</div>

  <?php
  
  //$connection = pg_connect("host=172.16.2.5 port=5432 dbname=devxleasenw user=dev password=nextstep") or die ("Not Connect PostGres");
  include("../config/config.php");
  $receipt = pg_escape_string($_REQUEST[receipt]);
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
				  "fotherpay"."O_RECEIPT" LIKE \'%'.$receipt.'%\'
				ORDER BY
				  "fotherpay"."IDNO" ASC';
		
			//echo $query;
				$sql_query = pg_query($query);
				
				$num_row = pg_num_rows($sql_query);
				
				echo "จำนวนทั้งหมด <font color=#FABEC2>$num_row</font> ข้อมูล<br><br>";
				
				
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
				
				 ?>
                
                

<table border="1" align="center" cellspacing="0" bgcolor=white>

  <tr bgcolor="#CCCCFF">
    <td><div align="center">&nbsp</div></td>
      <td><div align="center"><strong>ระบบใหม่</strong></div></td>
      <td><div align="center"><strong>ระบบเก่า</strong></div></td>
  </tr>
  <tr>
     <td <?php if($n_IDNO!=$o_IDNO){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>รหัสสัญญา</strong></div></td>
     <td <?php if($n_IDNO!=$o_IDNO){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_IDNO ?></div></td>
     <td <?php if($n_IDNO!=$o_IDNO){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_IDNO ?></div></td>
  </tr>
    <tr>
    <td <?php if($n_DUENO!=$o_DUENO){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>รหัสค่าใช้จ่าย</strong></div></td>
    <td <?php if($n_DUENO!=$o_DUENO){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_DUENO ?></div></td>
    <td <?php if($n_DUENO!=$o_DUENO){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_DUENO ?></div></td>
  </tr>
    <tr>
     <td <?php if($n_DATE!=$o_DATE){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>วันที่รับชำระ</strong></div></td>
     <td <?php if($n_DATE!=$o_DATE){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_DATE ?></div></td>
     <td <?php if($n_DATE!=$o_DATE){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_DATE ?></div></td>
    </tr>
    <tr>
     <td <?php if($n_RECEIPT!=$o_RECEIPT){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>เลขที่ใบเสร็จ</strong></div></td>
     <td <?php if($n_RECEIPT!=$o_RECEIPT){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_RECEIPT ?></div></td>
     <td <?php if($n_RECEIPT!=$o_RECEIPT){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_RECEIPT ?></div></td>
    </tr>
    <tr>
     <td <?php if($n_MONEY!=$o_MONEY){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>จำนวนเงิน</strong></div></td>
     <td <?php if($n_MONEY!=$o_MONEY){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_MONEY ?></div></td>
     <td <?php if($n_MONEY!=$o_MONEY){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_MONEY ?></div></td>
    </tr>
    <tr>
     <td <?php if($n_BANK!=$o_BANK){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>ธนาคาร</strong></div></td>
     <td <?php if($n_BANK!=$o_BANK){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_BANK ?></div></td>
     <td <?php if($n_BANK!=$o_BANK){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_BANK ?></div></td>
    </tr>
    <tr>
     <td <?php if($n_PRNDATE!=$o_PRNDATE){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>วันที่พิมพ์</strong></div></td>
     <td <?php if($n_PRNDATE!=$o_PRNDATE){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_PRNDATE ?></div></td>
     <td <?php if($n_PRNDATE!=$o_PRNDATE){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_PRNDATE ?></div></td>
    </tr>
    <tr>
     <td <?php if($n_PAYTYPE!=$o_PAYTYPE){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>รูปแบบการจ่าย</strong></div></td>
     <td <?php if($n_PAYTYPE!=$o_PAYTYPE){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_PAYTYPE ?></div></td>
     <td <?php if($n_PAYTYPE!=$o_PAYTYPE){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_PAYTYPE ?></div></td>
    </tr>
    <tr>
     <td <?php if($n_MEMO!=$o_MEMO){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>คำอธิบายรายการ</strong></div></td>
     <td <?php if($n_MEMO!=$o_MEMO){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $n_MEMO ?></div></td>
     <td <?php if($n_MEMO!=$o_MEMO){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_MEMO ?></div></td>
    </tr>
    <tr>

 <?php } ?>
    
</table>

<br>
<input id="saveForm" class="button_text" type="button" value="ปิด" onclick="window.close()" style='width:100px; height:50px'/>
<?Php  
echo "</center>
			<div class=form_description></div>
		
</body>
</html>";
?>
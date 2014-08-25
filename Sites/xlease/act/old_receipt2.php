<?php
set_time_limit(0);
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>

</head>
<body>
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td>       
		<div style="float:left"><input type="button" value="กลับ" class="ui-button" onclick="window.location='old_receipt.php'"></div>
		<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
		<div style="clear:both;"></div>

		<fieldset><legend><B>ตัดใบเสร็จประกันเก่า - ภาคบังคับ (พ.ร.บ.)</B></legend>
		<div align="center">
			<div class="ui-widget">
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
				<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
					<td>ID</td>
					<td>IDNO</td>
					<td>ชื่อลูกค้า</td>
					<td>ทะเบียน</td>
					<td>จำนวนเงิน</td>
					<td>&nbsp;</td>
				</tr>
				<?php
				$query=pg_query("select * from insure.\"VInsForceDetail\" WHERE \"outstanding\" > '0' ORDER BY \"IDNO\" ASC");
				while($resvc=pg_fetch_array($query)){
					$InsFIDNO = $resvc['InsFIDNO'];
					$IDNO = $resvc['IDNO'];
					$full_name = $resvc['full_name'];
					$C_REGIS = $resvc['C_REGIS'];
					$outstanding = $resvc['outstanding'];

					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=\"left\">";
					}else{
						echo "<tr class=\"even\" align=\"left\">";
					}
				?>
					<td align="center"><?php echo $InsFIDNO; ?></td>
					<td align="center"><?php echo $IDNO; ?></td>
					<td><?php echo $full_name; ?></td>
					<td><?php echo $C_REGIS; ?></td>
					<td align="right"><?php echo number_format($outstanding,2); ?></td>
					<td align="center"><input type="button" name="btn1" value="ทำรายการนี้" onclick="window.location='old_receipt_otherpay.php?idno=<?php echo $IDNO; ?>&id=<?php echo $InsFIDNO; ?>';"></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td align="left" colspan="10">รวมทั้งสิ้น <b><?php echo $i; ?></b> รายการ</td>
				</tr>
				</table>
			</div>
		</div>
		</fieldset>
    </td>
</tr>
</table>

</body>
</html>
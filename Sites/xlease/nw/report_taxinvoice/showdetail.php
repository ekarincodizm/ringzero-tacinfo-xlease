<?php
	include("../../config/config.php"); 
	
	$taxinvoiceID=$_GET['receiptID'];

	$qry_receipt = pg_query("	SELECT  a.*,b.\"fullname\" 
								FROM thcap_v_taxinvoice_otherpay a
								LEFT JOIN \"Vfuser\" b ON a.\"doerID\"	= b.\"username\"
								WHERE a.\"taxinvoiceID\" = '$taxinvoiceID'
							");
	$rows = pg_num_rows($qry_receipt);

	
	IF($rows == 0){
		$qry_receipt = pg_query("	SELECT  a.*,b.\"fullname\" 
									FROM thcap_v_taxinvoice_otherpay_cancel a
									LEFT JOIN \"Vfuser\" b ON a.\"doerID\"	= b.\"username\"
									WHERE a.\"taxinvoiceID\" = '$taxinvoiceID'
								");		
	}
	
	$result = pg_fetch_array($qry_receipt);
	$receiptID = $result["receiptRef"];
	$doer = $result['fullname'];
	$timestamp = $result['doerStamp'];
	list($date,$time)=explode(" ",$timestamp);
	
	
	$reciptpopup = "<a onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=350')\" style=\"cursor:pointer;\"><u>".$receiptID."</u></a>";
											
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายละเอียดผู้ออกใบกำกับภาษี</title>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<style type="text/css">
body center table tr td{
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 13px;
	font-weight: normal;
	color: #444;
	text-decoration: none;
}
</style>
</head>

<body>
<center>
<table border="0" cellpadding="3" cellspacing="0">
	<tr>
    	<td><b>ผู้ออกใบกำกับภาษี :</b> <?php echo $doer; ?></td>
    </tr>
    <tr>
        <td><b>วันที่ออกใบกำกับภาษี :</b> <?php echo $date; ?></td>
    </tr>
    <tr>
        <td><b>เวลาที่ออกใบกำกับภาษี :</b> <?php echo $time; ?></td>
    </tr>
    <tr>
        <td><b>ใบเสร็จที่เกี่ยวข้อง :</b> <?php echo $reciptpopup; ?></td>
    </tr>
</table>
</center>
</body>
</html>
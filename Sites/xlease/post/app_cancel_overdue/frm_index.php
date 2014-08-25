<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>อนุมัติยกเลิกหนี้ค่าใช้จ่ายค้างชำระ</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui-1.10.2/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-ui-1.10.2.custom.min.js"></script>
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<center><h2>อนุมัติยกเลิกหนี้ค่าใช้จ่ายค้างชำระ</h2></center>
<body >
<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่สัญญาเช่าซื้อ</th>
		<th>เลขที่</th>	
		<th>รายการ</th>		
		<th>ยอดเงินที่เก็บกับลูกค้า</th>
		<th>การชำระของลูกค้า</th>
		<th>ผู้ที่ทำรายการ</th>
		<th>วันที่ทำรายการ</th>
		<th>ทำรายการ</th>		
	</tr>
	<?php
	$qry_main = pg_query("select * from carregis.\"CarTaxDue_reserve\" where \"Approved\"=9 order by \"doerStamp\" desc	");
	$no=0;	
	
	while($res_main = pg_fetch_array($qry_main))
		{	
			$auto_id = $res_main["auto_id"];
			$IDCarTax = $res_main["IDCarTax"];
			$IDNO = $res_main["IDNO"];
			$TypeDep = $res_main["TypeDep"];
			$CusAmt = $res_main["CusAmt"];
			$cuspaid = $res_main["cuspaid"];		
			$doerID = $res_main["doerID"];
			$doerStamp = $res_main["doerStamp"];
			
			//รายการ $TypeDep			
			$qry_TName=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
			$TName=pg_fetch_array($qry_TName);
			$Pay_name= ($TName["TName"]);
			
			//การชำระเงิน 
			if($cuspaid	=='t'){
				$status_cuspaid	="ชำระแล้ว";
			}elseif($cuspaid=='f'){
				$status_cuspaid	="ยังไม่ชำระ";
			}
			//ผู้ทำรายการ
			$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
			$fullnameuser = pg_fetch_array($query_fullnameuser);
			$doerfullname=$fullnameuser["fullname"];
			
			$no+=1;
			if($no%2==0)
			{
				echo "<tr class=\"odd\" align=\"center\" height=25>";
			}
			else
			{
				echo "<tr class=\"even\" align=\"center\" height=25>";
			}
			echo "<td align=\"center\">$no</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../frm_viewcuspayment.php?idno_names=$IDNO','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$IDNO</u></font></a></td>";	
			echo "<td align=\"center\">$IDCarTax</td>";
			echo "<td align=\"left\">$Pay_name</td>";
			echo "<td align=\"right\">".number_format($CusAmt,2)."</td>";
			echo "<td align=\"center\">$status_cuspaid</td>";
			echo "<td align=\"center\">$doerfullname</td>";
			echo "<td align=\"center\">$doerStamp</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_checkappv.php?id=$auto_id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ทำรายการ</u></font></a></td>";
			echo "</tr>";
			
		}
		if($no==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#79BCFF\" height=25><td colspan=9><b>ข้อมูลทั้งหมด $no รายการ</b></td><tr>";
		}
		?>
</table>
<br>
<?php include('frm_historylimit.php');?>
</body>
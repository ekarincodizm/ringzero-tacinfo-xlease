<?php
include("../../config/config.php"); 
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
$idno  = $_GET['idno'];//เลขที่สัญญา
//path เริ่มที่ root สำหรับ link ไปหน้าตรวจสอบข้อมูลลูกค้า
$pathroot=redirect($_SERVER['PHP_SELF'],'nw/search_cusco');
if($idno !=""){
	$qry_namemain=pg_query("select * from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$idno' and \"CusState\" ='0'");
	if($resnamemain=pg_fetch_array($qry_namemain)){
		$name1=trim($resnamemain["thcap_fullname"]);
		$cusid1=trim($resnamemain["CusID"]);
	}
	$qr_ct = pg_query("select \"thcap_get_creditType\"('$idno') as credit_type");
	if($qr_ct)
	{
		$rs_ct = pg_fetch_array($qr_ct);
		$credit_type = $rs_ct['credit_type'];
	}
}
?>
<table>
<tr >
	<td align="right" bgcolor="#FF6464" ><font size="3"><b>เลขที่สัญญา:</b></font></td>	
	<td align="left" bgcolor="#FFC6C6"><font size="3"><?php echo $idno;?></font></td>
	<td width="10"></td>
	<?php 
	if($credit_type=="HIRE_PURCHASE" || $credit_type=="LEASING" || $credit_type=="GUARANTEED_INVESTMENT" || $credit_type=="FACTORING")
		{ ?>
		<td bgcolor="#FF6464" ><font size="3"><b>ผู้เช่า/ผู้เช่าซื้อ :</b></font></td>	
    <?php }else{?>
		<td bgcolor="#FF6464" ><font size="3"><b>ชื่อผู้กู้หลัก :</b></font></td>	
	 <?php }?>	
	<td align="left" bgcolor="#FFC6C6">
	(<a style="cursor:pointer;" onclick="javascipt:popU('<?php echo $pathroot; ?>/index.php?cusid=<?php echo $cusid1; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><font color="#FF1493"><u><?php echo $cusid1; ?></u></font></a>)
	<font size="3"><?php echo $name1;?></font>
	</td>
</tr>

</table>

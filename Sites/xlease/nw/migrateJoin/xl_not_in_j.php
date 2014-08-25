<?php
$excel = $_REQUEST[excel];

set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("../../config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$num_add = 0;
?>
<?php if($excel==1)header("Content-Type: application/vnd.ms-excel");
if($excel==1)header('Content-Disposition: attachment; filename="carID_ck.xls"'); ?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"

xmlns:x="urn:schemas-microsoft-com:office:excel"

xmlns="http://www.w3.org/TR/REC-html40">

<HTML>

<HEAD>

<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<link href="js/jquery-ui-1.8.19.custom.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</HEAD><BODY>
<style type="text/css">

table.t2 tr:hover td {
	background-color:#FFFF66;
}

</style>


<br><button onClick="window.open('xl_not_in_j.php?excel=1')" style="width:160px">Excel</button>


<table border="0" cellpadding="0" align="center" cellspacing="0">

  <tr>
   
    <td style="vertical-align:top">
    
    <fieldset><legend>
    <h3> ค้นหา รหัสรถยนต์ CarID ใน XL ไม่เจอใน Join Main </h3>
    </legend>   
    
    <?php			

$test_sql=pg_query("SELECT DISTINCT a.\"CarID\"
FROM \"Fc\" a
LEFT JOIN \"VJoinMain\" b
ON a.\"CarID\" = b.carid
WHERE (a.\"C_REGIS\" like 'ท%') and b.carid IS NULL order by a.\"CarID\" desc ");
$rowtest=pg_num_rows($test_sql);
echo "จำนวนทั้งหมด $rowtest ข้อมูล * แถบสีชมพู คือสัญญาที่ปิดแล้ว";
?><br><br>
    <TABLE BORDER="0" class="t2" align="center" cellpadding="1" cellspacing="1" style="vertical-align:top;" x:str>
<Tr bgcolor="#33CCFF">
<Th width="41" align="center" style="height:30px"><div align="center"><b>ลำดับ</b></div></Th>

<Th width="186"><div align="center"><b>CarID</b></div></Th>
<Th width="249"><div align="center"><b>เลขที่สัญญา</b></div></Th>
<Th width="63"><div align="center"><b> เพิ่ม </b></div></Th>
</Tr>
<?php			


$seq2=1;
while($result=pg_fetch_array($test_sql))
{

	$asset_id=trim($result["CarID"]);
	
		$sql_query5=pg_query("select \"IDNO\",\"P_ACCLOSE\" from \"Fp\" v WHERE v.\"asset_id\" = '$asset_id' order by v.\"P_STDATE\" desc limit 1 ");
	if($sql_row5 = pg_fetch_array($sql_query5))
				{	
	$idno=$sql_row5["IDNO"];
	$P_ACCLOSE=$sql_row5["P_ACCLOSE"];
				}

if($P_ACCLOSE=='f'){
	
        if($seq2%2==0){
            echo "<TR id=\"tr$seq2\" bgcolor=\"#EDF8FE\">";
        }else{
            echo "<TR id=\"tr$seq2\" bgcolor=\"#D5EFFD\">";
        }
}else{
	
	echo "<TR bgcolor=\"Pink\">";
	
}
?>

  <TD align="center"><?php echo $seq2; if($P_ACCLOSE=='t')echo "*"?></TD>
 
<TD><div align="center"><strong><?php echo $asset_id; ?></strong></div></TD>
 <TD><div align="center"><a href="#" onClick="javascript:popU('../../post/frm_viewcuspayment.php?idno=<?php echo "$idno"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')"><?php echo "<b><FONT COLOR=#0000FF><u>$idno</u></FONT></b>"; ?></a></div></TD>


<TD><button onClick="javascript:popU('../join_payment/extensions/ta_join_payment/pages/frm_main.php?new_sp=2&car_id_r=<?php echo $asset_id; ?>&action=add&idno=<?php echo "$idno"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')"" style="width:100px;height:30px" > เพิ่ม </button></TD>
</TR>
		<?php		
		//$id_card2=null;	
		$seq2++;		
	
		
	} ?>

 </table></fieldset>
</td>
      </tr>
    </table>
</BODY>

</HTML>

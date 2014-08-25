<?php
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="CkInvDup.xls"');
set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

//pg_query("BEGIN WORK");
$status = 0;
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"

xmlns:x="urn:schemas-microsoft-com:office:excel"

xmlns="http://www.w3.org/TR/REC-html40">

<HTML>

<HEAD>

<meta http-equiv="Content-type" content="text/html;charset=tis-620" />

</HEAD><BODY>

<TABLE  x:str BORDER="1">
<Tr>
<TD><b>ลำดับ</b></TD>

<TD><b>InvNo</b></TD>

<TD><b>CusID</b></TD>

</Tr>
<?php  $seq=1;
$sql_fc=mssql_query("select InvNo from TacInvoice GROUP BY InvNo HAVING COUNT( InvNo ) >1 ",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	
	$InvNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["InvNo"]));

				$sql_fc2=mssql_query("select CusID from TacInvoice where InvNo = '$InvNo' ",$conn);
				$num_row2 = mssql_num_rows($sql_fc2);

						$sql_fc3=mssql_query("select CusID from TacInvoice where InvNo = '$InvNo' GROUP BY CusID HAVING COUNT( CusID ) ='$num_row2' ",$conn);
						$num_row3 = mssql_num_rows($sql_fc3);
						
					//if(($num_row3==1) || ($num_row3>1 && $num_row3<$num_row2)){ 
					if($num_row3!=1){ 
							while($res_fc2 = mssql_fetch_array($sql_fc2)){
					$CusID=trim($res_fc2["CusID"]);	
					?>
<TR>
<TD><b><?php echo $seq; ?></b></TD>
<TD><b><?php echo $InvNo; ?></b></TD>
<TD><b><?php echo $CusID; ?></b></TD>
</TR>
		<?php			$seq++;		
						}
				}
	
	} ?> 
    
</TABLE>

</BODY>

</HTML>
<?php
//}
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
   // pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>


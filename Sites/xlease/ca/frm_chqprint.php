<table width="600" border="1">
<?php
include("../config/config.php");
echo $recid=pg_escape_string($_POST["idno_names"]);

$a=0;
$qry_ncq=pg_query("select * from \"FCheque\" where \"PostID\" ='$recid' ");
while($rescq=pg_fetch_array($qry_ncq))
{
 $a++;
?>
 <tr>
    <td colspan="6">เลขที่เช็ค <?php echo $c_no=trim($rescq["ChequeNo"]); ?></td>
 </tr>
   <tr>
    <td colspan="6"><?php echo $a; ?>&nbsp;&nbsp; detail เช็ค ธนาคาร : <?php echo $rescq["BankName"]; ?> 
	 สาขา : <?php echo $rescq["BankBranch"]; ?> วันที่บนเช็ค <?php echo $rescq["DateOnCheque"]; ?></td>
  </tr>
  <tr>
    <td width="34">no</td>
    <td width="77">IDNO</td>
    <td width="146">name</td>
    <td width="62">regis</td>
    <td width="166">pay for </td>
    <td width="75">Amt</td>
  </tr>
    <?php
	 $qry_dtl=pg_query("select A.*,B.*,C.* from \"DetailCheque\" A 
	                    LEFT OUTER JOIN \"VContact\" B on A.\"IDNO\"=B.\"IDNO\"
						LEFT OUTER JOIN \"TypePay\" C on A.\"TypePay\"=C.\"TypeID\"
	
	 					where (A.\"PostID\" ='$recid') AND (A.\"ChequeNo\"='$c_no') ");
	 $n=0;
	 while($resdt=pg_fetch_array($qry_dtl))
	 {
	   $n++;
	   if($resdt["C_REGIS"]=="")
		{
		
		$rec_regis=$resdt["car_regis"];
		$rec_cnumber=$resdt["gas_number"];
		$res_band=$resdt["gas_name"];
		
		
		}
		else
		{
		
		$rec_regis=$resdt["C_REGIS"];
		$rec_cnumber=$resdt["C_CARNUM"];
		$res_band=$resdt["C_CARNUM"];
		}
	 
	   
	?>
	  <tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $resdt["IDNO"]; ?></td>
		<td><?php echo $resdt["full_name"]; ?></td>
		<td><?php echo $rec_regis; ?></td>
		<td><?php echo "ชำระค่า ".$resdt["TName"]; ?></td>
		<td><?php echo $resdt["CusAmount"]; ?></td>
	  </tr>
	<?php
	 }
	?>	  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php
 
}

?>

 

</table>



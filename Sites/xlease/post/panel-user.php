<?php
include("../config/config.php");
$idno = $_GET['idno'];

if(!Empty($idno)){

$qry_name=@pg_query("select * from \"VContact\" WHERE \"IDNO\"='$idno'");
$numrows = @pg_num_rows($qry_name);
if($res_name=@pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $full_name=$res_name["full_name"];
    $dd_C_REGIS=$res_name["C_REGIS"];
    $dd_C_CARNAME=$res_name["C_CARNAME"];
    $dd_C_CARNUM=$res_name["C_CARNUM"];
    $dd_C_COLOR=$res_name["C_COLOR"];
    
    $qry_name=@pg_query("select * from \"Fc\" WHERE \"C_CARNUM\"='$dd_C_CARNUM'");
    if($res_name=@pg_fetch_array($qry_name)){
        $C_MARNUM=$res_name["C_MARNUM"];
    }
    
    $rs=pg_query("select \"cost_of_car_today\"('$IDNO')");
    $rt1=pg_fetch_result($rs,0);
    
?>
<table width="80%" cellpadding="5" cellspacing="5" border="0" bgcolor="#DDEEFF">
<tr align="left">
    <td width="20%"><b>ผู้เช่า</b></td><td width="30%"><?php echo "$full_name"; ?></td>
    <td width="20%"><b>เลขที่สัญญา</b></td><td width="30%"><?php echo "$IDNO"; ?></td>
</tr>
<tr align="left">
    <td><b>รายละเอียดรถ</b></td><td><?php echo "$dd_C_CARNAME"; ?></td>
    <td><b>เลขทะเบียน</b></td><td><?php echo "$dd_C_REGIS"; ?></td>
</tr>
<tr align="left">
    <td><b>เลขตัวถัง</b></td><td><?php echo "$dd_C_CARNUM"; ?></td>
    <td><b>เลขเครื่อง</b></td><td><?php echo "$C_MARNUM"; ?></td>
</tr>
<tr align="left">
    <td><b>สีรถ</b></td><td><?php echo "$dd_C_COLOR"; ?></td>
</tr>
</table>

<table width="80%" cellpadding="5" cellspacing="5" border="0" bgcolor="#FFFFDD">
<tr align="left">
    <td width="20%"><b>รายการต้นทุน</b></td>
    <td width="80%"><?php echo number_format($rt1,2); ?></td>
</tr>
</table>
<?php
}else{
    //echo "ไม่พบข้อมูล";
}
}
?>
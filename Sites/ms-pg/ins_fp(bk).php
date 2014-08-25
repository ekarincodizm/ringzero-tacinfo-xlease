<?php
set_time_limit (0); 
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>list fp</title>
</head>

<body>

<table width="100%" border="1">
  <tr>
    <td width="53">IDNO</td>
    <td width="84">TranIDRef1</td>
    <td width="84">TranIDRef2</td>
    <td width="82">P_STDATE</td>
    <td width="76">P_MONTH</td>
    <td width="50">P_VAT</td>
    <td width="69">P_TOTAL</td>
    <td width="69">P_DOWN</td>
    <td width="101">P_Vatoffdown</td>
    <td width="70">P_BEGIN</td>
    <td width="77">P_FDATE</td>
    <td width="96">CusID</td>
    <td width="91">CarID</td>
  </tr>
  <?php

	 $sql_in=mssql_query("SELECT  A.*,convert(varchar,A.P_STDATE,111) AS STDATE,convert(varchar,A.P_FDATE,111) AS FDATE, A.IDNO AS fp_idno, B.IDNO AS fa1_idno, B.A_NAME, C.IDNO AS fc_idno,D.CusID, D.o_name, E.car_id
								  FROM         Fp A LEFT OUTER JOIN
						  Fa1 B ON A.IDNO = B.IDNO LEFT OUTER JOIN
						  Fc C ON A.IDNO = C.IDNO LEFT OUTER JOIN
						  fill_CusID D ON B.A_NAME = D.o_name LEFT OUTER JOIN
						  fill_CarID E ON C.C_CARNUM = E.car_number 
						  WHERE A.IDNO!='' " ,$conn);
	 while($res_fp=mssql_fetch_array($sql_in))
	  {
	    $res_id=$res_fp["IDNO"];
	    $sql_fa1=mssql_query("SELECT DISTINCT A.IDNO, B.o_name, B.CusID
                              FROM   Fa1 A LEFT OUTER JOIN
                      fill_CusID B ON B.o_name = A.A_NAME 
					  WHERE ")
	    
  ?>
  
  <tr>
    <td><?php echo $res_fp["fp_idno"]; ?></td>
    <td><?php echo $res_fp["TranIDRef1"]; ?></td>
    <td><?php echo $res_fp["TranIDRef2"]; ?></td>
    <td><?php echo $res_fp["STDATE"]; ?></td>
    <td><?php echo $res_fp["P_MONTH"]; ?></td>
    <td><?php echo $res_fp["P_VAT"]; ?></td>
    <td><?php echo $res_fp["P_TOTAL"]; ?></td>
    <td><?php echo $res_fp["P_DOWN"]; ?></td>
    <td><?php echo $res_fp["P_VatOfDown"]; ?></td>
    <td><?php echo $res_fp["P_BEGIN"]; ?></td>
    <td><?php echo $res_fp["FDATE"]; ?></td>
    <td><?php echo $res_fp["CusID"]; ?></td>
    <td><?php echo $res_fp["car_id"]; ?></td>
  </tr>
    <?php
     
	 $str_idno=substr($res_fp["fp_idno"],0,1);
	 if($str_idno=="6")
	 {
	   $id_asset="2";
	 }
	 else
	 {
	   $id_asset="1";
	 }
	 
	 
	 
$ins_fp="insert into \"Fp\" (\"IDNO\",\"CusID\",\"P_STDATE\",\"TranIDRef1\",\"TranIDRef2\",
                     \"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",
	 			     \"P_VatOfDown\",\"P_VAT\",\"LockContact\",asset_type,asset_id,\"ComeFrom\"
					 ) 
                     values  
                    ('$res_fp[fp_idno]','$res_fp[CusID]','$res_fp[STDATE]','$res_fp[TranIDRef1]'
					,'$res_fp[TranIDRef2]'
					,'$res_fp[P_DOWN]','$res_fp[P_TOTAL]','$res_fp[P_MONTH]','$res_fp[FDATE]'
					,'$res_fp[P_BEGIN]'
                    ,'$res_fp[P_VatOfDown]','$res_fp[P_VAT]',FALSE,'$id_asset','$res_fp[car_id]','TA')";

  if($result_fp=pg_query($ins_fp))
  {
    $st= "ok";
  }
  else
  {
    $st= "error at ".$ins_fp;
  }
  
 
  // echo $st;
  }
  ?>
</table>

</body>
</html>

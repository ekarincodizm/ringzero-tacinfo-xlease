<?php
	set_time_limit(0);
	include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เช็คชื่อที่ไม่เหมือนกัน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<center><h1>ตรวจสอบ CusID ในตาราง ContactCus และตาราง Fp</h1></center>
<table cellSpacing="1" cellPadding="3" border="0" bgcolor="#D8D8D8" width="60%" align="center">
	<tr align="center"><th>ลำดับ</th><th>IDNO</th><th>CusState</th><th>CusID-ContactCus</th><th>CusID-Fp</th><th>Name-ContactCus</th><th>Name-Fp</th></tr>
<?php
	//ดึง IDNO ออกมาทั้งหมดเพื่อนำไปตรวจสอบ
	$query_idno = pg_query("select a.\"IDNO\" AS idno1,a.\"CusState\",a.\"CusID\" AS cusid1,b.\"IDNO\" AS idno2,b.\"CusID\" AS cusid2 from \"ContactCus\" a
					left join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
					where a.\"CusState\" = '0' and (a.\"CusID\" <> b.\"CusID\")");
	$i=1;
	while($res_name=pg_fetch_array($query_idno)){
		$IDNO1=$res_name["idno1"];
		$IDNO2=$res_name["idno2"];
		$CusState=$res_name["CusState"];
		$CusID1=$res_name["cusid1"];
		$CusID2=$res_name["cusid2"];
		
		//CusID ของตาราง  ContactCus
		$query_name = pg_query("select \"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\" from \"Fa1\" where \"CusID\" = '$CusID1'");
		$res_namecus = pg_fetch_array($query_name);
		$A_FIRNAME1 = $res_namecus["A_FIRNAME"];
		$A_NAME1 = $res_namecus["A_NAME"];
		$A_SIRNAME1 = $res_namecus["A_SIRNAME"];
		$namecus1 = $A_FIRNAME1.$A_NAME1." ".$A_SIRNAME1;
		
		//CusID ของตาราง  Fp
		$query_name2 = pg_query("select \"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\" from \"Fa1\" where \"CusID\" = '$CusID2'");
		$res_namecus2 = pg_fetch_array($query_name2);
		$A_FIRNAME2 = $res_namecus2["A_FIRNAME"];
		$A_NAME2 = $res_namecus2["A_NAME"];
		$A_SIRNAME2 = $res_namecus2["A_SIRNAME"];
		$namecus2 = $A_FIRNAME2.$A_NAME2." ".$A_SIRNAME2;
		
		if($i%2 == '0'){
			$color="#FFFFFF";
		}else{
			$color="CCFFFF";
		}
		?>
						
		<tr align="center" bgcolor="<?php echo $color;?>"><td><?php echo $i;?></td><td><?php echo $IDNO1;?></td><td><?php echo $CusState;?></td><td bgcolor="#FFEAFF"><?php echo $CusID1;?></td><td bgcolor="#FFEAFF"><?php echo $CusID2;?></td><td align="left"><?php echo $namecus1;?></td><td align="left"><?php echo $namecus2;?></td></tr>
						
		<?php	
		$i++;
	}
?>
</table>
</body>
</html>
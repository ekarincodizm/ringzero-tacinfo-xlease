<?php
	set_time_limit(0);
	include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เช็คชื่อซ้ำ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table cellSpacing="1" cellPadding="3" border="0" bgcolor="#D8D8D8" width="40%" align="center">
	<tr align="center"><th>IDNO</th><th>CusState</th><th>CusID</th><th>ชื่อ - สกุล</th></tr>
<?php
	//ดึง IDNO ออกมาทั้งหมดเพื่อนำไปตรวจสอบ
	$query_idno = pg_query("SELECT \"IDNO\" FROM \"ContactCus\" GROUP BY \"IDNO\" ");
	while($res_name=pg_fetch_array($query_idno)){
		$IDNO=$res_name["IDNO"];
		
		//ตรวจสอบหา CusID ที่ซ้ำกัน ถ้ามีให้แสดงข้อมูล
		$query_cusid = pg_query("SELECT \"CusID\" FROM \"ContactCus\" where \"IDNO\" = '$IDNO' and \"CusID\" != '' GROUP BY \"CusID\" Having COUNT(\"CusID\") > 1 ");
		$num_row = pg_num_rows($query_cusid);
		
		if($num_row > 0){
		$i=1;
			while($res_cusid = pg_fetch_array($query_cusid)){
				$CusID=$res_cusid["CusID"];
				//แสดงข้อมูลที่ซ้ำกัน
				
					$query_show = pg_query("SELECT * FROM \"ContactCus\" where \"IDNO\" = '$IDNO' and \"CusID\" = '$CusID' order by \"CusState\"");
					$color1 = "#CCFFFF";
					$color2 = "#FFFFFF";
					
					while($res_show = pg_fetch_array($query_show)){
						$IDNO2 = $res_show["IDNO"];
						$CusState = $res_show["CusState"];
						$CusID2 = $res_show["CusID"];
					
						$query_name = pg_query("select \"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\" from \"Fa1\" where \"CusID\" = '$CusID2'");
						$res_namecus = pg_fetch_array($query_name);
						$A_FIRNAME = $res_namecus["A_FIRNAME"];
						$A_NAME = $res_namecus["A_NAME"];
						$A_SIRNAME = $res_namecus["A_SIRNAME"];
						$namecus = $A_FIRNAME.$A_NAME." ".$A_SIRNAME;
						
						if($IDNO3 == ""){
							//$color1 = "red";
							$color = "$color1";
						}else{
							if($IDNO3 == $IDNO2){
								//$color1 = "#FFFFFF";
								$color = "$colorold";	
								}else{
								if($colorold == $color1){
									$color = $color2;
								}else{
									$color = $color1;
								}
								
							}

						}
						?>
						
						<tr align="center" bgcolor="<?php echo $color;?>"><td><?php echo $IDNO2;?></td><td><?php echo $CusState;?></td><td><?php echo $CusID2;?></td><td align="left"><?php echo $namecus;?></td></tr>
						
					<?php
					$IDNO3 = $IDNO2;
					$colorold=$color;
					
					}
				$i++;		
			}
		}
	}
?>
</table>
</body>
</html>
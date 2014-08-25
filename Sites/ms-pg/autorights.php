<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php

$conn_string = "host=172.16.2.5  port=5432 dbname=devxleasenw user=dev	password=nextstep";
$db_connect = pg_connect($conn_string) or die("can't connect");

// สร้าง ARRAY ของ สิทธิ์ ** ขั้นต่ำสุด*** ที่จะให้แต่ละกลุ่ม/แผนก/ฝ่าย
$rights = array(
				"ACC" => array("P05","U01","AC15","AC20","AC08","AC18","AC19","AC16","AC07","AC10","P04","SA02","AC11","SA01","SA04","SA03"),
				"ACS" => array("P05","U01","P10","TR03","C08","P18"),
				"AD" => array("P05","U01"),
				"AUD" => array("P05","U01","CR01","C04","C05","C11","SA03"),
				"CAS" => array("P05","U01","AC03","C01","C03","C04","C05","C11","C09","AC36","C02"),
				"CHI" => array("P05","U01"),
				"CHO" => array("P05","U01"),
				"CRE" => array("P05","U01"),
				"INS" => array("P05","U01","N12","N15","N01","N08","N11","N10","L01"),
				"IT" => array(),
				"OTH" => array("P05","U01"),
				"REG" => array("P05","U01","R01","CR01","C05"),
				"SAL" => array("P05","U01"),
				"TAC" => array("P05","U01"),
				"UNK" => array()
				);
	// นำเข้ารายละเอียด user-รหัส-กลุ่ม
     $sql_command="
		SELECT 
		  fuser.user_group, 
		  fuser.id_user,
		  fuser.fullname
		FROM 
		  public.fuser
	 ";
	 $tmp=pg_query($sql_command);
	 
	 //  INSERT สิทธิ์ กระจายตามแผนก/ฝ่าย/กลุ่ม ที่อยู่ ตาม ARRAY ข้างต้น
	 while($row = pg_fetch_array($tmp))
	 {
		if($row['user_group']=='ACC'){
			$count = count($rights['ACC']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['ACC'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: ACC (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='ACS'){
			$count = count($rights['ACS']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['ACS'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: ACS (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='AD'){
			$count = count($rights['AD']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['AD'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: AD (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='AUD'){
			$count = count($rights['AUD']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['AUD'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: AUD (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='CAS'){
			$count = count($rights['CAS']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['CAS'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: CAS (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='CHI'){
			$count = count($rights['CHI']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['CHI'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: CHI (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='CHO'){
			$count = count($rights['CHO']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['CHO'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: CHO (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='CRE'){
			$count = count($rights['CRE']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['CRE'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: CRE (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='INS'){
			$count = count($rights['INS']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['INS'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: INS (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='IT'){
			$count = count($rights['IT']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['IT'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: IT (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='OTH'){
			$count = count($rights['OTH']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['OTH'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: OTH (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='REG'){
			$count = count($rights['REG']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['REG'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: REG (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='SAL'){
			$count = count($rights['SAL']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['SAL'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed: SAL (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='TAC'){
			$count = count($rights['TAC']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					INSERT INTO f_usermenu(
								id_menu, id_user)
						VALUES ('".$rights['TAC'][$i]."', '".$row['id_user']."');
				 ";
				 pg_query($sql_command);
				 echo "Executed TAC : (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
		else if($row['user_group']=='UNK'){
			$count = count($rights['UNK']);
			for($i=0;$i<$count;$i++){
				 $sql_command="
					DELETE FROM f_usermenu
					 WHERE f_usermenu.id_user='".$row['id_user']."'
				 ";
				 pg_query($sql_command);
				 echo "Executed UNK : (".$row['fullname'].") : ".$sql_command." <br>";
			}
		}
	 }
	 echo "Finished";
?>
<button onclick="javascript:window.close();">CLOSE</button>
</body>
</html>

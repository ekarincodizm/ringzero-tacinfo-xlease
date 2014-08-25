<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<title></title>
</head>
<body>

<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#CCCCCC">
	<tr align="center">
		<td height="30">เลขที่สัญญาเก่า</td>
		<td bgcolor="#F4CEFD">เลขที่สัญญาใหม่</td>
		<td>ผู้เช่าซื้อเก่า</td>
		<td bgcolor="#F4CEFD">ผู้เช่าซื้อใหม่</td>
		<td>ผู้ค้ำเก่า</td>
		<td bgcolor="#F4CEFD">ผู้ค้ำใหม่</td>
	</tr>
	<?php 
	$query = pg_query("select a.\"IDNO\" as idnoa, b.\"IDNO\" as idnob from pmain.\"fp\" a full outer join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\" "); //where a.\"IDNO\" = '110-04041' or a.\"IDNO\" = '110-04042'
	
	while($result = pg_fetch_array($query)){
		$idno_old = $result["idnoa"];
		$idno_new = $result["idnob"];
		
		/*#####################หาผู้เช่าซื้อตารางเก่า###########################*/
		$query_cusold = pg_query("select \"A_NAME\" from Pmain.\"fa1\" where \"IDNO\" = '$idno_old'");
		$num_cusold = pg_num_rows($query_cusold);
		if($num_cusold == 0){
			$name_old = "";
		}else{
			$resultcusold = pg_fetch_array($query_cusold);
			$name_old = $resultcusold["A_NAME"];
			$name_old2 = explode(" ",$name_old);
			for($i = 0;$i<sizeof($name_old2);$i++){
				$nameold = $nameold.$name_old2[$i];
				$nameold = trim($nameold);
			}
			
		}

		/*#####################หาผู้เช่าซื้อตารางใหม่###########################*/
		$query_cusnew = pg_query("select b.\"A_NAME\",b.\"A_SIRNAME\" from \"ContactCus\"  a
		left join \"Fa1\" b on a.\"CusID\" = b.\"CusID\" where a.\"IDNO\" = '$idno_new' and a.\"CusState\" = '0' ");
		$num_cusnew = pg_num_rows($query_cusnew);
		if($num_cusnew == 0){
			$txtnamenew = "";
		}else{
			$resultcusnew = pg_fetch_array($query_cusnew);
			$name_f = $resultcusnew["A_NAME"];
			$name_l = $resultcusnew["A_SIRNAME"];
			$txtnamenew = $name_f.$name_l;
			//$name_new = trim($name_f).$name_l;
			//$name_new = trim($name_new);
			$name_new2 = explode(" ",$txtnamenew);
			for($h = 0;$h<sizeof($name_new2);$h++){
				$name_new = $name_new.$name_new2[$h];
				$name_new = trim($name_new);
			}
			
		}
		
		/*#####################เปรียบเทียบผู้ค้ำ หาผู้ค้ำจากตารางเก่า###########################*/
			$query_co_old = pg_query("select \"A2_NAME\",\"A2_STATE\" from Pmain.\"fa2\" where \"IDNO\" = '$idno_old' order by \"A2_STATE\"");
			$num_co_old = pg_num_rows($query_co_old);
			if($num_co_old == 0){
				$nameco = "";
			}else{
			$p=0;
				while($resco_old = pg_fetch_array($query_co_old)){
					$name_co_old = $resco_old["A2_NAME"];
					$state_co_old = $resco_old["A2_STATE"];
					$name_co_old2 = explode(" ",$name_co_old);
					for($j = 0;$j<sizeof($name_co_old2);$j++){ //ชื่อของผู้ค้ำเก่าจะได้มา 1 ชื่อ
						$namecoold = $namecoold.$name_co_old2[$j];
						$namecoold = trim($namecoold);
					}
						
					//เอาชื่อผู้ค้ำมาต่อกัน
					$nameco =$nameco.$state_co_old.".".$name_co_old."<br>";
					
					$nameco_old[$p] = $namecoold; //เก็บชื่อผู้ค้ำไว้ใน Array
					$stateco_old[$p] = $state_co_old;
					
					$namecoold="";
					$p++;
				}
			}
				
		/*#####################เปรียบเทียบผู้ค้ำ หาผู้ค้ำจากตารางใหม่###########################*/
			$query_co_new = pg_query("select b.\"A_NAME\",b.\"A_SIRNAME\",a.\"CusState\" from \"ContactCus\"  a left join \"Fa1\" b on a.\"CusID\" = b.\"CusID\" where a.\"IDNO\" = '$idno_new' and a.\"CusState\" != '0' order by a.\"CusState\"");
			$num_co_new = pg_num_rows($query_co_new);
			if($num_co_new == 0){
				$nameconew2="";
			}else{
				$t=0;
				while($resco_new = pg_fetch_array($query_co_new)){
					$name_fco = $resco_new["A_NAME"]; //ชื่อของผู้ค้ำใหม่
					$name_lco = $resco_new["A_SIRNAME"];
					$CusState = $resco_new["CusState"];
					$txtnameco = $name_fco.$name_lco;
					$nameconew = trim($name_fco).$name_lco;
					//$nameconew = trim($nameconew);
					$name_conew = explode(" ",$nameconew);
					$state_co_new = $resco_new["CusState"];	
					
					for($h = 0;$h<sizeof($name_conew);$h++){ //ชื่อของผู้ค้ำเก่าจะได้มา 1 ชื่อ
						$name_conew2 = $name_conew2.$name_conew[$h];
						$name_conew2 = trim($name_conew2);
					}
					
					//เอาชื่อผู้ค้ำมาต่อกัน
					$nameconew2 =$nameconew2.$CusState.".".$txtnameco."<br>";	
						
					$nameco_new[$t] = $name_conew2; //เก็บชื่อผู้ค้ำไว้ใน Array
					$stateco_new[$t] = $state_co_new;
					
					$name_conew2="";
					$t++;
				}
			}
			
		/*#############################*************************** เปรียบเทียบเพื่อให้แสดงค่า ****************************#################################*/
		//if($num_cusold == 0 || $num_cusnew == 0){ //กรณีผู้เช่าซื้อเป็น 0
			
		//}else{ //กรณีผู้เช่าซื้อไม่เป็น 0
			if($nameold == $name_new){ //เปรียบเทียบว่าผู้เช่าซื้อเก่ากับใหม่เหมือนกันหรือไม่
				
				if($num_co_old == $num_co_new){ //กรณีผู้ค้ำเท่ากัน
					$x=0;
					while($x<sizeof($nameco_old)){
						$nameco_old2 = trim($nameco_old[$x]);
						$nameco_new2 = trim($nameco_new[$x]);
						
						if($nameco_old2 == $nameco_new2){ //กรณีมีจำนวนผู้ค้ำเท่ากัน ถ้าข้อมูลไม่เหมือนกันให้เก็บชื่อไว้แสดงค่า
							$status = $status + 0;
						}else{
							$status = $status + 1;
						}
					$x++;	
					}
					
					if($status > 0 and $nameold != 0){
						//echo $status;
						echo "<tr>";
						echo "<td align=center valign=top bgcolor= #F4F4F4>$idno_old</td>";
						echo "<td align=center valign=top bgcolor=#E9E4ED>$idno_new</td>";
						echo "<td valign=top align=center bgcolor= #F4F4F4>มีค่าเหมือนกัน</td>";
						echo "<td valign=top align=center bgcolor=#E9E4ED>มีค่าเหมือนกัน</td>";
						echo "<td valign=top bgcolor= #F4F4F4>$nameco</td>";
						echo "<td valign=top bgcolor=#E9E4ED>$nameconew2</td>";
						echo "</tr>";	
					}else{
						//เหมือนกันทั้งผู้เช่าซื้อและผู้ค้ำ ไม่ต้องแสดงค่า
					}
				}else{
					// กรณีจำนวนผู้ค้ำไม่เท่ากันให้แสดงค่าเลยไม่ต้องเช็ค เดี๋ยวผู้ใช้ระบบจะเช็คข้อมูลเอง
					echo "<tr>";
					echo "<td align=center valign=top bgcolor= #F4F4F4>$idno_old</td>";
					echo "<td align=center valign=top bgcolor=#E9E4ED>$idno_new</td>";
					echo "<td valign=top align=center bgcolor= #F4F4F4>มีค่าเหมือนกัน</td>";
					echo "<td valign=top align=center bgcolor=#E9E4ED>มีค่าเหมือนกัน</td>";
					echo "<td valign=top bgcolor= #F4F4F4>$nameco</td>";
					echo "<td valign=top bgcolor=#E9E4ED>$nameconew2</td>";
					echo "</tr>";	
				}
				
			}else{  // กรณีชื่อผู้เช่าซื้อไม่เหมือนกัน ตรวจสอบต่อว่าผู้ค้ำเท่ากันหรือไม่ ถ้าไม่เท่าให้แสดงค่า ถ้าเท่าไม่ต้องแสดง
				if($num_co_old == $num_co_new){ //กรณีผู้ค้ำเท่ากัน
					$x=0;
					while($x<sizeof($nameco_old)){
						$nameco_old2 = trim($nameco_old[$x]);
						$nameco_new2 = trim($nameco_new[$x]);
						
						if($nameco_old2 == $nameco_new2){ //กรณีมีจำนวนผู้ค้ำเท่ากัน ถ้าข้อมูลไม่เหมือนกันให้เก็บชื่อไว้แสดงค่า
							$status = $status + 0;
						}else{
							$status = $status + 1;
						}
					$x++;	
					}
					
					if($status > 0 and $nameold != 0){
						echo "<tr>";
						echo "<td align=center valign=top bgcolor= #F4F4F4>$idno_old</td>";
						echo "<td align=center valign=top bgcolor=#E9E4ED>$idno_new</td>";
						echo "<td valign=top bgcolor= #F4F4F4>$name_old</td>";
						echo "<td valign=top bgcolor=#E9E4ED>$txtnamenew</td>";
						echo "<td valign=top bgcolor= #F4F4F4>$nameco</td>";
						echo "<td valign=top bgcolor=#E9E4ED>$nameconew2</td>";
						echo "</tr>";	
					}else{
						echo "<tr>";
						echo "<td align=center valign=top bgcolor= #F4F4F4>$idno_old</td>";
						echo "<td align=center valign=top bgcolor=#E9E4ED>$idno_new</td>";
						echo "<td valign=top bgcolor= #F4F4F4>$name_old</td>";
						echo "<td valign=top bgcolor=#E9E4ED>$txtnamenew</td>";
						if($num_co_old == 0){
							echo "<td valign=top align=center bgcolor= #F4F4F4></td>";
						}else{
							echo "<td valign=top align=center bgcolor= #F4F4F4>มีค่าเหมือนกัน</td>";
						}
						if($num_co_new == 0){
							echo "<td valign=top align=center bgcolor=#E9E4ED></td>";
						}else{
							echo "<td valign=top align=center bgcolor=#E9E4ED>มีค่าเหมือนกัน</td>";
						}
						echo "</tr>";
					}
				}else{
					// กรณีจำนวนผู้ค้ำไม่เท่ากันให้แสดงค่าเลยไม่ต้องเช็ค เดี๋ยวผู้ใช้ระบบจะเช็คข้อมูลเอง
					echo "<tr>";
					echo "<td align=center valign=top bgcolor= #F4F4F4>$idno_old</td>";
					echo "<td align=center valign=top bgcolor=#E9E4ED>$idno_new</td>";
					echo "<td valign=top bgcolor= #F4F4F4>$name_old</td>";
					echo "<td valign=top bgcolor=#E9E4ED>$txtnamenew</td>";
					echo "<td valign=top bgcolor= #F4F4F4>$nameco</td>";
					echo "<td valign=top bgcolor=#E9E4ED>$nameconew2</td>";
					echo "</tr>";	
				
				}
				
			}
		//}
		$nameold ="";
		$name_new = "";
		$nameco = "";
		$nameconew2 = "";
		$status = 0;
		$nameco_old = "";
	} //end while
	?>
</table>

</body>
</html>
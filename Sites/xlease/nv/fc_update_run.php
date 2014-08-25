<?php
set_time_limit(0);
session_start();
include("../config/config.php");
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
function fc_run($search)
{
  $query = 'SELECT "b"."CarID" as b_CarID,"a"."IDNO" as o_IDNO,"b"."IDNO" as b_IDNO ,"a"."C_CARNAME" as o_C_CARNAME,"b"."C_CARNAME" as b_C_CARNAME ,
  			"a"."C_YEAR" as o_C_YEAR,"b"."C_YEAR" as b_C_YEAR ,"a"."C_REGIS" as o_C_REGIS ,"b"."C_REGIS" as b_C_REGIS,
			 "a"."C_REGIS_BY" as o_C_REGIS_BY , "b"."C_REGIS_BY" as b_C_REGIS_BY , 
		   "a"."C_COLOR" as o_C_COLOR,"b"."C_COLOR" as b_C_COLOR, 
		   "a"."C_CARNUM" as o_C_CARNUM ,"b"."C_CARNUM" as b_C_CARNUM, "a"."C_MARNUM" as o_C_MARNUM ,"b"."C_MARNUM" as b_C_MARNUM,
		   "a"."C_Milage"as o_C_Milage ,"b"."C_Milage" as b_C_Milage , "a"."C_TAX_MON" as o_C_TAX_MON , "b"."C_TAX_MON" as b_C_TAX_MON
           FROM "pmain"."fc" as a left join 
		   (SELECT "a1"."CarID","a1"."C_CARNAME","a1"."C_YEAR","a1"."C_REGIS","a1"."C_REGIS_BY","a1"."C_COLOR","a1"."C_CARNUM","a1"."C_MARNUM","a1"."C_Milage","a1"."C_TAX_MON","a2"."IDNO" 
           FROM "public"."VCarregistemp" as a1 left join "public"."Fp" as a2 ON "a1"."IDNO" = "a2"."IDNO" ) as b ON "a"."IDNO"="b"."IDNO"
		   WHERE "a"."IDNO" LIKE \'%'.$search.'%\' and "b"."CarID" != \'\' 
		   ORDER BY "a"."IDNO" ASC';
		   
		
			//echo $query;
			$sql_query = pg_query($query);
			$num_row = pg_num_rows($sql_query);
			
			echo "แก้ไขสัญญาที่ขึ้นต้นด้วย : ".$search."  จำนวนทั้งหมด <font color=#FABEC2>$num_row</font> ข้อมูล<br><br>";
			
			
			while($sql_row = pg_fetch_array($sql_query))
			{
				$o_IDNO 		= 	$sql_row[o_idno];
				$o_C_REGIS		=	$sql_row[o_c_regis];
				$o_C_CARNAME 	= 	$sql_row[o_c_carname];
				$o_C_YEAR		=	$sql_row[o_c_year];
				$o_C_REGIS_BY 	= 	$sql_row[o_c_regis_by];
				$o_C_COLOR		=	$sql_row[o_c_color];
				$o_C_CARNUM 	= 	$sql_row[o_c_carnum];
				$o_C_MARNUM		=	$sql_row[o_c_marnum];
				$o_C_Milage		= 	$sql_row[o_c_milage];
				$o_C_TAX_MON 	= 	$sql_row[o_c_tax_mon];
				
				$b_CarID 		= 	$sql_row[b_carid];
				$b_IDNO 		= 	$sql_row[b_idno];
				$b_C_REGIS		=	$sql_row[b_c_regis];
				$b_C_CARNAME 	= 	$sql_row[b_c_carname];
				$b_C_YEAR		=	$sql_row[b_c_year];
				$b_C_REGIS_BY 	= 	$sql_row[b_c_regis_by];
				$b_C_COLOR		=	$sql_row[b_c_color];
				$b_C_CARNUM 	= 	$sql_row[b_c_carnum];
				$b_C_MARNUM		=	$sql_row[b_c_marnum];
				$b_C_Milage		= 	$sql_row[b_c_milage];
				$b_C_TAX_MON 	= 	$sql_row[b_c_tax_mon];
				
				
				//ระบบเก่ามีการ update Fc ดังนั้นจึงหาว่าเลขทะเบียนนี้ปัจจุบันอยู่กับใครสำหรับบันทึกเป็นประวัติในการแก้ไข
				$qrycarnow=pg_query("select \"IDNO\" from \"Fp\" a
				left join \"Fc\" b on a.asset_id=b.\"CarID\" where \"CarID\"='$b_CarID' order by \"P_STDATE\" DESC limit 1");
				$rescarnow=pg_fetch_array($qrycarnow);
				list($idnonow)=$rescarnow;
				
				if($idnonow==$b_IDNO){
					// อัพเดทข้อมูลใหม่
					$query2 = 'UPDATE "Fc"
									SET "C_CARNAME"='."'".$o_C_CARNAME."'".', "C_YEAR"='."'".$o_C_YEAR."'".', 
									"C_REGIS"='."'".$o_C_REGIS."'".', "C_REGIS_BY"='."'".$o_C_REGIS_BY."'".', 
									"C_COLOR"='."'".$o_C_COLOR."'".', "C_CARNUM"='."'".$o_C_CARNUM."'".', 
									"C_MARNUM"='."'".$o_C_MARNUM."'".', "C_Milage"='."'".$o_C_Milage."'".', 
									"C_TAX_MON"='."'".$o_C_TAX_MON."'".
									'WHERE "CarID" = '."'".$b_CarID."'".'';
					$sql_query2 = pg_query($query2);
				}
				
				//เก็บประวัติการบันทึกข้อมูล
				$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
					\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
					\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
					\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act) 
				select 
					\"IDNO\",'$o_C_REGIS', '$o_C_CARNAME', '$o_C_YEAR', '$o_C_REGIS_BY',
					'$o_C_COLOR', '$o_C_CARNUM', '$o_C_MARNUM', '$o_C_Milage', \"C_TAX_ExpDate\",
					'$o_C_TAX_MON', \"C_StartDate\",'$b_CarID', '$add_user', '$add_date', \"C_CAR_CC\", 
					\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act from \"Carregis_temp\" where \"IDNO\"='$b_IDNO' order by auto_id DESC limit 1";
			
				if($result_carregis=pg_query($in_carregis)){
				}else{
					$status++;
				}
				echo $b_IDNO.'('.$b_CarID.') : '.$o_C_CARNAME.'; '.$o_C_YEAR.'; '.$o_C_REGIS.'; '.$o_C_REGIS_BY.'; '.$o_C_COLOR.'; '.$o_C_CARNUM.'; '.$o_C_MARNUM.'; '.$o_C_Milage.'; '.$o_C_TAX_MON.' => updated </br>';
			}
			echo "-------------------------------------------------------------------------<br><br>";
			
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายการที่ไม่ตรงกัน</title>

<script type="text/javascript">  
function popup(url,name,windowWidth,windowHeight){       
    myleft=(screen.width)?(screen.width-windowWidth)/2:100;    
    mytop=(screen.height)?(screen.height-windowHeight)/2:100;      
    properties = "width="+windowWidth+",height="+windowHeight;   
    properties +=",scrollbars=yes, top="+mytop+",left="+myleft;      
    window.open(url,name,properties);   
}   
</script> </head><body bgcolor="#F5F5F5">
<center>
<div class="form_description">
				<h2>อัพเดทรายการที่ไม่ตรงกัน... </h2>

					</div>

  <?php
  //$connection = pg_connect("host=172.16.2.5 port=5432 dbname=devxleasenw user=dev password=nextstep") or die ("Not Connect PostGres");

  // รันจากเก่ามาใหม่เพื่อให้ไล่ update ข้อมูลรถให้หมด
  fc_run("115-");
  fc_run("116-");
  fc_run("117-");
  fc_run("118-");
  fc_run("119-");
  fc_run("110-");
  fc_run("111-");
  fc_run("112-");
  fc_run("113-");
  fc_run("114-"); // สัญญาปีล่าสุด

?>
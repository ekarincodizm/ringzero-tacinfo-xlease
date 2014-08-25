
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
				<h2>รายการที่ไม่ตรงกัน </h2>

					</div>

  <?php
  
  //$connection = pg_connect("host=172.16.2.5 port=5432 dbname=devxleasenw user=dev password=nextstep") or die ("Not Connect PostGres");
 // $id = "110-01013";
 include("../config/config.php");
  $id = pg_escape_string($_REQUEST[IDNO]);
  $query = 'SELECT "b"."CarID" as b_CarID,"a"."IDNO" as o_IDNO,"b"."IDNO" as b_IDNO ,"a"."C_CARNAME" as o_C_CARNAME,"b"."C_CARNAME" as b_C_CARNAME ,
  			"a"."C_YEAR" as o_C_YEAR,"b"."C_YEAR" as b_C_YEAR ,"a"."C_REGIS" as o_C_REGIS ,"b"."C_REGIS" as b_C_REGIS,
			 "a"."C_REGIS_BY" as o_C_REGIS_BY , "b"."C_REGIS_BY" as b_C_REGIS_BY , 
		   "a"."C_COLOR" as o_C_COLOR,"b"."C_COLOR" as b_C_COLOR, 
		   "a"."C_CARNUM" as o_C_CARNUM ,"b"."C_CARNUM" as b_C_CARNUM, "a"."C_MARNUM" as o_C_MARNUM ,"b"."C_MARNUM" as b_C_MARNUM,
		   "a"."C_Milage"as o_C_Milage ,"b"."C_Milage" as b_C_Milage , "a"."C_TAX_MON" as o_C_TAX_MON , "b"."C_TAX_MON" as b_C_TAX_MON
           FROM "pmain"."fc" as a left join 
		   (SELECT "a1"."CarID","a1"."C_CARNAME","a1"."C_YEAR","a1"."C_REGIS","a1"."C_REGIS_BY","a1"."C_COLOR","a1"."C_CARNUM","a1"."C_MARNUM","a1"."C_Milage","a1"."C_TAX_MON","a2"."IDNO" 
           FROM "public"."VCarregistemp" as a1 left join "public"."Fp" as a2 ON "a1"."IDNO" = "a2"."IDNO" ) as b ON "a"."IDNO"="b"."IDNO" 
		   WHERE "a"."IDNO"='."'".$id."'".' and "b"."IDNO"='."'".$id."'";
		   
			
		
			//echo $query;
				$sql_query = pg_query($query);
				
				$num_row = pg_num_rows($sql_query);
				
				echo "จำนวนทั้งหมด <font color=#FABEC2>$num_row</font> ข้อมูล<br><br>";
				
				
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
				
				 ?>
                
                

<table border="1" align="center" cellspacing="0" bgcolor=white>

  <tr bgcolor="#CCCCFF">
    <td><div align="center">&nbsp</div></td>
      <td><div align="center"><strong>ระบบใหม่</strong></div></td>
      <td><div align="center"><strong>ระบบเก่า</strong></div></td>
  </tr>
  <tr>
     <td <?php if($o_IDNO!=$b_IDNO){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>เลขที่สัญญา</strong></div></td>
     <td <?php if($o_IDNO!=$b_IDNO){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_IDNO.":".$b_CarID ?></div></td>
     <td <?php if($o_IDNO!=$b_IDNO){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_IDNO ?></div></td>
  </tr>
    <tr>
    <td <?php if($o_C_CARNAME!=$b_C_CARNAME){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>รุ่นยี่ห้อ</strong></div></td>
    <td <?php if($o_C_CARNAME!=$b_C_CARNAME){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_CARNAME ?></div></td>
    <td <?php if($o_C_CARNAME!=$b_C_CARNAME){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_CARNAME ?></div></td>
  </tr>
    <tr>
     <td <?php if($o_C_YEAR!=$b_C_YEAR){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>ปีรถ</strong></div></td>
     <td <?php if($o_C_YEAR!=$b_C_YEAR){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_YEAR ?></div></td>
     <td <?php if($o_C_YEAR!=$b_C_YEAR){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_YEAR ?></div></td>
    </tr>
    <tr>
     <td <?php if($o_C_REGIS!=$b_C_REGIS){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>ทะเบียน</strong></div></td>
     <td <?php if($o_C_REGIS!=$b_C_REGIS){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_REGIS ?></div></td>
     <td <?php if($o_C_REGIS!=$b_C_REGIS){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_REGIS ?></div></td>
    </tr>
    <tr>
     <td <?php if($o_C_REGIS_BY!=$b_C_REGIS_BY){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>จังหวัดรถ</strong></div></td>
     <td <?php if($o_C_REGIS_BY!=$b_C_REGIS_BY){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_REGIS_BY ?></div></td>
     <td <?php if($o_C_REGIS_BY!=$b_C_REGIS_BY){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_REGIS_BY ?></div></td>
    </tr>
    <tr>
     <td <?php if($o_C_COLOR!=$b_C_COLOR){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>สีรถ</strong></div></td>
     <td <?php if($o_C_COLOR!=$b_C_COLOR){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_COLOR ?></div></td>
     <td <?php if($o_C_COLOR!=$b_C_COLOR){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_COLOR ?></div></td>
    </tr>
    <tr>
     <td <?php if($o_C_CARNUM!=$b_C_CARNUM){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>เลขตัวถัง</strong></div></td>
     <td <?php if($o_C_CARNUM!=$b_C_CARNUM){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_CARNUM ?></div></td>
     <td <?php if($o_C_CARNUM!=$b_C_CARNUM){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_CARNUM ?></div></td>
    </tr>
    <tr>
     <td <?php if($o_C_MARNUM!=$b_C_MARNUM){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>เลขเครื่อง</strong></div></td>
     <td <?php if($o_C_MARNUM!=$b_C_MARNUM){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_MARNUM ?></div></td>
     <td <?php if($o_C_MARNUM!=$b_C_MARNUM){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_MARNUM ?></div></td>
    </tr>
    <tr>
     <td <?php if($o_C_Milage!=$b_C_Milage){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>ไมค์</strong></div></td>
     <td <?php if($o_C_Milage!=$b_C_Milage){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_Milage ?></div></td>
     <td <?php if($o_C_Milage!=$b_C_Milage){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_Milage ?></div></td>
    </tr>
    <tr>
     <td <?php if($o_C_TAX_MON!=$b_C_TAX_MON){?>bgcolor="#FABEC2"<?php } ?>><div align="center"><strong>ภาษีรถ</strong></div></td>
     <td <?php if($o_C_TAX_MON!=$b_C_TAX_MON){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $b_C_TAX_MON ?></div></td>
     <td <?php if($o_C_TAX_MON!=$b_C_TAX_MON){?>bgcolor="#FABEC2"<?php } ?>><div align="left"><?Php print $o_C_TAX_MON ?></div></td>
    </tr>
    <tr>

 <?php } ?>
    
</table>

<br>
<input id="saveForm" class="button_text" type="button" value="ปิด" onclick="window.close()" style='width:100px; height:50px'/>
<?Php  
echo "</center>
			<div class=form_description></div>
		
</body>
</html>";
?>
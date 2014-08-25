
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
  include("../config/config.php");
  $query = 'SELECT "a"."IDNO","a"."C_REGIS" 
           FROM "pmain"."fc" as a left join 
		   (SELECT "a1"."C_CARNAME","a1"."C_YEAR","a1"."C_REGIS","a1"."C_COLOR","a1"."C_CARNUM","a1"."C_MARNUM","a1"."C_Milage","a1"."C_TAX_MON","a2"."IDNO" 
           FROM "public"."VCarregistemp" as a1 left join "public"."Fp" as a2 ON "a1"."IDNO" = "a2"."IDNO" ) as b ON "a"."IDNO"="b"."IDNO" 
		   WHERE "a"."C_CARNAME"!="b"."C_CARNAME" or "a"."C_YEAR"!="b"."C_YEAR" or "a"."C_REGIS"!="b"."C_REGIS" or 
		   "a"."C_COLOR"!="b"."C_COLOR" or "a"."C_CARNUM"!="b"."C_CARNUM" or "a"."C_MARNUM"!="b"."C_MARNUM" or 
		   "a"."C_TAX_MON"!="b"."C_TAX_MON" 
		   ORDER BY "a"."IDNO" ASC';
				$sql_query = pg_query($query);
				
				$num_row = pg_num_rows($sql_query);
				
				echo "จำนวนทั้งหมด <font color=red>$num_row</font> ข้อมูล<br><br>";
				
				 ?>
                
                

<table border="1" align="center" cellspacing="0" bgcolor=white>

  <tr bgcolor="#CCCCFF">
    <td><div align="center"><strong>ลำดับ</strong></div></td>
      <td><div align="center"><strong>เลขที่สัญญา</strong></div></td>
      <td><div align="center"><strong>ทะเบียนรถ</strong></div></td>
  </tr>
  <?php
  
$k=1;
				while($sql_row = pg_fetch_array($sql_query))
				{		
				
				$IDNO = $sql_row[IDNO];
				$C_REGIS	=	$sql_row[C_REGIS];
				
				?>
  <tr>
    <td><div align="center"><?Php print $k ?></div></td>
    <td><div align="center"><a href="fc_update_details.php?IDNO=<?Php print $IDNO ?>" target="_blank"><?Php print $IDNO ?></a></div></td>
    <td><div align="left"><?Php print $C_REGIS ?></div></td>
    
  </tr>
 <?php $k++;} ?>
    
</table>

<br>
<input id="saveForm" class="button_text" type="button" value="ปิด" onclick="window.close()" style='width:100px; height:50px'/>
<?Php  
echo "</center>
			<div class=form_description></div>
		
</body>
</html>";
?>
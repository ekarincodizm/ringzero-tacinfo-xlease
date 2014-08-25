
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
  include("../config/config.php");
  //$connection = pg_connect("host=172.16.2.5 port=5432 dbname=devxleasenw user=dev password=nextstep") or die ("Not Connect PostGres");
  
  $query = 'SELECT 
				"fotherpay"."IDNO",
				"fotherpay"."O_RECEIPT",
				"Fr"."R_DueNo"
				FROM 
				  "pmain"."fotherpay", 
				  "public"."Fr"
				WHERE 
				  "fotherpay"."O_RECEIPT" = "Fr"."R_Receipt" AND
				  "Fr"."R_DueNo" = 900 AND
				  "Fr"."R_Date" != "fotherpay"."O_DATE" AND
				  "Fr"."R_Money" != "fotherpay"."O_MONEY" AND
				  "Fr"."R_memo" != "fotherpay"."O_DESCRIPTION" AND
				  "fotherpay"."O_RECEIPT" LIKE \'%R%\'
				ORDER BY
				  "Fr"."R_DueNo"';
				  
				$sql_query = pg_query($query);
				
				$num_row = pg_num_rows($sql_query);
				
				echo "จำนวนทั้งหมด <font color=red>$num_row</font> ข้อมูล<br><br>";
				
				 ?>
                
                

<table border="1" align="center" cellspacing="0" bgcolor=white>

  <tr bgcolor="#CCCCFF">
    <td><div align="center"><strong>ลำดับ</strong></div></td>
      <td><div align="center"><strong>เลขที่สัญญา</strong></div></td>
      <td><div align="center"><strong>เลขที่ใบเสร็จ</strong></div></td>
  </tr>
  <?php
  
$k=1;
				while($sql_row = pg_fetch_array($sql_query))
				{		
				
				$IDNO = $sql_row[IDNO];
				$O_RECEIPT = $sql_row[O_RECEIPT];
				$R_DueNo = $sql_row[R_DueNo];
				
				?>
  <tr>
    <td><div align="center"><?Php print $k ?></div></td>
    <td><div align="center"><a href="fo2fr_update_details.php?receipt=<?Php print $O_RECEIPT ?>" target="_blank"><?Php print $IDNO ?></a></div></td>
    <td><div align="left"><?Php print $O_RECEIPT ?></div></td>
    <td><div align="left"><?Php print $R_DueNo ?></div></td>
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
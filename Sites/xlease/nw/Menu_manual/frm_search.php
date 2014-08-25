<?php
include("../../config/config.php");
$wording = explode(" ",$_GET['stext']);
$userid = $_SESSION["av_iduser"];
$sqluser = pg_query("SELECT pg_catalog.concat(COALESCE(title, ''::character varying), COALESCE(fname, ''::character varying), ' ', COALESCE(lname, ''::character varying)) AS fullname FROM fuser where id_user = '$userid'");
$reuser = pg_fetch_array($sqluser);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
	function popU(U,N,T){
		newWindow = window.open(U, N, T);
	}	
$(document).ready(function(){	
	
	$("#stext").autocomplete({
        source: "Data_search.php",
        minLength:1
    });
	
	$('#btn1').click(function(){
		var aaaa = $("#stext").val();
        parent.location='frm_search.php?stext='+aaaa;
	});	
});	
</script>
<table width="100%" align="left" >
	<tr>
		<td>
			<table width="100%" align="left"  >
			
							<tr>					
								<td colspan="2" bgcolor="#222222" style="padding:5px 0px 5px 5px "><font color="white"><?php echo $reuser['fullname']; ?></font></td>														
													
							</tr>
							<tr bgcolor="#EEEEEE">					
								<td width="20%" align="center" style="padding:20px 0px 20px 0px "><font size="5px"><b>คู่มือเมนู</b></font></td>														
								<td ><input type="text" name="stext" id="stext" value="<?php echo $_GET['stext']; ?>" size="70"><input type="button" id="btn1" value="ค้นหา"></td>					
							</tr>
							
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding:0px 0px 10px 0px;" >
			<table width="100%" align="left">
						<tr>
								<td colspan="2"><font color="#8B8989"><hr width="100%"></font></td>
							</tr>	
			<?php	
			$recmenuid[] = "";
			$sum = 0;
			for($i=0;$i<sizeof($wording);$i++){
									
				$sql = "select *,date(rec_date) as recdate from  f_menu_manual a
				left join \"f_menu\" b on a.\"id_menu\" = b.\"id_menu\"
				where appstatus = '1' and  ((a.\"recheader\" like '%$wording[$i]%') OR (b.\"name_menu\" like '%$wording[$i]%'))  order by a.id_menu";
				$results=pg_query($sql);						 
				$nrows=pg_num_rows($results);

				
				while($row = pg_fetch_array($results)){	

				for($z=0;$z<sizeof($recmenuid);$z++){
					if($recmenuid[$z] == $row['recmenuid']){
						$count = 1;
					}				
				}					
				if($count == 1){}else{
					$recmenuid[] = $row['recmenuid'];
					$date = $row["recdate"];
					$sum +=	1;				
				?>			
						<tr>
							<td><span style="cursor:pointer;" onclick="javascript:popU('frm_index.php?recid=<?php echo $row['recmenuid']; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1350,height=600')" ><font color="#6495ED"><u><?php echo $row['recheader']; ?></u></font></span></td>
						<tr>
						<tr>		
							<td><font size="1px"><?php echo $date." - "; ?></font><?php echo $row['name_menu']; ?></td>
						<tr>

						<tr>
							<td><p></td>
						</tr>
				<?php 
				}
				} 
				}
			echo "<tr bgcolor=\"#FFFAFA\"><td><font color=\"#8B8989\">ผลการค้นหาประมาณ $sum รายการ</font></td></tr>";	
				?>	
				
			</table>
		</td>
	</tr>
</table>	
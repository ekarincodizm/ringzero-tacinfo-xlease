<?php
include("../../config/config.php");

$corpID = $_GET["corpID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title> รายละเอียดผู้ติดต่อ</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>
<center>
<h2>รายละเอียดผู้ติดต่อเดิม</h2>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">


<tr align="right" bgcolor="#79BCFF">
							<th align="center">ลำดับที่</th>
							<th align="center">ชื่อผู้ติดต่อ</th>
							<th align="center">ตำแหน่ง</th>
							<th align="center">ประสานงานเรื่อง</th>
							<th align="center">เบอร์โทรศัพท์</th>
							<th align="center">เบอร์มือถือ</th>
							<th align="center">email</th>
							
						</tr>
						
			<?php $sql3 = pg_query("SELECT * FROM th_corp_communicant where \"corpID\" = '$corpID' ");
				$row3 = pg_num_rows($sql3);		
				if($row3 == 0){ ?>
						<tr><td align="center" colspan="7">ไม่มีรายชื่อผู้ติดต่อ</td></tr>
			<?php	}else{	  
						$num = 1;
						
						while($re3 = pg_fetch_array($sql3)){
				
			?>					
							<tr align="right" width="25%" bgcolor="#FFCCCC">
							<tr align="right" width="25%" >
							<td align="center" width="5%"><?php echo $num;?></td>
							
			
							
							<td align="center" width="20%" bgcolor=<?php echo $color ?> ><?php echo $re3['CommunicantName'];?></td>
						
							<td align="center" width="10%" bgcolor=<?php echo $color ?> ><?php echo $re3['position'];?></td>
							
							<td align="center" width="20%" bgcolor=<?php echo $color ?> ><?php echo $re3['subject'];?></td>
						
							<td align="center" width="10%" bgcolor=<?php echo $color ?> ><?php echo $re3['phone'];?></td>
							<td align="center" width="10%" bgcolor=<?php echo $color ?> ><?php echo $re3['mobile'];?></td>
			
							<td align="center" width="15%" bgcolor=<?php echo $color ?> ><?php echo $re3['email'];?></td>
						</tr>
						
				<?php 
							$num++;
						} 
				}
				?>
			
			

			</table>
		<br>
		<input type="button" value="ปิด" onclick="javascript:window.close();">
</center>
</body>
</html>
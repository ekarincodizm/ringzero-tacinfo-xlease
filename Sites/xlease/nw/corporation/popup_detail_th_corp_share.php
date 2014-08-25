<?php
include("../../config/config.php");

$corpID = $_GET["corpID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title> รายละเอียดผู้ถือหุ้น</title>
	 <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
    <link type="text/css" rel="stylesheet" href="act.css"></link>	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript" src="lib/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>

<script type="text/javascript">
		$(document).ready(function() {
		
			$(".pdforpic").fancybox({
			   minWidth: 500,
			   maxWidth: 800,
			   'height' : '600',
			   'autoScale' : true,
			   'transitionIn' : 'none',
			   'transitionOut' : 'none',
			   'type' : 'iframe'
			});

		});
</script>

</head>
<body>
<center>
<h2>รายละเอียดผู้ถือหุ้น</h2>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">


<tr align="right" bgcolor="#79BCFF">
							<th align="center">ลำดับที่</th>
							<th align="center">ชื่อผู้ถือหุ้น</th>
							<th align="center">จำนวนหุ้น</th>
							<th align="center">มูลค่าหุ้น</th>
							<th align="center">มุลค่าหุ้นที่ถือ</th>
							<th align="center">เปอร์เซ็นต์หุ้น</th>
							<th align="center">ตัอวย่างลายเซ็นต์</th>
							
						</tr>
						
			<?php $sql6 = pg_query("SELECT * FROM th_corp_share where \"corpID\" = '$corpID' ");
				  $row6 = pg_num_rows($sql6);		
				if($row6 == 0){ ?>
						<tr><td align="center" colspan="7">ไม่มีรายชื่อผู้ถือหุ้น</td></tr>
			<?php	}else{  
						$num = 1;
						
						while($re6 = pg_fetch_array($sql6)){
							$CusID = $re6['CusID'];
							$sql7 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
							  $re7 = pg_fetch_array($sql7);
							  $row7 = pg_num_rows($sql7);
						
						if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}

						if($row7==0){
							$fullname =  $CusID;
						}else{
							$fullname = $re7['full_name'];
						}
							
							
						?>						
							<td align="center" width="5%"><?php echo $num;?></td>
				
							
							<td align="center" width="20%" color=<?php echo $color;?> ><?php echo $fullname;?></td>
				
							<td align="center" width="10%" color=<?php echo $color;?> ><?php echo $re6['share_amount'];?></td>
							
							<td align="center" width="20%" color=<?php echo $color;?> ><?php echo $re6['share_value'];?></td>
						<?php	
						
						if($re6['share_amount']=="" || $re6['share_value']==""){
						$sumshare = "";
						}else{
						$sumshare = number_format($re6['share_amount']*$re6['share_value'],2);
						}
						
						?>
							
							<td align="center" width="10%"><?php echo $sumshare;?></td>
							
							<?php if($current_capital == "" || $sumshare == ""){
									$percent = "";
									}else{
									$percent = ($sumshare/$current_capital)*100;
									$percent = $percent."%";
									}
							?>		
							<td align="center" width="10%"><?php echo $percent;?></td>
							<td align="center" width="15%">
						<?php if($re6['path_signature'] == ""){
							
				
						}else{	?>	
							
							<a class="pdforpic" href="upload/<?php echo $re6['path_signature']; ?>" data-fancybox-group="gallery" title=" <?php echo $fullname;?> "><u>ตัวอย่างลายเซ็นต์</u></a></td>
						<?php } ?>	
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
<?php
include("../../config/config.php");

$corpID = $_GET["corpID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title> รายละเอียดผู้รับมอบอำนาจ</title>
	
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
<h2>รายละเอียดผู้รับมอบอำนาจเดิม</h2>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">


<?php $sql4 = pg_query("SELECT * FROM th_corp_attorney where \"corpID\" = '$corpID'");
				$row4 = pg_num_rows($sql4);		
					if($row4 == 0){ ?>
						<center> ไม่มีรายชื่อผู้รับมอบอำนาจ </center>
			<?php	}else{  
						$num = 1;
						
						while($re4 = pg_fetch_array($sql4)){
							$CusID = $re4['CusID'];
				?>			
	
							<td>ผู้รับมอบอำนาจคนที่ <?php echo $num; ?> : </td>
							
						<?php $sql5 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
							  $re5 = pg_fetch_array($sql5);
							  $row5 = pg_num_rows($sql5);
							  
									if($row5==0){
									$fullname =  $CusID;
									}else{
										$fullname = $re5['full_name'];
									}
	  
							  
						?>
	
							<td align="left" width="30%"><b><?php echo $fullname;?></b></td>
							<td align="right" width="15%">ใบรับมอบอำนาจ : </td>
							<td align="left" width="30%">
						<?php if($re4['path_receipt_authority'] == ""){
							
				
						}else{	?>	
							
							<a class="pdforpic" href="upload/<?php echo $re4['path_receipt_authority']; ?>" data-fancybox-group="gallery" title=" <?php echo $fullname;?> "><u> แสดงใบรับมอบอำนาจ </u></a></td>
							
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
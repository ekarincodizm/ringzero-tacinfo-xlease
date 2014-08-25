<?php
include("../../config/config.php");

$corpID = $_GET["corpID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title> รายละเอียดกรรมการ</title>
	
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
<h2>รายละเอียดกรรมการเดิม</h2>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">


			<?php $sql1 = pg_query("SELECT * FROM th_corp_board where \"corpID\" = '$corpID'");
				 $row1 = pg_num_rows($sql1);		
					if($row1 == 0){ ?>
						<center> ไม่มีรายชื่อกรรมการ </center>
				<?php	}else{
						$num = 1;
						
						while($re1 = pg_fetch_array($sql1)){
							$CusID = $re1['CusID'];
							
							$sqlold1 = pg_query("SELECT * FROM th_corp_board where \"corpID\" = '$corpID' and \"CusID\" = '$CusID' ");
							$rowold1 = pg_num_rows($sqlold1);
													
							if($rowold1 == 0){
						?>	
								<tr align="right" width="25%" bgcolor="#FFCCCC">
							
					<?php	}else{ ?>
								<tr align="right" width="25%" >
					<?php	}  ?>
										
											<td>ชื่อกรรมการคนที่ <?php echo $num; ?> : </td>
											
										<?php $sql2 = pg_query("SELECT full_name FROM \"VSearchCus\" where \"CusID\" = '$CusID'"); 
											  $re2 = pg_fetch_array($sql2);
											   $row2 = pg_num_rows($sql2);
											  
													if($row2==0){
													$fullname =  $CusID;
													}else{
														$fullname = $re2['full_name'];
													}
											  
								?>
					
											<td align="left" width="30%"><b><?php echo $fullname;?></b></td>
											<td align="right" width="15%">ตัวอย่างลายเซ็นต์ :</td>
											<td align="left" width="30%">
								<?php 	if($re1['path_signature'] == ""){
											
								
										}else{	?>			
											<a class="pdforpic" href="upload/<?php echo $re1['path_signature']; ?>" data-fancybox-group="gallery" title=" <?php echo $fullname;?> "><u> แสดงลายเซ็นต์ </u></a></td>
								<?php 	} ?>		
										</tr>
						
					<?php 
							$num++;
							}
						
					}
					?>	
				</table>
		</table>
		<br>
		<input type="button" value="ปิด" onclick="javascript:window.close();">
</center>
</body>
</html>
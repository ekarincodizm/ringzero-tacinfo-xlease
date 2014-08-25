<?php
session_start();
include("../config/config.php");
mysql_query("SET NAMES UTF8");
$fpicID= $_GET['fpicID'];
$idno= $_GET['idno'];

	$sql1 = "SELECT \"fpicID\", \"IDNO\", picname, cusname, date, id_user, detail FROM \"Fp_document_pic\" where \"fpicID\" = '$fpicID'";
	$sqlquery1 = pg_query($sql1);
	$rows = pg_num_rows($sqlquery1);
	$no = 0;
?>
<!DOCTYPE html>
<html>
<head>
	<title>fancyBox - Fancy jQuery Lightbox Alternative | Demonstration</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <!--<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>-->
	<script type="text/javascript" src="fancybox/lib/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
		
	<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>
<script type="text/javascript">
$(function(){
    $("#box_tab").tabs();
});

$(document).ready(function() {
	$(".fancybox-effects-a").fancybox({
					minWidth: 300,
				   maxWidth: 700,
				   'height' : '600',
				   'autoScale' : true,
				   'transitionIn' : 'none',
				   'transitionOut' : 'none',
				   'type' : 'iframe'

				   
					// helpers: {
						
						// title : {
							// type : 'outside'
						// },
						// overlay : {
							// speedIn : 500,
							// opacity : 0.95
						// }
					// }
	});
});
</script>
</head>	
<body>
<div id="box_tab"> <!-- เริ่ม tabs -->
	<ul>
	<?php		
			echo "<li><a href=\"#show\">ตรวจสอบเอกสาร $fpicID</a></li>";				
	?>
	</ul>
<div id="show" name="show">
		<table width="600" border="0"  cellSpacing="1" cellPadding="1"  align="center" bgcolor="#E1E1FF">
			<?php	if($rows == 0){ ?>
			
						
						<tr bgcolor="#CCCCFF" height="25"><td align="center">ไม่มีไฟล์เอกสาร</td></tr>
				
			<?php		}else{ ?>			
							<tr bgcolor="#CCCCFF" height="25">	
								<th align="10%">เอกสารที่</th>
								<th align="35%">ไฟล์เอกสาร</th>						
							</tr>
				<?php		while($re = pg_fetch_array($sqlquery1)){ 
									  
							
								
				
								$ff = $re["picname"];
								$file=explode("!#",$ff);						
								for($i=0;$i<sizeof($file);$i++){
								
									if($file[$i] == ""){
									continue;
									}else{
										$no++; 
										if($no%2==0){
											$color="#E1E1FF";
										}else{
											$color="#F4F4FF";
										}	
									
						?>			
										<tr bgcolor=<?php echo $color?>>
										<td align="center" align="10%"><?php echo $no; ?></td>	
										<td align="center" align="35%">					
										<a class="fancybox-effects-a" href="fileupload/<?php echo $idno; ?>/<?php echo $file[$i]; ?>" title=""><u> <?php echo $file[$i]; ?></u><br></a>
										</td>
										</tr>
					<?php 			} ?>
								
						
					<?php 	 	} 	
							} 
						}	 ?>			
		</table>
</div>
</div>
</body>
</html>
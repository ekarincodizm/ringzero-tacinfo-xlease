<?php
session_start();
include("../config/config.php");

$assetid= pg_escape_string($_GET['assetid']);



   

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo $assetid ?>');
});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding-bottom: 3px;
    text-align: right;
}
</style>

</head>
<body>

<div class="title_top">ประวัติการเดินสัญญา</div>

<div id="tabs"> <!-- เริ่ม tabs -->
	<ul>
	<?php
		//สร้าง list รายการ โอนสิทธิ์
		
			echo "<li><a href=\"#tabs-$i\">$assetid</a></li>";
		
	?>
	</ul>

 
			
			<table width="880" border="0" cellSpacing="1" cellPadding="1"  align="center" bgcolor="#E1E1FF">
				<tr bgcolor="#CCCCFF" height="25">	
					<th>ลำดับที่</th>
					<th>เลขที่สัญญา</th>
					<th>วันที่ทำสัญญา</th>
					<th>ยอดไฟแนนซ์</th>
					<th>วันที่ปิดสัญญา</th>
					
					
				</tr>
			<?php	
			    $qry_top=pg_query("select \"IDNO\",\"P_STDATE\",\"P_BEGIN\",\"P_CLDATE\" from \"Fp\" WHERE \"asset_id\"='$assetid' order by \"P_STDATE\" DESC ");
				
			$i=0;
			while($res_1=pg_fetch_array($qry_top)){
				list($IDNO,$P_STDATE,$P_BEGIN,$P_CLDATE )=$res_1;
				 
				$i++;
				if($i%2==0){
					$color="#E1E1FF";
				}else{
					$color="#F4F4FF";
				}
				echo "
				<tr height=25 bgcolor=$color align=center>
					<td>$i</td>
					<td onclick=\"javascript:popU('frm_viewcuspayment.php?idno_names=$IDNO','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\"><u>$IDNO</u></td>
					<td>$P_STDATE</td>
					<td>$P_BEGIN</td>
					<td>$P_CLDATE</td>
				</tr>
				";
			}
			?>
			</table>
			</div>
		
</div>

</body>
</html>
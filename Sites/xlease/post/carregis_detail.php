<?php
session_start();
include("../config/config.php");

$g_idno = pg_escape_string($_GET['idno']);
$g_cusid = pg_escape_string($_GET['scusid']);

$search_top = $g_idno;
do{
    $qry_top=pg_query("select \"CusID\",\"IDNO\" from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top'");
    $res_top=pg_fetch_array($qry_top);
    $CusID=$res_top["CusID"];
    $arr_idno[$res_top["IDNO"]]=$CusID;
    $search_top=$res_top["IDNO"];
}while(!empty($search_top));

$qry_top=pg_query("select \"CusID\",\"P_TransferIDNO\" from \"Fp\" WHERE \"IDNO\"='$g_idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$g_idno]=$CusID;

if(!empty($P_TransferIDNO)){
    do{
        $qry_fp2=pg_query("select A.\"CusID\",\"P_TransferIDNO\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$P_TransferIDNO'");
        $res_fp2=pg_fetch_array($qry_fp2);
        $CusID=$res_fp2["CusID"];
        $arr_idno[$P_TransferIDNO]=$CusID;
        $P_TransferIDNO=$res_fp2["P_TransferIDNO"];
    }while(!empty($P_TransferIDNO));
}
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
    $("#tabs").tabs('select', '<?php echo $_SESSION["ses_idno"]; ?>');
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

<div class="title_top">ประวัติทะเบียนรถ</div>

<div id="tabs"> <!-- เริ่ม tabs -->
	<ul>
	<?php
		//สร้าง list รายการ โอนสิทธิ์
		foreach($arr_idno as $i => $v){
			if(empty($i)){
				continue;
			}
			echo "<li><a href=\"#tabs-$i\">$i</a></li>";
		}
	?>
	</ul>
 
	<?php
		foreach($arr_idno as $i => $v){
			if(empty($i)){
				continue;
			}
    
			$g_cusid = $v;
			$g_idno = $i;
    
			//กำหนดสี ให้กับข้อมูลล่าสุด
			if($_SESSION["ses_idno"] == $g_idno){
				$bgcolor = "#FFFFFF";
			}else{
				$bgcolor = "#FFFFFF";
			}
			//จบ กำหนดสี
   
			?>
 
			<div id="tabs-<?php echo $g_idno; ?>">
			<table width="880" border="0" cellSpacing="1" cellPadding="1"  align="center" bgcolor="#E1E1FF">
				<tr bgcolor="#CCCCFF" height="25">
					<th>ที่</th>
					<th>ทะเบียนรถ</th>
					<th>ยี่ห้อรถ</th>
					<th>รุ่นปี</th>
					<th>จังหวัดที่จดทะเบียน</th>
					<th>สีรถ</th>
					<th>เลขตัวถัง</th>
					<th>เลขเครื่องยนต์</th>
					<th>ผู้ทำรายการ</th>
					<th>วันเวลาที่ทำรายการ</th>
					<th>เพิ่มเติม</th>
				</tr>
			<?php	
			$qry_1=pg_query("SELECT auto_id,\"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
				   \"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"CarID\", \"fullname\", \"keyStamp\"
			  FROM \"Carregis_temp\" a
			  left join \"Vfuser\" b on a.\"keyUser\"=b.\"id_user\" where \"IDNO\"='$g_idno' order by \"keyStamp\" DESC");
			$i=0;
			while($res_1=pg_fetch_array($qry_1)){
				list($auto_id,$C_REGIS, $C_CARNAME, $C_YEAR, $C_REGIS_BY, 
				   $C_COLOR, $C_CARNUM, $C_MARNUM, $CarID, $fullname, $keyStamp)=$res_1;
				 
				$i++;
				if($i%2==0){
					$color="#E1E1FF";
				}else{
					$color="#F4F4FF";
				}
				echo "
				<tr height=25 bgcolor=$color align=center>
					<td>$i</td>
					<td>$C_REGIS</td>
					<td>$C_CARNAME</td>
					<td>$C_YEAR</td>
					<td>$C_REGIS_BY</td>
					<td>$C_COLOR</td>
					<td>$C_CARNUM</td>
					<td>$C_MARNUM</td>
					<td align=left>$fullname</td>
					<td>$keyStamp</td>
					<td><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('carregis_alldetail.php?auto_id=$auto_id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=650')\" style=\"cursor: pointer\"></td>
				</tr>
				";
			}
			?>
			</table>
			</div>
		<?php 
		} 
		?>
</div>

</body>
</html>
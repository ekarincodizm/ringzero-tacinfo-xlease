<?php
include("../config/config.php");
$IDNO = pg_escape_string($_GET['IDNO']);
$statussent = pg_escape_string($_GET['stasend']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<div>IDNO : <?php echo $IDNO;?></div>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
    <td>ลำดับที่</td>
    <td>ชื่อ/สกุล ผู้จดหมาย</td>
    <td>ที่อยู่</td>
	<td>วันที่ส่ง</td>
	<td>เลือก</td>
</tr>

<?php
$qry_name=pg_query("select * from pmain.\"fletter\" WHERE \"IDNO\" = '$IDNO' order by \"SENDDATE\" DESC");
$num_row = pg_num_rows($qry_name);
$p=1;
while($res_name2=pg_fetch_array($qry_name)){
	$name = $res_name2["NAME"];
    $address = $res_name2["ADDRESS1"].$res_name2["ADDRESS2"].$res_name2["ADDRESS3"];
	$senddate = $res_name2["SENDDATE"];
    
    $show_type = "";

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
    <td align="center" valign="top"><?php echo $p; ?></td>
    <td valign="top"><?php echo "$name"; ?></td>
    <td valign="top" width=""><?php echo "$address"; ?></td>
    <td valign="top" width="" align="center"><?php echo "$senddate"; ?></td>
	<td valign="top" width="50" align="center">
	<?php
	if($statussent == 1){
		echo "<input type=\"button\" name=\"btn1\" value=\"เลือก\" onClick=\"opener.document.frm_detail.txt_ads3.value='$address';window.close();\">";
	}else{
		echo "<input type=\"button\" name=\"btn1\" value=\"เลือก\" onClick=\"opener.document.frm_detail.txt_ads4.value='$address';window.close();\">";
	}
	?>
	</td> 
</tr>

<?php
	$p++;
}

if($num_row == 0){
    echo "<tr><td colspan=5 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
    echo "<tr><td colspan=5 align=left>พบข้อมูลทั้งหมด $num_row รายการ</td></tr>";
}
?>
</table>
</body>
</html>
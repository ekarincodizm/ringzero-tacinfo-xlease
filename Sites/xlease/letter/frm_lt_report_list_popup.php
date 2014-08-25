<?php
include("../config/config.php");
$id = pg_escape_string($_GET['IDNO']);
$CusID = pg_escape_string($_GET['CusID']);
//ค้นหาชื่อลูกค้าว่าใคร
$qry_name=pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\"='$CusID'");
list($full_name)=pg_fetch_array($qry_name);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานการส่งจดหมายของเลขที่สัญญา <?php echo $IDNO;?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<script>
	function popU(U,N,T) {
		newWindow = window.open(U, N, T);
	}
	</script>
</head>
<body>
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0">
<tr><td colspan="5" height="25"><b><?php echo "<span onclick=\"javascript:popU('../nw/manageCustomer/showdetail2.php?CusID=$CusID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ข้อมูลลูกค้า\"><u>$full_name</u></span> (<span onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$id&type=outstanding','$id','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ดูตารางการชำระ\"><u> เลขที่สัญญา :$id</u></span>)"; ?></b></td></tr>
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
	<td width="100">วันที่ส่ง</td>
    <td width="400">ที่อยู่</td>
	<td>รูปแบบจดหมาย</td>
    <td>ประเภทการส่ง</td>
</tr>

<?php
$qry_name2=pg_query("select \"auto_id\",\"send_date\",\"IDNO\",\"detail\",\"type_send\",\"userid\",\"coname\",\"A_FIRNAME\",
\"A_NAME\",\"A_SIRNAME\",\"address\"
 from letter.\"SendDetail\" A 
left join letter.\"cus_address\" B on A.\"address_id\" = B.\"address_id\"  
left join \"Fa1\" C on B.\"CusID\" = C.\"CusID\"
WHERE B.\"CusID\"='$CusID' and \"IDNO\"='$id' and A.\"send_date\" is not null order by A.\"send_date\" DESC");
$num_row = pg_num_rows($qry_name2);
while($res_name2=pg_fetch_array($qry_name2)){
	$auto_id = $res_name2["auto_id"];
	$send_date = $res_name2["send_date"];
	$IDNO = $res_name2["IDNO"];
    //$address_id=$res_name2["address_id"];
    $detail=$res_name2["detail"]; $detail = explode(",",$detail);
	$type_send=$res_name2["type_send"];
    $userid=$res_name2["userid"];
	
	if($res_name2["coname"]==""){
		$name = $res_name2["A_FIRNAME"].$res_name2["A_NAME"]." ".$res_name2["A_SIRNAME"];
	}else{
		$name= $res_name2["coname"];
	}
	$address = $res_name2["address"];
    
    $nub = 0;
    $show_type = "";
    foreach($detail as $v){
        $nub += 1;
        if($nub == 1){
            $qry_name3=pg_query("select \"type_name\" from letter.\"type_letter\" WHERE \"auto_id\"='$v'");
            if($res_name3=pg_fetch_array($qry_name3)){
                $type_name=$res_name3["type_name"];
            }
            $show_type .= "$type_name";
        }else{
            $qry_name3=pg_query("select \"type_name\" from letter.\"type_letter\" WHERE \"auto_id\"='$v'");
            if($res_name3=pg_fetch_array($qry_name3)){
                $type_name=$res_name3["type_name"];
            }
            $show_type .= ", $type_name";
        }
    }

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
	<td valign="top" align="center"><?php echo "$send_date"; ?></td>
    <td valign="top" width="300"><?php echo "$address"; ?></td>
	<td valign="top"><?php echo "$show_type"; ?></td>
	<td valign="top" width="100" align="center">
		<?php 
		if($type_send == "N"){
			echo "ส่งธรรมดา";
		}else if($type_send == "R"){
			echo "ลงทะเบียน";
		}else if($type_send == "A"){
			$qry_name4=pg_query("select * from letter.\"regis_send\" WHERE \"auto_id\"='$auto_id'");
            if($res_name4=pg_fetch_array($qry_name4)){
                $reg_num=$res_name4["reg_num"];
            }
			if($reg_num != ""){
				$reg_num = $reg_num;
			}else{
				$reg_num = "ไม่ระบุ";
			}
			echo "ลงทะเบียนตอบรับ<br><font color=\"red\">($reg_num)</font>";
		}else if($type_send == "E"){
			$qry_name4=pg_query("select ems_num from letter.\"regis_send\" WHERE \"auto_id\"='$auto_id'");
            if($res_name4=pg_fetch_array($qry_name4)){
                $reg_num=$res_name4["ems_num"];
            }
			if($reg_num != ""){
				$reg_num = $reg_num;
			}else{
				$reg_num = "ไม่ระบุ";
			}
			echo "EMS<br><font color=\"red\">($reg_num)</font>";			
		}
		?>
	</td>
</tr>

<?php
	$reg_num="";
} //end while

if($num_row == 0){
    echo "<tr height=50><td colspan=5 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
    echo "<tr><td colspan=5 align=left>พบข้อมูลทั้งหมด $num_row รายการ</td></tr>";
}
?>
</table>
<div style="text-align:center;padding:20px"><input type="button" value=" ปิด " onclick="window.close();"></div>
</body>
</html>
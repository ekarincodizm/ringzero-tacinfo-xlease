<?php
include("../config/config.php");
$date = pg_escape_string($_GET['date']);

if(empty($date)){
    exit;
}
?>

<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
    <td>IDNO</td>
    <td>วันที่ส่ง</td>
    <td>ชื่อ/สกุล</td>
    <td>ที่อยู่</td>
	<td>รูปแบบจดหมาย</td>
	<td>ประเภทการส่ง</td>
	<td>พิมพ์จดหมาย</td>
	<td>พิมพ์ใบเหลือง</td>
</tr>

<?php
$qry_name=pg_query("select \"auto_id\",\"send_date\",\"IDNO\",A.\"address_id\",\"detail\",\"type_send\",\"userid\",
\"coname\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"address\"
 from letter.\"SendDetail\" A 
left join letter.\"cus_address\" B on A.\"address_id\" = B.\"address_id\"  
left join \"Fa1\" C on B.\"CusID\" = C.\"CusID\"
WHERE A.\"send_date\"='$date' and A.\"detail\" !=''order by A.\"auto_id\" ASC");
$num_row = pg_num_rows($qry_name);
while($res_name2=pg_fetch_array($qry_name)){
	$auto_id = $res_name2["auto_id"];
    $send_date = $res_name2["send_date"];
	$IDNO = $res_name2["IDNO"];
    $address_id=$res_name2["address_id"];
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
    <td align="center" valign="top"><?php echo "$IDNO"; ?></td>
    <td valign="top"><?php echo "$send_date"; ?></td>
    <td valign="top"><?php echo "$name"; ?></td>
    <td valign="top" width="250"><?php echo "$address"; ?></td>
	<td valign="top"><?php echo "$show_type"; ?></td>
	<td valign="top" width="150" align="center">
		<?php 
		if($type_send == "N"){
			echo "ส่งธรรมดา";
		}else if($type_send == "R"){
			echo "ลงทะเบียน";
		}else if($type_send == "A"){
			$qry_name4=pg_query("select \"reg_num\" from letter.\"regis_send\" WHERE \"auto_id\"='$auto_id'");
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
			$qry_nameE=pg_query("select \"ems_num\" from letter.\"regis_send\" WHERE \"auto_id\"='$auto_id'");
            if($res_nameE=pg_fetch_array($qry_nameE)){
                $reg_num=$res_nameE["ems_num"];
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
	<td valign="top" align="center"><input type="button" value="พิมพ์จดหมาย" onclick="window.open('print_letter.php?cus_lid=<?php echo $auto_id?>')"></td>
	<td valign="top" align="center">
	<?php
		$nowdate=nowDate();//ดึง วันที่จาก server
		$post="คลองจั่น";
		if($reg_num == "" ){
			echo "-";		
		}else{ if($type_send == "A"){
		?>
			<input type="button" value="พิมพ์ใบเหลือง" onclick="window.open('print_yellow.php?cus_lid=<?php echo $auto_id?>&nowdate=<?php echo $nowdate?>&post=<?php echo $post?>')">
		<?php }
				else if($type_send == "E"){ ?>
			<input type="button" value="พิมพ์ใบฟ้า" onclick="window.open('print_yellow.php?cus_lid=<?php echo $auto_id?>&nowdate=<?php echo $nowdate?>&post=<?php echo $post?>')">	
		<?php		}
		}
		?>
	</td>
</tr>

<?php
	$reg_num="";
}

if($num_row == 0){
    echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
}else{
    echo "<tr><td colspan=10 align=left>พบข้อมูลทั้งหมด $num_row รายการ</td></tr>";
}
?>
</table>
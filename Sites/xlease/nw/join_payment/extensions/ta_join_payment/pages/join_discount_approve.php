<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$realpath = redirect($_SERVER['PHP_SELF'],'');
$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title><?php echo $_SESSION['session_company_name']; ?></title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<script src="../<?php echo $lo_ext_current_temp ?>scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.7.1.min.js" type="text/javascript"></script>

<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="../<?php echo $lo_ext_current_temp ?>scripts/css/ui-lightness/jquery-ui-1.8.1.custom.css" />
<link type="text/css" rel="stylesheet" href="act.css"></link>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function confirmappv(no){
	if(no=='1'){
		if(confirm('ยืนยันการอนุมัติ')==true){
			return true;
		}else{return false;}
	}
	else if(no=='0'){
		if(confirm('ยืนยันการไม่อนุมัติ!!')==true){
			return true;
		}else{return false;}
	}else{	
		return false;
	}
} 
</script>

</head>
<body>
<style type="text/css">
table.t2 tr:hover td {
	background-color:pink;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>อนุมัติการขอส่วนลดเข้าร่วม</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
				<td align="center">เลขที่สัญญา</td>
                <td align="center">เลขทะเบียนรถ</td>
                <td align="center">ชื่อลูกค้า</td>
                <td align="center">จำนวนเงินที่ขอ</td>
				<td align="center">ผู้ขออนุมัติ</td>
				<td align="center">วันเวลาที่ขอ</td>
                <td align="center">เหตุผล</td>
                <td align="center">ข้อมูลเข้าร่วม</td>
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_fr=pg_query("SELECT m.car_license,f.\"O_RECEIPT\",f.\"O_MONEY\",cpro_name,m.idno,m.id,f.approve_status,f.create_by,f.create_datetime,f.\"O_memo\" FROM \"FOtherpayDiscount\" f left join \"VJoinMain\" m on m.idno = f.\"IDNO\" WHERE m.deleted ='0' and m.car_license_seq = 0
			 and f.approve_status=0 order by f.create_datetime ");
			$nub=pg_num_rows($qry_fr);
			while($sql_row4=pg_fetch_array($qry_fr)){
				$cpro_name = $sql_row4['cpro_name'];
				$O_RECEIPT = $sql_row4['O_RECEIPT'];
				$car_license = $sql_row4['car_license'];
				$create_datetime =$sql_row4['create_datetime']; 
				$reason =$sql_row4['O_memo']; 
				$approve_status = $sql_row4['approve_status'];
				$O_MONEY =$sql_row4['O_MONEY']; 
				$create_by = $sql_row4['create_by'];//เอาไปหา id_card ด้วย
				$idno = trim($sql_row4['idno']);
				$id = trim($sql_row4['id']);
				
				$dt = $create_datetime;
				$by = $create_by;
		
				$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$by'");
				$res_userprofile=pg_fetch_array($res_profile);
				$by=  $by."-".$res_userprofile["fullname"];
				
				$i+=1;
				$nameform="my".$i;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				?>
				<td align="center"><?php echo $i; ?></td>
				<td><span onclick="javascript:popU('<?php echo $realpath; ?>post/frm_viewcuspayment.php?idno_names=<?php echo $idno; ?>&type=outstanding','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $idno;?></u></font></span></td>				
				<td align="left"><?php echo $car_license; ?></td>
				<td align="left"><?php echo $cpro_name; ?></td>
                <td align="right"><?php echo number_format($O_MONEY); ?></td>
                <td align="left"><?php echo $by; ?></td>
                <td align="center"><?php echo $dt; ?></td>
      
                <td align="left"><?php echo $reason; ?></td>
				<td align="center">
                    <img src="../images/open.png" width="16" height="16" onclick="javascript:popU('<?php echo $realpath; ?>nw/join_payment/extensions/ta_join_payment/pages/ta_join_payment_view_new.php?id=<?php echo $id; ?>&readonly=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1050,height=600')" style="cursor:pointer">
                </td>
				<td align="center">
					<!--button type="button" class="button_text" name="button" style="cursor:pointer" onclick="update_cs1('<?Php print $O_RECEIPT ?>');" id="button" ><img src="../images/staff_check.png" width="50" height="50" /> อนุมัติ</button>
					<button type="button" class="button_text" name="button" style="cursor:pointer" onclick="update_cs0('<?Php print $O_RECEIPT ?>');"  id="button2" ><img src="../images/del.png" width="50" height="50" /> ไม่อนุมัติ</button-->
					 <!--ส่งค่าแบบ POST-->
					<form name="<?php echo $nameform;?>" method="post" action="approve_join_discount.php">						
						<input type="hidden" name="RECEIPT" id="RECEIPT" value="<?Php echo $O_RECEIPT; ?>">						
						<input hidden name="appv" value="อนุมัติ" type="submit"/>						
						<button type="button" class="button_text" name="button" style="cursor:pointer" onclick=" if (confirmappv('1')){ 
						document.forms['<?php echo $nameform;?>'].appv.click();document.forms['<?php echo $nameform;?>'].submit();return false;} " id="button" ><img src="../images/staff_check.png" width="50" height="50" /> อนุมัติ</button>
						<button type="button" class="button_text" name="button" style="cursor:pointer" onclick=" if (confirmappv('0')){ document.forms['<?php echo $nameform;?>'].submit(); return false;}"  id="button2" ><img src="../images/del.png" width="50" height="50" /> ไม่อนุมัติ</button>
					</form>
				</td>	
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
<?php
include("appv_history_limit.php");
?>
</body>
</html>
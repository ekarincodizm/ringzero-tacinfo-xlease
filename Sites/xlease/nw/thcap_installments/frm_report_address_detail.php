<?php
session_start();
include("../../config/config.php");

$tempID = pg_escape_string($_GET["tempID"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>

<body>

<div class="header" align="center" >รายละเอียดที่อยู่ตามสัญญา</div>

<div id="tabs-<?php echo $idno; ?>">
	<div style="background-color:<?php echo $bgcolor; ?>">
			<?php
				//
				$color_table="#82C0FF";
				$color_head="#D9ECFF";
				$color1="#B5E8FB";
				$color2="#FFFFFF";
				//หาข้อมูลทประวัติี่ที่อยู่ทั้งหมด
					$qry_add = pg_query("select * from \"thcap_addrContractID_temp\" where \"tempID\" = '$tempID' ");
					$numrow = pg_num_rows($qry_add);
						if($numrow>0){
							while($resaddr = pg_fetch_array($qry_add)){
								$contractID=$resaddr["contractID"];
								$A_NO=$resaddr["A_NO"];
								$A_SUBNO=$resaddr["A_SUBNO"];
								$A_BUILDING=$resaddr["A_BUILDING"];
								$A_ROOM=$resaddr["A_ROOM"];
								$A_FLOOR=$resaddr["A_FLOOR"];
								$A_VILLAGE=$resaddr["A_VILLAGE"];
								$A_SOI=$resaddr["A_SOI"];
								$A_RD=$resaddr["A_RD"];
								$A_TUM=$resaddr["A_TUM"];
								$A_AUM=$resaddr["A_AUM"];
								$A_PRO=$resaddr["A_PRO"];
								$A_POST=$resaddr["A_POST"];
								$filerequest=$resaddr["filerequest"];
								$effectiveDate=$resaddr["effectiveDate"];
							}//end while
						}
			?>
			<table width="500" border="0" cellpadding="1" cellspacing="1" bgcolor="<?php echo $color_table;?>" align="center">	
				<tr>
					<td bgcolor="<?php echo $color_head;?>" colspan="2"><b><?php echo "$txt เลขที่สัญญา : $contractID";?></b></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">ห้อง :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_room" value="<?php echo $A_ROOM; ?>" readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">ชั้น :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_floor" value="<?php echo $A_FLOOR; ?>" readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">เลขที่ :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_no" value="<?php echo $A_NO; ?>" readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">หมู่ที่ :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_subno" value="<?php echo $A_SUBNO; ?>" readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">หมู่บ้าน :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_ban" value="<?php echo $A_VILLAGE; ?>" size="50" readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">อาคาร/สถานที่ :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_building" value="<?php echo $A_BUILDING; ?>" size="50" readonly="true"/></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">ซอย :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_soi" value="<?php echo $A_SOI; ?>" readonly="true"/></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">ถนน :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_rd" value="<?php echo $A_RD; ?>" readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">แขวง/ตำบล :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_tum" value="<?php echo $A_TUM; ?>"readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">เขต/อำเภอ :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_aum" value="<?php echo $A_AUM; ?>" readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">จังหวัด :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" value="<?php echo $A_PRO;?>" readonly="true" /></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">รหัสไปรษณีย์ :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_post" value="<?php echo $A_POST; ?>" maxlength="5" readonly="true"/></td>
				</tr>
				<tr>
					<td align="right" bgcolor="<?php echo $color1;?>">วันที่ที่มีผลบังคับใช้ :</td>
					<td bgcolor="<?php echo $color2;?>"><input type="text" name="f_effectiveDate" value="<?php echo $effectiveDate; ?>" maxlength="5" readonly="true"/></td>
				</tr>
				<tr>
					<td  align="right" bgcolor="<?php echo $color1;?>">ใบคำขอแก้ไขที่อยู่สัญญา :</td>
					<td bgcolor="<?php echo $color2;?>">
						<?php 
						if($filerequest!=""){
							echo "<a href=\"../thcap/upload_chgcontractadds/$filerequest\" target=\"_blank\"><img src=\"images/open.png\" width=\"20\" height=\"20\" title=\"แสดงใบคำขอแก้ไข\"></a>";
						}else{
							echo "<img src=\"images/noimage.gif\" width=\"20\" height=\"20\" title=\"ไม่มีข้อมูล\">";
						}
						?>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2"><input type="button" value="ปิด" onclick="window.close();"/></td>
				</tr>
		</table>
		</div>
	</div>
</div>


</body>
</html>
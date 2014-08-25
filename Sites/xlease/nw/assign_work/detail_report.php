<?php
session_start();
include("../../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$AssignNo = pg_escape_string($_GET["AssignNo"]);
$cancle = pg_escape_string($_GET["cancle"]);
$qry_detail = pg_query("select * from assign_work_detail where \"AssignNo\"='$AssignNo' ");
if($res=pg_fetch_array($qry_detail)){
	$AssignDate = $res["AssignDate"];
	$Institution = $res["Institution"];
	$str = substr($res["Subject"],1,count($res["Subject"])-2);
	$Subject = explode(",",$str);
	$Place = $res["Place"];
	$CusID = $res["CusID"];
	$DebtorID = $res["DebtorID"];
	$DebtorName = $res["DebtorName"];
	$PhoneNo = $res["PhoneNo"];
	$DeadlineDate = substr($res["DeadlineDate"],0,10);
	$deptStatus = $res["deptStatus"];
	$AssignName = $res["AssignName"];
	$contractID = $res["contractID"];
	$Note = $res["Note"];
	$CancleNote = $res["CancleNote"];
} 

$cusname_qry = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\"='$CusID'");
$cusname = pg_fetch_result($cusname_qry,0);

if($DebtorID==""){
	$newDebtID = "";
} else {
	$newDebtID = "(".$DebtorID.")";
}
for($i=0;$i<sizeof($Subject);$i++){
	if($Subject[$i]==1){
		$subname = "รับเช็ค";
	} else if($Subject[$i]==2){
		$subname = "เอกสารรับกลับ";
	}else {
		$subname = "ตรวจรับ/นับสินค้าบริการ";
	}
	
	if($i==0){
		$allSubname = $subname;
	}else{
		$allSubname = $allSubname." , ".$subname;
	}
}

if($DebtorName==""){
	$NewDebtorNam = "-";
} else {
	$NewDebtorNam = $DebtorName;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ใบสั่งงาน Checker</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
	newWindow = window.open(U, N, T);
}
function checkNote(){
	if($('#NoteCancle').val()==""){
		alert("กรุณาระบุหมายเหตุ");
		return false;
	}
}
</script>
<style>
#detail
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:60%;
}
#payment
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
}
#btset
{
margin-left:auto;
margin-right:auto;
margin-top:10px;
}
</style>
</head>
<body>
	<div class="header" align="center">
		<h1>(THCAP) รายงานการสั่งงานตรวจสอบ-วางบิลเก็บช็ค</h1>
	</div>

	<div id="detail">
				<div >
				<table width="700px"align="center" cellspacing="10" style="border-style:groove;border-color:yellow;" bgcolor="#F2F5A9">
					<tr bgcolor="#79BCFF">
						<td colspan="4" align="center">รายละเอียดงาน</td>
					</tr>
					<tr>
						<td align="right"><b>เลขทีสัญญา :</b></td>
						<td align="left"><u><a onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor:pointer"><?php echo $contractID; ?></u></td>
					</tr>
					<tr>
						<td align="right"><b>วันที่ :</b></td>
						<td align="left"><?php echo $AssignDate; ?></td>
					</tr>
						<tr>
						<td align="right"><b>เลขทีสั่งงาน :</b></td>
						<td align="left"><?php echo $AssignNo; ?></td>
					</tr>
					<tr>
						<td align="right"><b>หน่วยงาน :</b></td>
						<td align="left"><?php echo $Institution; ?></td>
					</tr>
					<tr>
						<td align="right"><b>เรื่อง :</b></td>
						<td align="left" colspan="3">
							<?php echo $allSubname; ?>
						</td>
					</tr>
					<tr>
						<td align="right"><b>ลูกค้า :</b></td>
						<td align="left"><u><font color="#FF1493"><a style="cursor:pointer;" onclick="javascript:popU('../search_cusco/index.php?cusid=<?php echo $CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=680')"><?php echo "(".$CusID.")";?></u></a></font> <?php echo $cusname; ?></td>
							<td align="right"><b>ลูกหนี้ :</b></td>
						<td align="left"><u><font color="#FF1493"><a style="cursor:pointer;" onclick="javascript:popU('../search_cusco/index.php?cusid=<?php echo $DebtorID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=680')"><?php echo $newDebtID;?></u></a></font> <?php echo $NewDebtorNam; ?></td>
					</tr>
					<tr>
						<td align="right"><b>สถานที่ :</b></td>
						<td align="left"><?php echo $Place; ?></td>
						<td align="right"><b>เบอร์โทร/ผู้ติดต่อ :</b></td>
						<td align="left"><?php echo $PhoneNo; ?></td>
					</tr>
					<tr>
						<td align="right"><b>กำหนดส่งงาน :</b></td>
						<td align="left"><?php echo $DeadlineDate; ?></td>
						<td align="right"><b>ผู้สั่งงาน :</b></td>
						<td align="left"><?php echo $AssignName; ?></td>
					</tr>
					<tr>
						<td align="right"><b>หมายเหตุ :</b></td>
						<td align="left" colspan="3"><?php echo $Note; ?></td>
					</tr>
				</table>
				</div>
				
				<?php 
					$chequ_qry = pg_query("select * from assign_work_owner where \"AssignNo\"='$AssignNo'");
					while($resChq=pg_fetch_array($chequ_qry)){
						$ChequeAmt = $resChq["ChequeAmt"];
						$Datechq = $resChq["Date"];
						$Number = $resChq["Number"];
						$ChqBank = $resChq["ChqBank"];
						$CashAmt = $resChq["CashAmt"];
						$DocReturn = $resChq["DocReturn"];
						
						
					if($DocReturn==""){
						$newDoc = "-";
					}else {
						$newDoc = $DocReturn;
					}
				?>
					<div id="revChq" style="margin-top:20px;">
						<table width="700px" align="center" cellspacing="10" style="border-style:groove;border-color:yellow;" bgcolor="#F2F5A9">
							<tr bgcolor="#79BCFF">
								<td align="center" colspan="3"><b>รับเช็ค</b></td>
							</tr>
							<tr>
								<td><b>เช็ค :</b> <?php echo $ChequeAmt; ?> <b>ฉบับ</b></td>
								<td><b>ลงวันที่ :</b> <?php echo $Datechq; ?> </td>
								<td><b>เลขที่ :</b><?php echo $Number; ?></td>
							</tr>
							<tr>
								<td colspan="2"><b>เช็คธนาคาร :</b> <?php echo $ChqBank; ?></td>
								<td><b>จำนวนเงิน :</b> <?php echo $CashAmt; ?>  บาท</td>
							</tr>
							<tr bgcolor="#79BCFF">
								<td align="center" colspan="3"><b>เอกสารรับกลับ</b></td>
							</tr>
							<tr>
								<td colspan="3"><b>เอกสารระบุ :</b> <?php echo $DocReturn; ?></td>
							</tr>
						</table>
					</div>
				<?php } ?>
				
				<div id="payment">
						<table width="700" align="center" bgcolor="#F2F5A9" style="border-style:groove;border-color:yellow;">
							<tr align="center" bgcolor="#79BCFF">
								<td colspan="2"><b>รายการตั้งหนี้</b></td>
							</tr>
							<tr>
								<td align="center"><?php if($deptStatus=="N"){ echo "<b>/</b>";}?></td>
								<td><b>ไม่มีค่าใช้จ่าย</b></td>
							</tr>
							<tr>
								<td align="center"><?php if($deptStatus=="Y"){ echo "<b>/</b>";}?></td>
								<td><b>มีค่าใช่จ่าย</b></td>
							</tr>
						</table>
					</div>
			<?php
				if($cancle=="Y"){
			?><form method="post" action="process_cancle.php" onsubmit="return checkNote();">
					<div id="btset">
						<table align="center" >
							<tr>
								<td><b>หมายเหตุ :<font color="red">*</font></b></td>
								<input type="hidden" name="AssignNo" value="<?php echo $AssignNo; ?>"/>
							</tr>
							<tr>
								<td><textarea name="NoteCancle" id="NoteCancle" cols="30" rows="3"></textarea></td>
							</tr>
							<tr>
								<td align="center" >
									<input type="submit" name="reset" value=" ยกเลิกงาน ">
									<input type="button" name="reset" value="ปิด" onclick="window.close();">
								</td>
							</tr>
						</table>
					</div>
				</form>
			<?php
				} else if($cancle=="D"){
			?>
					<div id="btset">
						<table align="center" >
							<tr>
								<td><b>หมายเหตุ :<font color="red">*</font></b></td>
							</tr>
							<tr>
								<td><textarea name="NoteCancle" id="NoteCancle" cols="30" rows="3" readonly ><?php echo $CancleNote; ?></textarea></td>
							</tr>
							<tr>
								<td align="center">
									<input type="button" name="reset" value="ปิด" onclick="window.close();">
								</td>
							</tr>
						</table>
					</div>
			<?php			
				}else{
			?>
					<div id="btset">
						<table align="center" >
							<tr>
								<td>
									<input type="button" name="reset" value="ปิด" onclick="window.close();">
								</td>
							</tr>
						</table>
					</div>
			<?php			
				}
			?>
	</div>
</body>
</html>
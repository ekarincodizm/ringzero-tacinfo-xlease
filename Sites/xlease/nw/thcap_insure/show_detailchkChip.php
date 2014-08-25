<?php
session_start();
include("../../config/config.php");

$auto_id=$_GET["auto_id"];
$method=$_GET["method"];

$qrycheck=pg_query("SELECT \"refDeedContract\", \"costBuilding\", \"costFurniture\", 
       \"costEngine\", \"costStock\", \"textOther\", \"costOther\", \"insureSpecial\", 
       \"totalChip\",\"numberQ\", \"statusInsure\",\"resultnotapp\" FROM thcap_insure_checkchip where auto_id='$auto_id'");
list($refDeedContract,$costBuilding,$costFurniture,$costEngine,$costStock,$textOther,$costOther,
$insureSpecial,$totalChip,$numberQ,$statusInsure,$resultnotapp)=pg_fetch_array($qrycheck);

if($statusInsure=="0"){
	//ค้นหาเลขที่โฉนด
	$qrynumdeed=pg_query("SELECT \"numDeed\" FROM nw_securities where \"securID\"='$refDeedContract'");
	list($numDeed)=pg_fetch_array($qrynumdeed);
	$txtval="ประกันใหม่ ของเลขที่โฉนด $numDeed";
}else{
	$txtval="ต่ออายุของเลขที่สัญญา $refDeedContract";
}

$sumtotal=$costBuilding+$costFurniture+$costEngine+$costStock+$costOther;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ประกันอัคคีภัย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="wrapper" style="width:800px;">
				<div align="center"><h2>แสดงรายละเอียดเงินเอาประกันภัย</h2></div>
				<fieldset><legend><B><?php echo $txtval;?></B></legend>
					<div style="padding-top:20px;">
						<table width="100%" border="0"  align="center">
						<tr>
							<td width="100" colspan="3"><b>:: จำนวนเงินเอาประกันภัยทั้งสิ้น ::</b></td>	
						</tr>
						<tr>
							<td width="50"></td>
							<td>
								<input type="checkbox" name="cost1" id="cost1" value="1" <?php if($costBuilding !="") echo "checked";?> disabled="true"> - สิ่งปลูกสร้าง (รากฐานฯไม่รวม)
							</td>
							<td>
								<div id="s1"><input type="text" name="txt1" id="txt1" size="20" style="text-align:right;"  value="<?php echo number_format($costBuilding,2);?>" readonly="true"></div>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="cost2" id="cost2" value="1" <?php if($costFurniture !="") echo "checked";?> disabled="true"> - เฟอร์นิเจอร์ เครื่องตกแต่งติดตั้งตรึงตรา และของใช้ต่างๆ
							</td>
							<td>
								<div id="s2"><input type="text" name="txt2" id="txt2" size="20" style="text-align:right;" value="<?php echo number_format($costFurniture,2);?>" readonly="true"></div>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="cost3" id="cost3" value="1" <?php if($costEngine !="") echo "checked";?> disabled="true"> - เครื่องจักร
							</td>
							<td>
								<div id="s3"><input type="text" name="txt3" id="txt3" size="20" style="text-align:right;" value="<?php echo number_format($costEngine,2);?>" readonly="true"></div>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="cost4" id="cost4" value="1" <?php if($costStock !="") echo "checked";?> disabled="true"> - สต๊อกสินค้า
							</td>
							<td>
								<div id="s4"><input type="text" name="txt4" id="txt4" size="20" style="text-align:right;" value="<?php echo number_format($costStock,2);?>" readonly="true"></div>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="cost5" id="cost5" value="1" <?php if($costOther !="") echo "checked";?> disabled="true"> - อื่นๆ ระบุ... <input type="text" name="textOther" id="textOther" size="30" value="<?php echo $textOther;?>" readonly="true">
							</td>
							<td>
								<div id="s5"><input type="text" name="txt5" id="txt5" size="20" style="text-align:right;" value="<?php echo number_format($costOther,2);?>" readonly="true"></div>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="right"><b>รวมทุกประกันทั้งสิ้น</b></td>
							<td><div><input type="text" name="sumtotal"  id="sumtotal" value="<?php echo number_format($sumtotal,2);?>" readonly="true" size="20" style="text-align:right;"></div></td>
						</tr>
						<tr>
							<td colspan="3"><hr color="#FFB3B3"></td>
						</tr>
						<tr>
							<td width="100" colspan="3"><b>:: ภัยเพิ่มพิเศษ ::</b></td>	
						</tr>
						<tr>
							<td colspan="3">
							<textarea readonly="true" cols="80" rows="5"><?php echo $insureSpecial;?></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="3"><hr color="#FFB3B3"></td>
						</tr>
						<tr>
							<td colspan="3"><b>:: เบี้ยรวม :</b> <input type="text" name="totalchip" id="totalchip" value="<?php echo number_format($totalChip,2);?>" readonly="true"></td>	
						</tr>
						<tr>
							<td colspan="3"><b>:: เลขคิว :</b> <input type="text" name="numQ" id="numQ" value="<?php echo $numberQ;?>" readonly="true"></td>	
						</tr>
						<tr>
							<td colspan="3"><hr color="#FFB3B3"></td>
						</tr>
						<?php
						if($method=="noapp"){
						?>
						<tr>
							<td colspan="3">:: เหตุผลที่ไม่อนุมัติ ::</td>
						</tr>
						<tr>
							<td colspan="3"><textarea cols="60" rows="5" readonly="true"><?php echo $resultnotapp?></textarea></td>
						</tr>
						<?php
						}
						?>
						<tr>
							<td align="center" colspan="3"><br>		
								<input type="button" value="  ปิด  " id="submitbutton" onclick="window.close();">&nbsp;
							</td>
						</tr>
						</table>
					</div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          
</body>
</html>
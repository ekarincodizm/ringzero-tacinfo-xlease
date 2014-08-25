<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<?php 
$autoid=pg_escape_string($_GET["autoid"]);
$show=pg_escape_string($_GET["show"]);

if($show == '0') // ถ้าต้องการดูข้อมูลเก่า
{
	// หา id จริง
	$qry_trueID = pg_query("select \"costtype\" from \"thcap_cost_type_temp\" where \"autoid\" = '$autoid' ");
	$trueID = pg_fetch_result($qry_trueID,0);
	
	if($trueID != '0') // ถ้ามีข้อมูลเก่า
	{
		$sql = pg_query("select *  from \"thcap_cost_type\" where \"costtype\" = '$trueID'");
		$result = pg_fetch_array($sql);
		$costname_edit=$result["costname"];
		$typeloansuse_edit=$result["typeloansuse"];
		$note_edit=$result["note"];
		$status_costtype=$result["status_costtype"];
	}
}
else
{
	$sql = pg_query("select *  from \"thcap_cost_type_temp\" where \"autoid\" ='$autoid'");
	$result = pg_fetch_array($sql);
	$costname_edit=$result["costname"];
	$typeloansuse_edit=$result["typeloansuse"];
	$note_edit=$result["note"];
	$approved=$result["approved"];
	$status_costtype=$result["status_costtype"];
	if($approved=='1'){ $approved="อนุมัติ";}
	else if($approved=='0') { $approved="ไม่อนุมัติ";}
}
$rest = substr($typeloansuse_edit,1,strlen($typeloansuse_edit)-2); 
$typeloan = explode(",",$rest );
$i=0;	
?>
<table>
	<tr>
		<td align="right" valign="top" ><b>ชื่อประเภทต้นทุนสัญญา:</b></td>
		<td><input id="Costname" name="Costname" size="75" value="<?php if($autoid!='0'){ echo $costname_edit;}?>" readonly></td>		
	</tr>	
	<tr>
		<td align="right" valign="top" ><b>ประเภทต้นทุน:</b></td>
		<td><select name="Costtype" id="Costtype" disabled> 	
				<option value="" <?php if($status_costtype=="") echo "selected";?>>กรุณาเลือก</option>		
				<option value="0" <?php if($status_costtype=="0") echo "selected";?>>ไม่ระบุ</option>
				<option value="1" <?php if($status_costtype=="1") echo "selected";?>>ต้นทุนเริ่มแรก</option>
				<option value="2" <?php if($status_costtype=="2") echo "selected";?>>ต้นทุนดำเนินการ</option>		
		</select></td>		
	</tr>
	<tr>
		<td align="right" valign="top" ><b>ประเภทสินเชื่อที่ใช้กับประเภทต้นทุนสัญญา :</b></td>	
		<td>
			<div id="showData">
				<table align="left" width="70%" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" id="tb1">
					<tr bgcolor="#4399c3" style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:13px;" height="20">
						<td width="100"  align="center">ประเภทสินเชื่อ</td>	
						<td width="100"  align="center">เลือก</td>		
					</tr>
						<?php 
						$sql_loantype = pg_query("select distinct \"conType\"  from \"thcap_contract\"");				
						while($re_loantype  = pg_fetch_array($sql_loantype ))
						{
							$i+=1;
							$loantype=$re_loantype["conType"];
							echo "<tr bgcolor=\"#DBF2FD\">";
							echo "<td align=\"center\">$loantype</td>";
							echo "<td  align=\"center\"><input type=\"checkbox\" id=\"showData\" name=\"showData\" disabled value=\"$loantype\"";
							if($autoid!='0')
							{
								for($t=0;$t<sizeof($typeloan);$t++)
								{
									if($typeloan[$t]==$loantype)
									{
										echo " checked=\"checked\" ";
									}
								}
							}
							echo "/></td></tr>";
						}
						?>
					<tr bgcolor="#DBF2FD">
					<td align="center"> ทุกประเภทสินเชื่อ</td>
					<td align="center"><input type="checkbox" disabled id="all"  name="all" value="all" ;
					<?php if($autoid!='0'){
							if($rest==""){
							echo " checked=\"checked\" />";
							}
						}?>
					</td>			
					</tr>			
				</table>
			</div>
		</td>
	<tr>		
		<td align="right" valign="top"><b>หมายเหตุ<font color="#FF0000"></font> :</b></td>
		<td><textarea id="note" name="note" cols="60" rows="4" readonly><?php if($autoid!='0'){echo $note_edit ;}?></textarea>
	</tr>		
	<tr>
	<?php if($show=='0'){/*?>	
		<td align="right" ><b>ผลการอนุมัติ:</b></td>
		<td><input size="75" value="<?php echo $approved;?>"></td>
	<?php */}?>	
	</tr>
</table>

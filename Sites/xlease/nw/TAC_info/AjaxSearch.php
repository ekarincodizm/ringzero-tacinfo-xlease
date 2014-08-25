<?php
$strSearch = $_POST["mySearch"];
include("config.php");
 
$strSQL = "SELECT * FROM \"TrMember\" WHERE \"Username\" LIKE '%".$strSearch."%' ORDER BY \"UserID\" ASC ";
$objQuery = pg_query($strSQL) or die ("Error Query [".$strSQL."]");
$rows=pg_num_rows($objQuery);
?>
<table width="780" border="0" cellpadding="0" cellspacing="0" id="tb_ShowAllNews">
<tr>
    <td id="td_Username" align="center" valign="middle">ชื่อผู้ใช้</td>
    <td id="td_UserType" align="center" valign="middle">ประเภท</td>
    <td id="td_MemberName" align="center" valign="middle">ชื่อสมาชิก</td>
    <td id="td_CarCode" align="center" valign="middle">ทะเบียนรถ</td>
    <td id="td_Telephone" align="center" valign="middle">หมายเลขโทรศัพท์</td>
    <td id="td_Edit" align="center" valign="middle">แก้ไข</td>
    <td id="td_Disable" align="center" valign="middle">ระงับ</td>
</tr>
<?
while($objResult = mysql_fetch_array($objQuery))
{
	$username=$objResult['Username'];
	$isAdmin="";
	if($objResult['isAdmin']==0)
	{
		$isAdmin="user";
	}
	else
	{
		$isAdmin="admin";
	}
	$memberName=$objResult['membername'];
	$carCode=$objResult['carregistrationnumber'];
	$telephone=$objResult['telephonenumber'];
	if($rows==0)
	{
?>
		<tr>
		<td id="allNews_NoData" align="center" valign="middle" colspan="7">**ยังไม่มีข้อมูล**</td>
		</tr>
<?
	}
	else
	{
		if($objResult['disabled']=="y")
		{
?>
			<tr>
			<td id="td_Username2" align="left" valign="middle"><?=$objResult["Username"];?></td>
			<td id="td_UserType2" align="center" valign="middle"><?=$objResult["isAdmin"];?></td>
			<td id="td_MemberName2" align="center" valign="middle"><?=$objResult["membername"];?></td>
			<td id="td_CarCode2" align="center" valign="middle"><?=$objResult["carregistrationnumber"];?></td>
			<td id="td_Telephone2" align="center" valign="middle"><?=$objResult["telephonenumber"];?></td>
			<td id="td_Edit2" align="center" valign="middle"><img src="images/gtk-edit.png" width="16" height="16"></td>
			<td id="td_Disable2" align="center" valign="middle"><img src="images/button-ban.gif" width="32" height="15"></td>
			</tr>
<?
		}
		else
		{
?>
			<tr>
			<td id="td_Username1" align="left" valign="middle"><?=$objResult["Username"];?></td>
			<td id="td_UserType1" align="center" valign="middle"><?=$objResult["isAdmin"];?></td>
			<td id="td_MemberName1" align="center" valign="middle"><?=$objResult["membername"];?></td>
			<td id="td_CarCode1" align="center" valign="middle"><?=$objResult["carregistrationnumber"];?></td>
			<td id="td_Telephone1" align="center" valign="middle"><?=$objResult["telephonenumber"];?></td>
			<td id="td_Edit1" align="center" valign="middle"><img src="images/gtk-edit.png" width="16" height="16"></td>
			<td id="td_Disable1" align="center" valign="middle"><img src="images/button-ban.gif" width="32" height="15"></td>
			</tr>
<?
		}
	}
}
?>
</table>
<?php
set_time_limit(0);
if($CusID == ""){
	include("../../config/config.php");
	if( empty($_SESSION["av_iduser"]) ){
		header("Location:../../index.php");
		exit;
	}
	$CusID= pg_escape_string($_GET["CusID"]);
	$dontshowapp =  pg_escape_string($_GET["notshowapp"]);
}else{
	$imgpath = redirect($_SERVER['PHP_SELF'],'nw/search_cusco');
}
$realpath = redirect($_SERVER['PHP_SELF'],'nw/manageCustomer/');
$ref_path = redirect($_SERVER['PHP_SELF'],'nw/search_cusco/');
$qry_temp=pg_query("select \"CustempID\",edittime,b.\"fullname\" as add_user,a.\"add_date\",c.\"fullname\" as app_user,a.\"app_date\",a.\"statusapp\" from \"Customer_Temp\" a 
			left join \"Vfuser\" b on a.\"add_user\"=b.\"id_user\"
			left join \"Vfuser\" c on a.\"app_user\"=c.\"id_user\"
			WHERE \"CusID\" = '$CusID' order by a.\"edittime\" DESC");
			$numrows=pg_num_rows($qry_temp);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>
<?php
if($numrows==0){
	?>
	<!--ข้อมูลปัจจุบัน-->
			<table width="850" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<?php if($dontshowapp != not){ ?>			
			<tr bgcolor="#FFFFFF">
			<td colspan="11" align="left" style="font-weight:bold;">ข้อมูลปัจจุบัน</td> 
			</tr> 
<?php } ?>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#FFCCCC" align="center">
				<td>ชื่อ-นามสกุลลูกค้า</td>
				<td>เลขทะเบียนนิติบุคคล</td>
				<td>อายุ </td>
				<td>สัญชาติ</td>
				<td>โทรศัพท์มือถือ</td>
				<td>โทรศัพท์บริษัท</td>
				<td>FAX</td>
				<td>ดูข้อมูล</td>
                <td>ตรวจสอบข้อมูล</td>
<?php if($showdatacus == 'true'){ echo 	"<td>ตรวจสอบข้อมูลลูกค้า</td>"; } ?>	
			</tr>
			<?php
			
			
			$qry_cus=pg_query("select a.\"CusID\", \"full_name\", \"N_AGE\", \"N_SAN\", \"N_IDCARD\", \"A_TELEPHONE\", \"A_MOBILE\" ,\"phone\", \"Fax\" ,\"IDCARD\"
			from \"VSearchCusCorp\" a
			left join \"Fn\" b on a.\"CusID\"=b.\"CusID\"
			left join \"Fa1\" c on a.\"CusID\"=c.\"CusID\"
			left join \"th_corp\" d on d.\"corpID\"::text=a.\"CusID\"
			where a.\"CusID\"='$CusID'");
			$numrows=pg_num_rows($qry_cus);
			while($res_cus=pg_fetch_array($qry_cus)){
				$CusID2=$res_cus["CusID"];
				$CusID3=$res_cus["IDCARD"];
				$full_name=$res_cus["full_name"];
				$N_AGE=$res_cus["N_AGE"];
				$N_SAN = $res_cus["N_SAN"]; 
				$IDCARD = $res_cus["N_IDCARD"];
				if($res_cus["A_TELEPHONE"]!="")$A_TELEPHONE = $res_cus["A_TELEPHONE"];else $A_TELEPHONE = $res_cus["phone"];
				if($res_cus["N_IDCARD"]!="")$IDCARD = $res_cus["N_IDCARD"];else $IDCARD = $res_cus["IDCARD"];
				if($res_cus["A_MOBILE"]!="")$A_MOBILE = $res_cus["A_MOBILE"];else $A_MOBILE = "-";
				if($res_cus["Fax"]!="")$Fax = $res_cus["Fax"];else $Fax = "-";
			?>
			<tr bgcolor="#FFEAEA">
				<td width="200px"><?php echo $full_name; ?></td>
				<td width="100px" align="center"><?php echo $IDCARD; ?></td>
				<td width="100px" align="center"><?php echo $N_AGE; ?></td>
				<td width="50px" align="center"><?php echo $N_SAN; ?></td>
				<td width="100px" align="center"><?php echo $A_MOBILE; ?></td>
				<td width="100px" align="center"><?php echo $A_TELEPHONE; ?></td>
				<td width="100px" align="center"><?php echo $Fax; ?></td>
				<td width="100px" align="center"><span onclick="javascript:popU('<?php echo $realpath ?>../corporation/frm_viewcorp_detail.php?corp_regis=<?php echo $CusID3; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=800')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
<?php if($showdatacus == 'true'){ 
	echo "<td width=\"150px\" align=\"center\"><img src=\"$imgpath/images/onebit_02.png\" width=\"20px;\" title=\"ดูข้อมูลของลูกค้า\"   height=\"20px;\" style=\"cursor:pointer;\" onclick=\"javascipt:popU('$imgpath/index.php?cusid=$CusID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')\" />";				
} ?>			
				<td width="100px" align="center"><span onclick="javascript:popU('<?php echo $ref_path; ?>index.php?cusid=<?php echo trim($CusID2).'%23'.$full_name.'%23'.$IDCARD; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>	
			</tr>
			<?php
			} //end while
			if($numrows == 0){
				echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
		</table>
	<?php
if($dontshowapp != not){	
	echo "<div style=\"text-align:center;padding:20px;\"><h2>ไม่พบข้อมูลที่ทำการแก้ไข</h2></div>";
}	
}else{
?>

<table width="850" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<!--ข้อมูลปัจจุบัน-->
			<table width="850" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<?php if($dontshowapp != not){ ?>			
			<tr bgcolor="#FFFFFF">
			<td colspan="11" align="left" style="font-weight:bold;">ข้อมูลปัจจุบัน</td> 
			</tr> 
<?php } ?>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#FFCCCC" align="center">
				<td>ชื่อ-นามสกุลลูกค้า</td>
				<td>เลขบัตรประชาชน </td>	
				<td>อายุ </td>
				<td>สัญชาติ</td>
				<td>โทรศัพท์มือถือ</td>
				<td>โทรศัพท์บ้าน</td>
				<td>ดูข้อมูล</td>
                <td>ตรวจสอบข้อมูล</td>
<?php if($showdatacus == 'true'){ echo 	"<td>ตรวจสอบข้อมูลลูกค้า</td>"; } ?>					
			</tr>
			<?php
			$qry_cus=pg_query("select a.\"CusID\", \"full_name\", \"N_AGE\", \"N_SAN\", \"N_IDCARD\", \"A_TELEPHONE\", \"A_MOBILE\" ,\"phone\",\"Fax\" ,\"IDCARD\"
			from \"VSearchCusCorp\" a
			left join \"Fn\" b on a.\"CusID\"=b.\"CusID\"
			left join \"Fa1\" c on a.\"CusID\"=c.\"CusID\"
			left join \"th_corp\" d on d.\"corpID\"::text=a.\"CusID\"
			where a.\"CusID\"='$CusID'");
			while($res_cus=pg_fetch_array($qry_cus)){
				$CusID2=$res_cus["CusID"];
				$full_name=$res_cus["full_name"];
				$N_AGE=$res_cus["N_AGE"];
				$N_SAN = $res_cus["N_SAN"]; 
				$IDCARD = $res_cus["N_IDCARD"];
				if($res_cus["A_TELEPHONE"]!="")$A_TELEPHONE = $res_cus["A_TELEPHONE"];else $A_TELEPHONE = $res_cus["phone"];
				if($res_cus["N_IDCARD"]!="")$IDCARD = $res_cus["N_IDCARD"];else $IDCARD = $res_cus["IDCARD"];
				if($res_cus["A_MOBILE"]!="")$A_MOBILE = $res_cus["A_MOBILE"];else $A_MOBILE = "-";
			?>
			<tr bgcolor="#FFEAEA">
				<td width="200px"><?php echo $full_name; ?></td>
				<td width="100px" align="center"><?php echo $IDCARD; ?></td>
				<td width="100px" align="center"><?php echo $N_AGE; ?></td>
				<td width="50px" align="center"><?php echo $N_SAN; ?></td>
				<td width="100px" align="center"><?php echo $A_MOBILE; ?></td>
				<td width="100px" align="center"><?php echo $A_TELEPHONE; ?></td>
				<td width="100px" align="center"><span onclick="javascript:popU('<?php echo $realpath ?>showdetail2.php?CusID=<?php echo $CusID2; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=800')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
<?php if($showdatacus == 'true'){ 

	echo "<td width=\"150px\" align=\"center\"><img src=\"$imgpath/images/onebit_02.png\" title=\"ดูข้อมูลของลูกค้า\"   width=\"20px;\" height=\"20px;\" style=\"cursor:pointer;\" onclick=\"javascipt:popU('$imgpath/index.php?cusid=$CusID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')\" /></td>";				
} ?>
				<td width="100px" align="center"><span onclick="javascript:popU('<?php echo $ref_path; ?>index.php?cusid=<?php echo trim($CusID2).'%23'.$full_name.'%23'.$IDCARD; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
			</tr>
			<?php
			} //end while
			if($numrows == 0){
				echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
<?php if($dontshowapp != not){ ?>	
			<!--ข้อมูลที่ถูกแก้ไข-->
			<div><?php include ("frm_history_EditDetail.php");?></div>
		<?php } ?>	
		</div>
	</td>
</tr>
</table>

</body>
</html>
<?php }?>
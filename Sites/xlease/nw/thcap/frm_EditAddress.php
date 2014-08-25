<?php
include("../../config/config.php");
$conidget = pg_escape_string($_GET["conid"]);

//หากมีคำสั่งให้อนุมัติเลยโดยไม่ต้องรอ
$autoapp = pg_escape_string($_GET["autoapp"]);
if($autoapp  == 't'){
	$readonly = 'Readonly';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) แก้ไขที่อยู่สัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#contractID").autocomplete({
        source: "s_letter.php",
        minLength:1
    });

    $('#btn1').click(function(){		
		$.post("check_conedit.php",{
			CONID : $("#contractID").val()
		},
		function(data){	
				if(data == 'yes'){
					 $("#panel").show();	
					 $("#panel").load("frm_EditAddress_details.php?contractID="+ $("#contractID").val());
				}else{				
					alert("ไม่มีเลขที่สัญาดังกล่าวในระบบ กรุณาตรวจสอบอีกครั้ง");
					$("#panel").hide();	
				}
		});    
    });
	
<?php if($conidget != ""){ ?>
			
		$.post("check_conedit.php",{
			CONID : $("#contractID").val()
		},
		function(data){	
				if(data == 'yes'){
					 $("#panel").show();	
					 $("#panel").load("frm_EditAddress_details.php?contractID="+ $("#contractID").val() +"&autoapp="+'<?php echo $autoapp ?>');
				}else{				
					alert("ไม่มีเลขที่สัญาดังกล่าวในระบบ กรุณาตรวจสอบอีกครั้ง");
					$("#panel").hide();	
				}
		});    	
<?php } ?>	

});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
		<div style="clear:both; padding-bottom: 10px;"></div>

		<fieldset><legend><B>(THCAP) แก้ไขที่อยู่สัญญา</B></legend>
		<div class="ui-widget" align="center">
			<div style="padding-left:80px;">ค้นได้จาก เลขที่สัญญา, ชื่อผู้กู้หลัก, ผู้กู้ร่วม, เลขบัตรผู้กู้หลัก, เลขบัตรผู้กู้ร่วม</div>
			<div style="margin:0">
				<b>ค้นหา เลขที่สัญญา</b>&nbsp;
				<input id="contractID" name="contractID" size="60" value="<?php echo $conidget; ?>" <?php echo $readonly; ?> />&nbsp;
				<input type="button" id="btn1" value="ค้นหา"/>
			</div>
			<div id="panel" style="padding-top: 10px;"></div>
		</div>
		</fieldset>
       </td>
</tr>
</table>


<!--ส่วนแสดงรายการรออนุมัติและประวัติ-->
<div>
	<table width="80%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<tr>
		<td>
			<div class="wrapper">
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFEAEA">
				<tr bgcolor="#FFFFFF">
					<td colspan="7" style="font-weight:bold;">รายการที่รออนุมัติ</td>
				</tr>
				<tr style="font-weight:bold;" valign="middle" bgcolor="#FFC6C6" align="center">
					<td>เลขที่สัญญา</td>
					<td>ผู้ขอแก้ไข</td>
					<td>วันเวลาขอแก้ไข</td>
					<td>ผลการอนุมัติ</td>
					<td>ตรวจสอบการเปลี่ยนแปลง</td>
				</tr>
				<?php
				$qry_fr=pg_query("SELECT \"tempID\", \"contractID\",\"fullname\", \"addStamp\"
				  FROM \"thcap_addrContractID_temp\" a
				  left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
				  where \"statusApp\"='2' and \"addsType\"='3' and \"withContractEdit\" is null order by \"addStamp\"");
				$nub=pg_num_rows($qry_fr);
				while($res_fr=pg_fetch_array($qry_fr)){
					$tempID=$res_fr["tempID"];
					$contractID=$res_fr["contractID"];
					$fullname=$res_fr["fullname"];
					$addStamp=$res_fr["addStamp"];
												
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#FFE6E6\" align=center>";
					}else{
						echo "<tr bgcolor=\"#FFF0F0\" align=center>";
					}
					?>
					<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
					<td align="left"><?php echo $fullname; ?></td>
					<td><?php echo $addStamp; ?></td>
					<td align="center">รออนุมัติ</td>
					<td align="center"><span onclick="javascript:popU('frm_EditAddress_compare.php?tempID=<?php echo $tempID; ?>&contractID=<?php echo $contractID; ?>&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
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
</div><br><br>

<table width="80%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F4F4F4" align="center">
<tr bgcolor="#FFFFFF">
	<td colspan="11" style="font-weight:bold;">ประวัติการอนุมัติ 30 รายการล่าสุด</td>
</tr>
<tr style="font-weight:bold;" valign="middle" bgcolor="#D4D4D4" align="center">
	<td>เลขที่สัญญา</td>
	<td>ผู้ขอแก้ไข</td>
	<td>วันเวลาขอแก้ไข</td>
	<td>ผู้ทำรายการอนุมัติ</td>
	<td>วันเวลาทำรายการอนุมัติ</td>
	<td>ผลการอนุมัติ</td>
	<td>ตรวจสอบการเปลี่ยนแปลง</td>
</tr>


<?php
	$qry=pg_query("SELECT \"tempID\", \"contractID\", b.\"fullname\", \"addStamp\",c.\"fullname\" as \"appUser\",\"appStamp\",\"statusApp\"
	  FROM \"thcap_addrContractID_temp\" a
	  left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
	  left join \"Vfuser\" c on a.\"appUser\"=c.\"id_user\"
	  where \"statusApp\" in('0','1') and \"addsType\"='3' order by \"tempID\" DESC limit(30);");
	$numrows=pg_num_rows($qry);
	$i=0;
	$sum=0;
	while($result=pg_fetch_array($qry)){
		$tempID=$result["tempID"];
		$contractID=$result["contractID"];
		$fullname=$result["fullname"];
		$addStamp=$result["addStamp"];
		$appUser=$result["appUser"];
		$appStamp=$result["appStamp"];
		$statusApp=$result["statusApp"];
				
		if($statusApp=="0"){
			$txtapp="ไม่อนุมัติ";
		}else{
			$txtapp="อนุมัติ";
		}
		$i+=1;
		if($i%2==0){
			echo "<tr bgcolor=\"#F9F9F9\" align=center>";
		}else{
			echo "<tr bgcolor=\"#F3F3F3\" align=center>";
		}
			
		echo "
			<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
			<u>$contractID</u></font></span></td>					
			<td align=left>$fullname</td>
			<td>$addStamp</td>
			<td align=left>$appUser</td>
			<td align=right>$appStamp</td>
			<td align=\"center\">$txtapp</td>
			<td align=\"center\"><span onclick=\"javascript:popU('frm_profileAddress_compare.php?tempID=$tempID&contractID=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>
		</tr>
		";		
	}
	if($numrows==0){
		echo "<tr><td colspan=7 height=50 align=center bgcolor=\"#FFFFFF\"><b>-ไม่พบประวัติการแก้ไข-</b></td></tr>";
	}
	?>
</table>
</body>
</html>
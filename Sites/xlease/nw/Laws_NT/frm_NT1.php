<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");
$nowdate = Date('Y-m-d');

$usersql = pg_query("SELECT * FROM \"fuser\" where \"id_user\" = '$id_user'  ");
$reuser = pg_fetch_array($usersql);
$leveluser = $reuser['emplevel'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- NT1 -</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

function Asubmit(frm)
{
frm.action="process_approve_NT1.php";
frm.submit();
}
function Bsubmit(frm)
{
frm.action="process_no_approve_NT1.php";
frm.submit();
}
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body bgcolor="#99FF99">
<form name="frm" method="POST">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#99CCFF">
    <tr>
        <td>
		<div class="header" align="center"><h2>(THCAP) ออก NT เตือน 1</h2></div>
			
			
		</td>
	</tr>
	<tr>
		<td>
			<table width="1000" border="1"  cellspacing="1" cellpadding="1" align="center" bgcolor="#99FFCC">
				<tr bgcolor="#9999CC">
					<td align="center">	
						เลขที่สัญญา
					</td>
					<td align="center">	
						ชื่อผู้กู้หลัก
					</td>
					<td align="center">	
						วันที่เริ่มผิดนัด
					</td>
					<td align="center">	
						จำนวนวันที่ค้างชำระ
					</td>
					<td align="center">	
						จำนวนเงินที่ค้างชำระ
					</td>
					<td align="center">	
						เงินต้นคงเหลือ
					</td>	
					<td align="center">	
						สถานะ NT
					</td>
					<?php if($leveluser <=3){ ?>
					<td align="center">	
						เลือก
					</td>
										
					<?php } ?>
				</tr>
				
				<?php
					$qry_fr=pg_query("select * from \"thcap_mg_behindhand\" a right join \"thcap_mg_contract\" b on a.\"contractID\" = b.\"contractID\" where b.\"conNumNTDays\" < a.\"behindhand_day\" order by a.\"contractID\"");
					$nub=0;
					$i=0;
					while($res_fr=pg_fetch_array($qry_fr)){
					$contractID = $res_fr['contractID'];

					//หาจำนวนเงินที่ค้างชำระ
							$qrybackAmt=pg_query("SELECT \"thcap_backAmt\"('$contractID','$nowdate')");
							list($backAmt)=pg_fetch_array($qrybackAmt);

					//หาเงินต้นคงเหลือ
							$qryleftprint=pg_query("select \"thcap_getPrinciple\"('$contractID','now()')");
							list($LeftPrinciple)=pg_fetch_array($qryleftprint);			
							
					?>	
				
				<tr>
					<td align="center"><?php echo $res_fr['contractID'];?></td>
					
				<?php   
				
						$sql1 = pg_query("SELECT * FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0' order by \"contractID\"");
						$re1 = pg_fetch_array($sql1);
						
				?>	
					
					<td align="center"><?php echo $re1['thcap_fullname'];?></td>
					<td align="center"><?php echo $res_fr['behind_day'];?></td>
					<td align="center"><?php echo $res_fr['behindhand_day'];?></td>
					<td align="center"><?php echo number_format($backAmt,2);?></td>
					<td align="center"><?php echo number_format($LeftPrinciple,2);?></td>
				<?php   
				
						$sql2 = pg_query("SELECT * FROM \"thcap_NT1_temp\" where \"contractID\" = '$contractID' order by \"NT_tempID\" DESC limit 1");
						$row2 = pg_num_rows($sql2);
						$re2 = pg_fetch_array($sql2);
						
						if($row2 != 0){ 
							if($re2['NT_1_Status']=='1'){ ?>
						<td align="center" ><span onclick="javascript:popU('frm_data_NT1.php?contractID=<?php echo $contractID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=400')" style="cursor:pointer"><u> อนุมัติแล้ว</u></span></td>	
				<?php 		}else if($re2['NT_1_Status']=='0'){ ?>				
						<td align="center" ><span onclick="javascript:popU('frm_data_NT1.php?contractID=<?php echo $contractID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=400')" style="cursor:pointer"><u> รอการอนุมัติ  </u></span></td>					
				<?php		}else{ ?>
						<td align="center"><input type="button" value=" ออก NT1 " onclick="javascript:popU('frm_add_NT1.php?contractID=<?php echo $contractID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=400')"></td>
				<?php		} ?>
						

					<?php if($leveluser <=3 && $re2['NT_1_Status']=='0'){ ?>
					<td align="center">	
						<input type="checkbox" name="chkapp[]" value="<?php echo $contractID; ?>">						
					</td>								
					<?php } 
					}else{ ?>
						<td align="center"><input type="button" value=" ออก NT1 " onclick="javascript:popU('frm_add_NT1.php?contractID=<?php echo $contractID ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=400')"></td>
				<?php 	} ?>
				
				</tr>
<?php            } ?>
<?php if($leveluser <=3){ ?>
				<tr>
					<td align="right" colspan="9">	
						<input type="submit" value="อนุมัติ" onclick="javascript:Asubmit(this.form)">
						<input type="submit" value="ปฎิเสธ" onclick="javascript:Bsubmit(this.form)">
					</td>	
				</tr>
	<?php } ?>				
			</table>
					</div>
					</div>
		</td>
	</tr>
</table>	
</form>
</body>
</html>

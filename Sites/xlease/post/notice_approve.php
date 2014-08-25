<?php
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

$nowdate = Date('Y-m-d');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>Approve Cancel NT</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $(window).bind("beforeunload",function(event){
        window.opener.$('div#div_admin_menu').load('list_admin_menu.php');
    });    
});
</script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
		<tr>
			<td>
				<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
				<div class="wrapper">
					<table width="800" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
						<tr bgcolor="#FFFFFF">
							<td colspan="11" align="left" style="font-weight:bold;">Approve Cancel NT</td>
						</tr>
						<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
							<td align="center">เลข NT</td>
							<td align="center">เลขที่สัญญา</td>
							<td align="center">ชื่อ</td>
							<td align="center">ทะเบียน</td>
							<td align="center">สีรถ</td>
							<td align="center">ผู้ออกหนังสือ</td>
							<td align="center">ผู้ที่ยกเลิกหนังสือ</td>
							<td align="center">ทำรายการอนุมัติ</td>
							<!--td align="center">อนุมัติ</td>
							<td align="center">ไม่อนุมัติ</td-->
						</tr>

					<?php
							$qry_fr=pg_query("select a.\"IDNO\",a.\"NTID\",c.\"fullname\" as \"markfullname\",d.\"fullname\" as \"canfullname\" from \"NTHead\" a
							LEFT JOIN \"Vfuser\" c ON a.\"makerid\" = c.\"id_user\"
							LEFT JOIN \"Vfuser\" d ON a.\"cancelid\" = d.\"id_user\"
							WHERE \"CusState\"='0' AND \"cancel\"='FALSE' AND \"cancelid\" IS NOT NULL 
							ORDER BY \"IDNO\" ");
							$nub=pg_num_rows($qry_fr);
							while($res_fr=pg_fetch_array($qry_fr)){
								$IDNO = $res_fr["IDNO"];
								$NTID = $res_fr["NTID"];
								$markfullname =  $res_fr["markfullname"];
								$canfullname =  $res_fr["canfullname"];

								$qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
								if($res_vc=pg_fetch_array($qry_vc)){
									$full_name = $res_vc["full_name"];
									$C_COLOR = $res_vc["C_COLOR"];
									$asset_type = $res_vc["asset_type"];
									$C_REGIS = $res_vc["C_REGIS"];
									$car_regis = $res_vc["car_regis"];
									if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
								}

								$i+=1;
								if($i%2==0){
									echo "<tr class=\"odd\">";
								}else{
									echo "<tr class=\"even\">";
								}
					?>
							<td align="center">
								<span onclick="javascript:popU('../nw/showNT/notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID?>','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="รายละเอียด NT">
									<u>
										<?php echo $NTID; ?>
									</u>
								</span>
							</td>
							<td align="center">
								<span onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ">
									<u>
										<?php echo $IDNO; ?>
									</u>
								</span>
							</td>
							<td align="left"><?php echo $full_name; ?></td>
							<td align="left"><?php echo $show_regis; ?></td>
							<td align="left"><?php echo $C_COLOR; ?></td>
							<td align="left"><?php echo $markfullname; ?></td>
							<td align="left"><?php echo $canfullname; ?></td>
							<td align="center">
								<span onclick="javascript:popU('notice_approve_remark.php?ntid=<?php echo "$NTID"; ?>&idno=<?php echo "$IDNO"; ?>&show=1','<?php echo "$NTID_approve_remark"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=250')" style="cursor: pointer;" title="Re Print">
									<u>
										รายละเอียด
									</u>
								</span>
							</td>
							<!--td align="center">
								<font color="blue">
									<a  onclick="if(confirm('ยืนยันการอนุมัติ')==true){ window.location='notice_approve_send.php?idno=<?php echo "$IDNO"; ?>' }" style="cursor:pointer;" title="อนุมัติรายการนี้">
										<u>
											อนุมัติ
										</u>
									</a>
								</font>
							</td>
							<td align="center">
								<font color="blue">
									<a  onclick="if(confirm('กรุณายืนยันการปฎิเสธการอนุมัติ ')==true){ javascript:popU('frm_confirm_cancel.php?idno=<?php echo "$IDNO"; ?>&NTID=<?php echo "$NTID"; ?>','','width=630,height=360') }" style="cursor:pointer;" title="ไม่อนุมัติรายการนี้">
										<u>
											ไม่อนุมัติ
										</u>
									</a>
								</font>
							</td-->
						</tr>
					<?php
						}//ปิด While

						if($nub == 0){
							echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
						}
					?>
					</table>
				</div>
				<!-- ประวัติการทำรายการอนุมัติ 30 รายาการล่าสุด -->
                <?php
    			include("notice_approve_history_limit.php");
				?>
			</td>
		</tr>
	</table>
</body>
</html>
<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$nowdate = Date('Y-m-d');
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
						<td colspan="11" align="left" style="font-weight:bold;">Approve Create งานยึด</td>
					</tr>
					<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
						<td align="center">เลข NT</td>
						<td align="center">เลขที่สัญญา</td>
						<td align="center">ชื่อ</td>
						<td align="center">ทะเบียน</td>
						<td align="center">สีรถ</td>
						<td align="center">ทำรายการอนุมัติ</td>
					</tr>

				<?php
							$qry_fr=pg_query("select * from \"nw_seize_car\" WHERE \"status_approve\" = '1'");
							$nub=pg_num_rows($qry_fr);
							while($res_fr=pg_fetch_array($qry_fr)){
								$seizeID = $res_fr["seizeID"];
								$IDNO = $res_fr["IDNO"];
								$NTID = $res_fr["NTID"];

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
							<span onclick="javascript:popU('../../post/notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID;?>','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;">
								<u>
									<?php echo $NTID; ?>
								</u>
							</span>
						</td>
						<td align="center">
							<span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ">
								<u>
									<?php echo $IDNO; ?>
								</u>
							</span>
						</td>
						<td align="left"><?php echo $full_name; ?></td>
						<td align="left"><?php echo $show_regis; ?></td>
						<td align="left"><?php echo $C_COLOR; ?></td>
						<td align="center">
							<span onclick="javascript:popU('seize_approve_remark.php?idno=<?php echo "$IDNO"; ?>&ntid=<?php echo "$NTID"?>&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=250')" style="cursor: pointer;" title="Re Print">
								<u>
									รายละเอียด
								</u>
							</span>
						</td>
				   </tr>
				<?php
					}//ปิด While

				if($nub == 0){
					echo "<tr><td colspan=6 align=center>- ไม่พบข้อมูล -</td></tr>";
				}
				?>
				</table>
			</div>


	<!-- ประวัติการทำรายการอนุมัติ 30 รายการล่าสุด -->

			<div style="padding-top:50px;"></div>
				<table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr bgcolor="#FFFFFF">
						<td colspan="12" align="left" style="font-weight:bold;">ประวัติการอนุมัติ 30 รายการล่าสุด</td>
					</tr>
					<tr style="font-weight:bold;" valign="middle" bgcolor="#B5B5B5">
						<td align="center">ลำดับ</td>
						<td align="center">เลข NT</td>
						<td align="center">เลขที่สัญญา</td>
						<td align="center">ชื่อ</td>
						<td align="center">ทะเบียน</td>
						<td align="center">สีรถ</td>
						<td align="center">เหตุผล</td>
						<td align="center">ผู้ทำรายการ</td>
						<td align="center">วันเวลาที่ทำรายการ</td>
						<td align="center">ผู้อนุมัติ</td>
						<td align="center">วันเวลาที่อนุมัติ</td>
						<td align="center">สถานะ</td>
					</tr>

				<?php
									$qry_fr=pg_query("	select a.\"seizeID\" ,a.\"IDNO\", a.\"NTID\", a.\"status_approve\", a.\"approve_date\",a.\"send_date\" ,b.\"fullname\" as \"appname\",c.\"fullname\" as  \"sendname\" 
														from \"nw_seize_car\"  a
														left join \"Vfuser\" b on a.\"approve_user\" = b.\"id_user\"
														left join \"Vfuser\" c on a.\"send_user\" = c.\"id_user\"
														WHERE a.\"status_approve\" != '1' order by a.\"approve_date\" DESC limit 30");
									$nub=pg_num_rows($qry_fr);
									$no = 1;
									while($res_fr=pg_fetch_array($qry_fr)){
											$seizeID = $res_fr["seizeID"];
											$IDNO = $res_fr["IDNO"]; //เลขที่สัญญา
											$NTID = $res_fr["NTID"]; //เลข NT
											$status_approve = $res_fr["status_approve"]; //สถานะการอนุมัติ
											$approve_date = $res_fr["approve_date"]; //วันที่อนุมัติ
											$approve_username = $res_fr["appname"]; //ผู้อนุมัติ
											$send_username = $res_fr["sendname"]; //ผู้ทำรายการยึด
											$send_date = $res_fr["send_date"]; //วันที่ทำรายการยึด
										

										//ตรวจสอบสถานะ
											if($status_approve == '2'){
												$statusapptxt = 'อนุมัติ (รอแจ้งงาน)';
											}else if($status_approve == '3'){
												$statusapptxt = 'อนุมัติ (อยู่ระหว่างยึด)';
											}else if($status_approve == '4'){
												$statusapptxt = 'อนุมัติ (ยึดรถเข้ามาแล้ว)';
											}else if($status_approve == '5'){
												$statusapptxt = 'ไม่อนุมัติ ';
											}
										//ดึงข้อมูลเลขที่สัญญา
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
											echo "<tr bgcolor=#CFCFCF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#CFCFCF';\" align=center>";
										}else{
											echo "<tr bgcolor=#E8E8E8 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E8E8E8';\" align=center>";
										}
				?>
							<td align="center"><?php echo $no; ?></td>
							<td align="center">
									<font color="blue">
										<span onclick="javascript:popU('../../post/notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID;?>','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;">
											<u>
											<?php echo $NTID; ?>
											</u>
										</span>
									</font>
							</td>
							<td align="center">
									<font color="blue">
										<span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ">
											<u>
												<?php echo $IDNO; ?>
											</u>
										</span>
									</font>				
							</td>
							<td align="left"><?php echo $full_name; ?></td>
							<td align="left"><?php echo $show_regis; ?></td>
							<td align="left"><?php echo $C_COLOR; ?></td>
							<td align="center">
									<font color="blue">
										<span onclick="javascript:popU('seize_approve_remark.php?seizeID=<?php echo "$seizeID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=250')" style="cursor: pointer;" title="Re Print">
											<u>
												รายละเอียด
											</u>
										</span>
									</font>
							</td>
							<td align="center"><?php echo $send_username ?></td> 
							<td align="center"><?php echo $send_date ?></td>
							<td align="center"><?php echo $approve_username ?></td>
							<td align="center"><?php echo $approve_date ?></td>
							<td align="center"><?php echo $statusapptxt ?></td>		
					   </tr>
				<?php
								//บวกจำนวนลำดับที่	
									$no++;
							}//ปิด While

					if($nub == 0){
						echo "<tr><td colspan=10 align=center>- ไม่พบข้อมูล -</td></tr>";
					}
				?>
			</table>
        </td>
    </tr>
</table>

</body>
</html>
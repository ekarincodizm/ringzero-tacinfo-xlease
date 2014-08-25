<?php
session_start();
$_SESSION["av_iduser"];
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>อนุมัติสัญญาเช่าซื้อ</title>
    <script type="text/javascript">
	    var xmlHttp;

	function createXMLHttpRequest() {
	     if (window.ActiveXObject) {
		    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		 } 
			else if (window.XMLHttpRequest) {
			 xmlHttp = new XMLHttpRequest();
			 }
		}
		
		function startRequest_nuLock()
		{
			if(document.getElementById("doerRemark").value == '')
			{
				alert('กรุณาระบุ สาเหตุที่ขอปลดล๊อกสัญญา');
			}
			else
			{
				createXMLHttpRequest();
				var sText = document.getElementById("var_lockidno").value;
				var sNumber = document.getElementById("f_carnum").value;
				var cusNumber = document.getElementById("var_cusid").value;
				var as_id = document.getElementById("ass_id").value;
				var doerRemark = document.getElementById("doerRemark").value;
				var doerRemarkSent = '';
				
				var d = doerRemark.split("\n");
				
				for(var i=0; i<d.length; i++)
				{
					if(doerRemarkSent == '')
					{
						doerRemarkSent = d[i];
					}
					else
					{
						doerRemarkSent = doerRemarkSent+'<br>'+d[i];
					}
				}
				
				xmlHttp.open("get", "process_request_unlock.php?stalock=1&idnoget=" + sText + "&fcusnum="+ cusNumber +"&fcarnum="+sNumber+"&fass_id="+as_id+"&doerRemark="+doerRemarkSent, true);
				xmlHttp.onreadystatechange = function () {
					if (xmlHttp.readyState == 4) {
						if (xmlHttp.status == 200) {
							displayInfo_lock(xmlHttp.responseText);
						} else {
							displayInfo_lock("พบข้อผิดพลาด: " + xmlHttp.statusText); 
						}
					}
						
				};
				xmlHttp.send(null);
			}
        }
		
        
        function displayInfo_lock() {
            document.getElementById("divInfo_lock").innerHTML = xmlHttp.responseText;
			 
        }
		
		function startRequest_acc() {
		    createXMLHttpRequest();
            var acc_Text = document.getElementById("var_lockidno").value;
            xmlHttp.open("get", "create_accpayment.php?idno_acc=" + acc_Text, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_acc(xmlHttp.responseText);
                    } else {
                        displayInfo_acc("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
        
        function displayInfo_acc() {
            document.getElementById("divInfo_acc").innerHTML = xmlHttp.responseText;
			 
        }
		
		
			function startRequest_ccc() {
		    createXMLHttpRequest();
            var ccc_Text = document.getElementById("var_lockidno").value;
            xmlHttp.open("get", "create_cuspayment.php?idno_ccc=" + ccc_Text, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_ccc(xmlHttp.responseText);
                    } else {
                        displayInfo_ccc("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
        
        function displayInfo_ccc() {
            document.getElementById("divInfo_ccc").innerHTML = xmlHttp.responseText;
			 
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

<body>
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:800px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" id="menu" style="height:25px; padding-left:10px; padding-top:10px; padding-right:10px;">อนุมัติสัญญาเช่าซื้อ <hr></div>
		<div id="contentpage" style="height:auto;">
			<div id="login"  style="height:auto; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
				<?php
				$idno = trim(pg_escape_string($_GET["IDNO"]));

				$qry_fp = pg_query("select A.* ,B.\"LockContact\", B.\"IDNO\", B.\"P_FDATE\", B.\"P_STDATE\" from \"VContact\" A LEFT OUTER JOIN \"Fp\" B on B.\"IDNO\" = A.\"IDNO\" where  A.\"IDNO\" = '$idno' ");
				$res_fp = pg_fetch_array($qry_fp);

				$num_r=pg_num_rows($qry_fp);
				if($num_r==1)
				{
					//cusid
					$res_cusid=$res_fp["CusID"];

					//C_CARNUM 
					if($res_fp["C_REGIS"]=="")
					{
						$rec_regis=trim($res_fp["car_regis"]);
						$rec_cnumber=trim($res_fp["carnum"]);
						$res_band="ยี่ห้อแก๊ส ".$res_fp["gas_name"];
					}
					else
					{

						$rec_regis=trim($res_fp["C_REGIS"]);
						$rec_cnumber=trim($res_fp["C_CARNUM"]);
						$res_band="ยี่ห้อรถ ".$res_fp["C_CARNUM"];
					}


					$reslock=$res_fp["LockContact"];
					if($reslock=='t')
					{
						$strlock="Lock แล้ว";
						$bt_lock="<input type=\"button\" value=\"ขอ ปลด Lock Contact\" name=\"lockidno\" id=\"lockidno\" onClick=\"startRequest_nuLock()\" style=\"cursor:pointer;\" >";
					}
					else
					{
						$strlock="ยังไม่ได้ Lock";
						$bt_lock="<input type=\"button\" value=\"Lock Contact\" name=\"lockidno\" id=\"lockidno\" disabled/>";
					}

					if($res_fp["P_BEGIN"]!=0)
					{
						$bt_ccc="บันทึก Cuspayment:P_BEGIN=$res_fp[P_BEGIN]";
					}

					if($res_fp["P_BEGINX"]!=0)
					{
						$bt_acc="บันทึก Accpayment:P_BEGINX=$res_fp[P_BEGINX]";
					}
				?>
					<div class="style3" style="background-color:#FFCC02;; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?></div>
					<div class="style3" style="background-color:#996600; width:auto; height:20px; padding-left:10px;"></div>
					<div class="style5" style="width:auto; height:auto; padding-left:10px;">
						<table width="782" border="0">
							<tr style="background-color:#FF9900;">
								<td colspan="4">Lock สัญญา </td>
							</tr>
							<tr style="background-color:#EBF0C6;">
								<td width="122">เลขที่สัญญา<input type="hidden" name="ass_id" id="ass_id" value="<?php echo $res_fp["asset_type"]; ?>" /></td>
								<td width="307"><input type="text" value="<?php echo $res_fp["IDNO"]; ?>" readOnly /></td>
								<td width="93">วันทำสัญญา</td>
								<td><input type="text" value="<?php echo $res_fp["P_STDATE"]; ?>" readOnly /></td>
							</tr>
							<tr style="background-color:#D0DCA0">
								<td>ทะเบียน</td>
								<td><input type="text" value="<?php echo $rec_regis; ?>" readOnly /></td>
								<td>วันชำระงวดแรก</td>
								<td><input type="text" value="<?php echo $res_fp["P_FDATE"]; ?>" readOnly /></td>
							</tr>
							<tr style="background-color:#EBF0C6;">
								<td>ชื่อ - นามสกุล </td>
								<td colspan="3"><input type="text" value="<?php echo $res_fp["full_name"]; ?>" size="50" readOnly /></td>
							</tr>
							<tr style="background-color:#D0DCA0">
								<td>เงินดาวน์</td>
								<td colspan="3"><input type="text" value="<?php echo number_format($res_fp["P_DOWN"],2); ?>" style="text-align:right;" readOnly /></td>
							</tr>
							<tr style="background-color:#EBF0C6;">
								<td>ผ่อนชำระเดือนละ</td>
								<td colspan="3"><input type="text" value="<?php echo number_format($res_fp["P_MONTH"],2); ?>" style="text-align:right;" readOnly /></td>
							</tr>
							<tr style="background-color:#D0DCA0">
								<td>จำนวนงวด</td>
								<td colspan="3"><input type="text" value="<?php echo $res_fp["P_TOTAL"]; ?>" style="text-align:right;" readOnly /></td>
							</tr>
							<tr style="background-color:#EBF0C6;">
								<td>เงินต้นลูกค้า</td>
								<td colspan="3"><input type="text" value="<?php echo number_format($res_fp["P_BEGIN"],2); ?>" style="text-align:right;" readOnly /></td>
							</tr>
							<tr style="background-color:#D0DCA0;">
								<td>เงินต้นทางบัญชี</td>
								<td colspan="3"><input type="text" value="<?php echo number_format($res_fp["P_BEGINX"],2); ?>" style="text-align:right;" readOnly /> </td>
							</tr>
							<tr>
								<td colspan="4">
									<table>
										<tr>
											<td>สาเหตุที่ขอปลดล๊อกสัญญา : </td>
											<td><textarea cols="60" name="doerRemark" id="doerRemark"></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td><?php echo $bt_lock; ?>
								<?php  
								$resstnumber=strlen($rec_cnumber);         
								$var_cnumber=substr($rec_cnumber,$resstnumber-9,9)
								?>
								<input type="hidden" value="<?php echo $var_cnumber; ?>" name="f_carnum" id="f_carnum"  />
								<input type="hidden" value="<?php echo $res_cusid; ?>" name="var_cusid" id="var_cusid"  />	
								<input type="hidden" value="<?php echo $idno; ?>" name="var_lockidno" id="var_lockidno"  />
								</td>
								<td colspan="2">&nbsp;</td>
								<td width="242"><div id="divInfo_lock"><?php echo $strlock; ?></div></td>
							</tr>
							<tr>
								<td><?php echo $bt_acc; ?></td>
								<td colspan="2"><?php /*echo $bt_acc2; */?></td>
								<td><div id="divInfo_acc"></div></td>
							</tr>
							<tr>
								<td><?php echo $bt_ccc; ?></td>
								<td colspan="2"><?php /* echo $bt_ccc2; */?></td>
								<td><div id="divInfo_ccc"></div></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>  
						<table width="95%" border="0" style="background-color:#999999;" cellpadding="1" cellspacing="1">
							<?php 
							$qry_cpm=pg_query("select * from \"CusPayment\" where \"IDNO\"='$idno' ");
							$numr=pg_num_rows($qry_cpm);
							if($numr==0)
							{
							?>
								<tr style="background-color:#FDE2AC">
									<td colspan="6">ยังไม่ได้สร้างข้อมูล Cuspayment</td>
								</tr>
							<?php
							}
							else
							{
							?>  
								<tr style="background-color:#EEF2DB;">
									<td colspan="6">ตารางแสดง Cuspayment </td>
								</tr>
								<tr style="background-color:#D0DCA0">
									<td width="106">DueNo</td>
									<td width="110">DueDate</td>
									<td width="110">Remine</td>
									<td width="152">Priciple</td>
									<td width="125">Interest</td>
									<td width="143">AccuInt</td>
								</tr>

								<?php
								while($rescus=pg_fetch_array($qry_cpm))
								{
								?>	
									<tr style="background-color:#EEF2DB">
										<td width="106"><?php echo $rescus["DueNo"]; ?></td>
										<td width="110"><?php echo $rescus["DueDate"]; ?></td>
										<td width="110" style="text-align:right;"><?php echo number_format($rescus["Remine"],2); ?></td>
										<td width="152" style="text-align:right;"><?php echo number_format($rescus["Priciple"],2); ?></td>
										<td width="125" style="text-align:right;"><?php echo number_format($rescus["Interest"],2); ?></td>
										<td width="143" style="text-align:right;"><?php echo number_format($rescus["AccuInt"],2); ?></td>
									</tr>
								<?php
								}
								?>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							<?php
							}
							?>
						</table>
					</div>
				<?php
				}
				else
				{
					echo "ไม่พบข้อมูล";
				}
				?>
			</div>
			<div style="height:300px; overflow:auto;"></div>
		</div>
		<div id="footerpage"></div>
	</div>
</div>

</body>
</html>

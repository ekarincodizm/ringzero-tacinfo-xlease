<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");

$vipon = $_GET['vip'];

if($vipon == 'true'){

	$vipsql = "where \"cusmore5year\" = true";
}

					$strSort = $_GET["sort"];
						if($strSort == "")
						{
							$strSort = "PSID";
						}

						$strOrder = $_GET["order"];
						if($strOrder == "")
						{
							$strOrder = "DESC";
						}
					
					
					
					
					$sql="select * from \"Poll_service\" $vipsql order by \"$strSort\" $strOrder";
						  $sqlquery = pg_query($sql);
						  $rows = pg_num_rows($sqlquery);
						  
						  
				$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
						  
						  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>-- แบบสำรวจ --</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};

function vipshow(){
	if($("#vipon").attr('checked') == true){
		window.location='index.php?vip=true';
	}else{
		window.location='index.php';
	}	
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
<form name="frm" action="poll_query.php" method="POST">
<table width="90%"  border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>	
		<td align="center"><h1> ตารางแบบสำรวจความพึงพอใจของผู้ใช้บริการ </h1></td>
	</tr>
<?php if($rows == 0){ ?> 	
	<tr>
		<td align="center"><hr width="850"></td>
	</tr>
	<tr>	
		<td align="center"><h1>ไม่พบข้อมูล </h1></td>
	</tr>
	<tr>	
		<td align="center"><h2>--- หากคุณต้องการเพิ่มแบบประเมิน ---</h2></td>
	</tr>
	<tr>	
		<td align="center"><input type="button" value=" เพิ่มแบบประเมิน " onclick="parent.location.href='poll_add.php'" style="width:250px; height:70px; background-color:#C2CFDF;"></td>
	</tr>
<?php }else{ ?>	
	<tr>
		<td align="center">	
			<div style="width: 1260px; height: 600px; overflow: auto; ">
				<table  width="1240" frame="box" border="0" align="center" >
					<tr bgcolor="#CDB5CD">
						<td width="5%" rowspan="2" align="center"><a href='index.php?sort=PSID&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ลำดับ</u></font></td>
						<td width="7%" rowspan="2" align="center"><a href='index.php?sort=regis_car&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ทะเบียน</u></font></td>						
						<td width="7%"rowspan="2" align="center"><a href='index.php?sort=phone&order=<?php echo $strNewOrder ?>'><font color="#003"><u>โทรศัพท์</u></font></td>														
						<td width="10%" rowspan="2" align="center"><a href='index.php?sort=date_service&order=<?php echo $strNewOrder ?>'><font color="#003"><u>วันที่รับบริการ</u></font></td>
						<td width="15%" rowspan="2" align="center"><a href='index.php?sort=emp_name&order=<?php echo $strNewOrder ?>'><font color="#003"><u>เจ้าหน้าที่ ที่ถูกประเมิน</u></font></td>
						<td width="5%" rowspan="2" align="center"><a href='index.php?sort=emp_nickname&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ชื่อเล่น </u></font></td>
						<td width="7%" rowspan="2" align="center"><a href='index.php?sort=identify_emp&order=<?php echo $strNewOrder ?>'><font color="#003"><u>รหัสประจำตัว</u></font></td>
						<td width="18%"  colspan="6" align="center"><font color="#003"> แบบประเมินความพึงพอใจ</font></td>					
						<td width="5%" rowspan="2" align="center"><font color="#003">รวม <br>(เต็ม30)</font></td>	
						<td width="7%" rowspan="2" align="center"><font color="#003">ดูเพิ่มเติม</font></td>						
					</tr>
					<tr bgcolor="#CDB5CD">
						
						<td width="3%" align="center"><a href='index.php?sort=poll1&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ข้อที่ 1</u></font></td>
						<td width="3%" align="center"><a href='index.php?sort=poll2&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ข้อที่ 2</u></font></td>	
						<td width="3%" align="center"><a href='index.php?sort=poll3&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ข้อที่ 3</u></font></td>
						<td width="3%" align="center"><a href='index.php?sort=poll4&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ข้อที่ 4</u></font></td>
						<td width="3%" align="center"><a href='index.php?sort=poll5&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ข้อที่ 5</u></font></td>
						<td width="3%" align="center"><a href='index.php?sort=poll6&order=<?php echo $strNewOrder ?>'><font color="#003"><u>ข้อที่ 6</u></font></td>
										
					</tr>
					<?php 
					
					
						  $i=0;
						  $sumpoint = 0;
						  while($re = pg_fetch_array($sqlquery)){ 
						
						  $i++;
				if($i%2==0){
					echo "<tr bgcolor=#EED2EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EED2EE';\" align=center>";
				}else{
					echo "<tr bgcolor=#FFE1FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFE1FF';\" align=center>";
				}
				?>						  
							<td align="center"><?php if($re['PSID'] ==""){ echo' ไม่ได้ระบุ '; }else{ echo $re['PSID']; }?> <font color="red"><?php if($re['cusmore5year'] == "t"){ echo 'VIP'; }?></font></td>
							<td align="center"><?php if($re['regis_car'] ==""){ echo' ไม่ได้ระบุ '; }else{ echo $re['regis_car']; }?></td>									
							<td align="center"><?php if($re['phone'] ==""){ echo' ไม่ได้ระบุ '; }else{ echo $re['phone']; }?></td>								
							<td align="center"><?php if($re['date_service'] ==""){ echo' ไม่ได้ระบุ '; }else{ echo $re['date_service']; }?></td>
							<td align="center"><?php if($re['emp_name'] ==""){ echo' ไม่ได้ระบุ '; }else{ echo $re['emp_name']; }?></td>
							<td align="center"><?php if($re['emp_name'] ==""){ echo' ไม่ได้ระบุ '; }else{ echo $re['emp_nickname']; }?></td>
							<td align="center"><?php if($re['identify_emp'] ==""){ echo' ไม่ได้ระบุ '; }else{ echo $re['identify_emp']; }?></td>
							<td align="center"><?php if($re['poll1'] < '3'){ $color1 = "red"; }else{ $color1 = "";} ;?> <font color="<?php echo $color1 ?>"><?php echo $re['poll1']; ?></font></td>
							<td align="center"><?php if($re['poll2'] < '3'){ $color1 = "red"; }else{ $color1 = "";} ;?> <font color="<?php echo $color1 ?>"><?php echo $re['poll2']; ?></font></td>	
							<td align="center"><?php if($re['poll3'] < '3'){ $color1 = "red"; }else{ $color1 = "";} ;?> <font color="<?php echo $color1 ?>"><?php echo $re['poll3']; ?></font></td>
							<td align="center"><?php if($re['poll4'] < '3'){ $color1 = "red"; }else{ $color1 = "";} ;?> <font color="<?php echo $color1 ?>"><?php echo $re['poll4']; ?></font></td>
							<td align="center"><?php if($re['poll5'] < '3'){ $color1 = "red"; }else{ $color1 = "";} ;?> <font color="<?php echo $color1 ?>"><?php echo $re['poll5']; ?></font></td>
							<td align="center"><?php if($re['poll6'] < '3'){ $color1 = "red"; }else{ $color1 = "";} ;?> <font color="<?php echo $color1 ?>"><?php echo $re['poll6']; ?></font></td>	
							<?php 
							$sum = $re['poll1']+$re['poll2']+$re['poll3']+$re['poll4']+$re['poll5']+$re['poll6'];
							$sumpoll1 += $re['poll1'];
							$sumpoll2 += $re['poll2'];
							$sumpoll3 += $re['poll3'];
							$sumpoll4 += $re['poll4'];
							$sumpoll5 += $re['poll5'];
							$sumpoll6 += $re['poll6'];
							if($sum < 10){
								$colorsum = 'red';
							}else if($sum >= 10 and $sum < 20){
								$colorsum = '#CD8500';
							}else if($sum >= 20){
								$colorsum = '#458B00';
							}	
							
							?>
							
							<td align="center"><font color=<?php echo $colorsum ?> size="2pt"><b><?php echo $sum ?></b></font></td>						  
							<td align="center"><a style="cursor:pointer" onclick="javascript:popU('poll_view.php?pollid=<?php echo $re['PSID']; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=765');"><u>ดูใบประเมิน</u></a></td>
							
						  </tr>
							

					<?php 
						$sumpoint = $sumpoint + $sum; 
						$pointfull = $rows * 30;
					} ?>
					<tr bgcolor="#CDB5CD">
						<td colspan="7" align="right">รวม :</td>
						<td  align="center"><?php echo $sumpoll1 ; ?></td>
						<td  align="center"><?php echo $sumpoll2 ; ?></td>
						<td  align="center"><?php echo $sumpoll3 ; ?></td>
						<td  align="center"><?php echo $sumpoll4 ; ?></td>
						<td  align="center"><?php echo $sumpoll5 ; ?></td>
						<td  align="center"><?php echo $sumpoll6 ; ?></td>
						<td colspan="2" align="right"></td>
					</tr>
				</table>
			</div>						
				<table  width="1250" frame="box" border="1" bordercolor="#3860BB" cellspacing="0" cellpadding="3" align="center" bgcolor="#EEF2F7">									
							<tr>
								<td bgcolor="#EEA2AD" width="10%">
									<input type="checkbox" id="vipon" value="viponly" <?php if($vipon == 'true'){ echo "checked"; } ?> onclick="vipshow();"><font color="red"><b> VIP เท่านั้น </b></font>
								</td>
								<td  bgcolor="#EEA2AD" width="25%">
									<font color="blue">แบบประเมินทั้งหมด <?php echo $rows ?> รายการ</font>
								</td>
								<td  bgcolor="#8B5F65" align="center" width="35%">
									<input type="button" value=" เพิ่มแบบประเมิน " onclick="parent.location.href='poll_add.php'" style="width:300px; background-color:#C2CFDF;">
								</td>
								<td  bgcolor="#EEA2AD" align="right" width="30%">
									<font color="blue"> รวมคะแนนจากการประเมินทั้งหมด  <font color="red" size="4pt"><?php echo $sumpoint ?></font> / <?php echo $pointfull ?> คะแนน</font>
								</td>
							</tr>
					
				</table>				
        </td>
    </tr>
<?php } ?>	
</table>
</form>
</body>
</html>

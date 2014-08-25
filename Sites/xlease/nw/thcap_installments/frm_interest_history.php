<?php
session_start();
include("../../config/config.php");

$idno = $_GET["idno"];

//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
$qrychk=pg_query("select * from \"thcap_contract\" where \"contractID\"='$idno'");
if(pg_num_rows($qrychk)==0){
	echo "<div align=center><h2>กรุณาระบุเลขที่สัญญาให้ถูกต้อง</h2></div>";
	exit;
}

//ค้นหาชื่อผู้กู้หลัก
$qry_namemain=pg_query("select * from  \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" ='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
}

//ค้นหาชื่อผู้กู้ร่วม
$qry_name=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" = '1'");
$numco=pg_num_rows($qry_name);
$i=1;
$nameco="";
while($resco=pg_fetch_array($qry_name)){
	$name2=trim($resco["thcap_fullname"]);
	if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$nameco=$name2;
	}else{
		if($i==$numco){
			$nameco=$nameco.$name2;
		}else{
			$nameco=$nameco.$name2.", ";
		}
	}
$i++;
}

$qry_top=pg_query("select * from \"Fp\" WHERE \"IDNO\"='$idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$idno]=$CusID;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo $_SESSION["ses_idno"]; ?>');
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>


<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
.odd{
    background-color:#EDF8FE;
    font-size:11px
}
.even{
    background-color:#D5EFFD;
    font-size:11px
}
.red{
    background-color:#FFD9EC;
    font-size:11px
}
</style>

</head>

<body>

<div class="title_top">ประวัติการเปลี่ยนแปลงอัตราดอกเบี้ย</div>

<div id="tabs"> <!-- เริ่ม tabs -->
<ul>
<?php
//สร้าง list รายการ โอนสิทธิ์
foreach($arr_idno as $i => $v){
    if(empty($i)){
        continue;
    }
    echo "<li><a href=\"#tabs-$i\">$i</a></li>";
}
?>
</ul>

<?php
foreach($arr_idno as $i => $v){
    if(empty($i)){
        continue;
    }
    
    $cusid = $v;
    $idno = $i;
    
    //กำหนดสี ให้กับข้อมูลล่าสุด
    if($_SESSION["ses_idno"] == $idno){
        $bgcolor = "#FFFFFF";
    }else{
        $bgcolor = "#FFFFFF"; // FFD2D2
    }
    //จบ กำหนดสี
?>

<div id="tabs-<?php echo $idno; ?>">
<div style="background-color:<?php echo $bgcolor; ?>">
<!--<div align="right">
	<form method="post" name="frmprint" action="frm_print_otherpay.php">
		<input type="hidden" name="idno" value="<?php echo $idno; ?>">
		<input type="submit" value="พิมพ์">
	</form>
</div>-->
<div align="right" style="font-weight:bold; padding-top:3px; padding-bottom:3px;">ผู้กู้หลัก : <?php echo $name3; if($nameco != ""){?> | ผู้กู้ร่วม : <?php echo $nameco;}?></div>

<fieldset><legend><b>ประวัติการเปลี่ยนแปลงอัตราดอกเบี้ย</b></legend>

<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#F0F0F0"  align="center">
    <tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="center" valign="middle">
        <td>ครั้งที่</td>
		<td>อัตราดอกเบี้ย</td>
        <td>วันที่เริ่มใช้</td>
        <td>วันที่สิ้นสุด</td>
    </tr>
<?php
$count=1;
$num = 1;
$qry_vcus=pg_query("select * from \"vthcap_contract_intRateHistory\" WHERE  \"contractID\"='$idno' ORDER BY \"contractID\",\"effectiveDate\"");
$rows = pg_num_rows($qry_vcus);
$interest = "";
if($rows > 0){
while($resvc=pg_fetch_array($qry_vcus)) {
	 $intchk = $resvc["conIntCurRate"];

		if($interest != $intchk){
			
	
			if($count == $rows){
					if($interest != ""){
					
					if($enddate != $resvc["Enddate"]){
						$singup_ar=explode("-",$enddate);						
						$c_s=mktime(0,0,0,$singup_ar[1],$singup_ar[2]-1,$singup_ar[0]);
						$enddate=date("Y-m-d",$c_s);
					}	
						echo "  	<tr>
							<td align=\"center\">$num</td>
							<td align=\"center\">$interest%</td>
							<td align=\"center\">$sdate</td>
							<td align=\"center\">$enddate</td>		
							</tr>
							
						";
						$num++;	
						$interest = $intchk;
						$sdate = $resvc["effectiveDate"];
						$enddate = nowDate();
						echo " 	<tr> 
							<td align=\"center\">$num</td>
							<td align=\"center\">$interest%</td>
							<td align=\"center\">$sdate</td>
							<td align=\"center\">$enddate</td>		
							</tr>
							
						";
						$num++;	
					}else{
						
						$interest = $intchk;
						$sdate = $resvc["effectiveDate"];
							$enddate = nowDate();
							echo "  
								<tr>
									<td align=\"center\">$num</td>
									<td align=\"center\">$interest%</td>
									<td align=\"center\">$sdate</td>
									<td align=\"center\">$enddate</td>		
									</tr>
									
								";
						$num++;									
					}
			}else{
			
				if($interest != ""){
					if($enddate != $resvc["Enddate"]){
						
						$singup_ar=explode("-",$enddate);						
						$c_s=mktime(0,0,0,$singup_ar[1],$singup_ar[2]-1,$singup_ar[0]);
						$enddate=date("Y-m-d",$c_s);
						
					}	
						echo "  	<tr>
							<td align=\"center\">$num</td>
							<td align=\"center\">$interest%</td>
							<td align=\"center\">$sdate</td>
							<td align=\"center\">$enddate</td>		
							</tr>
							
						";
						$num++;	
				}
					$interest = $intchk;
					$sdate = $resvc["effectiveDate"];
					$enddate = $resvc["Enddate"];
					
						
			}		
			
		}else{
			if($count == $rows){
					$enddate = nowDate();
				echo " 
						<tr>
						<td align=\"center\">$num</td>
						<td align=\"center\">$interest%</td>
						<td align=\"center\">$sdate</td>
						<td align=\"center\">$enddate</td>		
						</tr>
						
					";
					$num++;	
			}else{
				
					$enddate = $resvc["Enddate"];		
				
			}
					
		}
	$count++;	
	
  }
 
}else{
?>
    <tr>
        <td align="center" colspan="18">ไม่พบข้อมูล</td>
    </tr>
<?php
}
 $i+=1;
            if($i%2==0){
                echo "<tr class=\"odd\">";
            }else{
                echo "<tr class=\"even\">";
            }
?>
</table>

</fieldset>

</div>
</div>

<?php
}
?>

</body>
</html>
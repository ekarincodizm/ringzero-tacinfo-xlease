<?php
include("../../config/config.php");
include('class.upload.php');
require_once ("../../core/core_functions.php");

$branch_id=$_SESSION["av_officeid"];
$id_user=$_SESSION["av_iduser"];
$datenow=nowDateTime();
$datelog=$datenow;
$datepick = $_POST['datepick'];
$bank = $_POST['bank'];
$val = $_POST['val'];
$counter = $_POST['counter'];
$nowdateTime = date("YmdHis");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>

<table width="880" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<?php
if($val==1){
?>
<div style="float:left"><input type="button" value="  กลับ  " class="ui-button" onclick="window.location='frm_KeySystemInsert.php'"></div>
<?php }else{ ?>
<div style="float:left"><input type="button" value="  กลับ  " class="ui-button" onclick="window.location='cash_no_bill.php'"></div>
<?php } ?>

<div style="float:right">&nbsp;</div>
<div style="clear:both"></div>

<fieldset><legend><B>บันทึกเงินโอน</B></legend>

<div class="ui-widget" style="text-align:center">

<?php
pg_query("BEGIN WORK");


if($counter>0){
	if($val==1){
		for($c=1;$c<=$counter;$c++){
			//add file upload 
			$cli = (isset($argc) && $argc > 1);
			if ($cli) {
				if (isset($argv[1])) $_GET['file'] = $argv[1];
				if (isset($argv[2])) $_GET['dir'] = $argv[2];
				if (isset($argv[3])) $_GET['pics'] = $argv[3];
			}

			// set variables
			$dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : './upload/addcheque');
			$dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
			
			$files = array();
			foreach ($_FILES["my_field$c"] as $k => $l) {
				foreach ($l as $i => $v) {
					if (!array_key_exists($i, $files))
						$files[$i] = array();
					$files[$i][$k] = $v;
				}
			}
			foreach ($files as $file) {
				$handle = new Upload($file);
		   
				if($handle->uploaded) {
					//$handle->file_name_body_pre = $prepend;
					$handle->Process($dir_dest);    
					if ($handle->processed) 
					{
						$pathfile=$handle->file_dst_name;
						
						$Board_oldfile = $pathfile;			
						$Board_newfile = md5_file("./upload/addcheque/$pathfile", FALSE);
						
						$Board_cuttext = split("\.",$pathfile);
						$Board_nubtext = count($Board_cuttext);
						$Board_newfile = "$Board_newfile.".$Board_cuttext[$Board_nubtext-1];
						
						$Board_newfile = $nowdateTime."_".$Board_newfile; // ใส่วันเวลาไว้หน้าไฟล์
					
						$Boardfile[$c] = "'$Board_newfile'"; // ชื่อไฟล์ที่จะเอาไปเก็บใน database
											
						$flgRename = rename("./upload/addcheque/$Board_oldfile", "./upload/addcheque/$Board_newfile");
						
						if($flgRename)
						{
							//echo "บันทึกสำเร็จ";
						}
						else
						{
							echo "ไม่สามารถเปลี่ยนชื่อบางไฟล์ได้";
							$status++;
						}											
					}
					else
					{
						echo '<fieldset>';
						echo '  <legend>file not uploaded to the wanted location</legend>';
						echo '  Error: ' . $handle->error . '';
						echo '</fieldset>';
						$status++;
						$Boardfile[$c] = "NULL";
					}
				}
				else
				{
					$Boardfile[$c] = "NULL";
				}
			}
			// จบ add file upload
		} //end for
		
		for($i=1;$i<=$counter;$i++){
			$hh = $_POST['hh'.$i];
			$mm = $_POST['mm'.$i];
			$bran = $_POST['bran'.$i];
			$money = $_POST['money'.$i];
			
			if($hh=="" || $mm=="" || $bran=="" || $money==""){
				echo "ข้อมูลรายการที่ #$i ไม่ครบถ้วน $hh:$mm | $bran | $money<br />";
			}else{
				$nub++;
				
				$qry_auto=pg_query("select \"runningNum\" from \"thcap_running_number\" where \"compID\" = 'THCAP' AND \"fieldName\" = 'revTranID'");
				$num_auto=pg_num_rows($qry_auto);
				if($num_auto==0){
					$revTranID=1;
				}else{
					if($res_date=pg_fetch_array($qry_auto)){
						$revTranID = $res_date["runningNum"] + 1;
					}
				}
				
				$res_genpost=core_generate_frontzero($revTranID,10,'RT');
						
				$in_transfer="insert into \"finance\".\"thcap_receive_transfer\" (\"revTranID\",\"cnID\",\"bankRevAccID\",\"bankRevBranch\",\"bankRevStamp\",\"bankRevAmt\",\"revTranStatus\" ,\"pictran\") 
								values  ('$res_genpost','TSF','$bank','$bran','$datepick $hh:$mm:00','$money' ,'5' ,$Boardfile[$i])";
				if($result=pg_query($in_transfer)){   
				}else{
					$result1=$result;
					$status+=1;
				}
					 
				$in_transfer_action="insert into \"finance\".\"thcap_receive_transfer_action\" (\"tranActionType\",\"revTranID\",\"doerID\",\"doerStamp\") 
								values  ('I','$res_genpost','$id_user','$datenow')";
				if($resultac=pg_query($in_transfer_action)){   
				}else{
					$result2=$resultac;
					$status+=1;
				}
				
				//หาข้อมูลเพื่อนำมาเก็บใน log
				$qrydata=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$res_genpost'");
				if($resdata=pg_fetch_array($qrydata)){
					$BAccount=$resdata["BAccount"];
					$bankRevBranch=$resdata["bankRevBranch"];
					$bankRevAmt=$resdata["bankRevAmt"];
					$bankRevStamp=$resdata["bankRevStamp"];
				}
				//LOG
				if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
				   \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\") 
				VALUES ('คีย์เงินโอนผ่านระบบ','$res_genpost','$id_user', '$datelog','$BAccount',
				'$bankRevBranch','$bankRevAmt','$bankRevStamp')")); else $status++;
				//LOG---
				
				$upnum="update \"thcap_running_number\" set \"runningNum\"='$revTranID' where \"compID\" = 'THCAP' AND \"fieldName\" = 'revTranID'";
				
				if($resnum=pg_query($upnum)){
				}else{
					$status++;
				}
			}	
		}
	}else{
		for($i=1; $i<=$counter; $i++){
			$hh = $_POST['hh'.$i];
			$mm = $_POST['mm'.$i];
			$bran = $_POST['bran'.$i];
			$money = $_POST['money'.$i];
			
			if($hh=="" || $mm=="" || $bran=="" || $money==""){
				echo "ข้อมูลรายการที่ #$i ไม่ครบถ้วน $hh:$mm | $bran | $money<br />";
			}else{
			
				$nub++;
							
				$qry_auto=pg_query("select \"runningNum\" from \"thcap_running_number\" where \"compID\" = 'THCAP' AND \"fieldName\" = 'revTranID'");
				$num_auto=pg_num_rows($qry_auto);
				if($num_auto==0){
					$revTranID=1;
				}else{
					if($res_date=pg_fetch_array($qry_auto)){
						$revTranID = $res_date["runningNum"] + 1;
					}
				}
							
				$res_genpost=core_generate_frontzero($revTranID,10,'RT');
											
				$in_transfer="insert into \"finance\".\"thcap_receive_transfer\" (\"revTranID\",\"cnID\",\"bankRevAccID\",\"bankRevBranch\",\"bankRevStamp\",\"bankRevAmt\") 
				values  ('$res_genpost','TSF','$bank','$bran','$datepick $hh:$mm:00','$money')";
				if($result=pg_query($in_transfer)){   
				}else{
					$result1=$result;
					$status+=1;
				}
								 
				$in_transfer_action="insert into \"finance\".\"thcap_receive_transfer_action\" (\"tranActionType\",\"revTranID\",\"doerID\",\"doerStamp\") 
					values  ('I','$res_genpost','$id_user','$datenow')";
				if($resultac=pg_query($in_transfer_action)){   
				}else{
					$result2=$resultac;
					$status+=1;
				}
							
				//หาข้อมูลเพื่อนำมาเก็บใน log
				$qrydata=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$res_genpost'");
				if($resdata=pg_fetch_array($qrydata)){
					$BAccount=$resdata["BAccount"];
					$bankRevBranch=$resdata["bankRevBranch"];
					$bankRevAmt=$resdata["bankRevAmt"];
					$bankRevStamp=$resdata["bankRevStamp"];
				}
				//LOG
				if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
					\"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\") 
					VALUES ('เพิ่มรายการเงินโอน','$res_genpost','$id_user', '$datelog','$BAccount',
					'$bankRevBranch','$bankRevAmt','$bankRevStamp')")); else $status++;
				//LOG---
							
				$upnum="update \"thcap_running_number\" set \"runningNum\"='$revTranID' where \"compID\" = 'THCAP' AND \"fieldName\" = 'revTranID'";
							
				if($resnum=pg_query($upnum)){
				}else{
					$status++;
				}	
			}
		}
	}	
}
if($status == 0){
	pg_query("COMMIT");
	//pg_query("ROLLBACK");
	echo "รายการบันทึกเรียบร้อย<br><br>";
}else{
	pg_query("ROLLBACK");
	echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้<br><br>";
	echo $result1."<br>".$result2."<br><br>";
}


?>

</div>
 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>